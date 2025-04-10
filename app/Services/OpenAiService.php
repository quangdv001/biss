<?php

namespace App\Services;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAiService
{
    public function send($content) {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $content],
            ],
        ]);

        return $result->choices[0]->message->content;
    }
}
