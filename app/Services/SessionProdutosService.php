<?php

namespace App\Services;

use App\Services\ProdutosServiceInterface;

class SessionProdutosService implements ProdutosServiceInterface
{
	public function adicionarProduto($nome, $categoria, $valor, $imagem)
	{
        $produtosNoEstoque = session()->get('EstoqueProdutos', []);
        $produtosNoEstoque[] = ['produto' => $nome, 'categoria' => $categoria, 'valor'=> $valor, 'imagem' => $imagem];

        session()->put('EstoqueProdutos', $produtosNoEstoque);
	}
    
    public function editarProduto($produto_id, $nome, $valor, $imagem)
    {
        $produtosNoEstoque = [];

        if(session()->has('EstoqueProdutos'))
        {
            $produtosNoEstoque = session()->get('EstoqueProdutos'); 

            $produtosNoEstoque[$produto_id]['produto'] = $nome;
            $produtosNoEstoque[$produto_id]['valor'] = $valor;
              
            if(isset($imagem))     
                $produtosNoEstoque[$produto_id]['imagem'] = $imagem;      
        }

        session()->put('EstoqueProdutos', $produtosNoEstoque);
    }

    public function excluirProduto($produto_id)
    {
        if(session()->has('EstoqueProdutos'))
        {
            $produtos = session()->get('EstoqueProdutos');

            foreach ($produtos as $key => $value) {
                    if($key == $produto_id)
                        $produtos[$key]['softDelete'] = 1;
            }

            session()->put('EstoqueProdutos', $produtos);
        }
    }

    public function listarProduto($provider_promotions, $provider_produto, $softDelete)
    {
        $produtos = session()->get('EstoqueProdutos', []);

        $listarProdutos = [];
        $quantidade = 0;

        foreach ($produtos as $key => $value) 
        {
            $nome_produto = $value['produto'];
            $valor_produto = $value['valor'];
            $image_url_produto = $value['imagem'];
            $produto_id = $key;

            $promocao[$key] = $provider_promotions->listarPromocoes($provider_produto);
    
            $ativo = isset($promocao[$produto_id][0]['ativo']) ? $promocao[$produto_id][0]['ativo'] : 0;
           
            if($image_url_produto != false)
                $image_url_produto = asset("storage/" . $image_url_produto);

            if(!isset($value['softDelete']))
                $listarProdutos[$key] = ['produto' => $nome_produto, 'valor' => $valor_produto, 'image_url' => $image_url_produto, 'promocao' => $promocao, 'ativo' => $ativo];

            if($softDelete == true)
                $listarProdutos[$key] = ['produto' => $nome_produto, 'valor' => $valor_produto, 'image_url' => $image_url_produto, 'promocao' => $promocao, 'ativo' => $ativo];

        }

        return $listarProdutos; 
    }

    public function buscarProduto($produto_id, $softDelete)
    {
        $produtos = session()->get('EstoqueProdutos', []);
        $produtoEncontrado = [];    

        foreach ($produtos as $key => $value) {

            if($produto_id == $key )
            {
                $nome_produto = $value['produto'];
                $valor_produto = $value['valor'];
                $produto_id = $key;
                $image_url_produto = $value['imagem'];

                $image_url_produto = asset("storage/" . $image_url_produto);

                if($value['imagem'] == false)
                    $image_url_produto = false;

                if(!isset($value['softDelete']))
                    $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'produto_id' => $produto_id, 'image_url' => $image_url_produto];

                if($softDelete == true)
                    $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'produto_id' => $produto_id, 'image_url' => $image_url_produto];
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

    
}
