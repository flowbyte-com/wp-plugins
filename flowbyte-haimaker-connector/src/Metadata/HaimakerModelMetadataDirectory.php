<?php

namespace FlowByte\EightHaimaker\Metadata;

use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiBasedModelMetadataDirectory;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;

class HaimakerModelMetadataDirectory extends AbstractApiBasedModelMetadataDirectory
{
    protected function sendListModelsRequest(): array
    {
        return $this->buildStaticModelMap();
    }

    protected function buildStaticModelMap(): array
    {
        $modelMetadataMap = [];
        $modelMetadataMap['haimaker/auto'] = new ModelMetadata(
            'haimaker/auto',
            'Haimaker Auto',
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