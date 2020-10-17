<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model
{
    //
    protected $table = 'posts';
    protected $fillable = [
    	'id', 'user_id', 'content', 'link', 'video_link', 'created_at', 'modified_at'
    ];
}


