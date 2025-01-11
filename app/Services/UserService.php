<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function save($name, $email, $password, $idRole)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'role_id' => $idRole
        ]);

        return $user;
    }
}
