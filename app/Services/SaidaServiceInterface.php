<?php

namespace App\Services;

interface SaidaServiceInterface
{
	function adicionarSaida($produto_id, $pedido_id, $quantidade, $observacao);
	function buscarSaida($produto_id, $provider_user, $provider_entradas, $provider_saida);
	function listarSaida($provider_user);
}
