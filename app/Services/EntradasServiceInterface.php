<?php

namespace App\Services;

interface EntradasServiceInterface
{
	function adicionarEntrada($produto_id, $quantidade, $observacao, $tipo, $registro_id, $quantidade_anterior);
	function buscarEntrada($produto_id, $provider_user);
	function listarEntrada($provider_user);
	function buscarRegistro($registro_id, $provider_user, $provider_produto);
}
