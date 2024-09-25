<?php

namespace App\Services;


use App\Models\User;
use App\Services\UserServiceInterface;

class DBUserService implements UserServiceInterface
{
    public function buscarUsuario($create_by)
    {
        if(!$create_by)
            return null;


        $user = User::find($create_by);
        $nome = $user->name;
     
        return $nome;
    }
}