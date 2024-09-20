<?php

namespace App\Services;


use App\Services\PedidosServiceInterface;
use App\Services\ProdutosServiceInterface;
use \App\Services\DBProdutosService;
use \App\Services\DBClientesService;
use App\Models\PedidosIndividuais;
use App\Models\Pedidos;
use App\Models\Entradas_saidas;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;


class DBPedidosService implements PedidosServiceInterface
{
    public function listarQuantidadePedidos()
    {
        $pedidosPorClientes = PedidosIndividuais::all();
        
        $service_pedidos = new DBPedidosService();

        foreach ($pedidosPorClientes as $pedidoKey => $value) {
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];
            $pedido_id = $value['pedido_id'];
            $cliente_id = $service_pedidos->buscarPedido($pedido_id)['cliente_id'];
            

            $pedidosPorClientes[$pedidoKey] = ['cliente_id' => $cliente_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade]; 
        } 
        
        return $pedidosPorClientes; 
    }

    public function buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $pedidos = PedidosIndividuais::where('pedido_id', $pedido_id)->get();

        $service_produtos = new DBProdutosService();

        $lista = [];
        $total = 0;

        foreach ($pedidos as $key => $value) 
        {
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];
            $preco_unidade = $value['preco_unidade'];
            $porcentagem = $value['porcentagem'];
            $produto = $service_produtos->buscarProduto($produto_id);

            $total += $valor;
           
            $lista[] = ['pedido_id' => $pedido_id, 'produto_id' => $produto_id, 'produto' => $produto['produto'], 'quantidade' => $quantidade, 'total' => $valor, 'preco_unidade' => $preco_unidade, 'totalComDesconto' => $total, 'porcentagem' => $porcentagem];
        }

        return $lista;          
    }

    public function listarPedidos($cliente_id, $provider_estoque)
    {
        $array = [];
        $pedidos = Pedidos::where('cliente_id', $cliente_id)->get(); 

        foreach ($pedidos as $key => $value) {

            $pedido_id = $pedidos[$key]->id;
            $situacao = $provider_estoque->pedidosAprovados($pedido_id);

            if($situacao == false)
            {
                $id_cliente = $pedidos[$key]->cliente_id;
                $endereco = $pedidos[$key]->endereco_id;
                $total = $pedidos[$key]->total;
                $porcentagem = $pedidos[$key]->porcentagem;
                $total = $pedidos[$key]->total;

                $array[$pedido_id] = ['cliente_id' => $id_cliente, 'endereco' => $endereco, 'total' => $total, 'porcentagem' => $porcentagem]; 
            }
        }

        return $array;
    }

    public function buscarPedido($pedido_id)
    {
        $pedidoEncontrado = [];
        $pedido = Pedidos::where('id', $pedido_id)->get()[0];
 

        foreach ($pedido as $key => $value) {
            $id_cliente = $pedido->cliente_id;
            $endereco_id = $pedido->endereco_id;
            $total = $pedido->total;
            $totalSemDesconto = $pedido->totalSemDesconto;
            $porcentagem = $pedido->porcentagem;

            $pedidoEncontrado = ['cliente_id' => $id_cliente, 'endereco_id' => $endereco_id, 'total' => $total, 'totalSemDesconto' => $totalSemDesconto, 'porcentagem' => $porcentagem];
        }

        return $pedidoEncontrado;
    }

    public function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total)
    {
        $pedido = new Pedidos;

        $pedido->cliente_id = $cliente_id;
        $pedido->endereco_id = $endereco_id;
        $pedido->total = $valor_final;
        $pedido->porcentagem = $porcentagem;
        $pedido->totalSemDesconto = $valor_total;

        $pedido->save();

        $pedido_id = $pedido->id;

        return $pedido_id;

    }

    function salvarItemPedido($pedido_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade)
    {
        $pedido = new PedidosIndividuais;

        $pedido->pedido_id = $pedido_id;
        $pedido->produto_id = $produto_id;
        $pedido->quantidade = $quantidade;
        $pedido->porcentagem = $porcentagem_unidade;
        $pedido->preco_unidade = $preco_unidade;
        $pedido->total = $valor_final;
        $pedido->totalSemDesconto = $valor_total;

        $pedido->save();

        return $pedido->id;
    }

    public function reativarPedido($pedido_id)
    {
        $pedidos = Entradas_saidas::withTrashed()->where('pedido_id', $pedido_id)->get();

        foreach ($pedidos as $key => $value) {
            $pedidos[$key]->deleted_at = null;
            $pedidos[$key]->save();
        }
    }
}
