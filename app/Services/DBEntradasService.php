<?php

namespace App\Services;


use App\Models\Entrada;

use App\Services\EntradasServiceInterface;
use Illuminate\Support\Facades\Auth;

class DBEntradasService implements EntradasServiceInterface
{
    public function adicionarEntrada($produto_id, $quantidade, $observacao, $tipo, $ajuste_id, $multiplo_id)
    {
        $entrada = new Entrada();

        $entrada->user_id = Auth::id();
        $entrada->produto_id = $produto_id;
        $entrada->quantidade = $quantidade;
        $entrada->tipo = $tipo;
        $entrada->observacao = $observacao;
        $entrada->ajuste_id = $ajuste_id;
        $entrada->multiplo_id = $multiplo_id;

        $entrada->save();

        return $entrada->id;
    }

    function buscarEntrada($produto_id, $provider_user)
    {
        $entradas = Entrada::where('produto_id', $produto_id)->get();

        $entradas_array = [];
        $total_entrada = 0;


        foreach ($entradas as $key => $value) {
            if($value['produto_id'] == $produto_id)
            {
                $user_id = $value['user_id'];

                $nome = $provider_user->buscarUsuario($user_id);
                $produto_id = $value['produto_id'];
                $data = $value['created_at'];  
                $tipo = $value['tipo'];  
                $observacao = $value['observacao'];
                $multiplo_id = $value['multiplo_id'];
                $quantidade = $value['quantidade'];
                $ajuste_id = $value['ajuste_id'];

                $total_entrada += $quantidade;

                $entradas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'status' => 0, 'tipo' => $tipo, 'multiplo_id' => $multiplo_id, 'ajuste_id' => $ajuste_id];
            }
        }

        return ['entradas_array' => $entradas_array, 'total'  => $total_entrada];
    }
    
    function listarEntrada($provider_user)
    {

        $entradas = Entrada::all();

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
        $entradas = Entrada::where('ajuste_id', $ajuste_id)->get();

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
           
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

        return $entradas_array;
    }

    function buscarMultiplos($multiplo_id, $provider_user, $provider_produto)
    {
        $entradas = Entrada::where('multiplo_id', $multiplo_id)->get();

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
           
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

        return $entradas_array;
    }
}