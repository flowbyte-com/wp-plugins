<?php

declare(strict_types=1);

namespace FlowByte\EightMinimaxToken\Authentication;

use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;
use WordPress\AiClient\Providers\Http\DTO\Request;

class MinimaxTokenApiKeyRequestAuthentication extends ApiKeyRequestAuthentication
{
    public function authenticateRequest(Request $request): Request
    {
        return $request->withHeader('Authorization', 'Bearer ' . $this->apiKey);
    }
}