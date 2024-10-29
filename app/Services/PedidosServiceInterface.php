<?php

namespace App\Services;


interface PedidosServiceInterface
{
	function listarPedidos($search, $cliente_id, $data_inicial, $data_final, $pagina_atual, $order_by, $escolha, $maximo, $minimo, $provider_user);
	function buscarPedido($pedido_id);
	function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total);
	function buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos);
	function salvarItemPedido($pedido_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade);
    function excluirPedido($pedido_id, $provider_entradas_saidas);
}
