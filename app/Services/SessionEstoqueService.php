<?php

namespace App\Services;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use App\Services\SessionEstoqueService;
use App\Services\EstoqueServiceInterface;

class SessionEstoqueService implements EstoqueServiceInterface
{
    public function adicionarAjuste()
    {
        $estoque = session()->get('Ajustes', []);

        $estoque[] = ['create_by' => Auth::id()];

        session()->put('Ajustes', $estoque);

        $estoque_id = sizeof($estoque);

        return $estoque_id;
    }

    public function buscarAjuste($ajuste_id)
    {
        $ajuste = session()->get('Ajustes', []);

        foreach ($ajuste as $key => $value) {
            if($ajuste_id == $key)
                $create_by = $value['create_by'];
        }

        return $create_by;
    }
    
    public function adicionarMultiplos()
    {
        $estoque = session()->get('Multiplos', []);

        $estoque[] = ['create_by' => Auth::id()];

        session()->put('Multiplos', $estoque);

        $estoque_id = sizeof($estoque);

        return $estoque_id;
    }

    public function adicionarAjusteIndividuais($ajuste_id, $produto_id, $quantidade)
    {
        $estoque = session()->get('AjustesIndividuais', []);

        $estoque[] = ['create_by' => Auth::id(), 'ajuste_id' => $ajuste_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'created_at' => now()];

        session()->put('AjustesIndividuais', $estoque);
    }

    public function listarAjuste($ajuste_id, $provider_user, $provider_produto)
    {
        $estoque = session()->get('AjustesIndividuais', []);

        $array = [];

        foreach ($estoque as $key => $value) {
            
            if($ajuste_id == $value['ajuste_id'])
            {
                $create_by = $value['create_by'];
                $ajuste_id = $value['ajuste_id'];
                $produto_id = $value['produto_id'];
                $nome_usuario = $provider_user->buscarUsuario($create_by);
                $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
                $quantidade = $value['quantidade'];
                $created_at = $value['created_at'];

                $array[] = ['create_by' => $nome_usuario, 'ajuste_id' => $ajuste_id, 'produto_id' => $nome_produto, 'quantidade' => $quantidade, 'created_at' => $created_at];
            }
        }

        return $array;
    }

    public function atualizarEstoque($produto_id, $quantidade, $entrada_ou_saida, $observacao, $provider_entradas_saidas, $pedido_id,  $ajuste_id, $multiplo_id)
    {
        if($entrada_ou_saida == 'entrada')
        $provider_entradas_saidas->adicionarEntrada($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id);
        else
        $provider_entradas_saidas->adicionarSaida($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id);
    }

    public function buscarEstoque($produto_id)
    {
        $estoque = session()->get('entradas_saidas', []);

        $total = 0;

        foreach ($estoque as $key => $value) {
            if($produto_id == $value['produto_id'])
            {
                if(!$value['deleted_at'])
                {
                    $quantidade = $value['quantidade'];
                    $total += $quantidade;
                }
            }      
        }

        return $total;
    }

    function deletarSaida($pedido_id)
    {
        $estoque = session()->get('entradas_saidas', []);

        foreach ($estoque as $key => $value) {
            if($pedido_id == $value['pedido_id'])
               $estoque[$key]['deleted_at'] = now();
        }

        session()->put('entradas_saidas', $estoque);
    }

    public function pedidosAprovados($pedido_id)
    {
        $estoque = session()->get('entradas_saidas', []);

        $situacao = false;

        foreach ($estoque as $key => $value) {
            if($pedido_id == $value['pedido_id'])
                if( isset($value['deleted_at']) )
                    $situacao = true;

        }

        return $situacao;
    }
}
