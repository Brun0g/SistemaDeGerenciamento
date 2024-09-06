<?php

namespace App\Services;


use App\Models\Registro_multiplos;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


use App\Services\RegistroMultiplosServiceInterface;

class DBRegistroMultiplosService implements RegistroMultiplosServiceInterface
{
    public function adicionarRegistro()
    {
        $registro = new Registro_multiplos();

        $registro->user_id = Auth::id();

        $registro->save();

        return $registro->id;
    }
}
