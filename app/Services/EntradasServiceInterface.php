<?php

namespace App\Services;

interface EntradasServiceInterface
{
	function adicionarEntrada($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id);
	function adicionarSaida($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id);
	function buscarEntradaSaidas($produto_id, $provider_user);
	function listarEntradaSaidas($provider_user, $tipo);
	function buscarAjuste($ajuste_id, $provider_user, $provider_produto);
}
