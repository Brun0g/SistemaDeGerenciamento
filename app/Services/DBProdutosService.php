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

        $provider_entradas->adicionarEntrada($produto->id, $quantidade, null, 'Primeira entrada no sistema', null, null);
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
            $quantidade_estoque = $produto->quantidade;

            $promocao = $provider_promotions->buscarPromocao($produto_id);
            $ativo = $promocao['ativo'];
            $array[$produto_id] = $promocao['promocao'];

            $service_carrinho = new SessionCarrinhoService();

            $quantidade_carrinho = $service_carrinho->buscarQuantidade($produto_id)['quantidade'];
            $quantidade = $quantidade_estoque - $quantidade_carrinho;

            if($image_url_produto != false)
                $image_url_produto = asset("storage/" . $image_url_produto);

            $listarProdutos[$produto->id] = ['produto' => $nome_produto, 'valor' => $valor_produto, 'quantidade' => $quantidade, 'image_url' => $image_url_produto, 'promocao' => $array, 'ativo' =>  $ativo, 'quantidade_estoque' => $quantidade_estoque];       
        }

        return $listarProdutos;
    }

    public function buscarProduto($produto_id)
    {
        $produtos = Produto::withTrashed()->where('id', $produto_id)->get()[0];

        foreach ($produtos as $produto) 
        {
            $nome_produto = $produtos->produto;
            $valor_produto = $produtos->valor;
            $produto_id = $produtos->id;
            $image_url_produto = $produtos->imagem;
            $deleted_at = $produtos->deleted_at;
            $quantidade_estoque = $produtos->quantidade;

            $image_url_produto = asset("storage/" . $image_url_produto);

            if($produtos->imagem == false)
                $image_url_produto = false;

            $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'produto_id' => $produto_id, 'image_url' => $image_url_produto, 'deleted_at' => $deleted_at, 'quantidade_estoque' => $quantidade_estoque];
        }
    

        return $produtoEncontrado;
    }

    public function deletarImagem($produto_id)
    {
        $produto = Produto::find($produto_id);

        $produto->imagem = false;

        $produto->save();
    }

    public function atualizarEstoque($produto_id, $quantidade, $entrada_ou_saida, $observacao, $provider_entradas, $provider_saida, $pedido_id, $tipo, $ajuste_id, $multiplo_id)
    {
        $produto = Produto::find($produto_id);

        if(isset($entrada_ou_saida))
        {
            $quantidade_anterior = $produto->quantidade;
            $produto->quantidade += $quantidade;

            // if($tipo == 'Ajuste entrada' || $tipo == 'Ajuste saida')
            //     $produto->quantidade = $quantidade;
            
            if($entrada_ou_saida == 'entrada')
                $provider_entradas->adicionarEntrada($produto_id, $quantidade, $observacao, $tipo, $ajuste_id, $multiplo_id);
            else
                $provider_saida->adicionarSaida($produto_id, $pedido_id, $quantidade, $observacao, $tipo, $ajuste_id);

        } else 
            $produto->quantidade = $quantidade;

        $produto->save();
    }
}
