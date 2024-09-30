<?php

namespace App\Services;

interface PromocoesServiceInterface
{
	function adicionarPromocao($produto_id, $quantidade, $porcentagem);
	function listarPromocoes($softDeletes, $provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos);
	function ativarPromocao($promocoes_id, $situacao);
	function editarPromocao($promocoes_id, $quantidade, $porcentagem);
	function deletarPromocao($promocoes_id);
	function buscarQuantidade($promocoes_id, $quantidade);
	function buscarPromocao($promocoes_id);
}
