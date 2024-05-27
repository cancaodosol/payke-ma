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

    public function edit_password(int $id, string $new_password)
    {
        $user = $this->find_by_id($id);
        $user->password = Hash::make($new_password);
        $user->save();
    }

    public function find_admin_users()
    {
        return User::where("role", User::ROLE__ADMIN)->get();
    }

    public function find_by_id(int $userid)
    {
        return User::where("id", $userid)->firstOrFail();
    }

    public function exists_user(string $email)
    {
        return User::where("email", $email)->exists();
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