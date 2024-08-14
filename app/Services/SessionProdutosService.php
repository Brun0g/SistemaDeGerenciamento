<?php

namespace App\Services;

use App\Services\ProdutosServiceInterface;

class SessionProdutosService implements ProdutosServiceInterface
{
	public function adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade)
	{
        $produtosNoEstoque = session()->get('EstoqueProdutos', []);

        $produtosNoEstoque[] = ['produto' => $nome, 'categoria' => $categoria, 'valor'=> (int)$valor, 'quantidade' => (int)$quantidade,  'imagem' => $imagem, 'deleted_at' => null];

        session()->put('EstoqueProdutos', $produtosNoEstoque);
	}
    
    public function editarProduto($produto_id, $nome, $valor, $imagem, $quantidade)
    {
        $produtosNoEstoque = [];

        if(session()->has('EstoqueProdutos'))
        {
            $produtosNoEstoque = session()->get('EstoqueProdutos'); 

            $produtosNoEstoque[$produto_id]['produto'] = $nome;
            $produtosNoEstoque[$produto_id]['valor'] = $valor;
            $produtosNoEstoque[$produto_id]['quantidade'] = $quantidade;
              
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
                $estoque = $value['quantidade'];
                $produto_id = $key;

                $promocao = $provider_promotions->buscarPromocao($produto_id);
                $ativo = $promocao['ativo'];
                $array[$produto_id] = $promocao['promocao'];

                if($image_url_produto != false)
                    $image_url_produto = asset("storage/" . $image_url_produto);

                 $listarProdutos[$produto_id] = ['produto' => $nome_produto, 'valor' => $valor_produto, 'quantidade' => $estoque, 'image_url' => $image_url_produto, 'promocao' => $array, 'ativo' =>  $ativo];
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
                $delete = $value['deleted_at'];
                $estoque = $value['quantidade'];

                $image_url_produto = asset("storage/" . $image_url_produto);

                if($value['imagem'] == false)
                    $image_url_produto = false;

                $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'quantidade' => $estoque, 'produto_id' => $produto_id, 'image_url' => $image_url_produto, 'deleted_at' => $delete];
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

    public function atualizarEstoque($produto_id, $quantidade)
    {
        $produto = session()->get('EstoqueProdutos', []);

        foreach ($produto as $key => $value) {
            if($produto_id == $key)
                $produto[$key]['quantidade'] += $quantidade; 
        }

        session()->put('EstoqueProdutos', $produto);
    }
}
