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
    
    public function editarProduto($produto_id, $nome, $valor, $imagem)
    {
        $produto = Produto::find($produto_id);

        $produto->produto = $nome;
        $produto->valor = $valor;
        
        if(isset($imagem))
            $produto->imagem = $imagem;
   
        $produto->save();
    }

    public function excluirProduto($produto_id)
    {
        $produto = Produto::find($produto_id);

        $produto->delete($produto_id);
    }
    
    public function listarProduto($provider_promotions, $provider_produto, $softDelete)
    {
        $produtos = Produto::all();

        $listarProdutos = [];

        foreach ($produtos as $produto) 
        {
            $nome_produto = $produto->produto;
            $valor_produto = $produto->valor;
            $image_url_produto = $produto->imagem;
            $produto_id = $produto->id;

            $promocao = $provider_promotions->buscarPromocao($produto_id);
            $ativo = $promocao['ativo'];
            $array[$produto_id] = $promocao['promocao'];

            if($image_url_produto != false)
                $image_url_produto = asset("storage/" . $image_url_produto);

            $listarProdutos[$produto->id] = ['produto' => $nome_produto, 'valor' => $valor_produto, 'image_url' => $image_url_produto, 'promocao' => $array, 'ativo' =>  $ativo];       
        }


        return $listarProdutos;
    }

    public function buscarProduto($produto_id, $softDelete)
    {
        $produtos = Produto::find($produto_id);

        if($softDelete == true)
            $produtos = Produto::withTrashed()->where('id', $produto_id)->get()[0];
        
        $produtoEncontrado = [];    

        foreach ($produtos as $produto) {

            $nome_produto = $produtos->produto;
            $valor_produto = $produtos->valor;
            $produto_id = $produtos->id;
            $image_url_produto = $produtos->imagem;

            $image_url_produto = asset("storage/" . $image_url_produto);

            if($produtos->imagem == false)
                $image_url_produto = false;

            $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'produto_id' => $produto_id, 'image_url' => $image_url_produto];
        }

        return $produtoEncontrado;
    }

    public function deletarImagem($produto_id)
    {
        $produto = Produto::find($produto_id);

        $produto->imagem = false;

        $produto->save();
    }
}
