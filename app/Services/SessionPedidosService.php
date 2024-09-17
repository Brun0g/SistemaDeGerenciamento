<?php

namespace App\Services;

use App\Services\PedidosServiceInterface;
use \App\Services\SessionProdutosService;
use App\Models\Pedido;
use App\Models\Pedidos_finalizados;

use Illuminate\Support\Facades\Auth;

class SessionPedidosService implements PedidosServiceInterface
{
    public function excluirPedido($cliente_id, $pedido_id)
    {
        $PedidosEncerrados = session()->get('Pedido_encerrado',[]);
        $PedidosConcluidos = session()->get('Pedido_encerrado_individual',[]);

            foreach ($PedidosConcluidos as $id => $valor) {
                if($valor['cliente_id'] == $cliente_id)
                    if($valor['pedido_id'] == $pedido_id)
                        unset($PedidosConcluidos[$id]);
            }

            foreach ($PedidosEncerrados as $key => $value) {
                if ($cliente_id == $value['cliente_id']) {
                        if($pedido_id == $value['pedido_id'])
                            $PedidosEncerrados[$key]['excluido'] = 1;
                }
            }
            
        session()->put('Pedido_encerrado_individual', $PedidosConcluidos);
        session()->put('Pedido_encerrado', $PedidosEncerrados);
    }
    

    public function listarQuantidadePedidos()
    {
        $Pedido_encerrado_individual = session()->get('Pedido_encerrado_individual', []); 
        $service_produtos = new SessionProdutosService();

        foreach ($Pedido_encerrado_individual as $pedidoKey => $value) {
            $id = $value['cliente_id'];
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];
   

            $Pedido_encerrado_individual[$pedidoKey] = ['cliente_id' => $id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'total' => $valor]; 
        } 

        return $Pedido_encerrado_individual; 
    }

    public function listarPedidos($cliente_id)
    {
        $listaPedidos = [];
        $pedidos = session()->get('Pedido_encerrado',[]);

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
        $pedidos = session()->get('Pedido_encerrado')[$pedido_id]; 

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

    public function buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $Pedido_encerrado_individual = session()->get('Pedido_encerrado_individual', []);
        $service_produtos = new SessionProdutosService();
        $total = 0;

        foreach ($Pedido_encerrado_individual as $pedidoKey => $pedido) 
        {
            if($pedido['pedido_id'] == $pedido_id)
            {
                $pedido_id = $pedido['pedido_id'];
                $produto_id = $pedido['produto_id'];
                $quantidade = $pedido['quantidade'];
                $porcentagem = $pedido['porcentagem'];
                $valor = $pedido['total'];
                $produto = $service_produtos->buscarProduto($produto_id)['produto'];
                $preco_unidade = $pedido['preco_unidade'];

                $total += $valor;

    
            $lista[$pedidoKey] = ['produto_id' => $produto_id, 'produto' => $produto, 'pedido_id' => $pedido_id, 'quantidade' => $quantidade, 'total' => $valor, 'preco_unidade' => $preco_unidade, 'porcentagem' => $porcentagem, 'totalComDesconto' => $total];  
            } 
        }

        return $lista; 
    }

    public function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total)
    {
        $Pedido_encerrado = session()->get('Pedido_encerrado', []);
        $index = count($Pedido_encerrado);
        $pedido_id = $index + 1;
        
        $Pedido_encerrado[$pedido_id] = ['pedido_id' => $pedido_id, 'cliente_id' => $cliente_id, 'endereco_id' => $endereco_id, 'total' => $valor_final, 'porcentagem' => $porcentagem, 'totalSemDesconto' => $valor_total, 'excluido' => 0];

        session()->put('Pedido_encerrado', $Pedido_encerrado);

        return $pedido_id;
    }

    function salvarItemPedido($pedido_id, $cliente_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade)
    {
        $Pedido_encerrado_individual = session()->get('Pedido_encerrado_individual', []);
        $Pedido_encerrado = session()->get('Pedido_encerrado', []);

        $count = sizeof($Pedido_encerrado);
    
        $Pedido_encerrado_individual[] = ['pedido_id' => $pedido_id,'cliente_id' => $cliente_id, 'produto_id' => $produto_id,'quantidade' => $quantidade, 'porcentagem' => $porcentagem_unidade, 'total' => $valor_final, 'preco_unidade' => $preco_unidade, 'totalSemDesconto' => $valor_total];


        session()->put('Pedido_encerrado_individual', $Pedido_encerrado_individual);

        return $count;
    }
}
