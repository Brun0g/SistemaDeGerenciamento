<?php
namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

use \App\Services\PedidosServiceInterface;
use \App\Services\EnderecoServiceInterface;


class Order_controller extends Controller
{
    public function finishOrder(Request $request, $pedido_id, $cliente_id, PedidosServiceInterface $provider_pedido)
    {
        $provider_pedido->aprovarPedido($pedido_id, $cliente_id);
    
        return redirect('Cliente/' . $cliente_id);
    }

    public function deleteOrderFinish(Request $request, $cliente_id, $id_pedido, PedidosServiceInterface $provider_pedido){

        $provider_pedido->excluirPedido($cliente_id, $id_pedido);

        return redirect('Cliente/' . $cliente_id);
    }
    
    public function showFinishOrder(Request $request, $pedido_id, $cliente_id,PedidosServiceInterface $provider_pedido, EnderecoServiceInterface $provider_endereco)
    {
        $pedidoEncontrado = $provider_pedido->buscarPedido($pedido_id);
        $pedidosIndividuais = $provider_pedido->buscarItemPedido($pedido_id);
        $endereco_id = $pedidoEncontrado['endereco_id'];
        $enderecoEntrega = $provider_endereco->buscarEndereco($endereco_id);

       
        return view('pedidoFinalizado' , ['pedido_id' => $pedido_id, 'cliente_id' => $cliente_id, 'array' => $pedidosIndividuais, 'endereco' => $enderecoEntrega, 'total' => $pedidoEncontrado['total'], 'diferenca' => 0, 'porcentagem' => $pedidoEncontrado['porcentagem'], 'totalSemDesconto' => $pedidoEncontrado['totalSemDesconto']]);
    }
}
