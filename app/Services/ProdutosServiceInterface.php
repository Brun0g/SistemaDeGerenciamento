<?php

namespace App\Services;

interface ProdutosServiceInterface
{
	function adicionarProduto($nome, $categoria, $valor, $imagem);
	function excluirProduto($produto_id);
	function editarProduto($produto_id, $nome, $valor, $imagem);
	function listarProduto($provider_promotions);
	function buscarProduto($produto_id, $softDelete);
}
