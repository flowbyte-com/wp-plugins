<?php

declare(strict_types=1);

namespace FlowByte\EightMinimax\Provider;

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
use FlowByte\EightMinimax\Metadata\MinimaxModelMetadataDirectory;
use FlowByte\EightMinimax\Models\MinimaxTextGenerationModel;

/**
 * Class for the 808 AI MiniMax provider.
 *
 * @since 1.0.0
 */
class MinimaxProvider extends AbstractApiProvider
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
                return new MinimaxTextGenerationModel($modelMetadata, $providerMetadata);
            }
        }

        throw new RuntimeException(
            'Unsupported model capabilities: ' . implode(', ', $capabilities)
        );
    }

    protected static function createProviderMetadata(): ProviderMetadata
    {
        return new ProviderMetadata(
            'flowbyte-minimax',
            '808 AI (MiniMax)',
            ProviderTypeEnum::cloud(),
            'https://www.minimax.io/',
            RequestAuthenticationMethod::apiKey(),
            'MiniMax M2.7 — token plan billing, same cost for all models.'
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
        return new MinimaxModelMetadataDirectory();
    }
}