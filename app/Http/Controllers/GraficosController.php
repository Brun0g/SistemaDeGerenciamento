<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cliente;
use App\Models\Pedido;

class GraficosController extends Controller
{
    public function index (Request $request)
    {

        $totalClientes = Cliente::all();

        $nomesArray = [];

        foreach ($totalClientes as $key => $value) {
                $name = $value['name'];
                $nomesArray['nome'] = $name;
        }

  
      
        return view('graficos', ['totalClientes' => $nomesArray]);
    }
}
