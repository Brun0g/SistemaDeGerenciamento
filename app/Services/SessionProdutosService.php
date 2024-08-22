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

        $observacao = 'Primeira entrada no sistema';
        

        $provider_entradas->adicionarEntrada($produto_id, $quantidade, $observacao);
	}
    
    public function editarProduto($produto_id, $nome, $valor, $imagem, $quantidade, $entrada_ou_saida, $observacao, $provider_entradas, $provider_saida)
    {
        $estoque = [];
        

        if(session()->has('EstoqueProdutos'))
        {
            $estoque = session()->get('EstoqueProdutos'); 

            foreach ($estoque as $key => $value) {
                if($produto_id == $key)
                {
                    $entrada_estoque  = $quantidade - $value['quantidade'];  

                    $estoque[$produto_id]['produto'] = $nome;
                    $estoque[$produto_id]['valor'] = $valor;

                    if($quantidade != $value['quantidade'])
                    {
                        $estoque[$produto_id]['quantidade'] = $quantidade;
                        $quantidade = $entrada_estoque;
                        $provider_entradas->adicionarEntrada($produto_id, $quantidade, $observacao);
                    }  
                }
            }
            
            if(isset($imagem))     
                $estoque[$produto_id]['imagem'] = $imagem;      
        }



        session()->put('EstoqueProdutos', $estoque);

        
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

    public function buscarProduto($produto_id, $provider_entradas, $provider_saida, $provider_user, $provider_pedidos)
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
                $entradas = $provider_entradas->buscarEntrada($produto_id, $provider_user);
                $saidas = $provider_saida->buscarSaida($produto_id, $provider_user, $provider_entradas, $provider_saida);


                $novo_array = array_merge($entradas, $saidas);
                $sort = array_column($novo_array, 'data');

                array_multisort($sort, SORT_ASC, $novo_array);

                if($value['imagem'] == false)
                    $image_url_produto = false;

                $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'quantidade' => $estoque, 'entradas_saidas' => $novo_array, 'produto_id' => $produto_id, 'image_url' => $image_url_produto, 'deleted_at' => $delete];
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
                $produto[$key]['quantidade'] = $quantidade; 
        }

        session()->put('EstoqueProdutos', $produto);
    }
}
