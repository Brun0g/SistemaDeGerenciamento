<?php

namespace App\Services;


interface PedidosServiceInterface
{
	function excluirPedido($cliente_id, $pedido_id);
	function listarPedidos($cliente_id);
	function buscarPedido($pedido_id);
	function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total);
	function buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos);
	function salvarItemPedido($pedido_id, $cliente_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade);
    function listarQuantidadePedidos();

}
