<?php

namespace App\Services;


use App\Models\Saida;
use Illuminate\Support\Facades\Auth;


use App\Services\SaidaServiceInterface;

class DBSaidaService implements SaidaServiceInterface
{
    public function adicionarSaida($produto_id, $pedido_id, $quantidade, $observacao, $tipo, $registro_id, $quantidade_anterior)
    {
        $saida = new Saida();

        $saida->user_id = Auth::id();
        $saida->produto_id = $produto_id;
        $saida->pedido_id = $pedido_id;
        $saida->quantidade = abs($quantidade);
        $saida->tipo = $tipo;
        $saida->observacao = $observacao;
        $saida->registro_id = $registro_id;
        $saida->quantidade_anterior = $quantidade_anterior;

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
                $registro_id = $value['registro_id'];
                $observacao = $value['observacao'];
                $quantidade_anterior = $value['quantidade_anterior'];
                
           

                $total_entrada += $quantidade;
         

                $saidas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'pedido_id' => $pedido_id, 'quantidade' => -$quantidade, 'data' => $data, 'status' => 1, 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id, 'quantidade_anterior' => $quantidade_anterior];
            
        }

        return ['saidas_array' => $saidas_array, 'total' => $total_entrada];
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
            $registro_id = $value['registro_id'];

            $saidas_array[] = ['user_id' => $nome, 'produto_id' => $produto_id, 'pedido_id' => $pedido_id, 'quantidade' => -$quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id, 'ano' => $data->year, 'dia_do_ano' => $data->dayOfYear, 'dia_da_semana' => $data->dayOfWeek, 'hora' => $data->hour, 'minuto' => $data->minute, 'segundo' => $data->second, 'mes' => $data->month];
        }

        return $saidas_array;
    }
    
    function buscarRegistro($registro_id, $provider_user, $provider_produto)
    {
        $saidas = Saida::where('registro_id', $registro_id)->get();

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
            $registro_id = $value['registro_id'];
            $quantidade_anterior = $value['quantidade_anterior'];

            $saidas_array[] = ['user_id' => $nome, 'produto' => $nome_produto, 'pedido_id' => $pedido_id, 'quantidade' => $quantidade, 'data' => $data, 'observacao' => $observacao, 'tipo' => $tipo, 'registro_id' => $registro_id, 'quantidade_anterior' => $quantidade_anterior];
        }

        return $saidas_array;
    }
}