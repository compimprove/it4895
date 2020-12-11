<?php

namespace App\Http\Controllers;

use App\Enums\CommonResponse;
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
    public function addComment(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'described' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => ApiStatusCode::PARAMETER_NOT_ENOUGH,
                'message' => 'Số lượng parameter không dầy đủ',
                'data' => $validator->errors()
            ]);
        }
        $id = (int) $request->query("id");
        $validator = Validator::make($request->query(), [
            'described' => 'string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => ApiStatusCode::PARAMETER_TYPE_INVALID,
                'message' => 'Parameter type is invalid',
                'data' => $validator->errors()
            ]);
        } else {
            $post = Post::find($id);
            if ($post == null) {
                return [
                    "code" => ApiStatusCode::NOT_EXISTED,
                    "message" => "Bài viết không tồn tại"
                ];
            }
        }
        $user_id = $request->user()->id;
        $post = Post::find($id);
        $comment = new Comment([
            'user_id' => $user_id,
            'post_id' => $id,
            'content' => $request->query("described"),

        ]);
        if ($comment->save()) {
            return response()->json(
                [
                    'code' => ApiStatusCode::OK,
                    'message' => 'Tạo comment thành công',
                    'data' => [
                        'user_id' => $comment->user_id,
                        'post_id' => $id,
                        'content' => $request->query("described")
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

    public function getComment(Request $request)
    {
        $id = $request->query("id");
        if ($id == "") {
            return CommonResponse::getResponse(1004);
        }
        $id = (int)$id;
        $post = Post::where('id', $id)->first();
        $comments = Comment::where('post_id', $id)->get();
        foreach ($comments as $comment) {
            $author = User::where('id', $comment["user_id"])->get()[0];
            $comment['author'] = [
                "id" => $author->id,
                "name" => $author->name,
                "avatar" => $author->avatar
            ];
        }
        if ($post == null) {
            return [
                "code" => ApiStatusCode::NOT_EXISTED,
                "message" => "Bài viết không tồn tại"
            ];
        } else {
            // $user = User::find($post->user_id);
            return response()->json([
                'code' => ApiStatusCode::OK,
                'message' => 'Lấy comment bài viết thành công',
                'data' => [
                    'post' => $post,
                    'comment' => $comments
                ],
            ]);
        }
    }

    public function deleteComment($id)
    {
        $comment = Comment::where('id', $id)->first();

        if ($comment->delete()) {

            return response()->json([
                'code' => ApiStatusCode::OK,
                'message' => 'Xóa comment thành công'
            ]);
        }
    }
}
