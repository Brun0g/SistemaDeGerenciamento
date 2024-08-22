<?php

namespace App\Services;


interface PedidosServiceInterface
{
	function excluirPedido($cliente_id, $id_pedido);
	function listarPedidos($cliente_id);
	function buscarPedido($pedido_id);
	function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total);
	function buscarItemPedido($pedido_id, $provider_entradas, $provider_saida, $provider_user, $provider_pedidos);
	function salvarItemPedido($pedido_id, $cliente_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade, $provider_saida);
    function listarQuantidadePedidos();

}
