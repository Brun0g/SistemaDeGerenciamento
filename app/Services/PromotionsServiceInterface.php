<?php

namespace App\Services;

interface PromotionsServiceInterface
{
	function adicionarPromocao($produto_id, $quantidade, $porcentagem);
	function listarPromocoes($provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos);
	function ativarPromocao($promotion_id, $situacao);
	function editarPromocao($promotion_id, $quantidade, $porcentagem);
	function deletarPromocao($promotion_id);
	function buscarQuantidade($promotion_id, $quantidade);
	function buscarPromocao($promotion_id);
}
