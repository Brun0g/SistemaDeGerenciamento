<?php

namespace App\Services;



interface CarrinhoServiceInterface
{
	function adicionarProduto($cliente_id, $produto_id, $quantidade,  $provider_produto, $provider_carrinho, $provider_promotions);
	function calcularDesconto($cliente_id, $provider_produto, $provider_carrinho, $provider_promotions);
	function excluirProduto($cliente_id, $id_pedido);
	function visualizar($cliente_id, $provider_produto, $provider_promotions);
	function atualizar($pedido_id, $cliente_id, $quantidade, $provider_produto, $provider_carrinho, $provider_promotions);
	function atualizarPorcentagem($cliente_id, $porcentagem);
	function finalizarCarrinho($cliente_id, $endereco_id, $provider_carrinho, $provider_produto, $provider_pedido, $provider_promotions);
	function visualizarPorcentagem($cliente_id);

}
