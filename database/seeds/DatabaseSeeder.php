<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->email = 'admin1@gmail.com';
        $user->name = 'Admin1';
        $user->phone_number = '0987654321';
        $user->password = Hash::make('123456');
        $user->save();
        $user = new User();
        $user->email = 'admin2@gmail.com';
        $user->name = 'Admin2';
        $user->phone_number = '0987654322';
        $user->password = Hash::make('123456');
        $user->save();
        $user = new User();
        $user->email = 'admin3@gmail.com';
        $user->name = 'Admin3';
        $user->phone_number = '0987654323';
        $user->password = Hash::make('123456');
        $user->is_blocked = true;
        $user->save();
    }
}
