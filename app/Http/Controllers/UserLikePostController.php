<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\UserLikePost;
use App\User;
use App\Enums\ApiStatusCode;
use App\Enums\URL;
use Illuminate\Support\Facades\Validator;

class UserLikePostController extends Controller
{
    //
    public function likePost(Request $request,$id) {
    
		$like = new UserLikePost([
            'user_id' => 1,
            'post_id' => $id,
        ]);

        if ($like->save()) {
	$post=Post::find($id);
        	return response()->json(
        		[
        			'code' => ApiStatusCode::OK,
        			'message' => 'Liked',
        			'data' => [
        				'post_id' => $id,
        				'user_id'=>$post->user_id
        			]
				]
				
        	);
        }
        else return response()->json(
        	[
        		'code' => ApiStatusCode::LOST_CONNECT,
    			'message' => 'Lỗi mất kết nối DB/ hoặc lỗi thực thi câu lệnh DB'
    		]
        );
    }

    public function getlikePost($id) {
		$post = Post::where('id', $id)->first();
    	$like = UserLikePost::where('post_id', $post->id)->first();
  
		$user = User::where('id', $post->user_id)->first();
    	return response()->json([
			
    		'code' => ApiStatusCode::OK, 
    		'message' => 'Lấy số like thành công',
    		'data' => [
				'post_id'=>$id,
				'like_id'=>$like->id,
    			'created' => $like->created_at,
    			'modified' => $like->updated_at,
    		],
    		
    		'author' => $user
    	]);
    }
}
