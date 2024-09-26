<?php

namespace App\Services;

use App\Services\ProdutosServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;



class SessionProdutosService implements ProdutosServiceInterface
{
	public function adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade, $provider_entradas_saidas)
	{
        $estoque = session()->get('Produtos', []);

        $estoque[] = ['create_by' => Auth::id(), 'created_at' => now(), 'restored_by' => null, 'update_by' => null, 'updated_at' => null, 'produto' => $nome, 'categoria' => $categoria, 'valor'=> (int)$valor, 'imagem' => $imagem, 'delete_by' => null, 'deleted_at' => null];

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
                $produtos[$key]['update_by'] = Auth::id();
                $produtos[$key]['updated_at'] = now();
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
                {
                    $produtos[$key]['delete_by'] = Auth::id();    
                    $produtos[$key]['deleted_at'] = now();
                }    
            }

            session()->put('Produtos', $produtos);
        }
    }

    public function restaurarProduto($produto_id)
    {
        $produtos = session()->get('Produtos', []);

        foreach ($produtos as $key => $value) {
            if($produto_id == $key)
            {
                $produtos[$key]['restored_by'] = Auth::id();
                $produtos[$key]['deleted_at'] = null;
            }
        }
        
        session()->put('Produtos', $produtos);
    }

    public function listarProduto($provider_promocoes, $provider_estoque, $softDelete)
    {
        $produtos = session()->get('Produtos', []);
        $listarProdutos = [];


        $service_user= new DBUserService();
        $service_carrinho = new SessionCarrinhoService();
    
        foreach ($produtos as $key => $value) 
        {
            if($softDelete == $value['deleted_at'])
            {
                $nome_produto = $value['produto'];
                $valor_produto = $value['valor'];
                $image_url_produto = $value['imagem'];
                $produto_id = $key;
                $quantidade_estoque = $provider_estoque->buscarEstoque($produto_id);
                $promocao = $provider_promocoes->buscarPromocao($produto_id);

                $create_by = $value['create_by'];
                $created_at = $value['created_at'];
                $nome_usuario = $service_user->buscarUsuario($create_by);

                $updated_at = $value['updated_at'];
                $update_by = $value['update_by'];
                $nome_usuario_update = $service_user->buscarUsuario($update_by);

                $deleted_at = $value['deleted_at'];
                $delete_by = $value['delete_by'];
                $nome_usuario_delete = $service_user->buscarUsuario($delete_by);
                
                $ativo = $promocao['ativo'];
                $array[$produto_id] = $promocao['promocao'];

                
                $quantidade_carrinho = $service_carrinho->buscarQuantidade($produto_id)['quantidade'];
                $quantidade = $quantidade_estoque - $quantidade_carrinho;

                if($image_url_produto != false)
                    $image_url_produto = asset("storage/" . $image_url_produto);

                $listarProdutos[$produto_id] = ['create_by' => $nome_usuario, 'created_at' => $created_at, 'update_by' => $nome_usuario_update, 'updated_at' => $updated_at, 'produto' => $nome_produto, 'valor' => $valor_produto, 'quantidade' => $quantidade, 'image_url' => $image_url_produto, 'promocao' => $array, 'ativo' =>  $ativo, 'quantidade_estoque' => $quantidade_estoque, 'delete_by' => $nome_usuario_delete, 'deleted_at' => $deleted_at];     
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

                $image_url_produto = asset("storage/" . $image_url_produto);
               
                if($value['imagem'] == false)
                    $image_url_produto = false;

                $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'produto_id' => $produto_id, 'image_url' => $image_url_produto, 'deleted_at' => $deleted_at];
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
