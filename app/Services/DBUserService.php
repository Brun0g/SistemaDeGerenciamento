<?php

namespace App\Services;


use App\Models\User;
use App\Services\UserServiceInterface;

class DBUserService implements UserServiceInterface
{
    public function buscarUsuario($user)
    {
        if(!$user)
            return null;
        
        $user = User::find($user);
        $nome = $user->name;
     
        return $nome;
    }
}