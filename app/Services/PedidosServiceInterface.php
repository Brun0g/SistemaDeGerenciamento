<?php

namespace App\Services;


interface PedidosServiceInterface
{
	function listarPedidos($cliente_id, $provider_estoque, $provider_user);
	function listarPedidosExcluidos($provider_user);
	function buscarPedido($pedido_id);
	function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total);
	function buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos);
	function salvarItemPedido($pedido_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade);
    function listarQuantidadePedidos();
    function excluirPedido($pedido_id, $provider_entradas_saidas);

}
