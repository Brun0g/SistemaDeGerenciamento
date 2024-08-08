<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use \App\Services\ProdutosServiceInterface;
use \App\Services\CategoriaServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\PromotionsServiceInterface;

use Illuminate\Support\Facades\Storage;


class Products_controller extends Controller
{
    public function ProductsStorageView(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, PromotionsServiceInterface $provider_promotions)
    {
        $softDelete = false;
        
        $estoqueProdutos = $provider_produto->listarProduto($provider_promotions, $provider_produto, $softDelete);
        
        $listarCategorias = $provider_categoria->listarCategoria();

        return view('/Produtos', ['categorias' => $listarCategorias,'EstoqueProdutos' => $estoqueProdutos]);
    }

    public function newProduct(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria)
    {
        $listarCategorias = $provider_categoria->listarCategoria();
        $nome = $request->input('produtoEstoque');
        $valor = $request->input('valorEstoque');
        $categoria = $request->input('categoria');
   
        $validator = Validator::make($request->all(), [
            'produtoEstoque' => 'required|string',
            'valorEstoque'  => 'required|numeric',
            'categoria'  => 'required|string',
            'imagem' => 'required|image|mimes:jpeg,jpg,png,gif,svg|dimensions:max_width=1920,max_height=1080',
        ]);

        if($request->hasFile('imagem'))
            $imagem = Storage::disk('public')->put('/images', $request->file('imagem'));
        
        $url = url()->previous();

        if($validator->fails())
            return redirect($url)->withErrors($validator);


        foreach ($listarCategorias as $categoria_id => $value) {
                if($categoria == $value['categoria'])
                    $categoria = $categoria_id;
        }
        
        $provider_produto->adicionarProduto($nome, $categoria, $valor, $imagem);

        return redirect('/Produtos');
    }

    public function showProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $softDelete = false;
        $estoqueProdutos = $provider_produto->buscarProduto($produto_id, $softDelete);
        
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
        $imagem =  $request->file('imagem');


        $validator = Validator::make($request->all(), [
            'produto' => 'required|string',
            'valor'  => 'required|numeric',
        ]);

        $url = url()->previous();

        if($request->hasFile('imagem')){
            
            $validator = Validator::make($request->all(), [
            'imagem' => 'required|image|mimes:jpeg,jpg,png,gif,svg|dimensions:max_width=1920,max_height=1080',
            ]);

            $imagem = Storage::disk('public')->put('/images', $request->file('imagem'));
        }


        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);

        $provider_produto->editarProduto($produto_id, $nome, $valor, $imagem);

        return redirect($url);
    }

    public function viewFilterProducts(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $softDelete = false;

        $EstoqueProdutos = $provider_produto->buscarProduto($produto_id, $softDelete);
          
        return view('editFilterProducts', ['EstoqueProdutos' => $EstoqueProdutos, 'produto_id'=> $produto_id]);
    }
    public function deleteImage(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        
        $provider_produto->deletarImagem($produto_id);

        $url = url()->previous();

    
        return redirect($url);
    }
}
