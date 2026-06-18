<?php

declare(strict_types=1);

namespace FlowByte\EightOpenRouterProvider\Models;

use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Contracts\RequestAuthenticationInterface;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;
use FlowByte\EightOpenRouterProvider\Authentication\OpenRouterApiKeyRequestAuthentication;
use FlowByte\EightOpenRouterProvider\Provider\OpenRouterProvider;

class OpenRouterTextGenerationModel extends AbstractOpenAiCompatibleTextGenerationModel
{
    public function getRequestAuthentication(): RequestAuthenticationInterface
    {
        $requestAuthentication = parent::getRequestAuthentication();
        if (!$requestAuthentication instanceof ApiKeyRequestAuthentication) {
            return $requestAuthentication;
        }
        return new OpenRouterApiKeyRequestAuthentication($requestAuthentication->getApiKey());
    }

    protected function createRequest($method, string $path, array $headers = [], $data = null): Request
    {
        return new Request(
            $method,
            OpenRouterProvider::url($path),
            $headers,
            $data,
            $this->getRequestOptions()
        );
    }
}