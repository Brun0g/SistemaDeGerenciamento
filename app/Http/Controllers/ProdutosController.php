<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use \App\Services\ProdutosServiceInterface;
use \App\Services\CategoriaServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\PromocoesServiceInterface;
use \App\Services\EntradasServiceInterface;

use \App\Services\UserServiceInterface;
use \App\Services\PedidosServiceInterface;
use \App\Services\EstoqueServiceInterface;

use Illuminate\Support\Facades\Storage;


class ProdutosController extends Controller
{
    public function index(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, PromocoesServiceInterface $provider_promocoes, EstoqueServiceInterface $provider_estoque)
    {
        $Produtos = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, false);
        $listarCategorias = $provider_categoria->listarCategoria();

        return view('/produtos', ['categorias' => $listarCategorias,'Produtos' => $Produtos]);
    }

    public function softDeletesView(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, PromocoesServiceInterface $provider_promocoes, EstoqueServiceInterface $provider_estoque)
    {
        $Produtos = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, true);
        
        $listarCategorias = $provider_categoria->listarCategoria();

        return view('/produtos_excluidos', ['categorias' => $listarCategorias,'Produtos' => $Produtos]);
    }

    public function index_multiple(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, PromocoesServiceInterface $provider_promocoes, EstoqueServiceInterface $provider_estoque)
    {
        $listarCategorias = $provider_categoria->listarCategoria();
        $Produtos = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, false);

        return view('multiplas_entradas', ['categorias' => $listarCategorias, 'listarMultiplos' => $Produtos]);
    }

    public function index_adjustment(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, PromocoesServiceInterface $provider_promocoes, EstoqueServiceInterface $provider_estoque)
    {
        $listarCategorias = $provider_categoria->listarCategoria();
        $Produtos = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, false);

        return view('ajustar_estoque', ['categorias' => $listarCategorias, 'listarMultiplos' => $Produtos]);
    }

    public function store(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, EntradasServiceInterface $provider_entradas_saidas)
    {
        $listarCategorias = $provider_categoria->listarCategoria();
        
        $nome = $request->input('produtoEstoque');
        $valor = $request->input('valorEstoque');
        $quantidade = $request->input('quantidade_estoque');
        $categoria = $request->input('categoria');

        $validator = Validator::make($request->all(), [
            'produtoEstoque' => 'required|string',
            'valorEstoque'  => 'required|numeric',
            'categoria'  => 'required|string',
            'quantidade_estoque'  => 'required|numeric|min:0',
            'imagem' => 'required|image|mimes:jpeg,jpg,png,gif,svg|dimensions:max_width=1920,max_height=1080',
        ]);

        if($request->hasFile('imagem'))
            $imagem = Storage::disk('public')->put('/images', $request->file('imagem'));
        
        $url = url()->previous();

        if($validator->fails())
            return redirect($url)->withErrors($validator);

        $provider_produto->adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade, $provider_entradas_saidas);

        return redirect('/produtos');
    }

    public function restored(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $provider_produto->restaurarProduto($produto_id);

        $url = url()->previous();

        return redirect($url);
    }

    public function showProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, EstoqueServiceInterface $provider_estoque)
    {
        $entradas_saidas = $provider_entradas_saidas->buscarEntradaSaidas($produto_id, $provider_user);
        $produtos = $provider_produto->buscarProduto($produto_id);
        $resultado = $provider_estoque->buscarEstoque($produto_id);
     
        return view('/historico_produto', ['resultado' => $resultado, 'produtos' => $produtos, 'produto_id' => $produto_id, 'entradas_saidas' => $entradas_saidas]);
    }

    public function deleteProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $provider_produto->excluirProduto($produto_id);
        $url = url()->previous();

        return redirect($url);
    }

    public function deleteImage(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $provider_produto->deletarImagem($produto_id);
        $url = url()->previous();

        return redirect($url);
    }

    public function editProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
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
        $Produtos = $provider_produto->buscarProduto($produto_id);
          
        return view('editFilterProducts', ['Produtos' => $Produtos, 'produto_id'=> $produto_id]);
    }
}
