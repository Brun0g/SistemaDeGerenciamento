<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use App\Models\Promotion;
use App\Models\Produto;

use \App\Services\ClientesServiceInterface;
use \App\Services\CategoriaServiceInterface;
use \App\Services\PedidosServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\EnderecoServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\PromotionsServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\SaidaServiceInterface;
use \App\Services\UserServiceInterface;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ProdutosServiceInterface $provider_produto, PromotionsServiceInterface $provider_promotions, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos)
    {
        $softDelete = false;
        
        $produtos = $provider_produto->listarProduto($provider_promotions, $softDelete);
        $promotionsList = $provider_promotions->listarPromocoes($provider_produto, $provider_entradas, $provider_saida, $provider_user, $provider_pedidos);

        return view('promotions', ['produtos' => $produtos, 'listaPromocoes' => $promotionsList]);
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
    public function store(Request $request, PromotionsServiceInterface $provider_promotions)
    {
        $produto_id = $request->input('produto_id');
        $quantidade = $request->input('quantidade');
        $porcentagem = $request->input('porcentagem');

        $validator = Validator::make($request->all(), [
            'produto_id' => 'required|integer', 
            'quantidade' => 'required|integer',
            'porcentagem' => 'required|integer',
        ]);

        // |unique:promotions

        if($validator->fails())
            return redirect('promotions')->withErrors($validator);


        $provider_promotions->adicionarPromocao($produto_id, $quantidade, $porcentagem);


        return redirect('promotions');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(promotion $promotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $promotion_id, PromotionsServiceInterface $provider_promotions)
    {
        $quantidade = $request->input('atualizarQuantidade');
        $porcentagem = $request->input('atualizarPorcentagem');

        $validator = Validator::make($request->all(), [
        'atualizarQuantidade' => 'required|integer',
        'atualizarPorcentagem' => 'required|integer',
        ]);

        if($validator->fails())
            return redirect('promotions')->withErrors($validator);

        $provider_promotions->editarPromocao($promotion_id, $quantidade, $porcentagem);

 
        return redirect('promotions');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $promotion_id, PromotionsServiceInterface $provider_promotions)
    {
        
        $situacao = $request->input('situacao');

        $validator = Validator::make($request->all(), [
        'situacao' => 'required|integer',
        ]);

        if($validator->fails())
            return redirect('promotions')->withErrors($validator);

        $provider_promotions->ativarPromocao($promotion_id, $situacao);

        $url = url()->previous();
        
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $promotion_id, PromotionsServiceInterface $provider_promotions)
    {
       $provider_promotions->deletarPromocao($promotion_id);

       return redirect('promotions');
    }
}
