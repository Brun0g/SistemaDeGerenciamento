<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Illuminate\Http\Request;

use \App\Services\PedidosServiceInterface;
use \App\Services\ClientesServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\EnderecoServiceInterface;
use \App\Services\PromotionsServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\SaidaServiceInterface;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida, ProdutosServiceInterface $provider_produto, PromotionsServiceInterface $provider_promotions)
    {
       
       $entradas = $provider_entradas->listarEntrada();
       $saidas = $provider_saida->listarSaida();

       $produtos = $provider_produto->listarProduto($provider_promotions, false);



       return view('entradas_saida', ['entradas' => $entradas, 'saidas' => $saidas, 'produtos' => $produtos]);
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
    public function update(Request $request, Entrada $entrada)
    {
        //
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
