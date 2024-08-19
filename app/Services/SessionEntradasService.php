<?php

namespace App\Services;

use App\Services\SessionEntradasService;
use Illuminate\Support\Facades\Auth;

class SessionEntradasService implements EntradasServiceInterface
{
    public function adicionarEntrada($produto_id, $quantidade)
    {
        $entrada = session()->get('entrada', []);

        $entrada[] = ['user_id' => Auth::id(), 'produto_id' => $produto_id, 'quantidade' => (int)$quantidade, 'created_at' => date("Y-m-d H:i:s")];

        session()->put('entrada', $entrada);
    }

    function buscarEntrada($produto_id)
    {
        $entradas = session()->get('entrada' ,[]);

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
            if($value['produto_id'] == $produto_id)
            {
                $user_id = $value['user_id'];
                $produto_id = $value['produto_id'];
                $quantidade = $value['quantidade'];
                $data = $value['created_at'];   

                $entradas_array[] = ['user_id' => $user_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data];
            }
        }

        return $entradas_array;
    }
    function listarEntrada()
    {
        $entradas = session()->get('entrada' ,[]);

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
         
            $user_id = $value['user_id'];
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at'];   

            $entradas_array[] = ['user_id' => $user_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data];
        }

        return $entradas_array;
    }
}
