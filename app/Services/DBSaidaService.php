<?php

namespace App\Services;


use App\Models\Saida;
use Illuminate\Support\Facades\Auth;


use App\Services\SaidaServiceInterface;

class DBSaidaService implements SaidaServiceInterface
{
    public function adicionarSaida($produto_id, $pedido_id, $quantidade, $observacao, $tipo, $ajuste_id, $multiplo_id)
    {
        $saida = new Saida();

        $saida->user_id = Auth::id();
        $saida->produto_id = $produto_id;
        $saida->pedido_id = $pedido_id;
        $saida->quantidade = abs($quantidade);
        $saida->tipo = $tipo;
        $saida->observacao = $observacao;
        $saida->ajuste_id = $ajuste_id;
        $saida->multiplo_id = $multiplo_id;

        $saida->save();

        return $saida->id;
    }

    function buscarSaida($produto_id, $provider_user, $provider_entradas, $provider_saida)
    {
        $saidas = Saida::where('produto_id', $produto_id)->get();

        $saidas_array = [];
        $total_entrada = 0;

        foreach ($saidas as $key => $value) {
            
            $user_id = $value['user_id'];
            $nome = $provider_user->buscarUsuario($user_id);
            $produto_id = $value['produto_id'];
            $pedido_id = $value['pedido_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at'];
            $tipo = $value['tipo'];
            $ajuste_id = $value['ajuste_id'];
            $observacao = $value['observacao'];
            
            $total_entrada += $quantidade;
     

            $saidas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'pedido_id' => $pedido_id, 'quantidade' => -$quantidade, 'data' => $data, 'status' => 1, 'observacao' => $observacao, 'tipo' => $tipo, 'ajuste_id' => $ajuste_id, 'ano' => $data->year, 'dia_do_ano' => $data->dayOfYear, 'dia_da_semana' => $data->dayOfWeek, 'hora' => $data->hour, 'minuto' => $data->minute, 'segundo' => $data->second, 'mes' => $data->month]; 
        }

        return ['saidas_array' => $saidas_array, 'total' => $total_entrada, 'ajuste_id' => $ajuste_id];
    }

    function listarSaida($provider_user)
    {
        $saidas = Saida::all();

        $saidas_array = [];

        foreach ($saidas as $key => $value) {
            $user_id = $value['user_id'];
            $nome = $provider_user->buscarUsuario($user_id);
            $produto_id = $value['produto_id'];
            $pedido_id = $value['pedido_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at']; 
            $tipo = $value['tipo']; 
            $observacao = $value['observacao'];
            $ajuste_id = $value['ajuste_id'];
            $multiplo_id = $value['multiplo_id'];

            $saidas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'pedido_id' => $pedido_id, 'quantidade' => -$quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'ajuste_id' => $ajuste_id, 'ano' => $data->year, 'dia_do_ano' => $data->dayOfYear, 'dia_da_semana' => $data->dayOfWeek, 'hora' => $data->hour, 'minuto' => $data->minute, 'segundo' => $data->second, 'mes' => $data->month, 'multiplo_id' => $multiplo_id];
        }

        return $saidas_array;
    }
    
    function buscarAjuste($ajuste_id, $provider_user, $provider_produto)
    {
        $saidas = Saida::where('ajuste_id', $ajuste_id)->get();

        $saidas_array = [];

        foreach ($saidas as $key => $value) {
            $user_id = $value['user_id'];
            $nome = $provider_user->buscarUsuario($user_id);
            $produto_id = $value['produto_id'];

            $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
            $pedido_id = $value['pedido_id'];
            $quantidade = $value['quantidade'];
            $data = $value['created_at']; 
            $tipo = 'SAÍDA'; 
            $observacao = $value['observacao'];
            $ajuste_id = $value['ajuste_id'];


            $saidas_array[] = ['user_id' => $nome, 'produto' => $nome_produto, 'pedido_id' => $pedido_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'ajuste_id' => $ajuste_id, 'produto_id' => $produto_id, 'ano' => $data->year, 'dia_do_ano' => $data->dayOfYear, 'dia_da_semana' => $data->dayOfWeek, 'hora' => $data->hour, 'minuto' => $data->minute, 'segundo' => $data->second, 'mes' => $data->month, 'ajuste_id' => $ajuste_id];
        }

        return $saidas_array;
    }
}