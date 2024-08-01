<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use \App\Services\CategoriaServiceInterface;


class Categoria_controller extends Controller
{
    public function categoria_view(Request $request, CategoriaServiceInterface $provider_categoria)
    {
        $categorias = $provider_categoria->listarCategoria();

        return view('categoria', ['categorias' => $categorias]);
    }
    
    public function newCategory(Request $request, CategoriaServiceInterface $provider_categoria)
    {
        $categoria = $request->input('categoriaEstoque');

        $validator = Validator::make($request->all(), [
            'categoriaEstoque' => 'required|string'
     
        ]);

        if($validator->fails())
            return redirect('/Categoria')->withErrors($validator);

        $provider_categoria->adicionarCategoria($categoria);

        return redirect('/Categoria');
    }
    public function editCategory(Request $request, $categoria_id, CategoriaServiceInterface $provider_categoria)
    {
        $categoria = $request->input('categoria');
       
        $validator = Validator::make($request->all(), [
        'categoria' => 'required|string',
        ]);

        if($validator->fails())
            return redirect('Categoria/'. $categoria_id)->withErrors($validator);
        
        $provider_categoria->editarCategoria($categoria_id, $categoria);

        return redirect('Categoria/');
    }
    public function deleteCategory(Request $request, $categoria_id, CategoriaServiceInterface $provider_categoria)
    {
        $provider_categoria->deletarCategoria($categoria_id);

        return redirect('/Categoria');
    }
    public function showCategory(Request $request, $categoria_id, CategoriaServiceInterface $provider_categoria)
    {
         $categoria = $provider_categoria->visualizarCategoria($categoria_id);

         return view('editarCategoria', ['categoria' => $categoria, 'categoria_id' => $categoria_id]);
    }
}
