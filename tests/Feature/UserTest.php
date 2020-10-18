<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetInfo()
    {
        $user1 = User::find(1);
        $user = User::find(2);
        $data = [
            "id" => $user["id"],
            "username" => $user["name"],
            "avatar" => $user["avatar"],
            "cover_image" => $user["cover_image"],
            "address" => $user["address"],
            "city" => $user["city"],
            "country" => $user["country"]
        ];

        $response = $this
            ->actingAs($user1)
            ->get("it4895/user/2");
        $response
            ->assertStatus(200)
            ->assertJsonPath("code", 1000)
            ->assertJsonPath("message", "OK");
        foreach ($data as $key => $value) {
            $response->assertJsonPath("data." . $key, $value);
        }
    }

    public function testGetInfoWrong()
    {
        $user = User::find(3);
        $data = [
            "id" => $user["id"],
            "username" => $user["name"],
            "created" => $user["created_at"],
            "avatar" => $user["avatar"],
            "cover_image" => $user["cover_image"],
            "address" => $user["address"],
            "city" => $user["city"],
            "country" => $user["country"]
        ];

        $response = $this
            ->actingAs($user)
            ->get("it4895/user/1");
        $response
            ->assertStatus(200)
            ->assertJsonPath("code", 9995);
    }

    public function testCheckVerifyCode()
    {
        $user = User::find(3);
        $response = $this
            ->postJson(
                route("check_verify_code"),
                [
                    "phone_number" => $user["phone_number"],
                    "code_verify" => "343214"
                ]
            );
        $response
            ->assertStatus(200)
            ->assertJsonPath("code", 1000)
            ->assertJsonPath("message", "OK");
    }

    public function testCheckVerifyCodeWrong()
    {
        $response = $this
            ->postJson(
                route("check_verify_code"),
                [
                    "phone_number" => '0000000000',
                    "code_verify" => "343214"
                ]
            );
        $response
            ->assertStatus(200)
            ->assertJsonPath("code", 1004);
    }

    public function testCheckVerifyCodeNoneVerifyCode()
    {
        $user = User::find(3);
        $response = $this
            ->postJson(
                route("check_verify_code"),
                [
                    "phone_number" => $user["phone_number"]
                ]
            );
        $response
            ->assertStatus(200)
            ->assertJsonPath("code", 1002);
    }
}
