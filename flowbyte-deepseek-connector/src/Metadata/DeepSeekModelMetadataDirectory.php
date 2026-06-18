<?php

namespace FlowByte\EightDeepSeek\Metadata;

use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiBasedModelMetadataDirectory;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;

class DeepSeekModelMetadataDirectory extends AbstractApiBasedModelMetadataDirectory
{
    protected function sendListModelsRequest(): array
    {
        return $this->buildStaticModelMap();
    }

    protected function buildStaticModelMap(): array
    {
        $models = [
            'deepseek-chat' => 'DeepSeek Chat',
            'deepseek-coder' => 'DeepSeek Coder',
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