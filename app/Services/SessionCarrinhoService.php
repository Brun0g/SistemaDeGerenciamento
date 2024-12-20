<?php

namespace App\Services;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class SessionCarrinhoService implements CarrinhoServiceInterface
{
    public function adicionarProduto($cliente_id, $produto_id, $quantidade,  $provider_produto, $provider_carrinho, $provider_promocoes, $provider_estoque)
    {   
        $pedidos = session()->get('Pedidos', []);
        $produto = $provider_produto->buscarProduto($produto_id);
        $total = $produto['valor'] * $quantidade;

        $quantidade_estoque = $provider_estoque->buscarEstoque($produto_id);
        $error  = true;

        if($quantidade <= $quantidade_estoque)
        {
            $novoPedido = ['cliente_id' => (int)$cliente_id, 'produto_id' => (int)$produto_id, 'quantidade' => (int)$quantidade, 'produto' => $produto['produto'], 'total' => $total, 'total_final' => $total, 'deleted_at' => $produto['deleted_at'], 'unidade_desconto' => $produto['valor'], 'promocao_porcentagem' => 0];

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

            $provider_carrinho->calcularDesconto($cliente_id, $provider_carrinho, $provider_promocoes, $provider_produto);

            $error = false;
        }

        return ['error' => $error, 'quantidade' => $quantidade_estoque];
    }

    public function excluirProduto($cliente_id, $produto_id)
    {
        $pedidos = session()->get('Pedidos', []);

        foreach ($pedidos as $key => $value) 
        {
            if($cliente_id == $value['cliente_id'])
            {
                if($produto_id == $value['produto_id'])
                    unset($pedidos[$key]);
                
            }         
        } 

        session()->put('Pedidos', $pedidos);  
    }

    public function visualizar($cliente_id, $provider_produto, $provider_promocoes, $provider_carrinho, $provider_estoque)
    {
        $pedidos = session()->get('Pedidos', []);
        $carrinho = [];
        $array_erros = [];

        foreach ($pedidos as $key => $value) 
        {
            if($cliente_id == $value['cliente_id'])
            {
                $produto_id = $value['produto_id'];
                $produto = $provider_produto->buscarProduto($produto_id);
                $quantidade = $value['quantidade'];
                $total = $value['total'];
                $total_final = $value['total_final'];
                $preco_unidade = $produto['valor'];
                $deleted_at = $produto['deleted_at'];
                $quantidade_estoque = $provider_estoque->buscarEstoque($produto_id);
                $unidade_desconto = $value['unidade_desconto'];
                $promocao_porcentagem = $value['promocao_porcentagem'];

                $fora_de_estoque = $quantidade_estoque < $quantidade;

                
                $carrinho[$key] = ['cliente_id' => $cliente_id, 'produto_id' => $produto_id, 'quantidade' => (int)$quantidade, 'total' => $total, 'total_final' => $total_final, 'produto' => $produto['produto'], 'preco_unidade' => $preco_unidade, 'unidade_desconto' => $unidade_desconto, 'deleted_at' => $deleted_at, 'quantidade_estoque' => $quantidade_estoque, 'fora_de_estoque' => $fora_de_estoque, 'promocao_porcentagem' => $promocao_porcentagem];
            }       
        }

        return $carrinho; 
    }

    public function atualizar($pedido_id, $cliente_id, $quantidade, $provider_produto, $provider_carrinho, $provider_promocoes)
    {
        $pedidos = session()->get('Pedidos', []);


        foreach ($pedidos as $key => $value) 
        {
            if($pedido_id == $key)
            {
                $produto_id = $value['produto_id'];
                $produto = $provider_produto->buscarProduto($produto_id);


                $porcentagem_promocao = $provider_promocoes->buscarQuantidade($produto_id, $quantidade);

                $total = $produto['valor'] * $quantidade;

        
                if($porcentagem_promocao != [])
                    $pedidos[$key]['unidade_desconto'] = $produto['valor'] - ($produto['valor'] / 100 *  $porcentagem_promocao['porcentagem']);

                $pedidos[$key]['promocao_porcentagem'] = $porcentagem_promocao;
                $pedidos[$key]['total'] = $total;
                $pedidos[$key]['total_final'] = $total;
                $pedidos[$key]['quantidade'] = $quantidade;
            }
        }

        session()->put('Pedidos', $pedidos);

        $provider_carrinho->calcularDesconto($cliente_id, $provider_carrinho, $provider_promocoes, $provider_produto);
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

    public function finalizarCarrinho($cliente_id, $endereco_id, $provider_carrinho, $provider_produto, $provider_pedidos, $provider_promocoes, $provider_entradas_saidas, $provider_user, $provider_estoque)
    {
        $pedidos_carrinho = $provider_carrinho->visualizar($cliente_id, $provider_produto, $provider_promocoes, $provider_carrinho, $provider_estoque);
        $buscarValores = $provider_carrinho->calcularDesconto($cliente_id, $provider_carrinho, $provider_promocoes, $provider_produto);

        $porcentagem = $buscarValores['porcentagem'];
        $valor_total = $buscarValores['totalSemDesconto'];
        $valor_final = $buscarValores['totalComDesconto'];
        
        
        $pedido_id = $provider_pedidos->salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total, null, null);

        foreach ($pedidos_carrinho as $key => $value) {
            $produto_id = $value['produto_id'];

            if(!isset($value['deleted_at']))
            {
                $quantidade = $value['quantidade'];
                $valor_final = $value['total_final'];
                $valor_total = $value['total'];
                $porcentagem_unidade = $value['promocao_porcentagem'];

                $preco_unidade = $provider_produto->buscarProduto($produto_id)['valor'];


                $provider_pedidos->salvarItemPedido($pedido_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade, null, null);

                $provider_estoque->atualizarEstoque($produto_id, -$quantidade, 'saida', null, $provider_entradas_saidas, $pedido_id, null, null);

                $provider_carrinho->excluirProduto($cliente_id, $produto_id);
            }
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

    public function calcularDesconto($cliente_id, $provider_carrinho, $provider_promocoes, $provider_produto)
    {
        $pedidos = session()->get('Pedidos', []);
        
        $totalSemDesconto = 0;
        $preco_unidade = 0;
        $totalComDesconto = 0;
        $desconto_total_promocao = 0;
        $totalPedido = [];

        $porcentagem = $provider_carrinho->visualizarPorcentagem($cliente_id);
        
        foreach ($pedidos as $key => $value) 
        {
            if($cliente_id == $value['cliente_id'])
            {
                $produto_id = $value['produto_id'];
                $deleted_at = $provider_produto->buscarProduto($produto_id)['deleted_at'];

                if( !isset($deleted_at) )
                {

                    $total = $value['total'];
                    $quantidade = $value['quantidade'];

                    $totalSemDesconto += $total;

                    if($quantidade > 0)
                        $preco_unidade = $total / $quantidade;

                    $total_sem_desconto_unidade = $preco_unidade * $quantidade;

                    $totalComDesconto += $value['total_final'];
                    $pedidos[$key]['total_final'] = $total;

                    $promocao = $provider_promocoes->buscarQuantidade($produto_id, $quantidade);

                    if(isset($promocao['produto_id']))
                    {
                        $unidade_desconto = $preco_unidade - ($preco_unidade / 100 * $promocao['porcentagem']);
                        $total = $unidade_desconto * $value['quantidade'];
                        $pedidos[$key]['total_final'] = $total;

                        if($promocao['ativo'] == 1)
                        {
                            $desconto_total_promocao += $total_sem_desconto_unidade - $total;
                            $pedidos[$key]['desconto_total_promocao'] = $desconto_total_promocao;

                            if($promocao['quantidade'] >= $quantidade)
                            {
                                $pedidos[$key]['promocao_porcentagem'] = $promocao['porcentagem'];
                                $pedidos[$key]['unidade_desconto'] = $preco_unidade - ($preco_unidade / 100 * $promocao['porcentagem']);
                            }
                        }
                    }
                }
            } 
        }


        $valor_final = $totalComDesconto - ($totalComDesconto / 100 * $porcentagem) + $desconto_total_promocao;
        
        $totalPedido = [
        'totalSemDesconto' => $totalSemDesconto, 
        'totalComDesconto' => $totalComDesconto, 
        'porcentagem' => $porcentagem, 
        'preco_unidade' => $preco_unidade, 
        'valor_final' => $valor_final,
        'desconto_total_promocao' => $desconto_total_promocao];


        session()->put('Pedidos', $pedidos);

        return $totalPedido;
    }

    public function buscarCarrinho($cliente_id, $pedido_id)
    {
        $pedidos = session()->get('Pedidos', []);

        foreach ($pedidos as $key => $value) {
            if($pedido_id == $key)
            {
                $produto_id = $value['produto_id'];
                $quantidade = $value['quantidade'];
                $total = $value['total'];
                $total_final = $value['total_final'];

                $carrinho = ['cliente_id' => $cliente_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'total' => $total, 'total_final' => $total_final];
            }
        }

        return $carrinho;
    }

    public function buscarQuantidade($produto_id)
    {
        $pedidos = session()->get('Pedidos', []);

        $quantidade = 0;

        $array = ['produto' => $produto_id, 'quantidade' => $quantidade];
        
        foreach ($pedidos as $key => $value) {
            if($produto_id == $value['produto_id'])
                $array = ['produto' => $value['produto'], 'quantidade' => $quantidade += $pedidos[$key]['quantidade']];
        }

        return $array;
    }

    public function listarPedidosCarrinho()
    {
        $pedidos = session()->get('Pedidos', []);

        $carrinho = [];

        foreach ($pedidos as $key => $value) {
            $cliente_id = $value['cliente_id'];
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $total = $value['total'];
            $total_final = $value['total_final'];

            $carrinho[] = ['cliente_id' => $cliente_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'total' => $total, 'total_final' => $total_final];
            
        }

        return $carrinho;
    }
}
