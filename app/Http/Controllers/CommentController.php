<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

use App\User;
use App\Enums\ApiStatusCode;
use App\Enums\URL;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    
    public function addComment(Request $request) {

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
    	}

    	
    	
		$comment = new Comment([
            'user_id' => 1,
            'post_id'=>1,
            'content' => $request['described'],
          
        ]);

        if ($post->save()) {

        	return response()->json(
        		[
        			'code' => ApiStatusCode::OK,
        			'message' => 'Tạo comment thành công',
        			'data' => [
        				'id' => $comment->id,
        				'url' => URL::ADDRESS . '/comment/' . $comment->id
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
    	$comment = Comment::where('id', $id)->first();
    	//$post=Post::where('comment_id',$comment->id);
    	$user = User::where('id', $comment->user_id)->first();

    	return response()->json([
    		'code' => ApiStatusCode::OK, 
    		'message' => 'Lấy comment thành công',
    		'data' => [
    			'id' => $comment->id,
    			'described' => $comment->content,
    			'created' => $comment->created_at,
    			'modified' => $comment->updated_at,
    			'post_id' => $comment->post_id,
    		],
    		//'post'=>$post,
    		'author' => $user
    	]);
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
