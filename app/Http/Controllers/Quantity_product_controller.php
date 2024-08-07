<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use \App\Services\ClientesServiceInterface;
use \App\Services\PedidosServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\PromotionsServiceInterface;

class Quantity_product_controller extends Controller
{
    public function quantity_product_client(Request $request, ClientesServiceInterface $provider_client, ProdutosServiceInterface $provider_produto, PedidosServiceInterface $provider_pedido, PromotionsServiceInterface $provider_promotions)
    {   
        $service_clientes = $provider_client;
        $service_produtos = $provider_produto;
        $service_pedidos = $provider_pedido;

     
        $nomeDoClientPorID = $service_clientes->listarClientes();
        $produtos = $service_produtos->listarProduto($provider_promotions);
        
        foreach ($nomeDoClientPorID as $cliente_id => $value) {
            $produtosPorCliente = $service_pedidos->listarQuantidadePedidos($cliente_id);
        }

        $array = [];
        $produtosPorCliente = [];

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
                    if($valor['produto'] == $nome_produto)
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
