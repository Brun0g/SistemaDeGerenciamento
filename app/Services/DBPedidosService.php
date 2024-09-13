<?php

namespace App\Services;


use App\Services\PedidosServiceInterface;
use App\Services\ProdutosServiceInterface;
use \App\Services\DBProdutosService;
use \App\Services\DBClientesService;
use App\Models\Order;
use App\Models\OrderTotal;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;


class DBPedidosService implements PedidosServiceInterface
{
    public function excluirPedido($cliente_id, $id_pedido)
    {
        $pedidos = Order::where('cliente_id', $cliente_id)->get();
        $pedidos_por_cliente = OrderTotal::where('id', $id_pedido)->get();
            
        foreach ($pedidos as $pedido => $value) {
            if($value['pedido_id'] == $id_pedido)
                $pedidos[$pedido]->delete($id_pedido);          
        }

        foreach ($pedidos_por_cliente as $key => $value) {
            $pedidos_por_cliente[$key]->delete($id_pedido);
        }
    }
    
    public function listarQuantidadePedidos()
    {
        $pedidosPorClientes = Order::all(); 
        
        $service_produtos = new DBProdutosService();
    
        foreach ($pedidosPorClientes as $pedidoKey => $value) {
            $id = $value['cliente_id'];
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];
            

            $pedidosPorClientes[$pedidoKey] = ['cliente_id' => $id, 'produto_id' => $produto_id, 'quantidade' => $quantidade]; 
        } 
        
        return $pedidosPorClientes; 
    }

    public function buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $pedidos = Order::where('pedido_id', $pedido_id)->get();

        $service_produtos = new DBProdutosService();

        $lista = [];
        $total = 0;

        foreach ($pedidos as $key => $value) 
        {
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];
            $cliente_id = $value['cliente_id'];
            $preco_unidade = $value['preco_unidade'];
            $porcentagem = $value['porcentagem'];
            $produto = $service_produtos->buscarProduto($produto_id);

            $total += $valor;
           
            $lista[] = ['pedido_id' => $pedido_id, 'cliente_id' => $cliente_id, 'produto_id' => $produto_id, 'produto' => $produto['produto'], 'quantidade' => $quantidade, 'total' => $valor, 'preco_unidade' => $preco_unidade, 'totalComDesconto' => $total, 'porcentagem' => $porcentagem];
        }

        return $lista;          
    }

    public function listarPedidos($cliente_id)
    {
        $datas = [];
        $pedidos = OrderTotal::where('cliente_id', $cliente_id)->get(); 

        foreach ($pedidos as $key => $value) {
            $pedido_id = $pedidos[$key]->id;
            $id_cliente = $pedidos[$key]->cliente_id;
            $endereco = $pedidos[$key]->endereco_id;
            $total = $pedidos[$key]->total;
            $porcentagem = $pedidos[$key]->porcentagem;
            $total = $pedidos[$key]->total;

            $datas[$pedido_id] = ['cliente_id' => $id_cliente, 'endereco' => $endereco, 'total' => $total, 'porcentagem' => $porcentagem, 'total' => $total];   
        }

        return $datas;
    }

    public function buscarPedido($pedido_id)
    {
        $pedidoEncontrado = [];
        $pedido = OrderTotal::find($pedido_id);
        $totalComDesconto = 0;

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
        $pedido = new OrderTotal;

        $pedido->cliente_id = $cliente_id;
        $pedido->endereco_id = $endereco_id;
        $pedido->total = $valor_final;
        $pedido->porcentagem = $porcentagem;
        $pedido->totalSemDesconto = $valor_total;

        $pedido->save();

        $pedido_id = $pedido->id;

        return $pedido_id;

    }

    function salvarItemPedido($pedido_id, $cliente_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade)
    {
        $pedido = new Order;

        $pedido->pedido_id = $pedido_id;
        $pedido->cliente_id = $cliente_id;
        $pedido->produto_id = $produto_id;
        $pedido->quantidade = $quantidade;
        $pedido->porcentagem = $porcentagem_unidade;
        $pedido->preco_unidade = $preco_unidade;
        $pedido->total = $valor_final;
        $pedido->totalSemDesconto = $valor_total;

        $pedido->save();

        return $pedido->id;
    }

    public function buscarItem($pedido_id)
    {
        $pedidos = Order::find($pedido_id);

        $total = $pedidos->total;   
     
        $lista = ['total' => $total];

        return $lista;          
    }
}
