<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;
use App\User;
use App\Enums\ApiStatusCode;
use App\Enums\URL;
use Illuminate\Support\Facades\Validator;
use DB;

class CommentController extends Controller
{
    
    public function addComment(Request $request,$id) {

    	$validator = Validator::make($request->all(), [
            'described' => 'required'
        ]);

    	if ($validator->fails()) {
    		return response()->json([
    			'code' => ApiStatusCode::PARAMETER_NOT_ENOUGH,
    			'message' => 'Số lượng parameter không dầy đủ',
    			'data' => $validator->errors()
    		]);
    	}
    	else {
    		$validator = Validator::make($request->all(), [
	            'described' => 'string',
	            
	        ]);

	        if ($validator->fails()) {
	    		return response()->json([
	    			'code' => ApiStatusCode::PARAMETER_TYPE_INVALID,
	    			'message' => 'Kiểu tham số không đúng đắn',
	    			'data' => $validator->errors()
	    		]);
			}
			else {
				$post = Post::find($id);
				if ($post == null) {
					return [
						"code" => 9992,
						"message" => "Bài viết không tồn tại"
					];
			}
		}
		}
		

    	$post = Post::find($id);
    
		$comment = new Comment([
            'user_id' =>$post->user_id,
            'post_id'=>$id,
            'content' => $request['described'],
          
        ]);

        if ($comment->save()) {

        	return response()->json(
        		[
        			'code' => ApiStatusCode::OK,
        			'message' => 'Tạo comment thành công',
        			'data' => [
						'user_id'=>$comment->user_id,
        				'post_id' => $id,
        				'content'=>$request['described']
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

    public function getComment($id) {
		$post = Post::where('id', $id)->first();
		$comments = Comment::where('post_id', $id)->get();
		foreach($comments as $comment) {
			$comment['author'] = User::where('id', $comment["user_id"])->get();
		}
		if ($post == null) {
			return [
				"code" => 9992,
				"message" => "Bài viết không tồn tại"
			];
		}
		
    	else {
			$user=User::find($post->user_id);
			return response()->json([
    		'code' => ApiStatusCode::OK, 
    		'message' => 'Lấy comment bài viết thành công',
    		'data' => [
				'post'=>$post,
				'comment' =>$comments,
    			'author' => $user
			
    		],
    		
		]);
	  }
    }

    public function deleteComment($id) {
    	$comment = Comment::where('id', $id)->first();

    	if($comment->delete()) {
		
    		return response()->json([
    			'code' => ApiStatusCode::OK,
    			'message' => 'Xóa comment thành công'
			]);
			}
    	
    }
}
