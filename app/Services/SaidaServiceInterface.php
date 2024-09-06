<?php

namespace App\Services;

interface SaidaServiceInterface
{
	function adicionarSaida($produto_id, $pedido_id, $quantidade, $observacao, $tipo, $registro_id, $quantidade_anterior);
	function buscarSaida($produto_id, $provider_user, $provider_entradas, $provider_saida);
	function listarSaida($provider_user);
	function buscarRegistro($registro_id, $provider_user, $provider_produto);
}
