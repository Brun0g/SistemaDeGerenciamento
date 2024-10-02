<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use \App\Services\ClientesServiceInterface;
use \App\Services\CategoriaServiceInterface;
use \App\Services\PedidosServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\EnderecoServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\PromocoesServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\EstoqueServiceInterface;

use \App\Services\UserServiceInterface;

class PromocoesController extends Controller
{
    public function index(Request $request, ProdutosServiceInterface $provider_produto, PromocoesServiceInterface $provider_promocoes, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos, EstoqueServiceInterface $provider_estoque)
    {
        $produtos = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, false);
        $promocoesList = $provider_promocoes->listarPromocoes(true, $provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos);

        return view('promocoes', ['produtos' => $produtos, 'listaPromocoes' => $promocoesList]);
    }

    public function detail(Request $request, ProdutosServiceInterface $provider_produto, PromocoesServiceInterface $provider_promocoes, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos, EstoqueServiceInterface $provider_estoque)
    {
        
        $produtos = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, false);
        $promocoesList = $provider_promocoes->listarPromocoes(true, $provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos);

        return view('promocoes_excluidas', ['produtos' => $produtos, 'listaPromocoes' => $promocoesList]);
    }

    public function store(Request $request, PromocoesServiceInterface $provider_promocoes)
    {
        $produto_id = $request->input('produto_id');
        $quantidade = $request->input('quantidade');
        $porcentagem = $request->input('porcentagem');

        $validator = Validator::make($request->all(), [
            'produto_id' => 'required|integer', 
            'quantidade' => 'required|integer',
            'porcentagem' => 'required|integer',
        ]);


        if($validator->fails())
            return redirect('promocoes')->withErrors($validator);

        $provider_promocoes->adicionarPromocao($produto_id, $quantidade, $porcentagem);


        return redirect('promocoes');
    }

    public function edit(Request $request, $promocoes_id, PromocoesServiceInterface $provider_promocoes)
    {
        $quantidade = $request->input('atualizarQuantidade');
        $porcentagem = $request->input('atualizarPorcentagem');

        $validator = Validator::make($request->all(), [
        'atualizarQuantidade' => 'required|integer',
        'atualizarPorcentagem' => 'required|integer',
        ]);

        if($validator->fails())
            return redirect('promocoes')->withErrors($validator);

        $provider_promocoes->editarPromocao($promocoes_id, $quantidade, $porcentagem);

        return redirect('promocoes');
    }

    public function update(Request $request, $promocoes_id, PromocoesServiceInterface $provider_promocoes)
    {
        
        $situacao = $request->input('situacao');

        $validator = Validator::make($request->all(), [
        'situacao' => 'required|integer',
        ]);

        if($validator->fails())
            return redirect('promocoes')->withErrors($validator);

        if($situacao == 0)
        $provider_promocoes->desativarPromocao($promocoes_id, $situacao);
        else
        $provider_promocoes->ativarPromocao($promocoes_id, $situacao);

        $url = url()->previous();
        
        return redirect($url);
    }

    public function destroy(Request $request, $promocoes_id, PromocoesServiceInterface $provider_promocoes)
    {
       $provider_promocoes->deletarPromocao($promocoes_id);

       return redirect('promocoes');
    }

    public function restored(Request $request, $promocoes_id, PromocoesServiceInterface $provider_promocoes)
    {
       $provider_promocoes->restaurarPromocao($promocoes_id);

       $url = url()->previous();

       return redirect($url);
    }
}
