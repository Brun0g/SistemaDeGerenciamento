<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cliente;
use App\Models\Pedido;

class Graficos_controller extends Controller
{
    public function viewChart (Request $request)
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
