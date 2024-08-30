<?php

namespace App\Services;

use App\Services\PedidosServiceInterface;
use \App\Services\SessionProdutosService;
use App\Models\Pedido;
use App\Models\Pedidos_finalizados;

use Illuminate\Support\Facades\Auth;

class SessionPedidosService implements PedidosServiceInterface
{
    public function excluirPedido($cliente_id, $id_pedido)
    {
        $PedidosEncerrados = session()->get('PedidosTotalValor',[]);
        $PedidosConcluidos = session()->get('pedidoUnitario',[]);

            foreach ($PedidosConcluidos as $id => $valor) {
                if($valor['cliente_id'] == $cliente_id)
                    if($valor['pedido_id'] == $id_pedido)
                        unset($PedidosConcluidos[$id]);
            }

            foreach ($PedidosEncerrados as $key => $value) {
                if ($cliente_id == $value['cliente_id']) {
                        if($id_pedido == $value['pedido_id'])
                            $PedidosEncerrados[$key]['excluido'] = 1;
                }
            }
            
        session()->put('pedidoUnitario', $PedidosConcluidos);
        session()->put('PedidosTotalValor', $PedidosEncerrados);
    }
    

    public function listarQuantidadePedidos()
    {
        $pedidoUnitario = session()->get('pedidoUnitario', []); 
        $service_produtos = new SessionProdutosService();

        foreach ($pedidoUnitario as $pedidoKey => $value) {
            $id = $value['cliente_id'];
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];
   

            $pedidoUnitario[$pedidoKey] = ['cliente_id' => $id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'total' => $valor]; 
        } 

        return $pedidoUnitario; 
    }

    public function listarPedidos($cliente_id)
    {
        $listaPedidos = [];
        $pedidos = session()->get('PedidosTotalValor',[]);

        $service_produtos = new SessionProdutosService();

        foreach ($pedidos as $key => $value) {
            if($cliente_id == $value['cliente_id'])
            {
                $pedido_id = $value['pedido_id'];
                $total = $value['total'];
                $excluido = $value['excluido'];
                $porcentagem = $value['porcentagem'];
                $total = $value['total'];

                $listaPedidos[$key] = ['cliente_id' => $cliente_id, 'pedido_id' => $pedido_id, 'total' => $total, 'porcentagem' => $porcentagem, 'total' => $total, 'excluido' => $excluido];    
            }
        }
     
        return $listaPedidos;
    }

    public function buscarPedido($pedido_id)
    {
        $pedidoEncontrado = [];
        $pedidos = session()->get('PedidosTotalValor')[$pedido_id]; 

        foreach ($pedidos as $pedido) {
            $id_cliente = $pedidos['cliente_id'];
            $endereco_id = $pedidos['endereco_id'];
            $total = $pedidos['total'];
            $totalSemDesconto = $pedidos['totalSemDesconto'];
            $porcentagem = $pedidos['porcentagem'];
        
            $pedidoEncontrado = ['cliente_id' => $id_cliente, 'endereco_id' => $endereco_id, 'total' => $total, 'total' => $total, 'porcentagem' => $porcentagem, 'totalSemDesconto' => $totalSemDesconto];
        }

        return $pedidoEncontrado;
    }

    public function buscarItemPedido($pedido_id, $provider_entradas, $provider_saida, $provider_user, $provider_pedidos)
    {
        $pedidoUnitario = session()->get('pedidoUnitario', []);
        $service_produtos = new SessionProdutosService();
        $total = 0;

        foreach ($pedidoUnitario as $pedidoKey => $pedido) 
        {
            if($pedido['pedido_id'] == $pedido_id)
            {
                $id_pedido = $pedido['pedido_id'];
                $produto_id = $pedido['produto_id'];
                $quantidade = $pedido['quantidade'];
                $porcentagem = $pedido['porcentagem'];
                $valor = $pedido['total'];
                $produto = $service_produtos->buscarProduto($produto_id)['produto'];
                $preco_unidade = $pedido['preco_unidade'];

                $total += $valor;

    
            $lista[$pedidoKey] = ['produto_id' => $produto_id, 'produto' => $produto, 'pedido_id' => $id_pedido, 'quantidade' => $quantidade, 'total' => $valor, 'preco_unidade' => $preco_unidade, 'porcentagem' => $porcentagem, 'totalComDesconto' => $total];  
            } 
        }

        return $lista; 
    }

    public function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total)
    {
        $pedidosTotalValor = session()->get('PedidosTotalValor', []);
        $index = count($pedidosTotalValor);
        $pedido_id = $index + 1;
        
        $pedidosTotalValor[$pedido_id] = ['pedido_id' => $pedido_id, 'cliente_id' => $cliente_id, 'endereco_id' => $endereco_id, 'total' => $valor_final, 'porcentagem' => $porcentagem, 'totalSemDesconto' => $valor_total, 'excluido' => 0];

        session()->put('PedidosTotalValor', $pedidosTotalValor);

        return $pedido_id;
    }

    function salvarItemPedido($pedido_id, $cliente_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade, $provider_saida)
    {
        $pedidoUnitario = session()->get('pedidoUnitario', []);
        $pedidosTotalValor = session()->get('PedidosTotalValor', []);

        $count = sizeof($pedidosTotalValor);
    
        $pedidoUnitario[] = ['pedido_id' => $pedido_id,'cliente_id' => $cliente_id, 'produto_id' => $produto_id,'quantidade' => $quantidade, 'porcentagem' => $porcentagem_unidade, 'total' => $valor_final, 'preco_unidade' => $preco_unidade, 'totalSemDesconto' => $valor_total];


        session()->put('pedidoUnitario', $pedidoUnitario);

        return $count;
    }

    public function buscarItem($pedido_id)
    {
        $pedidos = session()->get('pedidoUnitario');

        foreach ($pedidos as $key => $value) {
            if($value['pedido_id'] == $pedido_id)
                $total = $value['total'];
        }

        $lista = ['total' => $total];

        return $lista;          
    }
}
