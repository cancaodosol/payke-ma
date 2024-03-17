<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function save_user(string $user_name, string $email_address, string $password)
    {
        $user = new User();
        $user->role = User::ROLE__USER;
        $user->name = $user_name;
        $user->email = $email_address;
        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }

    public function save_admin(string $user_name, string $email_address, string $password)
    {
        $user = new User();
        $user->role = User::ROLE__ADMIN;
        $user->name = $user_name;
        $user->email = $email_address;
        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }

    public function get_roles()
    {
        $roles = [
            ["id" => User::ROLE__USER, "name" => "通常"],
            ["id" => User::ROLE__ADMIN, "name" => "管理"]
        ];
        return $roles;
    }
}