<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Rules\HasPermission;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use \App\Services\PedidosServiceInterface;
use \App\Services\ClientesServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\EnderecoServiceInterface;
use \App\Services\PromotionsServiceInterface;

class Carrinho_controller extends Controller
{
    public function newProductCart(Request $request, $cliente_id, ProdutosServiceInterface $provider_produto, CarrinhoServiceInterface $provider_carrinho, PromotionsServiceInterface $provider_promotions)
    {
        $produtos = $request->input('produto');
        
   

        $validator = Validator::make($request->all(), [
            'produto.*' => 'bail|required|numeric',
            'categoria' => 'required|string',
        ]);

        if($validator->fails())
            return redirect()->to('Cliente/' . $cliente_id)->withErrors($validator);
            
        $validated = $validator->validated();

        foreach ($validated['produto'] as $key => $value) {

            $produto_id = $key;
            $quantidade = $value;
            
            if($quantidade != "0")
                $provider_carrinho->adicionarProduto($cliente_id, $produto_id, $quantidade,  $provider_produto, $provider_carrinho, $provider_promotions);
        }


        return redirect()->to('Cliente/' . $cliente_id);
    }

    public function updateCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ProdutosServiceInterface $provider_produto, PromotionsServiceInterface $provider_promotions)
    {
        $array = $request->input('atualizar');
      
        $validator = Validator::make($request->all(), [
            'atualizar.*'  => 'required|numeric',
        ]);



        $url = url()->previous();

        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);

        $validated = $validator->validated();



        foreach ($validated['atualizar'] as $key => $value) {

            $pedido_id = $key;
            $quantidade = $value;
            
            $provider_carrinho->atualizar($pedido_id, $cliente_id, $quantidade, $provider_produto, $provider_carrinho, $provider_promotions);
        }

        return redirect($url);
    }

    public function updateDiscountCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ProdutosServiceInterface $provider_produto, PromotionsServiceInterface $provider_promotions)
    {
        $porcentagem = $request->input('porcentagem');
    
        $validator = Validator::make($request->all(), [
            'porcentagem'  => 'required|numeric',
        ]);

        $url = url()->previous();

        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);

        $validated = $validator->validated();

        $provider_carrinho->atualizarPorcentagem($cliente_id, $porcentagem);

        return redirect($url);
    }

    public function showCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ClientesServiceInterface $provider_cliente, ProdutosServiceInterface $provider_produto, EnderecoServiceInterface $provider_endereco, PromotionsServiceInterface $provider_promotions)
    {
        $pedidosNaSession = $provider_carrinho->visualizar($cliente_id, $provider_produto, $provider_promotions);
        $visualizarCliente = $provider_cliente->listarClientes();
        $buscar = $provider_carrinho->calcularDesconto($cliente_id, $provider_produto, $provider_carrinho, $provider_promotions);
        $enderecos = $provider_endereco->listarEnderecos();

        $porcentagem = $buscar['porcentagem'];
        $totalSemDesconto = $buscar['totalSemDesconto'];
        $totalComDesconto = $buscar['totalComDesconto'];
        
        return view('carrinho', ['pedidosSession' => $pedidosNaSession, 'id' => $cliente_id, 'visualizarCliente' => $visualizarCliente, 'totalComDesconto' =>  $totalComDesconto, 'enderecos' => $enderecos, 'totalSemDesconto' => $totalSemDesconto, 'porcentagem' => $porcentagem]);
    }

    public function finishCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ProdutosServiceInterface $provider_produto, PedidosServiceInterface $provider_pedido, PromotionsServiceInterface $provider_promotions)
    {
        $endereco_id = $request->input('endereco_id');

        $provider_carrinho->finalizarCarrinho($cliente_id, $endereco_id, $provider_carrinho, $provider_produto,  $provider_pedido, $provider_promotions);

        return redirect('Cliente/' . $cliente_id);
    }
    
    public function deleteCart(Request $request, $cliente_id, $pedido_id, CarrinhoServiceInterface $provider_carrinho)
    {
        $provider_carrinho->excluirProduto($cliente_id, $pedido_id);

        return redirect('carrinho/' . $cliente_id);
    }
}
