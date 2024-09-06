<?php

namespace App\Services;



interface CarrinhoServiceInterface
{
	function adicionarProduto($cliente_id, $produto_id, $quantidade,  $provider_produto, $provider_carrinho, $provider_promotions);
	function calcularDesconto($cliente_id, $provider_carrinho, $provider_promotions);
	function excluirProduto($cliente_id, $produto_id);
	function visualizar($cliente_id, $provider_produto, $provider_promotions, $provider_carrinho);
	function atualizar($pedido_id, $cliente_id, $quantidade, $provider_produto, $provider_carrinho, $provider_promotions);
	function atualizarPorcentagem($cliente_id, $porcentagem);
	function finalizarCarrinho($cliente_id, $endereco_id, $provider_carrinho, $provider_produto,  $provider_pedidos, $provider_promotions, $provider_entradas, $provider_saida, $provider_user, $provider_registro);
	function visualizarPorcentagem($cliente_id);
	function buscarCarrinho($cliente_id, $pedido_id);
}
