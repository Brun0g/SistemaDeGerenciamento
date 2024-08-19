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
use \App\Services\EntradasServiceInterface;
use \App\Services\SaidaServiceInterface;




class Carrinho_controller extends Controller
{
    public function newProductCart(Request $request, $cliente_id, ProdutosServiceInterface $provider_produto, CarrinhoServiceInterface $provider_carrinho, PromotionsServiceInterface $provider_promotions, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida)
    {
        $produtos = $request->input('produto');
    
        $validator = Validator::make($request->all(), [
            'produto.*' => 'bail|required|numeric',
            'categoria' => 'required|string',
        ]);


        $url = url()->previous();

        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);
            
        $validated = $validator->validated();

    
        foreach ($validated['produto'] as $key => $value) {

            $produto_id = $key;
            $quantidade = $value;
            
            if($quantidade != "0"){
                $mensagem = $provider_carrinho->adicionarProduto($cliente_id, $produto_id, $quantidade,  $provider_produto, $provider_carrinho, $provider_promotions, $provider_entradas, $provider_saida);

                if($mensagem['mensagem']){
                    
                    $produto = $provider_produto->buscarProduto($produto_id, $provider_entradas, $provider_saida)['produto'];
                    session()->flash('error_estoque', 'Quantidade do Produto: ' . $produto . ' ultrapassa valor do   Estoque: ' . $mensagem['quantidade'] . ' Quantidade: ' . $quantidade);
                }
                else{
                    session()->flash('status', 'Produto adicionado com sucesso!');
                }
            }
        }

        return redirect($url);
    }

    public function updateCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ProdutosServiceInterface $provider_produto, PromotionsServiceInterface $provider_promotions, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida)
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

            $pedido_no_carrinho = $provider_carrinho->buscarCarrinho($cliente_id, $pedido_id);

            $produto_id = $pedido_no_carrinho['produto_id'];
            $quantidade_carrinho = (int)$pedido_no_carrinho['quantidade'];

            $produto = $provider_produto->buscarProduto($produto_id, $provider_entradas, $provider_saida);
            $quantidade_estoque = $produto['quantidade'];
            $total_estoque = $quantidade_estoque + $quantidade_carrinho;
            
            if($quantidade <= $total_estoque)
            {
                session()->flash('status', 'Quantidade adicionada com sucesso!');

                $provider_carrinho->atualizar($pedido_id, $cliente_id, $quantidade, $provider_produto, $provider_carrinho, $provider_promotions, $provider_entradas, $provider_saida); 
            }
            else{

                session()->flash('error_estoque', 'Quantidade do Produto: ' . $produto['produto'] . ' ultrapassa valor do   Estoque: ' . $quantidade_estoque . ' Quantidade: ' . $quantidade);

                break;
            }
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

    public function showCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ClientesServiceInterface $provider_cliente, ProdutosServiceInterface $provider_produto, EnderecoServiceInterface $provider_endereco, PromotionsServiceInterface $provider_promotions, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida)
    {
        $pedidosNaSession = $provider_carrinho->visualizar($cliente_id, $provider_produto, $provider_promotions, $provider_carrinho, $provider_entradas, $provider_saida);

        $visualizarCliente = $provider_cliente->listarClientes();
        $buscar = $provider_carrinho->calcularDesconto($cliente_id, $provider_produto, $provider_carrinho, $provider_promotions);
        $enderecos = $provider_endereco->listarEnderecos();

        $porcentagem = $buscar['porcentagem'];
        $totalSemDesconto = $buscar['totalSemDesconto'];
        $totalComDesconto = $buscar['totalComDesconto'];
        
        return view('carrinho', ['pedidosSession' => $pedidosNaSession, 'id' => $cliente_id, 'visualizarCliente' => $visualizarCliente, 'totalComDesconto' =>  $totalComDesconto, 'enderecos' => $enderecos, 'totalSemDesconto' => $totalSemDesconto, 'porcentagem' => $porcentagem]);
    }

    public function finishCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ProdutosServiceInterface $provider_produto, PedidosServiceInterface $provider_pedido, PromotionsServiceInterface $provider_promotions, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida)
    {
        $endereco_id = $request->input('endereco_id');

        $provider_carrinho->finalizarCarrinho($cliente_id, $endereco_id, $provider_carrinho, $provider_produto,  $provider_pedido, $provider_promotions, $provider_entradas, $provider_saida);

        return redirect('Cliente/' . $cliente_id);
    }
    
    public function deleteCart(Request $request, $cliente_id, $produto_id, CarrinhoServiceInterface $provider_carrinho, ProdutosServiceInterface $provider_produto, EntradasServiceInterface $provider_entradas, SaidaServiceInterface $provider_saida)
    {

        $provider_carrinho->excluirProduto($cliente_id, $produto_id, true, $provider_produto, $provider_entradas, $provider_saida);

        return redirect('carrinho/' . $cliente_id);
    }
}
