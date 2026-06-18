<?php

declare(strict_types=1);

namespace FlowByte\EightMinimax\Models;

use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Contracts\RequestAuthenticationInterface;
use WordPress\AiClient\Providers\Models\Enums\MessageRoleEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;
use FlowByte\EightMinimax\Authentication\MinimaxApiKeyRequestAuthentication;
use FlowByte\EightMinimax\Provider\MinimaxProvider;
use WordPress\AiClient\Messages\Enums\MessageRoleEnum as CoreMessageRoleEnum;

class MinimaxTextGenerationModel extends AbstractOpenAiCompatibleTextGenerationModel
{
    public function getRequestAuthentication(): RequestAuthenticationInterface
    {
        $requestAuthentication = parent::getRequestAuthentication();
        if (!$requestAuthentication instanceof ApiKeyRequestAuthentication) {
            return $requestAuthentication;
        }
        return new MinimaxApiKeyRequestAuthentication($requestAuthentication->getApiKey());
    }

    protected function createRequest($method, string $path, array $headers = [], $data = null): Request
    {
        return new Request(
            $method,
            MinimaxProvider::url($path),
            $headers,
            $data,
            $this->getRequestOptions()
        );
    }

    protected function prepareGenerateTextParams(array $prompt): array
    {
        $config = $this->getConfig();

        $params = [
            'model' => $this->metadata()->getId(),
            'messages' => $this->prepareMessagesParam($prompt),
        ];

        $systemInstruction = $config->getSystemInstruction();
        if ($systemInstruction !== null && $systemInstruction !== '') {
            $params['system'] = $systemInstruction;
        }

        $maxTokens = $config->getMaxTokens();
        if ($maxTokens !== null) {
            $params['max_tokens'] = $maxTokens;
        } else {
            $params['max_tokens'] = 2048;
        }

        $temperature = $config->getTemperature();
        if ($temperature !== null) {
            $params['temperature'] = $temperature;
        }

        $topP = $config->getTopP();
        if ($topP !== null) {
            $params['top_p'] = $topP;
        }

        $stopSequences = $config->getStopSequences();
        if (!empty($stopSequences)) {
            $params['stop_sequences'] = $stopSequences;
        }

        return $params;
    }

    protected function prepareMessagesParam(array $messages): array
    {
        $anthropicMessages = [];

        foreach ($messages as $message) {
            $roleValue = $message->getRole();
            // Check if it's a system message by role value
            $roleString = is_object($roleValue) && method_exists($roleValue, 'value') ? $roleValue->value : (string) $roleValue;
            if ($roleString === 'system') {
                continue;
            }
            $apiRole = ($roleString === 'model') ? 'assistant' : 'user';
            $content = [];
            foreach ($message->getParts() as $part) {
                $text = is_object($part) && method_exists($part, 'getText') ? $part->getText() : '';
                if ($text !== '') {
                    $content[] = ['type' => 'text', 'text' => $text];
                }
            }
            if (!empty($content)) {
                $anthropicMessages[] = [
                    'role' => $apiRole,
                    'content' => $content,
                ];
            }
        }

        return $anthropicMessages;
    }
}