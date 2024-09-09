<?php

namespace App\Services;

use App\Services\SessionEntradasService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SessionEntradasService implements EntradasServiceInterface
{
    public function adicionarEntrada($produto_id, $quantidade, $observacao, $tipo, $ajuste_id, $multiplo_id)
    {
        $entrada = session()->get('entrada', []);

        $entrada[] = ['user_id' => Auth::id(), 'produto_id' => $produto_id, 'quantidade' => (int)$quantidade, 'created_at' => now(), 'observacao' => $observacao, 'tipo' => $tipo, 'ajuste_id' => $ajuste_id, 'multiplo_id' => $multiplo_id];

    
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
                $multiplo_id = $value['multiplo_id'];
                $ajuste_id = $value['ajuste_id'];

                $entradas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'status' => 0, 'observacao' => $observacao, 'tipo' => $tipo, 'multiplo_id' => $multiplo_id, 'ajuste_id' => $ajuste_id];
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
            $nome = $provider_user->buscarUsuario($user_id);
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at'];
            $tipo = $value['tipo'];
            $observacao = $value['observacao'];
            $multiplo_id = $value['multiplo_id'];
            $ajuste_id = $value['ajuste_id'];

            $entradas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'multiplo_id' => $multiplo_id, 'ano' => $data->year, 'dia_do_ano' => $data->dayOfYear, 'dia_da_semana' => $data->dayOfWeek, 'hora' => $data->hour, 'minuto' => $data->minute, 'segundo' => $data->second, 'mes' => $data->month, 'ajuste_id' => $ajuste_id];
        }



        return $entradas_array;
    }

    function buscarAjuste($ajuste_id, $provider_user, $provider_produto)
    {
        $entradas = session()->get('entrada' ,[]);

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
            if($ajuste_id == $value['ajuste_id'])
            {
                $user_id = $value['user_id'];
                $nome = $provider_user->buscarUsuario($user_id);
                $produto_id = $value['produto_id'];
                $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
                $quantidade = $value['quantidade'];
                $data = $value['created_at'];   
                $tipo = 'ENTRADA';   
                $observacao = $value['observacao'];
                $multiplo_id = $value['multiplo_id'];

                $entradas_array[] = ['user_id' => $nome, 'produto' => $nome_produto, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'ajuste_id' => $ajuste_id];
            }
        }

        return $entradas_array;
    }

    function buscarMultiplos($multiplo_id, $provider_user, $provider_produto)
    {
        $entradas = session()->get('entrada' ,[]);

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
            if($multiplo_id == $value['multiplo_id'])
            {
                $user_id = $value['user_id'];
                $nome = $provider_user->buscarUsuario($user_id);
                $produto_id = $value['produto_id'];
                $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
                $quantidade = $value['quantidade'];
                $data = $value['created_at'];   
                $tipo = 'ENTRADA';   
                $observacao = $value['observacao'];
                $multiplo_id = $value['multiplo_id'];

                $entradas_array[] = ['user_id' => $nome, 'produto' => $nome_produto, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'multiplo_id' => $multiplo_id];
            }
        }

        return $entradas_array;
    }
}
