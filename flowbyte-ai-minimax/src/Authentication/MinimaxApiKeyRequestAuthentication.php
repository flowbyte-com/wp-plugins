<?php

declare(strict_types=1);

namespace FlowByte\EightMinimax\Authentication;

use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;
use WordPress\AiClient\Providers\Http\DTO\Request;

class MinimaxApiKeyRequestAuthentication extends ApiKeyRequestAuthentication
{
    public function authenticateRequest(Request $request): Request
    {
        return $request->withHeader('Authorization', 'Bearer ' . $this->apiKey);
    }
}