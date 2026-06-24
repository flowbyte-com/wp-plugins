<?php

namespace FlowByte\EightMinimaxToken\Metadata;

use WordPress\AiClient\Messages\Enums\ModalityEnum;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiBasedModelMetadataDirectory;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;

class MinimaxTokenModelMetadataDirectory extends AbstractApiBasedModelMetadataDirectory
{
    protected function sendListModelsRequest(): array
    {
        // MiniMax-M3 first: current generation with reasoning enabled,
        // ~1.5s P50 for short prompts. M2.7/M2.7-highspeed are kept for users
        // who prefer them but default to M3 so AI plugin auto-select picks
        // the fastest option. The OpenAI-compatible /v1/chat/completions
        // endpoint accepts any of these IDs (M2.7 is a legacy alias).
        $modelMetadataMap = [];
        $modelMetadataMap['MiniMax-M3'] = new ModelMetadata(
            'MiniMax-M3',
            'MiniMax M3 (Token Plan)',
            $this->getCapabilities(),
            $this->getSupportedOptions()
        );
        $modelMetadataMap['MiniMax-M2.7'] = new ModelMetadata(
            'MiniMax-M2.7',
            'MiniMax M2.7 (Token Plan)',
            $this->getCapabilities(),
            $this->getSupportedOptions()
        );
        $modelMetadataMap['MiniMax-M2.7-highspeed'] = new ModelMetadata(
            'MiniMax-M2.7-highspeed',
            'MiniMax M2.7 Highspeed (Token Plan)',
            $this->getCapabilities(),
            $this->getSupportedOptions()
        );
        return $modelMetadataMap;
    }

    protected function getCapabilities(): array
    {
        return [
            CapabilityEnum::textGeneration(),
            CapabilityEnum::chatHistory(),
        ];
    }

    protected function getSupportedOptions(): array
    {
        return [
            new SupportedOption(OptionEnum::systemInstruction()),
            new SupportedOption(OptionEnum::maxTokens()),
            new SupportedOption(OptionEnum::temperature()),
            new SupportedOption(OptionEnum::topP()),
            new SupportedOption(OptionEnum::stopSequences()),
            new SupportedOption(OptionEnum::customOptions()),
            // Required: AI Client's ModelRequirements::fromPromptData() adds
            // inputModalities=[text] for any prompt with text content, and
            // generateTextResult() adds outputModalities=[text]. Without
            // declaring these, ModelRequirements::areMetBy() returns false
            // and the provider is invisible to text-generation discovery.
            new SupportedOption(
                OptionEnum::inputModalities(),
                [[ModalityEnum::text()]]
            ),
            new SupportedOption(
                OptionEnum::outputModalities(),
                [[ModalityEnum::text()]]
            ),
        ];
    }

    protected function parseResponseToModelMetadataList(Response $response): array
    {
        // AbstractApiBasedModelMetadataDirectory does not invoke this method;
        // sendListModelsRequest() returns the metadata map directly. Kept for
        // completeness in case future upstream changes route through here.
        return [];
    }

    protected function getModelsApiPath(): string
    {
        return '/models';
    }
}