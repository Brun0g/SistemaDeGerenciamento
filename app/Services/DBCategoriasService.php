<?php

namespace App\Services;

use App\Models\Categoria;
use App\Services\CategoriaServiceInterface;


class DBCategoriasService implements CategoriaServiceInterface
{
	function listarCategoria()
	{
		$categorias = Categoria::all();
		$listarCategoria = [];

		foreach ($categorias as $categoria) 
		{
			$nome_categoria = $categoria->categoria;
			
			$listarCategoria[$categoria->id] = ['categoria' => $nome_categoria];       
		}

		return $listarCategoria;
	}

	function adicionarCategoria($categoria)
	{
		$nome_categoria = new Categoria;

		$nome_categoria->categoria = $categoria;
		
		$nome_categoria->save();
	}
	
	function visualizarCategoria($categoria_id)
	{
		$categorias = Categoria::find($categoria_id);

		$categoria = [];

		foreach ($categorias as $key => $value) {
			$nome_categoria = $categorias->categoria;
			
			$categoria[$categorias->id] = ['categoria' => $nome_categoria];
		}

		return $categoria;
	}

	function editarCategoria($categoria_id, $categoria)
	{
		$categorias = Categoria::find($categoria_id);

		$categorias->categoria = $categoria;
		
		$categorias->save();
	}

	function deletarCategoria($categoria_id)
	{
		$categorias = Categoria::find($categoria_id);
		
		$categorias->delete($categoria_id);
	}
}
