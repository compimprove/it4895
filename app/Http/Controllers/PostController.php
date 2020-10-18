<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Images;
use App\Enums\ApiStatusCode;
use App\Enums\URL;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    //
    public function addPost(Request $request) {

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
	            'video_link' => 'string',
	            'image_link' => 'string'
	        ]);

	        if ($validator->fails()) {
	    		return response()->json([
	    			'code' => ApiStatusCode::PARAMETER_TYPE_INVALID,
	    			'message' => 'Kiểu tham số không đúng đắn',
	    			'data' => $validator->errors()
	    		]);
	    	}
    	}

    	
		$post = new Post([
            'user_id' => 1,
            'content' => $request['described'],
            'like' => 0
        ]);

        if ($post->save()) {

        	$image = new Images([
	        	'post_id' => $post->id,
	        	'link' => $request['image_link'],
	        	'image_sort' => 0
	        ]);
	        $image->save();

        	return response()->json(
        		[
        			'code' => ApiStatusCode::OK,
        			'message' => 'Tạo bài viết thành công',
        			'data' => [
        				'id' => $post->id,
        				'url' => URL::ADDRESS . '/posts/' . $post->id
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
}
