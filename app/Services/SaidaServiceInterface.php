<?php

namespace App\Services;

interface SaidaServiceInterface
{
	function adicionarSaida($produto_id, $pedido_id, $quantidade);
	function buscarSaida($produto_id);
	function listarSaida();
}
