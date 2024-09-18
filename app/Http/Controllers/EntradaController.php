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
use \App\Services\PromocoesServiceInterface;
use \App\Services\EntradasServiceInterface;

use \App\Services\UserServiceInterface;
use \App\Services\EstoqueServiceInterface;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request, $produto_id, EntradasServiceInterface $provider_entradas_saidas, ProdutosServiceInterface $provider_produto, PromocoesServiceInterface $provider_promocoes, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos, CarrinhoServiceInterface $provider_carrinho, EstoqueServiceInterface $provider_estoque)
    {
       $entradas = $provider_entradas_saidas->listarEntradaSaidas($provider_user);

       $produtos = $provider_produto->buscarProduto($produto_id);
       $quantidade_carrinho = $provider_carrinho->buscarQuantidade($produto_id)['quantidade'];
       $resultado = $provider_estoque->buscarEstoque($produto_id);

        
       return view('entradas_saida', ['entradas' => $entradas, 'Produtos' => $produtos, 'produto_id' => $produto_id, 'carrinho' => $quantidade_carrinho, 'quantidade_estoque' => $resultado]);
    }

    public function update(Request $request, $produto_id, EntradasServiceInterface $provider_entradas_saidas, ProdutosServiceInterface $provider_produto, UserServiceInterface $provider_user, PedidosServiceInterface $provider_pedidos, PromocoesServiceInterface $provider_promocoes, EstoqueServiceInterface $provider_estoque)
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

        $quantidade_estoque = $provider_estoque->buscarEstoque($produto_id);


        if($entrada_ou_saida == 'entrada')
        {
       
            session()->flash('status', 'Entrada adicionada com sucesso!');
            
        } else {

            if($quantidade_estoque - $quantidade < 0)
            {
                    session()->flash('error', 'Não é possível adicionar valor negativo ao produto!');
                    return redirect($url);
            }

            session()->flash('status', 'Saída adicionada com sucesso!');
            $quantidade = -(int)$quantidade;
        }
   

        $provider_estoque->atualizarEstoque($produto_id, $quantidade, $entrada_ou_saida, $observacao, $provider_entradas_saidas, null,  null, null);

        return redirect($url);
    }
}
