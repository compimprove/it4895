<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\ChatEvent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchAllMessages(Request $request)
    {
    }

    public function sendMessage(Request $request)
    {
        $chat = $request->user()->messages()->create([
            'message' => $request->message
        ]);
        broadcast(new ChatEvent($chat->load('user')))->toOthers();
        return ['status' => 'success'];
    }
}
