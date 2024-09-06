<?php

namespace App\Services;


use App\Models\Registro_multiplos;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


use App\Services\RegistroMultiplosServiceInterface;

class SessionRegistroMultiplosService implements RegistroMultiplosServiceInterface
{
    public function adicionarRegistro()
    {
        $registro = session()->get('Registro_multiplos', []);

        $registro[] = ['user_id' => Auth::id()];

        session()->put('Registro_multiplos', $registro);

        $registro_id = sizeof($registro);

        return $registro_id;
    }
}
