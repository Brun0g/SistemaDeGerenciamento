<?php

namespace App\Services;


use App\Models\User;
use App\Services\UserServiceInterface;

class DBUserService implements UserServiceInterface
{
    public function buscarUsuario($user_id)
    {
        $user = User::find($user_id);

        $nome = $user->name;
     
        return $nome;
    }
}