<?php

namespace App\Services;


use App\Models\Produto;

use \App\Services\DBPedidosService;
use \App\Services\DBClientesService;

use App\Services\ProdutosServiceInterface;
use Illuminate\Support\Facades\Storage;

class DBProdutosService implements ProdutosServiceInterface
{
	public function adicionarProduto($nome, $categoria, $valor, $imagem)
	{
        $produto = new Produto();

        $produto->produto = $nome;
        $produto->categoria_id = $categoria;
        $produto->valor = $valor;
        $produto->imagem = $imagem;
      
        $produto->save();
	}
    
    public function editarProduto($produto_id, $nome, $valor)
    {
        $produto = Produto::find($produto_id);

        $produto->produto = $nome;
        $produto->valor = $valor;

        $produto->save();

    }

    public function excluirProduto($produto_id)
    {
        $produto = Produto::find($produto_id);

        $produto->delete($produto_id);
    }
    
    public function listarProduto()
    {
        $produtos = Produto::all();

        $listarProdutos = [];

        foreach ($produtos as $produto) 
        {
            $nome_produto = $produto->produto;
            $valor_produto = $produto->valor;
            $image_url_produto = $produto->imagem;
           
            $listarProdutos[$produto->id] = ['produto' => $nome_produto, 'valor' => $valor_produto, 'image_url' => $image_url_produto];       
        }

        return $listarProdutos;
    }

    public function buscarProduto($produto_id)
    {
        $produtoEncontrado = [];

        $produto = Produto::find($produto_id);

        foreach ($produto as $key => $value) {
            $nome_produto = $produto->produto;
            $valor_produto = $produto->valor;
            $produto_id = $produto->id;

        }
        

        $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'produto_id' => $produto_id];

        return $produtoEncontrado;
    }
}
