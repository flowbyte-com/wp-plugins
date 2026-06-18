<?php

namespace FlowByte\EightOpenRouterProvider\Metadata;

use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiBasedModelMetadataDirectory;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;

class OpenRouterModelMetadataDirectory extends AbstractApiBasedModelMetadataDirectory
{
    protected function sendListModelsRequest(): array
    {
        return $this->buildStaticModelMap();
    }

    protected function buildStaticModelMap(): array
    {
        $models = [
            'openrouter/auto' => 'OpenRouter Auto (best available)',
            'openai/gpt-4o' => 'GPT-4o',
            'openai/gpt-4o-mini' => 'GPT-4o Mini',
            'openai/gpt-4-turbo' => 'GPT-4 Turbo',
            'anthropic/claude-3.5-sonnet' => 'Claude 3.5 Sonnet',
            'anthropic/claude-3-opus' => 'Claude 3 Opus',
            'anthropic/claude-3-haiku' => 'Claude 3 Haiku',
            'google/gemini-pro-1.5' => 'Gemini Pro 1.5',
            'google/gemini-flash-1.5' => 'Gemini Flash 1.5',
            'meta-llama/llama-3-70b-instruct' => 'Llama 3 70B',
            'meta-llama/llama-3-8b-instruct' => 'Llama 3 8B',
            'deepseek/deepseek-chat' => 'DeepSeek Chat',
            'mistralai/mistral-7b-instruct' => 'Mistral 7B',
            'mistralai/mixtral-8x7b-instruct' => 'Mixtral 8x7B',
            'x-ai/grok-2' => 'Grok 2',
            'perplexity/llama-3-sonar-small-32b-online' => 'Sonar Small (online)',
            'perplexity/llama-3-sonar-large-32b-online' => 'Sonar Large (online)',
        ];

        $modelMetadataMap = [];
        foreach ($models as $modelId => $modelName) {
            $modelMetadataMap[$modelId] = new ModelMetadata(
                $modelId,
                $modelName,
                $this->getCapabilities(),
                $this->getSupportedOptions()
            );
        }

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
        ];
    }

    protected function parseResponseToModelMetadataList(Response $response): array
    {
        return [];
    }

    protected function getModelsApiPath(): string
    {
        return '/models';
    }
}