<?php

namespace App;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isBlocked()
    {
        return $this["is_blocked"];
    }

    public function messages()
    {
        return $this->hasMany(Chat::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function changePassword(string $password)
    {
        $this->password = Hash::make($password);
    }
    // -------------------------------- static --------------------------------
    public static function makeUser(array $data): User
    {
        $user = new User();
        $user->email = $data['email'];
        $user->name = $data["name"];
        $user->phone_number = $data["phone_number"];
        $user->uuid = $data["uuid"];
        $user->password = Hash::make($data['password']);
        return $user;
    }
}
