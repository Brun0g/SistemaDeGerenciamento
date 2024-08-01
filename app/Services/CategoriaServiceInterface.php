<?php

namespace App\Services;


interface CategoriaServiceInterface
{
	function adicionarCategoria($categoria);
	function listarCategoria();
	function editarCategoria($categoria_id, $categoria);
	function visualizarCategoria($categoria_id);
	function deletarCategoria($categoria_id);
}
