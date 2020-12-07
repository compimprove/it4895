<?php

$allApiName = [
    "Login", "Signup", "Logout",
    "check_verify_code",
    "change_info_after_signup",
    "add_post", "get_post", "delete_post",
    "get_list_posts", "edit_post","check_new_item",
    "get_comment", "set_comment",
    "report_post", "like_post",
    "search", "get_saved_search", "del_saved_search",
    "get_user_friends", "get_user_info",
    "set_user_info",
    "get_list_videos",//"check_new_version",

    "get_conversation", "delete_message", "get_list_conversation", "delete_conversation", "set_read_message",

    "get_list_blocks", "set_block", "set_accept_friend", "get_requested_friends", "set_request_friend", "get_push_settings", "set_push_settings", "change_password", "set_devtoken", "get_list_suggested_friends", "get_notification", "set_read_notification"

]
?>

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
<div class="flex-center position-ref">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif
    <div class="content">
        <div class="title m-b-md">
            IT4895 Project
        </div>
        <p>Danh sách các API</p>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Key</th>
                <th>Path</th>
                <th>Method</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($allApiName as $item)
                @php
                    $route = route($item, ['id'=>"1",'post_id' => "2", 'user_id' => "3"]);
                    $route = explode("?", $route)[0];
                @endphp
                <tr>
                    <th>{{$item}}</th>
                    <td>{{ $route }}</td>
                    <td>{{Route::getRoutes()->getByName($item)->methods[0]}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>Sum</th>
                <th>{{count($allApiName)}}</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <div>


    </div>
</div>
</div>
</body>

</html>
