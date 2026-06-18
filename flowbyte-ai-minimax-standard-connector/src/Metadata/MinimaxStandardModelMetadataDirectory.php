<?php

namespace FlowByte\EightMinimaxStandard\Metadata;

use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiBasedModelMetadataDirectory;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;

class MinimaxStandardModelMetadataDirectory extends AbstractApiBasedModelMetadataDirectory
{
    protected function sendListModelsRequest(): array
    {
        // Static model map — MiniMax standard models
        $models = [
            'MiniMax-Text-01' => 'MiniMax Text 01',
            'MiniMax-Text-01-preview' => 'MiniMax Text 01 Preview',
            'abab6.5s-chat' => 'ABAB 6.5S Chat',
            'abab6.5-chat' => 'ABAB 6.5 Chat',
            'abab5.5-chat' => 'ABAB 5.5 Chat',
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