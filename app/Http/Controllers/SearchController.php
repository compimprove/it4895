<?php

namespace App\Http\Controllers;

use App\Enums\ApiStatusCode;
use Illuminate\Http\Request;
use App\Post;
use App\User;
use App\Comment;
use App\Search;
use App\URL;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function search(Request $request)
    {

        $user_id = $request->query('user_id');
        $index = $request->query("index");
        $count = $request->query("count");
        $keyword = $request->query("keyword");
        $user = $request->user();

        if ($user_id == "") {
            $user_id = $user->id;
        } else if (!User::find($user_id) || User::find($user_id)->isBlocked()) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        }

        if ($index == '' || $count == '' || $keyword == '') {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        }

        if ((int)$index < 0 || (int)$count < 0) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        }


        $index = (int)$index;
        $count = (int)$count;
        $result = [];

        $postBySearch = Post::where('described', 'LIKE', "%$keyword%")->get()->toArray();

        $search = new Search ([
            'user_id' => $user->id,
            'keyword' => $keyword,
            'index' => $index
        ]);

        $search->save();

        if ($postBySearch == null) {
            return [
                "code" => ApiStatusCode::NO_DATA,
                "message" => "Post not found"
            ];
        } else {
            $postBySearch = array_slice($postBySearch, $count * $index, $count);
            foreach ($postBySearch as $item) {
                $user = User::find($item["user_id"]);
                array_push($result, [
                    'id' => $item["id"],
                    'like' => $item["like"],
                    'described' => $item["described"],
                    'comment' => Comment::where('id', $item["id"])->count(),
                    'author' => [
                        "id" => $user->id,
                        "username" => $user["name"],
                        "avatar" => $user["avatar"]
                    ],

                ]);
            };
            return [
                "code" => ApiStatusCode::OK,
                "message" => "OK",
                "data" => [
                    "list_posts" => $result
                ]
            ];
        }

    }

    public function getSavedSearch(Request $request)
    {
        $index = $request->query("index");
        $count = $request->query("count");
        $user_id = $request->query('user_id');

        $user = $request->user();

        if ($user_id == "") {
            $user_id = $user->id;
        }
        if ($index == '' || $count == '') {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        }

        if ((int)$index < 0 || (int)$count < 0) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        }


        $index = (int)$index;
        $count = (int)$count;

        $result = [];

        $getSavedSearch = array_slice(Search::where("user_id", $user_id)->get()->toArray(), $count * $index, $count);

        foreach ($getSavedSearch as $item) {
            array_push($result, [
                'id' => $item["user_id"],
                'keyword' => $item["keyword"],
                'created' => (string)strtotime($item["created_at"])
            ]);
        };
        return [
            "code" => ApiStatusCode::OK,
            "message" => "OK",
            "data" => [
                "list_saved_search" => $result
            ]
        ];
    }

    public function delSavedSearch(Request $request, $search_id)
    {

        $user = $request->user();

        if ($user->isBlocked()) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        }

        $search = Search::where('id', $search_id)->first();

        if ($search->delete()) {
            return response()->json([
                'code' => ApiStatusCode::OK,
                'message' => 'Xóa tìm kiếm thành công'
            ]);
        }
    }

}
