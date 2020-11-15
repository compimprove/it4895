<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\ChatEvent;
use App\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('chat', ['token' => $user->createToken(env('APP_KEY'))->plainTextToken, 'userId' => $user->id]);
    }

    public function fetchAllMessages(Request $request, $userId2)
    {
        return Chat::getMessages($request->user()->id, $userId2);
    }

    public function sendMessage(Request $request, $userId2)
    {
        $chat = new Chat();
        $chat->content = $request->query('content');
        $chat->user_a_id = $request->user()->id;
        $chat->user_b_id = (int)$userId2;
        $chat->save();
        broadcast(new ChatEvent($chat));
        return $chat;
    }
}
