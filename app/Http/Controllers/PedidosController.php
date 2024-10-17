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

use Illuminate\Support\Carbon;


class PedidosController extends Controller
{
    public function finish(Request $request, $pedido_id, $cliente_id, PedidosServiceInterface $provider_pedido)
    {
        $provider_pedido->aprovarPedido($pedido_id, $cliente_id);

    
        return redirect('Cliente/' . $cliente_id);
    }

    public function delete(Request $request, $pedido_id, PedidosServiceInterface $provider_pedidos, EntradasServiceInterface $provider_entradas_saidas){

        $provider_pedidos->excluirPedido($pedido_id, $provider_entradas_saidas);

        $pagina_atual = $request->input('pagina_atual');

        $url = url()->previous();

        session()->flash('status', 'Pedido deletado com sucesso!'); 

        if(isset($pagina_atual))
            return redirect('pedidos_excluidos');

        return redirect($url);
    }
    
    public function showFinishOrder(Request $request, $pedido_id, PedidosServiceInterface $provider_pedidos, EnderecoServiceInterface $provider_endereco, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, ClientesServiceInterface $provider_cliente)
    {
        $pedidoEncontrado = $provider_pedidos->buscarPedido($pedido_id);
        $pedidosIndividuais = $provider_pedidos->buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos);
        $cliente_id = $pedidoEncontrado['cliente_id'];
        $nome = $provider_cliente->buscarCliente($cliente_id);
        $endereco_id = $pedidoEncontrado['endereco_id'];
        $enderecoEntrega = $provider_endereco->buscarEndereco($endereco_id);

        return view('pedidoFinalizado' , ['nome' => $nome['name'], 'pedido_id' => $pedido_id, 'array' => $pedidosIndividuais, 'endereco' => $enderecoEntrega, 'total' => $pedidoEncontrado['total'], 'diferenca' => 0, 'porcentagem' => $pedidoEncontrado['porcentagem'], 'totalSemDesconto' => $pedidoEncontrado['totalSemDesconto'], 'data_pedido' =>$pedidoEncontrado['created_at'], 'create_by' => $pedidoEncontrado['create_by'] ]);
    }

    public function orders_deleted(Request $request, PedidosServiceInterface $provider_pedidos, UserServiceInterface $provider_user, ClientesServiceInterface $provider_cliente, EstoqueServiceInterface $provider_estoque)
    {
        $escolha = $request->input('pedidos');
        $data_inicial = $request->input('data_inicial');
        $data_final = $request->input('data_final');
        $request_page = $request->input('page');

        $pagina_atual = 0;
        $total_paginas = 0;
        $order_by = ['id' => null, 'total' => null, 'created_at' => null];

        if($request_page)
            $pagina_atual = $request_page;

        if( !isset($data_inicial, $data_final, $escolha) )
        {
            $data_inicial = now()->toDateString();
            $data_final = now()->toDateString();
            $escolha = 1;
        }

        if( Carbon::parse($data_inicial) > Carbon::parse($data_final) )
        {
            session()->flash('date_error', 'A data inicial deve ser menor ou igual a data final!');
            $data_inicial = now()->toDateString();
        }


        if($escolha == "1"){
            $pedidos = $provider_pedidos->listarPedidos(null, $provider_estoque, $provider_user, $data_inicial, $data_final, $pagina_atual, $order_by);

            $total_paginas = $pedidos['page'];
            $pedidos = $pedidos['array']; 
        }
        else
        {
            $pedidos = $provider_pedidos->listarPedidosExcluidos($provider_user, $data_inicial, $data_final, $pagina_atual);

            $total_paginas = $pedidos['page'];
            $pedidos = $pedidos['array']; 
        }

        $now = now();

        $data = ['ano' => $now->year, 'dia_do_ano' => $now->dayOfYear, 'dia_da_semana' => $now->dayOfWeek, 'hora' => $now->hour, 'minuto' => $now->minute, 'segundo' => $now->second, 'mes' => $now->month];

        return view('pedidos_excluidos' , ['excluidos' => $pedidos, 'data_atual' => $data, 'data_inicial' => $data_inicial, 'data_final' => $data_final, 'escolha' => $escolha, 'pagina_atual' => $pagina_atual, 'page' => $total_paginas, 'order_by' => $order_by]);
    }

    public function orders_list(Request $request, $pagina_atual, PedidosServiceInterface $provider_pedidos, UserServiceInterface $provider_user, ClientesServiceInterface $provider_cliente, EstoqueServiceInterface $provider_estoque)
    {
        $escolha = $request->input('pedidos');
        $data_inicial = $request->input('data_inicial');
        $data_final = $request->input('data_final');

        $ordernar_id = $request->input('ordernar_id');
        $ordernar_total = $request->input('ordernar_total');
        $ordernar_data = $request->input('ordernar_data');

        $order_by = ['id' => $ordernar_id];
        $total_paginas = 0;

        if(is_numeric($ordernar_total))
            $order_by = ['total' => $ordernar_total];
        
        if(is_numeric($ordernar_data))
            $order_by = ['created_at' => $ordernar_data];


        $array_order = ['id' => $ordernar_id, 'total' => $ordernar_total, 'created_at' => $ordernar_data];

        
        if( !isset($data_inicial, $data_final, $escolha) )
        {
            $data_inicial = now()->toDateString();
            $data_final = now()->toDateString();
            $escolha = 1;
        }

        if( Carbon::parse($data_inicial) > Carbon::parse($data_final) )
        {
            session()->flash('date_error', 'A data inicial deve ser menor ou igual a data final!');
            $data_inicial = now()->toDateString();
        }


        if($escolha == "1"){
            $pedidos = $provider_pedidos->listarPedidos(null, $provider_estoque, $provider_user, $data_inicial, $data_final, $pagina_atual, $order_by);

            $total_paginas = $pedidos['page'];
            $pedidos = $pedidos['array']; 
        }
        else
        {
            $pedidos = $provider_pedidos->listarPedidosExcluidos($provider_user, $data_inicial, $data_final, $pagina_atual);

            $total_paginas = $pedidos['page'];
            $pedidos = $pedidos['array']; 
        }

        $now = now();

        
        $data = ['ano' => $now->year, 'dia_do_ano' => $now->dayOfYear, 'dia_da_semana' => $now->dayOfWeek, 'hora' => $now->hour, 'minuto' => $now->minute, 'segundo' => $now->second, 'mes' => $now->month];

        return view('pedidos_excluidos' , ['excluidos' => $pedidos, 'data_atual' => $data, 'data_inicial' => $data_inicial, 'data_final' => $data_final, 'escolha' => $escolha, 'pagina_atual' => $pagina_atual, 'page' => $total_paginas, 'order_by' => $array_order]);
    }

    public function orders_active(Request $request, $pedido_id, PedidosServiceInterface $provider_pedidos, EntradasServiceInterface $provider_entradas_saidas)
    {
        $tem_estoque = $provider_pedidos->reativarPedido($pedido_id);

        $url = url()->previous();

        if(!$tem_estoque)
            session()->flash('error_estoque', 'Não há estoque para Restaurar o pedido!');
        else
        {
            $provider_pedidos->RestaurarPedido($pedido_id, $provider_entradas_saidas);
            session()->flash('status', 'Pedido realocado com sucesso!'); 
        }
            
        return redirect($url);
    }

    public function orders_client(Request $request, PedidosServiceInterface $provider_pedidos, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user)
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
