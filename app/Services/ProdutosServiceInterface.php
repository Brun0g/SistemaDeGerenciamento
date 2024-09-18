<?php

namespace App\Services;

interface ProdutosServiceInterface
{
	function adicionarProduto($nome, $categoria, $valor, $imagem, $quantidade, $provider_entradas_saidas);
	function excluirProduto($produto_id);
	function editarProduto($produto_id, $nome, $valor, $imagem);
	function listarProduto($provider_promocoes, $provider_estoque, $softDelete);
	function buscarProduto($produto_id);
}
