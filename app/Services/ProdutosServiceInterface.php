<?php

namespace App\Services;

interface ProdutosServiceInterface
{
	function adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade, $provider_entradas);
	function excluirProduto($produto_id);
	function editarProduto($produto_id, $nome, $valor, $imagem, $quantidade, $provider_entradas, $provider_saida);
	function listarProduto($provider_promotions, $softDelete);
	function buscarProduto($produto_id, $provider_entradas, $provider_saida);
}
