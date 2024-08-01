<?php

namespace App\Services;

interface ProdutosServiceInterface
{
	function adicionarProduto($nome, $categoria, $valor, $imagem);
	function excluirProduto($produto_id);
	function editarProduto($produto_id, $nome, $valor);
	function listarProduto();
	function buscarProduto($produto_id);
}
