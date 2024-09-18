<?php

namespace App\Services;

use App\Services\ProdutosServiceInterface;
use Illuminate\Support\Facades\Auth;


class SessionProdutosService implements ProdutosServiceInterface
{
	public function adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade, $provider_entradas_saidas)
	{
        $estoque = session()->get('Produtos', []);

        $estoque[] = ['produto' => $nome, 'categoria' => $categoria, 'valor'=> (int)$valor, 'quantidade' => (int)$quantidade,  'imagem' => $imagem, 'deleted_at' => null];

        session()->put('Produtos', $estoque);

        $produto_id = array_key_last($estoque);

        $provider_entradas_saidas->adicionarEntrada($produto_id, $quantidade, 'Primeira entrada no sistema', null, null, null, null);
	}
    
    public function editarProduto($produto_id, $nome, $valor, $imagem)
    {
        $produtos = session()->get('Produtos', []);


        foreach ($produtos as $key => $value) {
            if($produto_id == $key)
            {
                $produtos[$key]['produto'] = $nome;
                $produtos[$key]['valor'] = $valor;

                if(isset($imagem))
                    $produtos[$key]['imagem'] = $imagem;
            }
        }

        session()->put('Produtos', $produtos);
    }

    public function excluirProduto($produto_id)
    {
        if(session()->has('Produtos'))
        {
            $produtos = session()->get('Produtos');

            foreach ($produtos as $key => $value) {
                    if($key == $produto_id)
                        $produtos[$key]['deleted_at'] = date("Y-m-d H:i:s");
                    
            }

            session()->put('Produtos', $produtos);
        }
    }

    public function listarProduto($provider_promocoes, $provider_estoque, $softDelete)
    {
        $produtos = session()->get('Produtos', []);
        $listarProdutos = [];

        foreach ($produtos as $key => $value) 
        {
            if($softDelete == $value['deleted_at'])
            {
                $produto_id = $key;
                $nome_produto = $value['produto'];
                $valor_produto = $value['valor'];
                $image_url_produto = $value['imagem'];
                $quantidade_estoque = $provider_estoque->buscarEstoque($produto_id);;
                $promocao = $provider_promocoes->buscarPromocao($produto_id);
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
        $produtos = session()->get('Produtos', []);

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
        $produto = session()->get('Produtos', []);

        foreach ($produto as $key => $value) {
            if($produto_id == $key)
                $produto[$produto_id]['imagem'] = false; 
        }

        session()->put('Produtos', $produto);
    }
}
