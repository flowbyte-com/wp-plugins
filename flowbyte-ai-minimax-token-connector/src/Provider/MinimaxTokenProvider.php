<?php

declare(strict_types=1);

namespace FlowByte\EightMinimaxToken\Provider;

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
use FlowByte\EightMinimaxToken\Metadata\MinimaxTokenModelMetadataDirectory;
use FlowByte\EightMinimaxToken\Models\MinimaxTokenTextGenerationModel;

class MinimaxTokenProvider extends AbstractApiProvider
{
    protected static function baseUrl(): string
    {
        return 'https://api.minimax.io/anthropic/v1';
    }

    protected static function createModel(
        ModelMetadata $modelMetadata,
        ProviderMetadata $providerMetadata
    ): ModelInterface {
        $capabilities = $modelMetadata->getSupportedCapabilities();
        foreach ($capabilities as $capability) {
            if ($capability->isTextGeneration()) {
                return new MinimaxTokenTextGenerationModel($modelMetadata, $providerMetadata);
            }
        }

        throw new RuntimeException(
            'Unsupported model capabilities: ' . implode(', ', $capabilities)
        );
    }

    protected static function createProviderMetadata(): ProviderMetadata
    {
        return new ProviderMetadata(
            'flowbyte-minimax-token',
            '808 AI (MiniMax Token Plan)',
            ProviderTypeEnum::cloud(),
            'https://www.minimax.io/',
            RequestAuthenticationMethod::apiKey(),
            'MiniMax Token Plan — same cost for all models, M2.7 token plan billing.'
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
        return new MinimaxTokenModelMetadataDirectory();
    }
}