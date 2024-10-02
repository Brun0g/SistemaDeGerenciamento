<?php

namespace App\Http\Controllers;

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
use \App\Services\PromocoesServiceInterface;
use \App\Services\EntradasServiceInterface;

use \App\Services\UserServiceInterface;
use \App\Services\EstoqueServiceInterface;

class AjusteEstoqueController extends Controller
{
    public function index_adjustment(Request $request, EntradasServiceInterface $provider_entrada, UserServiceInterface $provider_user, ProdutosServiceInterface $provider_produto)
    {
        $multipla_entrada = $provider_entrada->listarEntradaSaidas($provider_user, null);
        $multiplos = collect($multipla_entrada)->unique('ajuste_id')->sortBy(['data', 'asc']);
        $now = now();

        $dia_atual = ['ano' => $now->year, 'dia_do_ano' => $now->dayOfYear, 'dia_da_semana' => $now->dayOfWeek, 'hora' => $now->hour, 'minuto' => $now->minute, 'segundo' => $now->second, 'mes' => $now->month];

        return view('visualizar_ajuste', ['multiplos' => $multiplos, 'data_atual' => $dia_atual]);
    }

    public function index_multiple(Request $request, EntradasServiceInterface $provider_entrada, UserServiceInterface $provider_user, ProdutosServiceInterface $provider_produto)
    {
        $multipla_entrada = $provider_entrada->listarEntradaSaidas($provider_user, null);
        $multipla_entrada = collect($multipla_entrada)->unique('multiplo_id')->sortBy(['data', 'asc']);
        
        $now = now();

        $dia_atual = ['ano' => $now->year, 'dia_do_ano' => $now->dayOfYear, 'dia_da_semana' => $now->dayOfWeek, 'hora' => $now->hour, 'minuto' => $now->minute, 'segundo' => $now->second, 'mes' => $now->month];

        return view('visualizar_entradas', ['multiplos' => $multipla_entrada, 'data_atual' => $dia_atual]);
    }

    public function detail_adjustment(Request $request, $ajuste_id, UserServiceInterface $provider_user, ProdutosServiceInterface $provider_produto, EstoqueServiceInterface $provider_estoque)
    {
        $array_merge = $provider_estoque->listarAjuste($ajuste_id, $provider_user, $provider_produto);
        
        return view('detalhes_ajuste', ['estoque_id' => $ajuste_id, 'multiplos' => $array_merge]);
    }

    public function detail_multiple(Request $request, $multiplo_id, EntradasServiceInterface $provider_entrada,UserServiceInterface $provider_user, ProdutosServiceInterface $provider_produto)
    {
        $entrada = $provider_entrada->listarMultiplos($multiplo_id, $provider_user, $provider_produto);

        $sort = array_column($entrada, 'quantidade');

        array_multisort($sort, SORT_DESC, $entrada);

        return view('detalhes_multiplos', ['estoque_id' => $multiplo_id, 'multiplos' => $entrada]);
    }

    public function storeMultiple(Request $request, EntradasServiceInterface $provider_entradas_saidas, EstoqueServiceInterface $provider_estoque)
    {
        $quantidade = $request->input('quantidade');
        $observacao = $request->input('observacao');

        $validator = Validator::make($request->all(), [
            'quantidade.*' => 'bail|required|integer|min:0',
            'observacao' => 'nullable|string',
        ]);

        $url = url()->previous();

        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);
            
        $validated = $validator->validated();

        $multiplo_id = $provider_estoque->adicionarMultiplos();


        foreach ($validated['quantidade'] as $key => $value) {

            $produto_id = $key;
            $quantidade = $value;

            if($quantidade != "0")
            {
                $provider_estoque->atualizarEstoque($produto_id, $quantidade, 'entrada', $observacao, $provider_entradas_saidas, null, null, $multiplo_id);

                session()->flash('status', 'Múltiplas entradas adicionada com sucesso!');       
            }
        }

        return redirect($url);
    }

    public function storeAdjustment(Request $request, EntradasServiceInterface $provider_entradas_saidas, EstoqueServiceInterface $provider_estoque)
    {
        $quantidade = $request->input('quantidade');
        $observacao = $request->input('observacao');

        $validator = Validator::make($request->all(), [
            'quantidade.*' => 'bail|required|integer|min:0',
            'observacao' => 'nullable|string',
        ]);


        $url = url()->previous();

        if($validator->fails())
            return redirect()->to($url)->withErrors($validator);
            
        $validated = $validator->validated();

        $ajuste_id = $provider_estoque->adicionarAjuste();

        foreach ($validated['quantidade'] as $key => $value) {

            $produto_id = $key;
            $quantidade = $value;
            $quantidade_estoque = $provider_estoque->buscarEstoque($produto_id);


            if($quantidade_estoque != $quantidade)
            {
                $provider_estoque->adicionarAjusteIndividuais($ajuste_id, $produto_id, $quantidade);

                if($quantidade_estoque < $quantidade)
                {
                    $quantidade = $quantidade - $quantidade_estoque;

                    session()->flash('status', 'Ajuste de múltiplas entradas realizada com sucesso!');       

                    $provider_estoque->atualizarEstoque($produto_id, $quantidade, 'entrada', $observacao, $provider_entradas_saidas, null, $ajuste_id, null);
                }
                else
                {
                    $quantidade = $quantidade - $quantidade_estoque;
                    
                    session()->flash('status', 'Ajuste de múltiplas saidas realizada com sucesso!');    

                    $provider_estoque->atualizarEstoque($produto_id, $quantidade, 'saida', $observacao, $provider_entradas_saidas, null, $ajuste_id, null);
                }
            }
        }

        return redirect($url);
    }
}
