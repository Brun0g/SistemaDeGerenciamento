<?php

namespace App\Services;


use App\Models\Saida;
use Illuminate\Support\Facades\Auth;


use App\Services\SaidaServiceInterface;

class DBSaidaService implements SaidaServiceInterface
{
    public function adicionarSaida($produto_id, $pedido_id, $quantidade)
    {
        $saida = new Saida();

        $saida->user_id = Auth::id();
        $saida->produto_id = $produto_id;
        $saida->pedido_id = $pedido_id;
        $saida->quantidade = $quantidade;
     
        $saida->save();
    }

    function buscarSaida($produto_id)
    {
        $saidas = Saida::all()->where('produto_id', $produto_id);

        $saidas_array = [];

        foreach ($saidas as $key => $value) {
            if($value['produto_id'] == $produto_id)
            {
                $user_id = $value['user_id'];
                $produto_id = $value['produto_id'];
                $pedido_id = $value['pedido_id'];
                $quantidade = $value['quantidade'];
                $data = $value['created_at'];   

                $saidas_array[] = ['user_id' => $user_id, 'produto_id' => $produto_id, 'pedido_id' => $pedido_id, 'quantidade' => $quantidade, 'data' => $data];
            }
        }

        return $saidas_array;
    }

    function listarSaida()
    {
        $saidas = Saida::all()->where('produto_id', $produto_id);

        $saidas_array = [];

        foreach ($saidas as $key => $value) {
            $user_id = $value['user_id'];
            $produto_id = $value['produto_id'];
            $pedido_id = $value['pedido_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at'];   

            $saidas_array[] = ['user_id' => $user_id, 'produto_id' => $produto_id, 'pedido_id' => $pedido_id, 'quantidade' => $quantidade, 'data' => $data];
        }

        return $saidas_array;
    }
}