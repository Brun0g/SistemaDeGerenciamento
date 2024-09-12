<?php

namespace App\Services;

use App\Services\ProdutosServiceInterface;
use Illuminate\Support\Facades\Auth;

class SessionProdutosService implements ProdutosServiceInterface
{
	public function adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade, $provider_entradas)
	{
        $estoque = session()->get('EstoqueProdutos', []);

        $estoque[] = ['produto' => $nome, 'categoria' => $categoria, 'valor'=> (int)$valor, 'quantidade' => (int)$quantidade,  'imagem' => $imagem, 'deleted_at' => null];

        session()->put('EstoqueProdutos', $estoque);

        $produto_id = array_key_last($estoque);

        $provider_entradas->adicionarEntrada($produto_id, $quantidade, null, 'Primeira entrada no sistema', null, null);
	}
    
    public function editarProduto($produto_id, $nome, $valor, $imagem)
    {
        $produtos = session()->get('EstoqueProdutos', []);


        foreach ($produtos as $key => $value) {
            if($produto_id == $key)
            {
                $produtos[$key]['produto'] = $nome;
                $produtos[$key]['valor'] = $valor;

                if(isset($imagem))
                    $produtos[$key]['imagem'] = $imagem;
            }
        }

        session()->put('EstoqueProdutos', $produtos);
    }

    public function excluirProduto($produto_id)
    {
        if(session()->has('EstoqueProdutos'))
        {
            $produtos = session()->get('EstoqueProdutos');

            foreach ($produtos as $key => $value) {
                    if($key == $produto_id)
                        $produtos[$key]['deleted_at'] = date("Y-m-d H:i:s");
                    
            }

            session()->put('EstoqueProdutos', $produtos);
        }
    }

    public function listarProduto($provider_promotions, $softDelete)
    {
        $produtos = session()->get('EstoqueProdutos', []);
        $listarProdutos = [];

     

        foreach ($produtos as $key => $value) 
        {
            if($softDelete == $value['deleted_at'])
            {
                $nome_produto = $value['produto'];
                $valor_produto = $value['valor'];
                $image_url_produto = $value['imagem'];
                $quantidade_estoque = $value['quantidade'];
                $produto_id = $key;

                $promocao = $provider_promotions->buscarPromocao($produto_id);
                $ativo = $promocao['ativo'];
                $array[$produto_id] = $promocao['promocao'];

                $service_carrinho = new SessionCarrinhoService();

                $quantidade_carrinho = $service_carrinho->buscarQuantidade($produto_id)['quantidade'];
                $quantidade = $quantidade_estoque - $quantidade_carrinho;

                if($image_url_produto != false)
                    $image_url_produto = asset("storage/" . $image_url_produto);

                 $listarProdutos[$produto_id] = ['produto' => $nome_produto, 'valor' => $valor_produto, 'quantidade' => $quantidade, 'image_url' => $image_url_produto, 'promocao' => $array, 'ativo' =>  $ativo, 'quantidade_estoque' => $quantidade_estoque];
            }
        }

        return $listarProdutos;
    }

    public function buscarProduto($produto_id)
    {
        $produtoEncontrado = [];
        $produtos = session()->get('EstoqueProdutos', []);


        foreach ($produtos as $key => $value) {
            if($produto_id == $key)
            {
                $nome_produto = $value['produto'];
                $valor_produto = $value['valor'];
                $produto_id = $key;
                $image_url_produto = $value['imagem'];
                $deleted_at = $value['deleted_at'];
                $quantidade_estoque = $value['quantidade'];

                $image_url_produto = asset("storage/" . $image_url_produto);
               
                if($value['imagem'] == false)
                    $image_url_produto = false;

                $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'produto_id' => $produto_id, 'image_url' => $image_url_produto, 'deleted_at' => $deleted_at, 'quantidade_estoque' => $quantidade_estoque];
            }
        }

   
        return $produtoEncontrado;
    }

    public function deletarImagem($produto_id)
    {
        $produto = session()->get('EstoqueProdutos', []);

        foreach ($produto as $key => $value) {
            if($produto_id == $key)
                $produto[$produto_id]['imagem'] = false; 
        }

        session()->put('EstoqueProdutos', $produto);
    }

    public function atualizarEstoque($produto_id, $quantidade, $entrada_ou_saida, $observacao, $provider_entradas, $provider_saida, $pedido_id, $tipo, $ajuste_id, $multiplo_id)
    {
        $produto = session()->get('EstoqueProdutos', []);

        foreach ($produto as $key => $value) {
            if($produto_id == $key)
            {
                if(isset($entrada_ou_saida))
                {
                    $quantidade_anterior = $value['quantidade'];
                    $produto[$key]['quantidade'] += $quantidade;

                    // if($tipo == 'Ajuste entrada' || $tipo == 'Ajuste saida')
                    //     $produto[$key]['quantidade'] = $quantidade;

                    if($entrada_ou_saida == 'entrada')
                        $provider_entradas->adicionarEntrada($produto_id, $quantidade, $observacao, $tipo, $ajuste_id, $multiplo_id);
                    else
                        $provider_saida->adicionarSaida($produto_id, $pedido_id, $quantidade, $observacao, $tipo, $ajuste_id, $multiplo_id);
                
                } else 
                    $produto[$key]['quantidade'] = $quantidade;
            }
        }

        session()->put('EstoqueProdutos', $produto);
    }
}
