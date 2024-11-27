<?php

namespace App\Services;


use App\Models\Produto;
use App\Models\Entrada;

use \App\Services\DBPedidosService;
use \App\Services\DBClientesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Services\ProdutosServiceInterface;
use Illuminate\Support\Facades\Storage;

class DBProdutosService implements ProdutosServiceInterface
{
	public function adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade, $provider_entradas_saidas)
	{
        $produto = new Produto();

        $produto->create_by = Auth::id();
        $produto->delete_by = null;
        $produto->restored_by = null;
        $produto->update_by = null;
        $produto->produto = $nome;
        $produto->categoria_id = $categoria;
        $produto->valor = $valor;
        $produto->imagem = $imagem;
        $produto->restored_at = null;
      
        $produto->save();

        $provider_entradas_saidas->adicionarEntrada($produto->id, $quantidade, 'Primeira entrada no sistema', null, null, null, null);
	}
    
    public function editarProduto($produto_id, $nome, $valor, $imagem)
    {
        $produto = Produto::find($produto_id);

        $produto->produto = $nome;
        $produto->valor = $valor;
        $produto->update_by = Auth::id();
        
        if(isset($imagem))
            $produto->imagem = $imagem;
   
        $produto->save();
    }

    public function excluirProduto($produto_id)
    {
        $produto = Produto::find($produto_id);

        $produto->delete_by = Auth::id();
        $produto->save();

        $produto->delete($produto_id);
    }

    public function restaurarProduto($produto_id)
    {
        $produto = Produto::withTrashed()->where('id', $produto_id)->get()[0];
        $produto->restored_by = Auth::id();
        $produto->restored_at = Carbon::now();
        $produto->restore();
        $produto->save();
    }
    
    public function listarProduto($provider_promocoes, $provider_estoque, $softDelete)
    {
        $produtos = Produto::all();
        
        if($softDelete)
            $produtos = Produto::withTrashed()->get();

        $service_user= new DBUserService();
        
        $service_carrinho = new SessionCarrinhoService();
        $listarProdutos = [];

        foreach ($produtos as $produto) 
        {
            $nome_produto = $produto->produto;
            $valor_produto = $produto->valor;
            $image_url_produto = $produto->imagem;
            $produto_id = $produto->id;
            $quantidade_estoque = $provider_estoque->buscarEstoque($produto_id);
            $promocao = $provider_promocoes->buscarPromocao($produto_id);

            $create_by = $produto->create_by;
            $created_at = $produto->created_at;
            $nome_usuario = $service_user->buscarNome($create_by);

            $updated_at = $produto->updated_at;
            $update_by = $produto->update_by;
            $nome_usuario_update = $service_user->buscarNome($update_by);

            $deleted_at = $produto->deleted_at;
            $delete_by = $produto->delete_by;
            $nome_usuario_delete = $service_user->buscarNome($delete_by);

            $restored_at = $produto->restored_at;
            $restored_by = $produto->restored_by;
            $nome_usuario_restored = $service_user->buscarNome($restored_by);
            
            $ativo = $promocao['ativo'];
            $array[$produto_id] = $promocao['promocao'];

            
            $quantidade_carrinho = $service_carrinho->buscarQuantidade($produto_id)['quantidade'];
            $quantidade = $quantidade_estoque - $quantidade_carrinho;

            if($image_url_produto != false)
                $image_url_produto = asset("storage/" . $image_url_produto);

            $listarProdutos[$produto->id] = ['create_by' => $nome_usuario, 'created_at' => date_format($created_at,"d/m/Y H:i:s"), 'update_by' => $nome_usuario_update, 'updated_at' => date_format($updated_at,"d/m/Y H:i:s"), 'restored_by' => $nome_usuario_restored, 'produto' => $nome_produto, 'valor' => $valor_produto, 'quantidade' => $quantidade, 'image_url' => $image_url_produto, 'promocao' => $array, 'ativo' =>  $ativo, 'quantidade_estoque' => $quantidade_estoque, 'delete_by' => $nome_usuario_delete, 'deleted_at' => isset($deleted_at) ? date_format($deleted_at,"d/m/Y H:i:s") : null, $deleted_at, 'restored_at' => isset($restored_at) ? date_format($restored_at, "d/m/Y H:i:s") : null ];       
        }
               
        return $listarProdutos;
    }

    public function buscarProduto($produto_id)
    {
        $produtos = Produto::withTrashed()->where('id', $produto_id)->get()[0];

        $service_user= new DBUserService();

        foreach ($produtos as $produto) 
        {
            $nome_produto = $produtos->produto;
            $valor_produto = $produtos->valor;
            $produto_id = $produtos->id;
            $image_url_produto = $produtos->imagem;
            $deleted_at = $produtos->deleted_at;

            $create_by = $produtos->create_by;
            $created_at = $produtos->created_at;
            $nome_usuario_create = $service_user->buscarNome($create_by);

            $updated_at = $produtos->updated_at;
            $update_by = $produtos->update_by;
            $nome_usuario_update = $service_user->buscarNome($update_by);

            $deleted_at = $produtos->deleted_at;
            $delete_by = $produtos->delete_by;
            $nome_usuario_delete = $service_user->buscarNome($delete_by);

            $restored_at = $produtos->restored_at;
            $restored_by = $produtos->restored_by;
            $nome_usuario_restored = $service_user->buscarNome($restored_by);


            $image_url_produto = asset("storage/" . $image_url_produto);

            if($produtos->imagem == false)
                $image_url_produto = false;

            $produtoEncontrado = ['produto' => $nome_produto, 'valor' => $valor_produto, 'produto_id' => $produto_id, 'image_url' => $image_url_produto,

            'update_by' => $nome_usuario_update, 
            'updated_at' => isset($updated_at) ? date_format($updated_at,"d/m/Y H:i:s") : null,
            
            'create_by' => $nome_usuario_create,
            'created_at' => date_format($created_at,"d/m/Y H:i:s"),

            'delete_by' => $nome_usuario_delete, 
            'deleted_at' => isset($deleted_at) ? date_format($deleted_at,"d/m/Y H:i:s") : null,

            'restored_by' => $nome_usuario_restored,  
            'restored_at' => isset($restored_at) ? date_format($restored_at, "d/m/Y H:i:s") : null

            ];
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
