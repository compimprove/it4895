<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chat extends Model
{
    protected $guarded = [];
    protected $fillable = ['content', 'user_a_id', "user_b_id"];

    public function from()
    {
        return $this->belongsTo(User::class, 'user_a_id');
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'user_b_id');
    }

    public static function getMessages($userId1, $userId2)
    {
        $chat1 = DB::table('chats')->where('user_a_id', '=', $userId1)->where('user_b_id', '=', $userId2)->get();
        $chat2 = DB::table('chats')->where('user_a_id', '=', $userId2)->where('user_b_id', '=', $userId1)->get();
        $chats =  $chat1->concat($chat2)->toArray();
        sort($chats);
        return $chats;
    }
}
