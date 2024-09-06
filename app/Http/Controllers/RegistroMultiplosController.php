<?php

namespace App\Http\Controllers;

use App\Models\Registro_multiplos;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

use \App\Services\ClientesServiceInterface;
use \App\Services\CategoriaServiceInterface;
use \App\Services\PedidosServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\EnderecoServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\PromotionsServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\SaidaServiceInterface;
use \App\Services\UserServiceInterface;



class RegistroMultiplosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $registro_id, EntradasServiceInterface $provider_entrada, SaidaServiceInterface $provider_saida, UserServiceInterface $provider_user, ProdutosServiceInterface $provider_produto)
    {
        
        $multipla_entrada = $provider_entrada->buscarRegistro($registro_id, $provider_user, $provider_produto);
        $multipla_saida = $provider_saida->buscarRegistro($registro_id, $provider_user, $provider_produto);


        $multiplos = array_merge($multipla_entrada, $multipla_saida);
        $sort = array_column($multiplos, 'quantidade');

        array_multisort($sort, SORT_DESC, $multiplos);





        return view('detalhes', ['registro_id' => $registro_id, 'multiplos' => $multiplos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\registro_multiplos  $registro_multiplos
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, EntradasServiceInterface $provider_entrada, SaidaServiceInterface $provider_saida, UserServiceInterface $provider_user, ProdutosServiceInterface $provider_produto)
    {
        $multipla_entrada = $provider_entrada->listarEntrada($provider_user);
        $multipla_saida = $provider_saida->listarSaida($provider_user);
        $array_merge = array_merge($multipla_entrada, $multipla_saida);


        $multiplos = collect($array_merge)->unique('registro_id')->sortBy(['data', 'asc']);
        $now = now();

        $dia_atual = ['ano' => $now->year, 'dia_do_ano' => $now->dayOfYear, 'dia_da_semana' => $now->dayOfWeek, 'hora' => $now->hour, 'minuto' => $now->minute, 'segundo' => $now->second, 'mes' => $now->month];

        return view('visualizar_ajuste', ['multiplos' => $multiplos, 'data_atual' => $dia_atual]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\registro_multiplos  $registro_multiplos
     * @return \Illuminate\Http\Response
     */
    public function edit(registro_multiplos $registro_multiplos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\registro_multiplos  $registro_multiplos
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, registro_multiplos $registro_multiplos)
    {
        
    }


    public function entradas_view(Request $request, EntradasServiceInterface $provider_entrada, SaidaServiceInterface $provider_saida, UserServiceInterface $provider_user, ProdutosServiceInterface $provider_produto)
    {
        $multipla_entrada = $provider_entrada->listarEntrada($provider_user);
      
        $multipla_entrada = collect($multipla_entrada)->unique('registro_id')->sortBy(['data', 'asc']);
     
        $now = now();


        $dia_atual = ['ano' => $now->year, 'dia_do_ano' => $now->dayOfYear, 'dia_da_semana' => $now->dayOfWeek, 'hora' => $now->hour, 'minuto' => $now->minute, 'segundo' => $now->second, 'mes' => $now->month];

        return view('visualizar_entradas', ['multiplos' => $multipla_entrada, 'data_atual' => $dia_atual]);
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\registro_multiplos  $registro_multiplos
     * @return \Illuminate\Http\Response
     */
    public function destroy(registro_multiplos $registro_multiplos)
    {
        //
    }
}