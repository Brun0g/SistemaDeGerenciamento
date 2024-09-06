<?php

namespace App\Services;

use App\Services\SessionSaidaService;
use Illuminate\Support\Facades\Auth;

class SessionSaidaService implements SaidaServiceInterface
{
    public function adicionarSaida($produto_id, $pedido_id, $quantidade, $observacao, $tipo, $registro_id, $quantidade_anterior)
    {
        $saida = session()->get('saida', []);

        $saida[] = ['user_id' => Auth::id(), 'pedido_id' => $pedido_id, 'produto_id' => $produto_id, 'quantidade' => abs((int)$quantidade), 'created_at' => date("Y-m-d H:i:s"), 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id, 'quantidade_anterior' => $quantidade_anterior];

        session()->put('saida', $saida);
    }

    function buscarSaida($produto_id, $provider_user, $provider_entradas, $provider_saida)
    {
        $saidas = session()->get('saida' , []);

        $saidas_array = [];
        $total = 0;

        foreach ($saidas as $key => $value) {
            if($produto_id == $value['produto_id'])
            {
                $user_id = $value['user_id'];
                $nome = $provider_user->buscarUsuario($user_id);
                $produto_id = $value['produto_id'];
                $pedido_id = $value['pedido_id'];
                $quantidade = $value['quantidade'];
                $total += $value['quantidade'];
                $saida_ativa = 1;
                $data = $value['created_at'];   
                $tipo = $value['tipo'];   
                $observacao = $value['observacao'];
                $registro_id = $value['registro_id'];
                $quantidade_anterior = $value['quantidade_anterior'];

                $saidas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'pedido_id' => $pedido_id, 'quantidade' => -$quantidade, 'data' => $data, 'status' => $saida_ativa, 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id, 'quantidade_anterior' => $quantidade_anterior];
            }   
        }

        return ['saidas_array' => $saidas_array, 'total' => $total];
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
            $tipo = $value['tipo'];
            $observacao = $value['observacao'];
            $registro_id = $value['registro_id'];
            $quantidade_anterior = $value['quantidade_anterior'];  

            $saida_array[] = ['user_id' => $user_id, 'pedido_id' => $pedido_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id, 'quantidade_anterior' => $quantidade_anterior];
        }

        return $saida_array;
    }

    function buscarRegistro($registro_id, $provider_user, $provider_produto)
    {
        $saidas = session()->get('saida' ,[]);

        $saidas_array = [];

        foreach ($saidas as $key => $value) 
        {
            if($value['registro_id'] == $registro_id)
            {
                $user_id = $value['user_id'];
                $nome = $provider_user->buscarUsuario($user_id);
                $produto_id = $value['produto_id'];

                $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
                $pedido_id = $value['pedido_id'];
                $quantidade = $value['quantidade'];
                $data = $value['created_at']; 
                $tipo = 'SAÍDA'; 
                $observacao = $value['observacao'];
                $registro_id = $value['registro_id'];
                $quantidade_anterior = $value['quantidade_anterior'];

            $saidas_array[] = ['user_id' => $nome, 'produto' => $nome_produto, 'pedido_id' => $pedido_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id, 'quantidade_anterior' => $quantidade_anterior];
            }
        }

        return $saidas_array;
    }
}
