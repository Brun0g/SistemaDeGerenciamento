<?php
namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

use \App\Services\PedidosServiceInterface;
use \App\Services\EnderecoServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\EstoqueServiceInterface;
use \App\Services\ClientesServiceInterface;

use \App\Services\UserServiceInterface;



class Order_controller extends Controller
{
    public function finishOrder(Request $request, $pedido_id, $cliente_id, PedidosServiceInterface $provider_pedido)
    {
        $provider_pedido->aprovarPedido($pedido_id, $cliente_id);
    
        return redirect('Cliente/' . $cliente_id);
    }

    public function deleteOrderFinish(Request $request, $cliente_id, $pedido_id, PedidosServiceInterface $provider_pedidos, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, EstoqueServiceInterface $provider_estoque){

        $provider_pedidos->excluirPedido($pedido_id, $provider_entradas_saidas, null);

        return redirect('Cliente/' . $cliente_id);
    }
    
    public function showFinishOrder(Request $request, $pedido_id, PedidosServiceInterface $provider_pedidos, EnderecoServiceInterface $provider_endereco, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, ClientesServiceInterface $provider_cliente)
    {
        $pedidoEncontrado = $provider_pedidos->buscarPedido($pedido_id);
        $pedidosIndividuais = $provider_pedidos->buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos);


        $cliente_id = $pedidoEncontrado['cliente_id'];
        $nome = $provider_cliente->buscarCliente($cliente_id);
        $nome = $nome[$cliente_id]['name'];

        $endereco_id = $pedidoEncontrado['endereco_id'];
        $enderecoEntrega = $provider_endereco->buscarEndereco($endereco_id);

       
        return view('pedidoFinalizado' , ['nome' => $nome, 'pedido_id' => $pedido_id, 'array' => $pedidosIndividuais, 'endereco' => $enderecoEntrega, 'total' => $pedidoEncontrado['total'], 'diferenca' => 0, 'porcentagem' => $pedidoEncontrado['porcentagem'], 'totalSemDesconto' => $pedidoEncontrado['totalSemDesconto']]);
    }

    public function orders_deleted(Request $request, PedidosServiceInterface $provider_pedidos, EnderecoServiceInterface $provider_endereco, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, ClientesServiceInterface $provider_cliente)
    {
        
        $excluidos = $provider_pedidos->listarPedidosExcluidos($provider_user);
      
       
        $now = now();


        $data = ['ano' => $now->year, 'dia_do_ano' => $now->dayOfYear, 'dia_da_semana' => $now->dayOfWeek, 'hora' => $now->hour, 'minuto' => $now->minute, 'segundo' => $now->second, 'mes' => $now->month];

     
        return view('pedidos_excluidos' , ['excluidos' => $excluidos, 'data_atual' => $data]);
    }

    public function orders_active(Request $request, $pedido_id, PedidosServiceInterface $provider_pedidos, EntradasServiceInterface $provider_entradas_saidas)
    {
        
        $tem_estoque = $provider_pedidos->reativarPedido($pedido_id);

      

        $url = url()->previous();

        if(!$tem_estoque)
            session()->flash('error_estoque', 'Não há estoque para realocar o pedido!');
        else
        {
            $provider_pedidos->realocarPedido($pedido_id, $provider_entradas_saidas);
            session()->flash('status', 'Pedido realocado com sucesso!'); 
        }
            
        return redirect($url);
    }

    public function orders_client(Request $request, PedidosServiceInterface $provider_pedidos, EnderecoServiceInterface $provider_endereco, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, ClientesServiceInterface $provider_cliente)
    {
        $excluidos = $provider_entradas_saidas->listarEntradaSaidas($provider_user, true);
        $excluidos = collect($excluidos)->unique('pedido_id')->where('deleted_at', '!=', null)->sortBy(['data', 'asc']);

        $array = [];


        foreach ($excluidos as $key => $value) {
            $pedido_id = $value['pedido_id'];
            $totalComDesconto = $provider_pedidos->buscarPedido($pedido_id);
            $array[] = $totalComDesconto['total'];

        }
       
        $now = now();

        $data_atual = ['ano' => $now->year, 'dia_do_ano' => $now->dayOfYear, 'dia_da_semana' => $now->dayOfWeek, 'hora' => $now->hour, 'minuto' => $now->minute, 'segundo' => $now->second, 'mes' => $now->month];

        return view('pedidos_clientes' , ['excluidos' => $excluidos, 'data_atual' => $data_atual, 'totalComDesconto' => $array ]);
    }
}
