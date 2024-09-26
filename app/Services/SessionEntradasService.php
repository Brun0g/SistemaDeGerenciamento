<?php

namespace App\Services;

use App\Services\SessionEntradasService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SessionEntradasService implements EntradasServiceInterface
{
    public function adicionarEntrada($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id)
    {
        $entradas = session()->get('entradas_saidas', []);

        $entradas[] = ['create_by' => Auth::id(), 'delete_by' => null, 'restored_by' => null, 'produto_id' => $produto_id, 'quantidade' => (int)$quantidade, 'observacao' => $observacao, 'ajuste_id' => $ajuste_id, 'multiplo_id' => $multiplo_id, 'pedido_id' => $pedido_id, 'created_at' => now(), 'deleted_at' => null];

    
        session()->put('entradas_saidas', $entradas);
    }

    public function adicionarSaida($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id)
    {
        $saidas = session()->get('entradas_saidas', []);

        $saidas[] = ['create_by' => Auth::id(), 'delete_by' => null, 'restored_by' => null, 'produto_id' => $produto_id, 'quantidade' => (int)$quantidade, 'created_at' => now(), 'observacao' => $observacao,  'ajuste_id' => $ajuste_id, 'multiplo_id' => $multiplo_id, 'pedido_id' => $pedido_id, 'created_at' => now(), 'deleted_at' => null];

    
        session()->put('entradas_saidas', $saidas);
    }

    function deletarSaida($pedido_id)
    {
        $estoque = session()->get('entradas_saidas', []);

        foreach ($estoque as $key => $value) {
            if($pedido_id == $value['pedido_id']){
                $estoque[$key]['delete_by'] = Auth::id();
                $estoque[$key]['deleted_at'] = now();
            }
        }

        session()->put('entradas_saidas', $estoque);
    }

    function RestaurarSaida($pedido_id)
    {
        $saida = session()->get('entradas_saidas', []);

        foreach ($saida as $key => $value) {
            if($pedido_id == $value['pedido_id'])
            {
                $saida[$key]['restored_by'] = Auth::id();
                $saida[$key]['deleted_at'] = null;
            }
           
        }

        session()->put('entradas_saidas', $saida);
    }

    function buscarEntradaSaidas($produto_id, $provider_user)
    {
        $entradas = session()->get('entradas_saidas', []);

        $entradas_array = [];
        $total_entrada = 0;

        foreach ($entradas as $key => $value) {
            if($value['produto_id'] == $produto_id)
            {
                if(!$value['deleted_at'])
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
        }

        return ['entradas_array' => $entradas_array, 'total'  => $total_entrada];
    }

    function listarEntradaSaidas($provider_user, $tipo)
    {
        $entradas = session()->get('entradas_saidas' ,[]);

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
         
            $deleted_at = $value['deleted_at'];

            if($deleted_at  == $tipo)
            {
                $create_by = $value['create_by'];
                $nome = $provider_user->buscarUsuario($create_by);
                $produto_id = $value['produto_id'];
                $quantidade = $value['quantidade'];
                $data = $value['created_at'];   
                $observacao = $value['observacao'];
                $multiplo_id = $value['multiplo_id'];
                $ajuste_id = $value['ajuste_id'];
                $pedido_id = $value['pedido_id'];
              

                $entradas_array[] = ['create_by' => $nome, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'multiplo_id' => $multiplo_id, 'ano' => $data->year, 'dia_do_ano' => $data->dayOfYear, 'dia_da_semana' => $data->dayOfWeek, 'hora' => $data->hour, 'minuto' => $data->minute, 'segundo' => $data->second, 'mes' => $data->month, 'ajuste_id' => $ajuste_id, 'pedido_id' => $pedido_id, 'deleted_at' => $deleted_at];
            }
        }

        return $entradas_array;
    }

    function buscarAjuste($ajuste_id, $provider_user, $provider_produto)
    {
        $entradas = session()->get('entradas_saidas' ,[]);

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
            if($ajuste_id == $value['ajuste_id'])
            {
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
        }

        return $entradas_array;
    }

    function buscarMultiplos($multiplo_id, $provider_user, $provider_produto)
    {
        $entradas = session()->get('entradas_saidas' ,[]);

        $entradas_array = [];

        foreach ($entradas as $key => $value) {
            if($multiplo_id == $value['multiplo_id'])
            {
                $create_by = $value['create_by'];
                $nome = $provider_user->buscarUsuario($create_by);
                $produto_id = $value['produto_id'];
                $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
                $quantidade = $value['quantidade'];
                $data = $value['created_at'];   
                $tipo = 'ENTRADA';   
                $observacao = $value['observacao'];
                $multiplo_id = $value['multiplo_id'];

                $entradas_array[] = ['create_by' => $nome, 'produto' => $nome_produto, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'multiplo_id' => $multiplo_id];
            }
        }

        return $entradas_array;
    }
}
