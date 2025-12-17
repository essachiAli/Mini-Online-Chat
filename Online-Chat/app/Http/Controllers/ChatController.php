<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index(): View
    {
        return view('chat');
    }

    public function fetchMessages(): JsonResponse
    {
        try {
            $messages = $this->chatService->getMessagesArray();
            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch messages'
            ], 500);
        }
    }

    public function sendMessage(Request $request): JsonResponse
    {
        try {
            $message = $this->chatService->storeMessage($request->all());
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'username' => $message->username,
                    'content' => $message->content,
                    'created_at' => $message->created_at->format('H:i')
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message'
            ], 500);
        }
    }
}
