<?php

namespace App\Http\Controllers;

use App\Enums\CommonResponse;
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
    public function likePost(Request $request )
    {
        $post_id = $request->query("id");
        if ($post_id == "") {
            return CommonResponse::getResponse(1004);
        }
        $post_id = (int) $post_id;
        $user = $request->user();
        if (UserLikePost::where("user_id", $user->id)->where("post_id", $post_id)->exists()) return [
            "code" => ApiStatusCode::NO_DATA,
            "message" => "Đã thích bài viết"
        ];
        $like = new UserLikePost([
            'user_id' => $user->id,
            'post_id' => $post_id,
        ]);
        $post = Post::find($post_id);
        if ($post == null) {
            return [
                "code" => ApiStatusCode::NOT_EXISTED,
                "message" => "Bài viết không tồn tại"
            ];
        }
        if ($like->save()) {
            $post = Post::find($post_id);
            $post->like += 1;
            $post->save();
            return response()->json(
                [
                    'code' => ApiStatusCode::OK,
                    'message' => 'Ok',
                    'data' => [
                        'post_id' => $post_id,
                        'user' => $like->user_id
                    ]
                ]

            );
        } else return response()->json(
            [
                'code' => ApiStatusCode::LOST_CONNECTED,
                'message' => 'Lỗi mất kết nối DB/ hoặc lỗi thực thi câu lệnh DB'
            ]
        );
    }

    public function getlikePost($id)
    {
        $post = Post::where('id', $id)->first();
        $like = UserLikePost::where('post_id', $id)->count();


        if ($post == null) {
            return [
                "code" => ApiStatusCode::NOT_EXISTED,
                "message" => "Bài viết không tồn tại"
            ];
        } else {
            return response()->json([

                'code' => ApiStatusCode::OK,
                'message' => 'OK',
                'data' => [
                    'post_id' => $post,
                    'count_like' => $like
                ],


            ]);
        }
    }
}
