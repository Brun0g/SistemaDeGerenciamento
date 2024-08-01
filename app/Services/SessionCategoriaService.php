<?php

namespace App\Services;

use App\Services\CategoriaServiceInterface;


class SessionCategoriaService implements CategoriaServiceInterface
{
	function listarCategoria()
	{
		$categorias = session()->get('categorias', []);
		$listarCategoria = [];

		foreach ($categorias as $key => $value) 
        {
            $nome_categoria = $value['nome'];
       
            $listarCategoria[$key] = ['categoria' => $nome_categoria];       
        }

        return $listarCategoria;
	}

	function adicionarCategoria($categoria)
	{
      	$categorias = session()->get('categorias', []);

        $categorias[] = ['nome' => $categoria];

        session()->put('categorias', $categorias);
	}
	
	function visualizarCategoria($categoria_id)
	{
		$categorias = session()->get('categorias', []);

		$categoria = [];

		foreach ($categorias as $key => $value) {
			$nome_categoria = $value['nome'];
            $categoria[$key] = ['categoria' => $nome_categoria];
		}

		return $categoria;
	}

	function editarCategoria($categoria_id, $categoria)
	{
		$categorias = session()->get('categorias', []);

   		$categorias[$categoria_id]['nome'] = $categoria;
		
		session()->put('categorias', $categorias);
	}

	function deletarCategoria($categoria_id)
	{
        if(session()->has('categorias'))
        {
            $categorias = session()->get('categorias', []);

            if(array_key_exists($categoria_id, $categorias))
            {
                unset($categorias[$categoria_id]);

                session()->put('categorias', $categorias);
            }
        }
	}
}
