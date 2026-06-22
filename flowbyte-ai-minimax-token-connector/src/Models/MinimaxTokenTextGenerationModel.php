<?php

declare(strict_types=1);

namespace FlowByte\EightMinimaxToken\Models;

use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Contracts\RequestAuthenticationInterface;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;
use FlowByte\EightMinimaxToken\Authentication\MinimaxTokenApiKeyRequestAuthentication;
use FlowByte\EightMinimaxToken\Provider\MinimaxTokenProvider;

class MinimaxTokenTextGenerationModel extends AbstractOpenAiCompatibleTextGenerationModel
{
    public function getRequestAuthentication(): RequestAuthenticationInterface
    {
        $requestAuthentication = parent::getRequestAuthentication();
        if (!$requestAuthentication instanceof ApiKeyRequestAuthentication) {
            return $requestAuthentication;
        }
        return new MinimaxTokenApiKeyRequestAuthentication($requestAuthentication->getApiKey());
    }

    protected function createRequest($method, string $path, array $headers = [], $data = null): Request
    {
        return new Request(
            $method,
            MinimaxTokenProvider::url($path),
            $headers,
            $data,
            $this->getRequestOptions()
        );
    }
}
