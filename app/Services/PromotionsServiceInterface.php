<?php

namespace App\Services;

interface PromotionsServiceInterface
{
	function adicionarPromocao($produto_id, $quantidade, $porcentagem);
	function listarPromocoes($provider_produto);
	function ativarPromocao($promotion_id, $situacao);
	function editarPromocao($promotion_id, $quantidade, $porcentagem);
	function deletarPromocao($promotion_id);
	function buscarPromocao($promotion_id, $quantidade);
}
