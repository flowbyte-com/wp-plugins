<?php

declare(strict_types=1);

namespace FlowByte\EightMinimaxStandard\Models;

use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Contracts\RequestAuthenticationInterface;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;
use FlowByte\EightMinimaxStandard\Authentication\MinimaxStandardApiKeyRequestAuthentication;
use FlowByte\EightMinimaxStandard\Provider\MinimaxStandardProvider;

class MinimaxStandardTextGenerationModel extends AbstractOpenAiCompatibleTextGenerationModel
{
    public function getRequestAuthentication(): RequestAuthenticationInterface
    {
        $requestAuthentication = parent::getRequestAuthentication();
        if (!$requestAuthentication instanceof ApiKeyRequestAuthentication) {
            return $requestAuthentication;
        }
        return new MinimaxStandardApiKeyRequestAuthentication($requestAuthentication->getApiKey());
    }

    protected function createRequest($method, string $path, array $headers = [], $data = null): Request
    {
        return new Request(
            $method,
            MinimaxStandardProvider::url($path),
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
            $params['stop'] = $stopSequences;
        }

        return $params;
    }

    protected function prepareMessagesParam(array $messages): array
    {
        $result = [];
        foreach ($messages as $message) {
            $roleValue = $message->getRole();
            $roleString = is_object($roleValue) && method_exists($roleValue, 'value') ? $roleValue->value : (string) $roleValue;
            if ($roleString === 'system') {
                continue;
            }
            $apiRole = ($roleString === 'model') ? 'assistant' : 'user';
            $textContent = '';
            foreach ($message->getParts() as $part) {
                $text = is_object($part) && method_exists($part, 'getText') ? $part->getText() : (is_string($part) ? $part : '');
                if (is_string($text) && $text !== '') {
                    $textContent .= $text;
                }
            }
            if ($textContent !== '') {
                $result[] = ['role' => $apiRole, 'content' => $textContent];
            }
        }
        return $result;
    }
}