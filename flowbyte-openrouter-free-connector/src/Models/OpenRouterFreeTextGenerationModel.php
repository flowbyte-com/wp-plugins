<?php

declare(strict_types=1);

namespace FlowByte\EightOpenRouterFree\Models;

use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Contracts\RequestAuthenticationInterface;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;
use FlowByte\EightOpenRouterFree\Authentication\OpenRouterFreeApiKeyRequestAuthentication;
use FlowByte\EightOpenRouterFree\Provider\OpenRouterFreeProvider;

class OpenRouterFreeTextGenerationModel extends AbstractOpenAiCompatibleTextGenerationModel
{
    public function getRequestAuthentication(): RequestAuthenticationInterface
    {
        $requestAuthentication = parent::getRequestAuthentication();
        if (!$requestAuthentication instanceof ApiKeyRequestAuthentication) {
            return $requestAuthentication;
        }
        return new OpenRouterFreeApiKeyRequestAuthentication($requestAuthentication->getApiKey());
    }

    protected function createRequest($method, string $path, array $headers = [], $data = null): Request
    {
        return new Request(
            $method,
            OpenRouterFreeProvider::url($path),
            $headers,
            $data,
            $this->getRequestOptions()
        );
    }
}