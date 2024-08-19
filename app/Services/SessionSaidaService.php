<?php

namespace App\Services;

use App\Services\SessionSaidaService;
use Illuminate\Support\Facades\Auth;

class SessionSaidaService implements SaidaServiceInterface
{
    public function adicionarSaida($produto_id, $pedido_id, $quantidade)
    {
        $saida = session()->get('saida', []);

        $saida[] = ['user_id' => Auth::id(), 'pedido_id' => $pedido_id, 'produto_id' => $produto_id, 'quantidade' => (int)$quantidade, 'created_at' => date("Y-m-d H:i:s")];

        session()->put('saida', $saida);
    }

    function buscarSaida($produto_id)
    {
        $saida = session()->get('saida' ,[]);

        $saida_array = [];

        foreach ($saida as $key => $value) {
            if($value['produto_id'] == $produto_id)
            {
                $user_id = $value['user_id'];
                $pedido_id = $value['pedido_id'];
                $produto_id = $value['produto_id'];
                $quantidade = $value['quantidade'];
                $data = $value['created_at'];   

                $saida_array[] = ['user_id' => $user_id, 'pedido_id' => $pedido_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data];
            }
        }

        return $saida_array;
    }

    function listarSaida(){

        $saida = session()->get('saida' ,[]);

        $saida_array = [];

        foreach ($saida as $key => $value) {
            $user_id = $value['user_id'];
            $pedido_id = $value['pedido_id'];
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at'];   

            $saida_array[] = ['user_id' => $user_id, 'pedido_id' => $pedido_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data];
        }

        return $saida_array;
    }
}
