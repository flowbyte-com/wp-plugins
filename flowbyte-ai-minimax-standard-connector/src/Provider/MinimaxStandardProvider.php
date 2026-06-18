<?php

declare(strict_types=1);

namespace FlowByte\EightMinimaxStandard\Provider;

use WordPress\AiClient\Common\Exception\RuntimeException;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiProvider;
use WordPress\AiClient\Providers\ApiBasedImplementation\ListModelsApiBasedProviderAvailability;
use WordPress\AiClient\Providers\Contracts\ModelMetadataDirectoryInterface;
use WordPress\AiClient\Providers\Contracts\ProviderAvailabilityInterface;
use WordPress\AiClient\Providers\DTO\ProviderMetadata;
use WordPress\AiClient\Providers\Enums\ProviderTypeEnum;
use WordPress\AiClient\Providers\Http\Enums\RequestAuthenticationMethod;
use WordPress\AiClient\Providers\Models\Contracts\ModelInterface;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use FlowByte\EightMinimaxStandard\Metadata\MinimaxStandardModelMetadataDirectory;
use FlowByte\EightMinimaxStandard\Models\MinimaxStandardTextGenerationModel;

class MinimaxStandardProvider extends AbstractApiProvider
{
    protected static function baseUrl(): string
    {
        // Standard MiniMax OpenAI-compatible endpoint
        return 'https://api.minimax.io/v1';
    }

    protected static function createModel(
        ModelMetadata $modelMetadata,
        ProviderMetadata $providerMetadata
    ): ModelInterface {
        $capabilities = $modelMetadata->getSupportedCapabilities();
        foreach ($capabilities as $capability) {
            if ($capability->isTextGeneration()) {
                return new MinimaxStandardTextGenerationModel($modelMetadata, $providerMetadata);
            }
        }

        throw new RuntimeException(
            'Unsupported model capabilities: ' . implode(', ', $capabilities)
        );
    }

    protected static function createProviderMetadata(): ProviderMetadata
    {
        return new ProviderMetadata(
            'flowbyte-minimax-standard',
            '808 AI (MiniMax Standard)',
            ProviderTypeEnum::cloud(),
            'https://www.minimax.io/',
            RequestAuthenticationMethod::apiKey(),
            'MiniMax Standard — OpenAI-compatible API endpoint.'
        );
    }

    protected static function createProviderAvailability(): ProviderAvailabilityInterface
    {
        return new ListModelsApiBasedProviderAvailability(
            static::modelMetadataDirectory()
        );
    }

    protected static function createModelMetadataDirectory(): ModelMetadataDirectoryInterface
    {
        return new MinimaxStandardModelMetadataDirectory();
    }
}