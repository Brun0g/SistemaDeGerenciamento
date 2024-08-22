<?php

namespace App\Http\Controllers;

use App\Models\Entrada;


use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use \App\Services\PedidosServiceInterface;
use \App\Services\ClientesServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\EnderecoServiceInterface;
use \App\Services\PromotionsServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\SaidaServiceInterface;
use \App\Services\UserServiceInterface;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request, $produto_id, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida, ProdutosServiceInterface $provider_produto, PromotionsServiceInterface $provider_promotions, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos)
    {
      

       $entradas = $provider_entradas->listarEntrada($provider_user);
       $saidas = $provider_saida->listarSaida($provider_user);
       $produtos = $provider_produto->buscarProduto($produto_id, $provider_entradas, $provider_saida, $provider_user, $provider_pedidos);

    

       return view('entradas_saida', ['entradas' => $entradas, 'saidas' => $saidas, 'EstoqueProdutos' => $produtos, 'produto_id' => $produto_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function show(Entrada $entrada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function edit(Entrada $entrada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $produto_id, EntradasServiceInterface $provider_entradas, ProdutosServiceInterface $provider_produto, SaidaServiceInterface $provider_saida)
    {
        $nome = $request->input('produto');
        $valor =  $request->input('valor');
        $imagem =  $request->file('imagem');
        $quantidade =  $request->input('quantidade');


        $validator = Validator::make($request->all(), [
            'produto' => 'required|string',
            'valor'  => 'required|numeric',
            'quantidade'  => 'required|numeric',
        ]);

        $url = url()->previous();

        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);

        $provider_produto->editarProduto($produto_id, $nome, $valor, $imagem, $quantidade, $provider_entradas, $provider_saida);

        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entrada $entrada)
    {
        //
    }
}
