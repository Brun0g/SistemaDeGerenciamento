<?php

namespace App\Services;


use App\Services\PedidosServiceInterface;
use App\Services\ProdutosServiceInterface;
use App\Services\PromotionsServiceInterface;
use \App\Services\DBProdutosService;
use \App\Services\DBClientesService;
use \App\Services\SessionProdutosService;
use App\Models\Pedido;
use App\Models\Pedidos_finalizados;
use Carbon\Carbon;


class SessionCarrinhoService implements CarrinhoServiceInterface
{
    public function adicionarProduto($cliente_id, $produto_id, $quantidade,  $provider_produto, $provider_carrinho, $provider_promotions)
    {   

        $pedidos = session()->get('Pedidos', []);
        $produto = $provider_produto->buscarProduto($produto_id);
        $total = $produto['valor'] * $quantidade;

        $novoPedido = ['cliente_id' => (int)$cliente_id, 'produto_id' => (int)$produto_id, 'quantidade' => (int)$quantidade, 'produto' => $produto['produto'], 'total' => $total, 'total_final' => $total];

        $produtoExiste = false;

        foreach ($pedidos as $key => $value) 
        {
            if($value['cliente_id'] == $cliente_id)
            {
                if($value['produto_id'] == $novoPedido['produto_id'])
                {
                    $pedidos[$key]['quantidade'] += (int)$novoPedido['quantidade'];
                    $pedidos[$key]['total'] += (int)$novoPedido['total'];
                    $produtoExiste = true;
                } 
            }  
        }

        if(!$produtoExiste)
            $pedidos[] = $novoPedido;

        session()->put('Pedidos', $pedidos);

        $provider_carrinho->calcularDesconto($cliente_id, $provider_produto, $provider_carrinho, $provider_promotions);
    }

    public function excluirProduto($cliente_id, $id_pedido)
    {
        $ProdutosPorCliente = session()->get('Pedidos', []);
       
        foreach ($ProdutosPorCliente as $pedido => $value) {
            if($cliente_id == $value['cliente_id'])
            {
                if($pedido == $id_pedido)
                    unset($ProdutosPorCliente[$id_pedido]);
 
                session()->put('Pedidos', $ProdutosPorCliente);  
            }         
        }   
    }

    public function visualizar($cliente_id, $provider_produto, $provider_promotions)
    {
        $pedidos = session()->get('Pedidos', []);
        $carrinho = [];

        foreach ($pedidos as $key => $value) 
        {
            if($cliente_id == $value['cliente_id'])
            {
                $produto_id = $value['produto_id'];
                $quantidade = $value['quantidade'];
                $total = $value['total'];
                $total_final = $value['total_final'];
                $produto = $provider_produto->buscarProduto($produto_id);
                $preco_unidade = $produto['valor'];

                $unidade_desconto = $preco_unidade;

                if(in_array($produto_id, $provider_promotions->buscarPromocao($produto_id, $quantidade)))
                {
                    $promocao = $provider_promotions->buscarPromocao($produto_id, $quantidade);
                
                    if($quantidade >= $promocao['quantidade'])
                    {
                        $promocao_ativa = $promocao['ativo'];

                        if($promocao['ativo'] == 1)
                            $unidade_desconto = $preco_unidade - ($preco_unidade / 100 * $promocao['porcentagem']); 
                    }
                }


                $carrinho[$key] = ['cliente_id' => $cliente_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'total' => $total, 'total_final' => $total_final, 'produto' => $produto['produto'], 'preco_unidade' => $preco_unidade, 'unidade_desconto' => $unidade_desconto];
            }       
        } 

        return $carrinho; 
    }

    public function atualizar($pedido_id, $cliente_id, $quantidade, $provider_produto, $provider_carrinho, $provider_promotions)
    {
        $pedidos = session()->get('Pedidos', []);
        
        foreach ($pedidos as $key => $value) 
        {
            if($pedido_id == $key)
            {
                $produto_id = $value['produto_id'];
                $preco_unidade = $provider_produto->buscarProduto($produto_id)['valor'];
                $total = $preco_unidade * $quantidade;
                
                $pedidos[$key]['total'] = $total;
                $pedidos[$key]['total_final'] = $total;
                $pedidos[$key]['quantidade'] = $quantidade;
            }
        }

        session()->put('Pedidos', $pedidos);

        $provider_carrinho->calcularDesconto($cliente_id, $provider_produto, $provider_carrinho, $provider_promotions);
    }

    public function atualizarPorcentagem($cliente_id, $porcentagem)
    {   

        $descontos = session()->get('porcentagem', []);

        $clienteExiste = false;
        
        foreach ($descontos as $key => $value) 
        {
            if($value['cliente_id'] == $cliente_id)
            {
                $descontos[$key]['cliente_id'] = $cliente_id;
                $descontos[$key]['porcentagem'] = $porcentagem;
                $clienteExiste = true;
            }
        } 

        $array = ['cliente_id' => (int)$cliente_id, 'porcentagem' => (int)$porcentagem];

        if(!$clienteExiste)
            $descontos[] = $array;

        session()->put('porcentagem', $descontos);
    }

    public function finalizarCarrinho($cliente_id, $endereco_id, $provider_carrinho, $provider_produto,  $provider_pedido, $provider_promotions)
    {
        $pedidos_carrinho = $provider_carrinho->visualizar($cliente_id, $provider_produto, $provider_promotions);
        $buscar = $provider_carrinho->calcularDesconto($cliente_id, $provider_produto, $provider_carrinho, $provider_promotions);

    
        $porcentagem = $buscar['porcentagem'];

        $valor_total = $buscar['totalSemDesconto'];
        $totalComDesconto = $buscar['totalComDesconto'];
        $valor_final = $totalComDesconto - ($totalComDesconto / 100 * $porcentagem);
        
        $pedido_id = $provider_pedido->salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total);


        foreach ($pedidos_carrinho as $key => $value) {
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor_final = $value['total_final'];
            $valor_total = $value['total'];
            $porcentagem_unidade = 0;


            $preco_unidade = $provider_produto->buscarProduto($produto_id)['valor'];

            if(in_array($produto_id, $provider_promotions->buscarPromocao($produto_id, $quantidade)))
            {
                $promocao = $provider_promotions->buscarPromocao($produto_id, $quantidade);

                if($produto_id == $promocao['produto_id'])
                {
                    if($quantidade >= $promocao['quantidade'])
                    {
                        if($promocao['ativo'] == 1){
                            $porcentagem_unidade = $promocao['porcentagem'];
                        }
                    }
                }
            }

            $provider_pedido->salvarItemPedido($pedido_id, $cliente_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade);
        }
    }

    public function visualizarPorcentagem($cliente_id)
    {
        $descontos = session()->get('porcentagem', []);

        $porcentagem = 0;
    
        foreach ($descontos as $key => $value) {
            if($cliente_id == $value['cliente_id'])
                $porcentagem = $value['porcentagem'];
        }

        return $porcentagem;
    }

    public function calcularDesconto($cliente_id, $provider_produto, $provider_carrinho, $provider_promotions)
    {
        $pedidos = session()->get('Pedidos', []);
    
        $totalSemDesconto = 0;
        $porcentagem_promocao = 0;
        $preco_unidade = 0;
        $valor_final = 0;

        $totalPedido = [];
        $array = [];

        $porcentagem = $provider_carrinho->visualizarPorcentagem($cliente_id);
        
        foreach ($pedidos as $key => $value) {
            if($cliente_id == $value['cliente_id'])
            {
                $total = $value['total'];
                $produto_id = $value['produto_id'];
                $quantidade = $value['quantidade'];

                $totalSemDesconto += $total;
                $preco_unidade = $total / $quantidade;
                
                $valor_final += $value['total_final'];
                $pedidos[$key]['total_final'] = $total;

                $promocao = $provider_promotions->buscarPromocao($produto_id, $quantidade);

                if(isset($promocao['produto_id']))
                {
                    $unidade_desconto = $preco_unidade - ($preco_unidade / 100 * $promocao['porcentagem']);
                    $total = $unidade_desconto * $value['quantidade'];
                    $pedidos[$key]['total_final'] = $total;
    
                }
            } 
        }

        $totalPedido = ['totalSemDesconto' => $totalSemDesconto, 'totalComDesconto' => $valor_final, 'porcentagem' => $porcentagem, 'preco_unidade' => $preco_unidade];


        session()->put('Pedidos', $pedidos);

        return $totalPedido;
    }
}
