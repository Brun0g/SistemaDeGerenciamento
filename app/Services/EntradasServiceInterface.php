<?php

namespace App\Services;

interface EntradasServiceInterface
{
	function adicionarEntrada($produto_id, $quantidade, $observacao, $tipo, $ajuste_id, $multiplo_id);
	function buscarEntrada($produto_id, $provider_user);
	function listarEntrada($provider_user);
	function buscarAjuste($ajuste_id, $provider_user, $provider_produto);
}
