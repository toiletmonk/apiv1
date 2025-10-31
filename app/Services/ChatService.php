<?php

namespace App\Services;

use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;

class ChatService
{
    protected string $systemPrompt = "
        You are a customer support assistant for DummyShop.
        - Greet the user.
        - Answer clearly and politely.
        - Provide actionable steps.
        - If you cannot answer, respond: 'I’m not sure, let me connect you to a human agent.'
        - Do not handle payments or sensitive account changes.
    ";

    public function ask(string $userMessage): string
    {
        $response = Prism::text()
            ->using(Provider::OpenAI, 'gpt-4o')
            ->withSystemPrompt($this->systemPrompt)
            ->withPrompt($userMessage)
            ->asText();

        return $response->text;
    }
}
