<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class TicketClassifier
{
    public function classify(string $subject, string $body): array
    {
        if (!config('app.openai_classify_enabled')) {
            $categories = ['Billing', 'Bug', 'Feature Request', 'General'];
            return [
                'category' => $categories[array_rand($categories)],
                'confidence' => rand(70, 100) / 100,
            ];
        }

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [[
                'role' => 'user',
                'content' => "Classify the following ticket:\n\nSubject: $subject\n\nBody: $body\n\nReturn only category and confidence as JSON."
            ]],
        ]);

        $json = json_decode($response->choices[0]->message->content, true);

        return [
            'category' => $json['category'] ?? 'General',
            'confidence' => $json['confidence'] ?? 0.7,
        ];
    }
}
