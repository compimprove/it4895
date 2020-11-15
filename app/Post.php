<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model
{
    //
    protected $table = 'posts';
    protected $fillable = [
        'id', 'user_id', 'content', 'like'
    ];

    public function images() {
   		return $this->hasMany('App\Images');
    }
}


