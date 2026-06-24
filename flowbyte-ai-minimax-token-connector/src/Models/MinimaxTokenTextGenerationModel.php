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

    /**
     * Strips the <think>...</think> reasoning wrapper from MiniMax content.
     *
     * MiniMax's reasoning-enabled models (M2.7, M3) emit chain-of-thought
     * inline in `choices[].message.content` as `<think>...</think>\n\n<answer>`.
     * The OpenAI-compatible parser preserves `content` verbatim, which would
     * leak the model's reasoning into the user-visible result. Strip the
     * wrapper here so the assistant surface text matches expectations.
     *
     * @param string $content Raw `content` field from the API response.
     * @return string Content with the leading reasoning block removed.
     */
    private function stripThinkingWrapper(string $content): string
    {
        $cleaned = preg_replace('/^\s*<think>.*?<\/think>\s*/s', '', $content);
        return is_string($cleaned) ? $cleaned : $content;
    }

    protected function parseResponseChoiceMessageParts(array $messageData, int $index): array
    {
        $parts = parent::parseResponseChoiceMessageParts($messageData, $index);
        foreach ($parts as $i => $part) {
            if ($part->getType()->isText()) {
                $cleaned = $this->stripThinkingWrapper($part->getText());
                if ($cleaned !== $part->getText()) {
                    $parts[$i] = new \WordPress\AiClient\Messages\DTO\MessagePart($cleaned);
                    break;
                }
            }
        }
        return $parts;
    }
}