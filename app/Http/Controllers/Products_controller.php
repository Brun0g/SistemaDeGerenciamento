<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use \App\Services\ProdutosServiceInterface;
use \App\Services\CategoriaServiceInterface;
use \App\Services\CarrinhoServiceInterface;

use Illuminate\Support\Facades\Storage;


class Products_controller extends Controller
{
 
    public function ProductsStorageView(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria)
    {
        $estoqueProdutos = $provider_produto->listarProduto();
        $listarCategorias = $provider_categoria->listarCategoria();

        return view('/Produtos', ['categorias' => $listarCategorias,'EstoqueProdutos' => $estoqueProdutos]);
    }

    public function newProduct(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria)
    {
        $listarCategorias = $provider_categoria->listarCategoria();

        $nome = $request->input('produtoEstoque');
        $valor = $request->input('valorEstoque');
        $categoria = $request->input('categoria');
        $imagem = $request->input('imagem');


        Storage::put($imagem, 'local', 'public');


    
    


        // $validator = Validator::make($request->all(), [
        //     'produtoEstoque' => 'required|string',
        //     'valorEstoque'  => 'required|numeric',
        //     'categoria'  => 'required|string',
        //     'imagem' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:5120',
        // ]);

     


        // $url = url()->previous();

        // if($validator->fails())
        //     return redirect($url)->withErrors($validator);


        foreach ($listarCategorias as $categoria_id => $value) {
                if($categoria == $value['categoria'])
                    $categoria = $categoria_id;
        }
        
        $provider_produto->adicionarProduto($nome, $categoria, $valor, $imagem);

        return redirect('/Produtos');
    }

    public function showProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $estoqueProdutos = $provider_produto->buscarProduto($produto_id);
        
        return view('/showFilterProducts', ['EstoqueProdutos' => $estoqueProdutos, 'produto_id' => $produto_id]);
    }

    public function deleteProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $provider_produto->excluirProduto($produto_id);

        return redirect('/Produtos');
    }
    public function editProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto, CarrinhoServiceInterface $provider_carrinho)
    {
        $nome = $request->input('produto');
        $valor =  $request->input('valor');
        
        $validator = Validator::make($request->all(), [
        'produto' => 'required|string',
        'valor'  => 'required|numeric',
        ]);

        if($validator->fails())
            return redirect()->to('EditarProduto/' . $produto_id)->withErrors($validator);

        $provider_produto->editarProduto($produto_id, $nome, $valor);

        return redirect('/Produtos');
    }

    public function viewFilterProducts(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $EstoqueProdutos = $provider_produto->buscarProduto($produto_id);
          
        return view('editFilterProducts', ['EstoqueProdutos' => $EstoqueProdutos, 'produto_id'=> $produto_id]);
    }
}
