<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(protected ChatService $chatService) {}

    public function chat(ChatRequest $request)
    {
        $request->validated();

        $userMessage = $request->input('message');

        $reply = $this->chatService->ask($userMessage);

        return response()->json([
            'reply' => $reply,
        ]);
    }
}
