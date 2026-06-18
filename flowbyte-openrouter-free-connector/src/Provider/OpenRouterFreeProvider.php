<?php

declare(strict_types=1);

namespace FlowByte\EightOpenRouterFree\Provider;

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
use FlowByte\EightOpenRouterFree\Metadata\OpenRouterFreeModelMetadataDirectory;
use FlowByte\EightOpenRouterFree\Models\OpenRouterFreeTextGenerationModel;

class OpenRouterFreeProvider extends AbstractApiProvider
{
    protected static function baseUrl(): string
    {
        return 'https://openrouter.ai/api/v1';
    }

    protected static function createModel(
        ModelMetadata $modelMetadata,
        ProviderMetadata $providerMetadata
    ): ModelInterface {
        $capabilities = $modelMetadata->getSupportedCapabilities();
        foreach ($capabilities as $capability) {
            if ($capability->isTextGeneration()) {
                return new OpenRouterFreeTextGenerationModel($modelMetadata, $providerMetadata);
            }
        }

        throw new RuntimeException(
            'Unsupported model capabilities: ' . implode(', ', $capabilities)
        );
    }

    protected static function createProviderMetadata(): ProviderMetadata
    {
        return new ProviderMetadata(
            'flowbyte-openrouter-free',
            '808 AI (OpenRouter Free)',
            ProviderTypeEnum::cloud(),
            'https://openrouter.ai/',
            RequestAuthenticationMethod::apiKey(),
            'OpenRouter Free — access to free models including Llama, Mistral, and OpenChat.'
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
        return new OpenRouterFreeModelMetadataDirectory();
    }
}