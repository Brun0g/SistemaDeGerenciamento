<?php

namespace App\Services;

interface ProdutosServiceInterface
{
	function adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade);
	function excluirProduto($produto_id);
	function editarProduto($produto_id, $nome, $valor, $imagem, $quantidade);
	function listarProduto($provider_promotions, $softDelete);
	function buscarProduto($produto_id);
}
