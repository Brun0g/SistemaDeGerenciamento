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
    public function index(request $request, $produto_id, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida, ProdutosServiceInterface $provider_produto, PromotionsServiceInterface $provider_promotions, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos, CarrinhoServiceInterface $provider_carrinho)
    {
       $entradas = $provider_entradas->listarEntrada($provider_user);
       $saidas = $provider_saida->listarSaida($provider_user);
       $produtos = $provider_produto->buscarProduto($produto_id);

        $quantidade_carrinho = $provider_carrinho->buscarQuantidade($produto_id)['quantidade'];

        

       return view('entradas_saida', ['entradas' => $entradas, 'saidas' => $saidas, 'EstoqueProdutos' => $produtos, 'produto_id' => $produto_id, 'carrinho' => $quantidade_carrinho]);
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
    public function update(Request $request, $produto_id, EntradasServiceInterface $provider_entradas, ProdutosServiceInterface $provider_produto, SaidaServiceInterface $provider_saida, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos, CarrinhoServiceInterface $provider_carrinho, PromotionsServiceInterface $provider_promotions)
    {
        $entrada_ou_saida =  $request->input('escolha');
        $observacao = $request->input('observacao');
        $quantidade =  $request->input('quantidade');

        $validator = Validator::make($request->all(), [
            'quantidade'  => 'required|numeric',
            'escolha'  => 'required|string',
            'observacao'  => 'nullable|string',
        ]);

        $url = url()->previous();

        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);

        $produto = $provider_produto->buscarProduto($produto_id);

        // $quantidade_carrinho = $provider_carrinho->buscarQuantidade($produto_id)['quantidade'];



        if($entrada_ou_saida == 'entrada')
        {
            session()->flash('status', 'Entrada adicionada com sucesso!');

            $pedidos = $provider_carrinho->listarPedidosCarrinho();

            if(isset($pedidos))
            {
                foreach ($pedidos as $key => $value) {

                    $pedido_id = $key;
                    $cliente_id = $value['cliente_id'];
                    $quantidade_carrinho = $value['quantidade'];

                    if($quantidade_carrinho == 0)
                        $provider_carrinho->atualizar($pedido_id, $cliente_id, 1, $provider_produto, $provider_carrinho, $provider_promotions, $provider_entradas, $provider_saida, $provider_user, $provider_pedidos);
                }
            }

        } else {

            // if($quantidade_carrinho >= $produto['quantidade_estoque'])
            // {
            //         session()->flash('error', 'Operação cancelada, há produtos no carrinho!');
            //         return redirect($url);
            // }
            
            if($produto['quantidade_estoque'] - $quantidade < 0)
            {
                    session()->flash('error', 'Não é possível adicionar valor negativo ao produto!');
                    return redirect($url);
            }

            session()->flash('status', 'Saída adicionada com sucesso!');
            $quantidade = -(int)$quantidade;
        }
   

        $provider_produto->atualizarEstoque($produto_id, $quantidade, $entrada_ou_saida, $observacao, $provider_entradas, $provider_saida, null);

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
