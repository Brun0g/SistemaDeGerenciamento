<?php

namespace App\Services;


use App\Models\Entrada;

use App\Services\EntradasServiceInterface;
use Illuminate\Support\Facades\Auth;

class DBEntradasService implements EntradasServiceInterface
{
    public function adicionarEntrada($produto_id, $quantidade, $observacao)
    {
        $entrada = new Entrada();

        $entrada->user_id = Auth::id();
        $entrada->produto_id = $produto_id;
        $entrada->quantidade = $quantidade;
        $entrada->observacao = $observacao;
     
        $entrada->save();
    }

    function buscarEntrada($produto_id, $provider_user)
    {
        $entradas = Entrada::all()->where('produto_id', $produto_id);

        $entradas_array = [];
        $total = 0;
    
        foreach ($entradas as $key => $value) {
            if($value['produto_id'] == $produto_id)
            {
                $user_id = $value['user_id'];

                $nome = $provider_user->buscarUsuario($user_id);

                $produto_id = $value['produto_id'];
                $quantidade = $value['quantidade'];
                $total += $value['quantidade'];
                $data = $value['created_at'];  
                $observacao = $value['observacao'];  

                $entradas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'status' => 0];
            }
        }

        return ['entradas_array' => $entradas_array, 'total'  => $total];
    }
    
    function listarEntrada($provider_user){

        $entradas = Entrada::all();

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
           
            $user_id = $value['user_id'];
            $nome = $provider_user->buscarUsuario($user_id);
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at'];   
            $observacao = $value['observacao'];   

            $entradas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao];
        }

        return $entradas_array;
    }
}