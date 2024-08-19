<?php

namespace App\Services;


use App\Models\Entrada;

use App\Services\EntradasServiceInterface;
use Illuminate\Support\Facades\Auth;

class DBEntradasService implements EntradasServiceInterface
{
    public function adicionarEntrada($produto_id, $quantidade)
    {
        $entrada = new Entrada();

        $entrada->user_id = Auth::id();
        $entrada->produto_id = $produto_id;
        $entrada->quantidade = $quantidade;
     
        $entrada->save();
    }

    function buscarEntrada($produto_id)
    {
        $entradas = Entrada::all()->where('produto_id', $produto_id);

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
    function listarEntrada(){

        $entradas = Entrada::all()->where('produto_id', $produto_id);

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