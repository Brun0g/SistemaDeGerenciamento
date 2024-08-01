<?php

namespace App\Services;

use App\Services\ProdutosServiceInterface;

class SessionProdutosService implements ProdutosServiceInterface
{
	public function adicionarProduto($nome, $categoria, $valor, $imagem)
	{
        $produtosNoEstoque = session()->get('EstoqueProdutos', []);
        $produtosNoEstoque[] = ['produto' => $nome, 'categoria' => $categoria, 'valor'=> $valor];

        session()->put('EstoqueProdutos', $produtosNoEstoque);
	}
    
    public function editarProduto($produto_id, $nome, $valor)
    {
        $produtosNoEstoque = [];

        if(session()->has('EstoqueProdutos'))
        {
            $produtosNoEstoque = session()->get('EstoqueProdutos'); 

            if(array_key_exists($produto_id, $produtosNoEstoque))
            {
                $produtosNoEstoque[$produto_id]['produto'] = $nome;
                $produtosNoEstoque[$produto_id]['valor'] = $valor;

                session()->put('EstoqueProdutos', $produtosNoEstoque);
            }
        } 
    }

    public function excluirProduto($produto_id)
    {
        if(session()->has('EstoqueProdutos'))
        {
            $produtosNoEstoque = session()->get('EstoqueProdutos');

            if(array_key_exists($produto_id, $produtosNoEstoque))
            {
                unset($produtosNoEstoque[$produto_id]);
                session()->put('EstoqueProdutos', $produtosNoEstoque);
            }
        }
    }

    public function listarProduto()
    {
        $listar_estoque_produto = [];

        if(session()->has('EstoqueProdutos'))
        {
            $estoqueProdutos = session()->get('EstoqueProdutos', []);
           
            foreach ($estoqueProdutos as $key => $value) {
                $nome_produto = $value['produto'];
                $categoria_produto = $value['categoria'];
                $valor_produto = $value['valor'];

                $listar_estoque_produto[$key] = ['produto' => $nome_produto, 'categoria' => $categoria_produto, 'valor' => $valor_produto];
            }
        }

        return $listar_estoque_produto; 
    }

    public function buscarProduto($produto_id)
    {
        $produtoEncontrado = [];
        $produto = session()->get('EstoqueProdutos');

        foreach ($produto as $key => $value) {
            if($produto_id == $key)
            {
                $nome_produto = $value['produto'];
                $categoria_produto = $value['categoria'];
                $valor_produto = $value['valor'];
           
                $produtoEncontrado = [ 'produto' => $nome_produto, 'categoria' => $categoria_produto, 'valor' => $valor_produto, 'produto_id' => $produto_id];
            }
        }

        return $produtoEncontrado;
    }
    
}
