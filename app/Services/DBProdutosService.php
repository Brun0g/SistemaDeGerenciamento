<?php

namespace App\Services;


use App\Models\Produto;
use App\Models\Entrada;

use \App\Services\DBPedidosService;
use \App\Services\DBClientesService;
use Illuminate\Support\Facades\Auth;

use App\Services\ProdutosServiceInterface;
use Illuminate\Support\Facades\Storage;

class DBProdutosService implements ProdutosServiceInterface
{
	public function adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade, $provider_entradas)
	{
        $produto = new Produto();

        $produto->produto = $nome;
        $produto->categoria_id = $categoria;
        $produto->valor = $valor;
        $produto->imagem = $imagem;
        $produto->quantidade = $quantidade;
      
        $produto->save();

        $provider_entradas->adicionarEntrada($produto->id, $quantidade);
	}
    
    public function editarProduto($produto_id, $nome, $valor, $imagem, $quantidade, $provider_entradas, $provider_saida)
    {
        $produto = Produto::find($produto_id);

        $entrada_estoque  = $quantidade - $produto->quantidade;
        $valor_anterior = $produto->quantidade;

        $produto->produto = $nome;
        $produto->valor = $valor;
        $produto->quantidade = $quantidade;

        if(isset($imagem))
            $produto->imagem = $imagem;
   
        $produto->save();

        if($quantidade != $valor_anterior)
            $provider_entradas->adicionarEntrada($produto->id, $entrada_estoque);
    }

    public function excluirProduto($produto_id)
    {
        $produto = Produto::find($produto_id);

        $produto->delete($produto_id);
    }
    
    public function listarProduto($provider_promotions, $softDelete)
    {
        $produtos = Produto::all();
        
        if($softDelete)
            $produtos = Produto::withTrashed()->get();

        $listarProdutos = [];

        foreach ($produtos as $produto) 
        {
            $nome_produto = $produto->produto;
            $valor_produto = $produto->valor;
            $image_url_produto = $produto->imagem;
            $produto_id = $produto->id;
            $quantidade = $produto->quantidade;

            $promocao = $provider_promotions->buscarPromocao($produto_id);
            $ativo = $promocao['ativo'];
            $array[$produto_id] = $promocao['promocao'];

            if($image_url_produto != false)
                $image_url_produto = asset("storage/" . $image_url_produto);

            $listarProdutos[$produto->id] = ['produto' => $nome_produto, 'valor' => $valor_produto, 'quantidade' => $quantidade, 'image_url' => $image_url_produto, 'promocao' => $array, 'ativo' =>  $ativo];       
        }

        return $listarProdutos;
    }

    public function buscarProduto($produto_id, $provider_entradas, $provider_saida)
    {
        $produtos = Produto::withTrashed()->where('id', $produto_id)->get()[0];

        foreach ($produtos as $produto) 
        {
            $nome_produto = $produtos->produto;
            $valor_produto = $produtos->valor;
            $produto_id = $produtos->id;
            $image_url_produto = $produtos->imagem;
            $deleted_at = $produtos->deleted_at;
            $quantidade = $produtos->quantidade;

            $image_url_produto = asset("storage/" . $image_url_produto);
            $entradas = $provider_entradas->buscarEntrada($produto_id);
            $saidas = $provider_saida->buscarSaida($produto_id);

            if($produtos->imagem == false)
                $image_url_produto = false;

            $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'quantidade' => $quantidade, 'entradas' => $entradas, 'saidas' => $saidas, 'produto_id' => $produto_id, 'image_url' => $image_url_produto, 'deleted_at' => $deleted_at];
        }
    

        return $produtoEncontrado;
    }

    public function deletarImagem($produto_id)
    {
        $produto = Produto::find($produto_id);

        $produto->imagem = false;

        $produto->save();
    }

    public function atualizarEstoque($produto_id, $quantidade)
    {
        $produto = Produto::find($produto_id);

        $produto->quantidade = $quantidade; 
      
        $produto->save();
    }
}
