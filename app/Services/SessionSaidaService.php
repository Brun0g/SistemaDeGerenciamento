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

    function buscarSaida($produto_id, $provider_user, $provider_entradas, $provider_saida)
    {
        $saidas = session()->get('saida' , []);

        $saidas_array = [];
        $total_valor = 0;

        foreach ($saidas as $key => $value) {
            
                $user_id = $value['user_id'];
                $nome = $provider_user->buscarUsuario($user_id);
                $produto_id = $value['produto_id'];
                $pedido_id = $value['pedido_id'];
                $quantidade = $value['quantidade'];
                $saida_ativa = 1;
                $data = $value['created_at'];   

                $saidas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'pedido_id' => $pedido_id, 'quantidade' => $quantidade, 'data' => $data, 'status' => $saida_ativa];
        }

        return $saidas_array;
    }

    function listarSaida($provider_user){

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
