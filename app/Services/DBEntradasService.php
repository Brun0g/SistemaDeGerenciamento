<?php

namespace App\Services;


use App\Models\Entradas_saidas;
use App\Models\Pedidos;

use App\Services\EntradasServiceInterface;
use Illuminate\Support\Facades\Auth;

class DBEntradasService implements EntradasServiceInterface
{
    public function adicionarEntrada($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id)
    {
        $entrada = new Entradas_saidas();

        $entrada->create_by = Auth::id();
        $entrada->delete_by = null;
        $entrada->relocate_by = null;
        $entrada->produto_id = $produto_id;
        $entrada->quantidade = $quantidade;
        $entrada->observacao = $observacao;
        $entrada->ajuste_id = $ajuste_id;
        $entrada->multiplo_id = $multiplo_id;
        $entrada->pedido_id = $pedido_id;
        

        $entrada->save();

        return $entrada->id;
    }

    public function adicionarSaida($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id)
    {
        $saida = new Entradas_saidas();

        $saida->create_by = Auth::id();
        $saida->delete_by = null;
        $saida->relocate_by = null;
        $saida->produto_id = $produto_id;
        $saida->quantidade = $quantidade;
        $saida->observacao = $observacao;
        $saida->ajuste_id = $ajuste_id;
        $saida->multiplo_id = $multiplo_id;
        $saida->pedido_id = $pedido_id;
        

        $saida->save();

        return $saida->id;
    }

    function deletarSaida($pedido_id)
    {
        $estoque = Entradas_saidas::where('pedido_id', $pedido_id)->get();

        foreach ($estoque as $key => $value) {

            $estoque[$key]->delete_by = Auth::id();
            $estoque[$key]->save();
            $estoque[$key]->delete($pedido_id);
        }
    }

    function realocarSaida($pedido_id)
    {
        $saida = Entradas_saidas::withTrashed()->where('pedido_id', $pedido_id)->get();

        foreach ($saida as $key => $value) {
            $saida[$key]->relocate_by = Auth::id();
            $saida[$key]->deleted_at = null;
            $saida[$key]->save();
        }
    }

    function buscarEntradaSaidas($produto_id, $provider_user)
    {
        $entradas = Entradas_saidas::where('produto_id', $produto_id)->get();

        $entradas_array = [];
        $total_entrada = 0;


        foreach ($entradas as $key => $value) {
            if($value['produto_id'] == $produto_id)
            {
                $create_by = $value['create_by'];
                $nome = $provider_user->buscarUsuario($create_by);
                $produto_id = $value['produto_id'];
                $data = $value['created_at'];    
                $observacao = $value['observacao'];
                $multiplo_id = $value['multiplo_id'];
                $quantidade = $value['quantidade'];
                $ajuste_id = $value['ajuste_id'];
                $pedido_id = $value['pedido_id'];

                $total_entrada += $quantidade;

                $entradas_array[] = ['create_by' => $nome, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'status' => 0, 'multiplo_id' => $multiplo_id, 'ajuste_id' => $ajuste_id, 'ano' => $data->year, 'dia_do_ano' => $data->dayOfYear, 'dia_da_semana' => $data->dayOfWeek, 'hora' => $data->hour, 'minuto' => $data->minute, 'segundo' => $data->second, 'mes' => $data->month, 'pedido_id' => $pedido_id];
            }
        }

        return ['entradas_array' => $entradas_array, 'total'  => $total_entrada];
    }
    
    function listarEntradaSaidas($provider_user, $tipo)
    {
        $entradas = Entradas_saidas::all();

        if($tipo)
            $entradas = Entradas_saidas::withTrashed()->get();

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
           
            $create_by = $value['create_by'];
            $nome = $provider_user->buscarUsuario($create_by);
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at']; 
            $observacao = $value['observacao'];
            $multiplo_id = $value['multiplo_id'];
            $ajuste_id = $value['ajuste_id'];
            $pedido_id = $value['pedido_id'];
            $deleted_at = $value['deleted_at'];

            $entradas_array[] = ['create_by' => $nome, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'multiplo_id' => $multiplo_id, 'ano' => $data->year, 'dia_do_ano' => $data->dayOfYear, 'dia_da_semana' => $data->dayOfWeek, 'hora' => $data->hour, 'minuto' => $data->minute, 'segundo' => $data->second, 'mes' => $data->month, 'ajuste_id' => $ajuste_id, 'pedido_id' => $pedido_id, 'deleted_at' => $deleted_at];
        }

        return $entradas_array;
    }

    function buscarAjuste($ajuste_id, $provider_user, $provider_produto)
    {
        $entradas = Entradas_saidas::where('ajuste_id', $ajuste_id)->get();

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
           
            $create_by = $value['create_by'];
            $nome = $provider_user->buscarUsuario($create_by);
            $produto_id = $value['produto_id'];
            $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at'];   
            $tipo = 'ENTRADA';   
            $observacao = $value['observacao'];
            $multiplo_id = $value['multiplo_id'];

            $entradas_array[] = ['create_by' => $nome, 'produto' => $nome_produto, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'ajuste_id' => $ajuste_id, 'produto_id' => $produto_id, 'ano' => $data->year, 'dia_do_ano' => $data->dayOfYear, 'dia_da_semana' => $data->dayOfWeek, 'hora' => $data->hour, 'minuto' => $data->minute, 'segundo' => $data->second, 'mes' => $data->month];
        }

        return $entradas_array;
    }

    function buscarMultiplos($multiplo_id, $provider_user, $provider_produto)
    {
        $entradas = Entradas_saidas::where('multiplo_id', $multiplo_id)->get();

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
           
            $create_by = $value['create_by'];
            $nome = $provider_user->buscarUsuario($create_by);
            $produto_id = $value['produto_id'];
            $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at'];   
            $tipo = 'ENTRADA';   
            $observacao = $value['observacao'];
            $multiplo_id = $value['multiplo_id'];

            $entradas_array[] = ['create_by' => $nome, 'produto' => $nome_produto, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo,  'multiplo_id' => $multiplo_id];
        }

        return $entradas_array;
    }
}
