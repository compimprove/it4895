<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use App\Enums\ApiStatusCode;
use App\Enums\URL;
use Illuminate\Support\Facades\Validator;

class UserReportPostController extends Controller
{
    //
    public function reportPost(Request $request) {

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

    	
		$report = new UserReportPost([
            'user_id' => 1,
            'post_id' => 1,
            'description' => $request['described'],
            'type' => 1
        ]);

        if ($post->save()) {
	
        	return response()->json(
        		[
        			'code' => ApiStatusCode::OK,
        			'message' => 'Report thành công',
        			'data' => [
        				'id' => $report->id,
        				'url' => URL::ADDRESS . '/report/' . $report->id
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
