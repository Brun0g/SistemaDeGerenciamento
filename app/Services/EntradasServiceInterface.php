<?php

namespace App\Services;

interface EntradasServiceInterface
{
	function adicionarEntrada($produto_id, $quantidade);
	function buscarEntrada($produto_id, $provider_user);
	function listarEntrada($provider_user);
}
