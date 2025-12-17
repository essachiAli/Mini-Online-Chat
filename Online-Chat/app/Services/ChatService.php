<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ChatService
{
    public function validateMessage(array $data): array
    {
        $validator = Validator::make($data, [
            'username' => 'required|string|max:50',
            'content' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function storeMessage(array $data): Message
    {
        $validated = $this->validateMessage($data);
        return Message::create($validated);
    }

    public function fetchMessages(): Collection
    {
        return Message::orderBy('created_at', 'asc')->get();
    }

    public function getMessagesArray(): array
    {
        return $this->fetchMessages()->map(function ($message) {
            return [
                'id' => $message->id,
                'username' => $message->username,
                'content' => $message->content,
                'created_at' => $message->created_at->format('H:i')
            ];
        })->toArray();
    }
}
