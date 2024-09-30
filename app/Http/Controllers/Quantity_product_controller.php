<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use \App\Services\ClientesServiceInterface;
use \App\Services\PedidosServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\PromocoesServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\EstoqueServiceInterface;

use \App\Services\UserServiceInterface;

class Quantity_product_controller extends Controller
{
    public function quantity_product_client(Request $request, ClientesServiceInterface $provider_cliente, ProdutosServiceInterface $provider_produto, PedidosServiceInterface $provider_pedidos, PromocoesServiceInterface $provider_promocoes, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, EstoqueServiceInterface $provider_estoque)
    {   
        $nomeDoClientPorID = $provider_cliente->listarClientes(true);
        $produtos = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, false);
        $produtosPorCliente = $provider_pedidos->listarQuantidadePedidos();
        $array = [];

        if(isset($produtos, $nomeDoClientPorID, $produtosPorCliente))
        {
            foreach($produtos as $key => $value) {
                // PEGAR NOME DO PRODUTO
                $nome_produto = $value['produto'];

                foreach($produtosPorCliente as $id => $valor) {
                    // PEGAR ID DO CLIENTE
                    $cliente_id = $valor['cliente_id'];

                    // SE NÃO EXISTER UMA KEY COM O ID DO CLIENTE
                    if(!isset($array[$cliente_id])) 
                        $array[$cliente_id] = [];

                    // SE O PRODUTO DO PEDIDO FOR IGUAL AO PRODUTO DO ESTOQUE
                    if($valor['produto_id'] == $key)
                    {
                        
                        // SE NÃO EXISTIR O PRODUTO, INSERIR E COLOCAR QUANTIDADE 0
                        if(!isset($array[$cliente_id][$nome_produto]))
                            $array[$cliente_id][$nome_produto] = 0;

                        // SOMAR A QUANTIDADE DO PRODUTO SE JÁ EXISTIR
                        $array[$cliente_id][$nome_produto] += $valor['quantidade'];
                    }
                }
            }
        }

     

        return view('Products_view_client', ['Clientes' => $nomeDoClientPorID,  'produtosPorCliente' => $produtosPorCliente, 'produtos' => $produtos,'clientes_produtos' => $array]);
    }
}















        // $startTime = microtime(true);

   // $endTime = microtime(true);
        // $executionTime = $endTime - $startTime;
        // $executionTime = 'Tempo de execução: ' . number_format($executionTime, 2, '.', '');

        // dd($array, $executionTime);