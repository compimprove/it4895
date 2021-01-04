<?php

namespace App\Http\Controllers;

use App\Enums\CommonResponse;
use Illuminate\Http\Request;
use App\Post;
use App\Image;
use App\Video;
use App\Comment;
use App\User;
use App\Enums\ApiStatusCode;
use App\Enums\URL;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{

    public function getListVideos(Request $request)
    {
        $validatorRequire = Validator::make($request->query(), [
            'user_id' => 'required',
            'in_campaign' => 'required',
            'campaign_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'last_id' => 'required',
            'index' => 'required',
            'count' => 'required'
        ]);
        if ($validatorRequire->fails()) {
            return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_ENOUGH);
        }
        $user_id = $request->query('user_id');
        $in_campaign = $request->query('in_campaign');
        $campaign_id = $request->query('campaign_id');
        $latitude = $request->query('latitude');
        $longtitude = $request->query('longtitude');
        $last_id = $request->query('last_id');
        $index = $request->query('index');
        $count = $request->query('count');
        if ($user_id == "" || $in_campaign == "" || $campaign_id == "" || $latitude == "" || $longtitude == "" || $last_id == "" || $index == "" || $count == "") {
            return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_VALID);
        }

        $list_videos = Video::where('id', '>', $last_id)
            ->orderBy('id', 'desc')
            ->limit($count)
            ->get();
        $new_last_id = $list_videos->first()->id;

        return response()->json([
            'code' => ApiStatusCode::OK,
            'message' => 'OK',
            'data' => [
                'videos' => $list_videos,
            ],
            'last_id' => $new_last_id,
        ]);
    }
}
