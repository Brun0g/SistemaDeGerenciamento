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
use \App\Services\PromocoesServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\UserServiceInterface;
use \App\Services\EstoqueServiceInterface;




class Carrinho_controller extends Controller
{
    public function newProductCart(Request $request, $cliente_id, ProdutosServiceInterface $provider_produto, CarrinhoServiceInterface $provider_carrinho, PromocoesServiceInterface $provider_promocoes)
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
            
            if($quantidade != "0")
            {
                $retornar_erro = $provider_carrinho->adicionarProduto($cliente_id, $produto_id, $quantidade,  $provider_produto, $provider_carrinho, $provider_promocoes);

                if($retornar_erro['error']){
                    $produto = $provider_produto->buscarProduto($produto_id)['produto'];
                    session()->flash('error_estoque', 'Quantidade do Produto: ' . strtoupper($produto) . ' ultrapassa valor do   Estoque: ' . $retornar_erro['quantidade'] . ' Quantidade: ' . $quantidade);
                }
                else
                    session()->flash('status', 'Produto adicionado com sucesso!');       
            }
        }

        return redirect($url);
    }

    public function updateCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ProdutosServiceInterface $provider_produto, PromocoesServiceInterface $provider_promocoes)
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

            $produto = $provider_produto->buscarProduto($produto_id);
            $quantidade_estoque = $produto['quantidade_estoque'];

       
            if($quantidade <= $quantidade_estoque && $quantidade != $quantidade_carrinho)
            {
                session()->flash('status', 'Quantidade alterada com sucesso! Produto: ' . strtoupper($produto['produto']));

                $provider_carrinho->atualizar($pedido_id, $cliente_id, $quantidade, $provider_produto, $provider_carrinho, $provider_promocoes);
            }
            
            if($quantidade > $quantidade_estoque && $quantidade != $quantidade_carrinho)
                session()->flash('error_estoque', 'Quantidade do Produto: ' . strtoupper($produto['produto']) . ' ultrapassa valor do   Estoque: ' . $quantidade_estoque . ' Quantidade: ' . $quantidade);
        }

        return redirect($url);
    }

    public function updateDiscountCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho)
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

    public function showCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ClientesServiceInterface $provider_cliente, ProdutosServiceInterface $provider_produto, EnderecoServiceInterface $provider_endereco, PromocoesServiceInterface $provider_promocoes)
    {
        $pedidosNaSession = $provider_carrinho->visualizar($cliente_id, $provider_produto, $provider_promocoes, $provider_carrinho);

        $visualizarCliente = $provider_cliente->listarClientes();
        $buscar = $provider_carrinho->calcularDesconto($cliente_id, $provider_carrinho, $provider_promocoes);

        $enderecos = $provider_endereco->listarEnderecos();

        $porcentagem = $buscar['porcentagem'];
        $totalSemDesconto = $buscar['totalSemDesconto'];
        $totalComDesconto = $buscar['totalComDesconto'];
        
        return view('carrinho', ['pedidosSession' => $pedidosNaSession, 'id' => $cliente_id, 'visualizarCliente' => $visualizarCliente, 'totalComDesconto' =>  $totalComDesconto, 'enderecos' => $enderecos, 'totalSemDesconto' => $totalSemDesconto, 'porcentagem' => $porcentagem]);
    }

    public function finishCart(Request $request, $cliente_id, CarrinhoServiceInterface $provider_carrinho, ProdutosServiceInterface $provider_produto, PedidosServiceInterface $provider_pedidos, PromocoesServiceInterface $provider_promocoes, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, EstoqueServiceInterface $provider_estoque)
    {
        $endereco_id = $request->input('endereco_id');

        $pedidos_no_carrinho = $provider_carrinho->visualizar($cliente_id, $provider_produto, $provider_promocoes, $provider_carrinho);
        $provider_carrinho->calcularDesconto($cliente_id, $provider_carrinho, $provider_promocoes);

        $url = url()->previous();
        $array = [];



        foreach ($pedidos_no_carrinho as $key => $value) {
            $acima_do_estoque = $value['fora_de_estoque'];
            $produto_id = $value['produto_id'];
            $produto = $value['produto'];
            $quantidade_estoque = $value['quantidade_estoque'];
            $deleted_at = $provider_produto->buscarProduto($produto_id)['deleted_at'];

            if( isset($deleted_at) )
            {        
                $array_erros[] = strtoupper($produto) . ' fora de estoque!';
                break;
            }

            if($acima_do_estoque == true)
            {
                $fora_estoque = true;
            
                $array_erros[] = strtoupper($produto) . ' quantidade desejada ultrapassa valor do estoque! ' . 'Estoque atual: ' . $quantidade_estoque;
            }
        }



        if( isset($fora_estoque) || isset($deleted_at) )
             return redirect($url)->with('array_erros', $array_erros);
        
        if($pedidos_no_carrinho)
        {
            session()->flash('status', 'Pedido encaminhado com sucesso!');

            $provider_carrinho->finalizarCarrinho($cliente_id, $endereco_id, $provider_carrinho, $provider_produto,  $provider_pedidos, $provider_promocoes, $provider_entradas_saidas, $provider_user, $provider_estoque);
        } else {

            $array_erros[] = 'Não há produtos no carrinho!';

            return redirect($url)->with('array_erros', $array_erros);
        }
        
       
        return redirect($url);
    }
    
    public function deleteCart(Request $request, $cliente_id, $produto_id, CarrinhoServiceInterface $provider_carrinho, ProdutosServiceInterface $provider_produto, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos)
    {

        $provider_carrinho->excluirProduto($cliente_id, $produto_id, true, $provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos);

        return redirect('carrinho/' . $cliente_id);
    }
}
