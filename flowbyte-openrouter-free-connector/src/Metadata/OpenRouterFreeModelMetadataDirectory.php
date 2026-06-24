<?php

namespace FlowByte\EightOpenRouterFree\Metadata;

use WordPress\AiClient\Messages\Enums\ModalityEnum;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleModelMetadataDirectory;
use FlowByte\EightOpenRouterFree\Provider\OpenRouterFreeProvider;

/**
 * Discovers OpenRouter's currently-free models via the live /models endpoint.
 *
 * OpenRouter's free tier rotates regularly — the hardcoded lists shipped in
 * older versions of this plugin (Llama 3 8B / Mistral 7B / OpenChat 7B /
 * openrouter/free) rotted within months. This directory queries
 * https://openrouter.ai/api/v1/models at startup, filters for models whose ID
 * ends with `:free`, and surfaces every currently-free model with its actual
 * input modalities. The parent class caches the result for 24 hours via
 * WithDataCachingTrait, so this hits the network roughly once per day.
 *
 * @since 1.1.0
 *
 * @phpstan-type ModelsResponseData array{
 *     data: list<array{
 *         id: string,
 *         name?: string,
 *         context_length?: int,
 *         architecture?: array{
 *             input_modalities?: list<string>,
 *             output_modalities?: list<string>
 *         },
 *         pricing?: array<string, string>
 *     }>
 * }
 *
 * Free-tier identification uses pricing, not the `:free` ID suffix, because
 * OpenRouter also exposes meta-model routers (`openrouter/free`,
 * `openrouter/owl-alpha`) that have no `:free` marker in the ID but charge
 * $0 for prompt and completion. The `:free` suffix on per-model IDs is a
 * stable secondary signal for the model-level free tier; using both checks
 * covers every currently-known way OpenRouter marks a model as free.
 */
class OpenRouterFreeModelMetadataDirectory extends AbstractOpenAiCompatibleModelMetadataDirectory
{
    protected function createRequest(
        HttpMethodEnum $method,
        string $path,
        array $headers = [],
        $data = null
    ): Request {
        return new Request(
            $method,
            OpenRouterFreeProvider::url($path),
            $headers,
            $data
        );
    }

    protected function parseResponseToModelMetadataList(Response $response): array
    {
        /** @var ModelsResponseData $responseData */
        $responseData = $response->getData();

        if (!isset($responseData['data']) || !is_array($responseData['data'])) {
            return [];
        }

        $models = [];
        $smartRouterIds = ['openrouter/free'];
        $smartRouters = [];

        foreach ($responseData['data'] as $modelData) {
            if (!is_array($modelData) || !isset($modelData['id']) || !is_string($modelData['id'])) {
                continue;
            }

            if (!$this->isFreeModel($modelData)) {
                continue;
            }

            $metadata = $this->buildModelMetadata($modelData);

            // Surface OpenRouter's smart-router meta-models first so the AI
            // plugin's "pick first matching model" auto-selection uses the
            // router instead of an arbitrary explicit model. `openrouter/free`
            // dynamically picks the best currently-free model for each request
            // based on load, capabilities, and request shape.
            if (in_array($modelData['id'], $smartRouterIds, true)) {
                $smartRouters[] = $metadata;
            } else {
                $models[] = $metadata;
            }
        }

        // Stable sort: smart routers first (in declared order), then explicit
        // models by ID. AI Client's prompt builder picks the first metadata
        // entry whose options match the prompt requirements.
        usort(
            $models,
            static fn($a, $b) => strcmp($a->getId(), $b->getId())
        );

        return array_merge($smartRouters, $models);
    }

    /**
     * Decides whether a model entry from OpenRouter's /models response should
     * be exposed as part of this free-tier connector.
     *
     * Two ways a model can be free:
     *   1. Pricing is exactly $0 for both prompt and completion
     *      (`openrouter/free`, `openrouter/owl-alpha`).
     *   2. The ID ends with `:free`
     *      (`meta-llama/llama-3.3-70b-instruct:free`, etc.).
     *
     * @param array<string, mixed> $modelData
     */
    private function isFreeModel(array $modelData): bool
    {
        $id = $modelData['id'];
        if (is_string($id) && str_ends_with($id, ':free')) {
            return true;
        }

        $pricing = $modelData['pricing'] ?? null;
        if (!is_array($pricing)) {
            return false;
        }

        $prompt = $pricing['prompt'] ?? null;
        $completion = $pricing['completion'] ?? null;

        // OpenRouter reports `$0` as the string "0" for zero-priced models.
        // Negative numbers (`-1`) mean variable / paid; only exact "0" is free.
        return $prompt === '0' && $completion === '0';
    }

    /**
     * Builds a ModelMetadata for a single OpenRouter free model entry.
     *
     * @param array<string, mixed> $modelData One entry from `data[]` in the
     *                                        OpenRouter /models response.
     */
    private function buildModelMetadata(array $modelData): ModelMetadata
    {
        $modelId = $modelData['id'];
        $modelName = isset($modelData['name']) && is_string($modelData['name'])
            ? $modelData['name']
            : $modelId;

        $inputModalitySets = $this->extractInputModalitySets($modelData);
        $outputModalitySets = $this->extractOutputModalitySets($modelData);

        return new ModelMetadata(
            $modelId,
            $modelName,
            $this->getCapabilities(),
            [
                new SupportedOption(OptionEnum::systemInstruction()),
                new SupportedOption(OptionEnum::maxTokens()),
                new SupportedOption(OptionEnum::temperature()),
                new SupportedOption(OptionEnum::topP()),
                new SupportedOption(OptionEnum::stopSequences()),
                new SupportedOption(OptionEnum::customOptions()),
                // AI Client's ModelRequirements::fromPromptData() adds
                // inputModalities for any prompt that contains non-text content
                // (image, audio, etc.). Declaring the model's actual supported
                // input modality combinations makes it discoverable to both
                // text-only and multimodal prompts.
                new SupportedOption(OptionEnum::inputModalities(), $inputModalitySets),
                new SupportedOption(OptionEnum::outputModalities(), $outputModalitySets),
            ]
        );
    }

    /**
     * Returns all non-empty input-modality combinations the model supports.
     *
     * OpenRouter reports modalities as strings like `text`, `image`, `audio`,
     * `video`. The AI Client expects a list of modality enums and a list of
     * all supported *combinations* (each combination being a list of enums
     * that must appear together). For a model with modalities `[text, image]`,
     * we declare supported sets `[[text]]` and `[[text, image]]` so a prompt
     * requiring either text alone or text+image finds the model.
     *
     * @param array<string, mixed> $modelData
     * @return list<list<ModalityEnum>>
     */
    private function extractInputModalitySets(array $modelData): array
    {
        $modalities = $modelData['architecture']['input_modalities'] ?? ['text'];
        $enums = $this->parseModalities($modalities);
        if (empty($enums)) {
            $enums = [ModalityEnum::text()];
        }
        return $this->buildModalityCombinations($enums);
    }

    /**
     * Returns output modality combinations. OpenRouter's free tier is text-out
     * only, but we surface what the API reports.
     *
     * @param array<string, mixed> $modelData
     * @return list<list<ModalityEnum>>
     */
    private function extractOutputModalitySets(array $modelData): array
    {
        $modalities = $modelData['architecture']['output_modalities'] ?? ['text'];
        $enums = $this->parseModalities($modalities);
        if (empty($enums)) {
            $enums = [ModalityEnum::text()];
        }
        return $this->buildModalityCombinations($enums);
    }

    /**
     * @param mixed $modalities Raw `architecture.input_modalities` value.
     * @return list<ModalityEnum>
     */
    private function parseModalities($modalities): array
    {
        if (!is_array($modalities)) {
            return [];
        }
        $result = [];
        foreach ($modalities as $modality) {
            if (!is_string($modality)) {
                continue;
            }
            $enum = ModalityEnum::tryFrom($modality);
            if ($enum !== null) {
                $result[] = $enum;
            }
        }
        return $result;
    }

    /**
     * Builds all non-empty combinations of the given modalities, preserving
     * the original order so `text` is always first.
     *
     * @param list<ModalityEnum> $enums
     * @return list<list<ModalityEnum>>
     */
    private function buildModalityCombinations(array $enums): array
    {
        $count = count($enums);
        if ($count === 0) {
            return [[ModalityEnum::text()]];
        }
        $combinations = [];
        // Powerset iteration up to (1 << $count), but cap at 8 entries to
        // avoid combinatorial blowup on extreme modality lists.
        $max = min(1 << $count, 8);
        for ($mask = 1; $mask < $max; $mask++) {
            $subset = [];
            for ($i = 0; $i < $count; $i++) {
                if (($mask & (1 << $i)) !== 0) {
                    $subset[] = $enums[$i];
                }
            }
            if (!empty($subset)) {
                $combinations[] = $subset;
            }
        }
        return $combinations ?: [[ModalityEnum::text()]];
    }

    /**
     * @return list<CapabilityEnum>
     */
    private function getCapabilities(): array
    {
        return [
            CapabilityEnum::textGeneration(),
            CapabilityEnum::chatHistory(),
        ];
    }
}