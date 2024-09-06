<?php

namespace App\Services;

use App\Services\SessionEntradasService;
use Illuminate\Support\Facades\Auth;

class SessionEntradasService implements EntradasServiceInterface
{
    public function adicionarEntrada($produto_id, $quantidade, $observacao, $tipo, $registro_id, $quantidade_anterior)
    {
        $entrada = session()->get('entrada', []);

        $entrada[] = ['user_id' => Auth::id(), 'produto_id' => $produto_id, 'quantidade' => (int)$quantidade, 'created_at' => date("Y-m-d H:i:s"), 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id];

    
        session()->put('entrada', $entrada);
    }

    function buscarEntrada($produto_id, $provider_user)
    {
        $entradas = session()->get('entrada', []);

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
                $entrada_ativa = 0;
                $tipo = $value['tipo'];
                $observacao = $value['observacao'];
                $registro_id = $value['registro_id'];

                $entradas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'status' => 0, 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id];
            }
        }

        return ['entradas_array' => $entradas_array, 'total'  => $total];
    }

    function listarEntrada($provider_user)
    {
        $entradas = session()->get('entrada' ,[]);

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
         
            $user_id = $value['user_id'];
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at'];
            $tipo = $value['tipo'];
            $observacao = $value['observacao'];
              $registro_id = $value['registro_id'];

            $entradas_array[] = ['user_id' => $user_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id];
        }

        return $entradas_array;
    }

    function buscarRegistro($registro_id, $provider_user, $provider_produto)
    {
        $entradas = session()->get('entrada' ,[]);

        $entradas_array = [];

        foreach ($entradas as $key => $value) 
        {
            if($value['registro_id'] == $registro_id)
            {
                $user_id = $value['user_id'];
                $nome = $provider_user->buscarUsuario($user_id);
                $produto_id = $value['produto_id'];

                $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
                $quantidade = $value['quantidade'];
                $data = $value['created_at']; 
                $tipo = 'ENTRADA'; 
                $observacao = $value['observacao'];
                $registro_id = $value['registro_id'];

                $entradas_array[] = ['user_id' => $nome, 'produto' => $nome_produto, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id];
            }
        }

        return $entradas_array;
    }
}
