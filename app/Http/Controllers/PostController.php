<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    //
    public function addPost(Request $request) {

		$post = new Post([
			'id' => 2,
            'user_id' => 2,
            'content' => $request['content'],
            'video_link' => $request['video_link']
        ]);
        if ($post->save()) {
        	return response()->json(
        		[
        			'message' => 'Tạo bài viết thành công'
        		]
        	);
        }
        else return response()->json(
        	[
    			'message' => 'Tạo bài viết thất bại'
    		], 
    		400
        );
    }
}
