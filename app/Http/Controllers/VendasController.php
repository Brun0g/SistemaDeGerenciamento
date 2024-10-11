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

use Illuminate\Support\Carbon;


class VendasController extends Controller
{
    public function index(Request $request, ClientesServiceInterface $provider_cliente, ProdutosServiceInterface $provider_produto, PedidosServiceInterface $provider_pedidos, PromocoesServiceInterface $provider_promocoes, EstoqueServiceInterface $provider_estoque,
        UserServiceInterface $provider_user)
    {   
        $nomeDoClientPorID = $provider_cliente->listarClientes(true); // t
        $produtos = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, false); // t
        
        $data_inicial = $request->input('data_inicial'); //t
        $data_final = $request->input('data_final'); //t
        $cliente = $request->input('procurar_cliente'); //t

        if( !isset($data_inicial) )
        {
            $data_inicial = now()->toDateString();
            $data_final = now()->toDateString();
        } // t

        if( Carbon::parse($data_inicial) > Carbon::parse($data_final) )
        {
            session()->flash('date_error', 'A data inicial deve ser menor ou igual a data final!');
            $data_inicial = now()->toDateString();
        }

        $produtos_vendidos = $provider_pedidos->listarQuantidadePedidos($cliente, $data_inicial, $data_final, $provider_estoque, $provider_user);
      
        $array = [];

        if( isset($produtos, $nomeDoClientPorID, $produtos_vendidos) )
        {
            foreach($produtos as $key => $value) {
                $nome_produto = $value['produto'];

                foreach($produtos_vendidos as $id => $valor) {
                    $cliente_id = $valor['cliente_id'];

                    if(!isset($array[$cliente_id])) 
                        $array[$cliente_id] = [];

                    if($valor['produto_id'] == $key)
                    {
                        
                        if(!isset($array[$cliente_id][$nome_produto]))
                            $array[$cliente_id][$nome_produto] = 0;

                        $array[$cliente_id][$nome_produto] += $valor['quantidade'];
                    }
                }
            }
        }

        
        
        return view('produtos_vendidos', [ 'Clientes' => $nomeDoClientPorID,  'produtos_vendidos' => $produtos_vendidos, 'produtos' => $produtos, 'clientes_produtos' => $array, 'search' => $cliente, 'data_inicial' => $data_inicial, 'data_final' => $data_final ]);
    }
}
