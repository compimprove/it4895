<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

use App\User;
use App\Enums\ApiStatusCode;
use App\Enums\URL;
use Illuminate\Support\Facades\Validator;

class UserLikePostController extends Controller
{
    //
    public function likePost(Request $request) {
        $validator = Validator::make($request->all(), [
            
        ]);
        if ($validator->fails()) {
            
        }
		$like = new UserLikePost([
            'user_id' => 1,
            'post_id' => 1,
        
        ]);

        if ($like->save()) {
	
        	return response()->json(
        		[
        			'code' => ApiStatusCode::OK,
        			'message' => 'Liked',
        			'data' => [
        				'id' => $like->id,
        				'url' => URL::ADDRESS . '/like/' . $like->id
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
    	$like = UserLikePost::where('id', $id)->first();
    	$post = Post::where('like', $like->id);
    	$user = User::where('id', $like->user_id)->first();

    	return response()->json([
    		'code' => ApiStatusCode::OK, 
    		'message' => 'Lấy số like thành công',
    		'data' => [
    			'id' => $like->id,
    			'created' => $like->created_at,
    			'modified' => $like->updated_at,
    		],
    		'post' => $post,
    		'author' => $user
    	]);
    }

    public function dislikePost($id) {
    	$like = UserLikePost::where('id', $id)->first();
    	if($like->delete()) {
    		return response()->json([
    			'code' => ApiStatusCode::OK,
    			'message' => 'Disliked'
    		]);
    	}
    }
}
