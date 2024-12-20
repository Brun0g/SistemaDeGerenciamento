<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cliente;
use App\Models\Pedidos;

class GraficosController extends Controller
{
    public function index (Request $request)
    {

        $data = Pedidos::selectRaw("date_format(created_at, '%d/%m/%Y') as date, count(*) as aggregate")
    ->whereDate('created_at', '<=', now())
    ->groupBy('date')
    ->get();

  
      
        return view('graficos', ['data' => $data]);
    }
}
