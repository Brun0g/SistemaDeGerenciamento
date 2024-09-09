<?php

namespace App\Services;


use App\Models\Ajuste;
use App\Models\Multiplos;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


use App\Services\RegistroMultiplosServiceInterface;

class DBRegistroMultiplosService implements RegistroMultiplosServiceInterface
{
    public function adicionarAjuste()
    {
        $registro = new Ajuste();

        $registro->user_id = Auth::id();

        $registro->save();

        return $registro->id;
    }

    public function adicionarMultiplos()
    {
        $registro = new Multiplos();

        $registro->user_id = Auth::id();

        $registro->save();

        return $registro->id;
    }
}
