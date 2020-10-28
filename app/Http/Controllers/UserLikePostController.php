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
    public function likePost(Request $request,$user_id,$post_id) {
		$user=User::find($user_id);
		$like = new UserLikePost([
            'user_id'=>$user_id,
            'post_id' => $post_id,
		]);
		    $post = Post::find($post_id);
				if ($post == null) {
					return [
						"code" => 9992,
						"message" => "Bài viết không tồn tại"
					];
		}
        if ($like->save()) {
			$post=Post::find($post_id);
			
        	return response()->json(
        		[
        			'code' => ApiStatusCode::OK,
        			'message' => 'Liked',
        			'data' => [
        				'post_id' => $post_id,
        				'user'=>$like->user_id
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
    	$like = UserLikePost::where('post_id', $id)->count();
  
	
		if ($post == null) {
			return [
				"code" => 9992,
				"message" => "Bài viết không tồn tại"
			];
		}
		else{
    	return response()->json([
			
    		'code' => ApiStatusCode::OK, 
    		'message' => 'Lấy số like thành công',
    		'data' => [
				'post_id'=>$post,
				'count_like'=>$like
    		],
    		
    		
    	]);}
    }
}
