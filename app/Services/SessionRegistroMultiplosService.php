<?php

namespace App\Services;


use App\Models\Registro_multiplos;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


use App\Services\RegistroMultiplosServiceInterface;

class SessionRegistroMultiplosService implements RegistroMultiplosServiceInterface
{
    public function adicionarAjuste()
    {
        $registro = session()->get('RegistroAjuste', []);

        $registro[] = ['user_id' => Auth::id()];

        session()->put('RegistroAjuste', $registro);

        $registro_id = sizeof($registro);

        return $registro_id;
    }
    
    public function adicionarMultiplos()
    {
        $registro = session()->get('AdicionarMultiplo', []);

        $registro[] = ['user_id' => Auth::id()];

        session()->put('AdicionarMultiplo', $registro);

        $registro_id = sizeof($registro);

        return $registro_id;
    }
}
