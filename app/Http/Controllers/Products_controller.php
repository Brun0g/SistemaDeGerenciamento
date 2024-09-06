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
use \App\Services\EntradasServiceInterface;
use \App\Services\SaidaServiceInterface;
use \App\Services\UserServiceInterface;
use \App\Services\PedidosServiceInterface;
use \App\Services\RegistroMultiplosServiceInterface;

use Illuminate\Support\Facades\Storage;


class Products_controller extends Controller
{
    public function ProductsStorageView(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, PromotionsServiceInterface $provider_promotions)
    {
        $softDelete = false;
        
        $estoqueProdutos = $provider_produto->listarProduto($provider_promotions, $softDelete);
        
        $listarCategorias = $provider_categoria->listarCategoria();

        return view('/Produtos', ['categorias' => $listarCategorias,'EstoqueProdutos' => $estoqueProdutos]);
    }

    public function newProduct(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, EntradasServiceInterface $provider_entradas)
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

        $provider_produto->adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade, $provider_entradas);

        return redirect('/Produtos');
    }

    public function showProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos, CarrinhoServiceInterface $provider_carrinho)
    {
        $estoqueProdutos = $provider_produto->buscarProduto($produto_id);


        $entradas = $provider_entradas->buscarEntrada($produto_id, $provider_user);
        $saidas = $provider_saida->buscarSaida($produto_id, $provider_user, $provider_entradas, $provider_saida);
        $entradas_saidas = array_merge($entradas['entradas_array'], $saidas['saidas_array']);
        $sort = array_column($entradas_saidas, 'data');

        array_multisort($sort, SORT_ASC, $entradas_saidas);

        $quantidade_carrinho = $provider_carrinho->buscarQuantidade($produto_id)['quantidade'];

 

        $resultado = $entradas['total'] - $saidas['total'];




        return view('/showFilterProducts', ['resultado' => $resultado, 'EstoqueProdutos' => $estoqueProdutos, 'produto_id' => $produto_id, 'entradas_saidas' => $entradas_saidas, 'resultado' => $resultado]);
    }

    public function deleteProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $provider_produto->excluirProduto($produto_id);

        return redirect('/Produtos');
    }
    public function editProduct(Request $request, $produto_id, ProdutosServiceInterface $provider_produto, CarrinhoServiceInterface $provider_carrinho, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida)
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

    public function viewFilterProducts(Request $request, $produto_id, ProdutosServiceInterface $provider_produto, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos)
    {
        $EstoqueProdutos = $provider_produto->buscarProduto($produto_id);
          
        return view('editFilterProducts', ['EstoqueProdutos' => $EstoqueProdutos, 'produto_id'=> $produto_id]);
    }

    public function deleteImage(Request $request, $produto_id, ProdutosServiceInterface $provider_produto)
    {
        $provider_produto->deletarImagem($produto_id);
        $url = url()->previous();

        return redirect($url);
    }

    public function multipleProductView(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, PromotionsServiceInterface $provider_promotions)
    {
        $listarCategorias = $provider_categoria->listarCategoria();
        $estoqueProdutos = $provider_produto->listarProduto($provider_promotions, false);

        return view('multiplosProdutos', ['categorias' => $listarCategorias, 'listarMultiplos' => $estoqueProdutos]);
    }

    public function newMultiple(Request $request, ProdutosServiceInterface $provider_produto, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida, RegistroMultiplosServiceInterface $provider_registro)
    {
        $quantidade = $request->input('quantidade');
        $observacao = $request->input('observacao');

        $validator = Validator::make($request->all(), [
            'quantidade.*' => 'bail|required|integer|min:0',
            'observacao' => 'nullable|string',
        ]);



        $url = url()->previous();

        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);
            
        $validated = $validator->validated();
        $registro_id = $provider_registro->adicionarRegistro();

       
        foreach ($validated['quantidade'] as $key => $value) {

            $produto_id = $key;
            $quantidade = $value;
   
            if($quantidade != "0")
            {
                $provider_produto->atualizarEstoque($produto_id, $quantidade, 'entrada', $observacao, $provider_entradas, $provider_saida, null, 'Múltipla entrada', $registro_id);

                session()->flash('status', 'Múltiplas entradas adicionada com sucesso!');       
            }
        }

        return redirect($url);
    }
    public function editMultipleProductView(Request $request, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, PromotionsServiceInterface $provider_promotions)
    {
        $listarCategorias = $provider_categoria->listarCategoria();
        $estoqueProdutos = $provider_produto->listarProduto($provider_promotions, false);

        return view('EditarMultiplosProdutos', ['categorias' => $listarCategorias, 'listarMultiplos' => $estoqueProdutos]);
    }

    public function EditMultiple(Request $request, ProdutosServiceInterface $provider_produto, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida, RegistroMultiplosServiceInterface $provider_registro)
    {
        $quantidade = $request->input('quantidade');
        $observacao = $request->input('observacao');

        $validator = Validator::make($request->all(), [
            'quantidade.*' => 'bail|required|integer|min:0',
            'observacao' => 'nullable|string',
        ]);


        $url = url()->previous();

        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);
            
        $validated = $validator->validated();

        $registro_id = $provider_registro->adicionarRegistro();


        foreach ($validated['quantidade'] as $key => $value) {

            $produto_id = $key;
            $quantidade = $value;
            $quantidade_estoque = $provider_produto->buscarProduto($produto_id)['quantidade_estoque'];

            if($quantidade_estoque != $quantidade)
            {
                if($quantidade_estoque < $quantidade)
                {
                    // $quantidade = $quantidade - $quantidade_estoque;

                    session()->flash('status', 'Ajuste de múltiplas entradas realizada com sucesso!');       

                    $provider_produto->atualizarEstoque($produto_id, $quantidade, 'entrada', $observacao, $provider_entradas, $provider_saida, $observacao, 'Ajuste-A', $registro_id);
                }
                else
                {
                    $quantidade = $quantidade_estoque - $quantidade;

                    session()->flash('status', 'Ajuste de múltiplas saidas realizada com sucesso!');    

                    $provider_produto->atualizarEstoque($produto_id, -$quantidade, 'saida', $observacao, $provider_entradas, $provider_saida, $observacao, 'Ajuste', $registro_id);
                }
            }
        }

        return redirect($url);
    }
}


