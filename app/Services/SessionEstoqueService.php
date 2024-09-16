<?php

namespace App\Services;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


use App\Services\EstoqueServiceInterface;

class SessionEstoqueService implements EstoqueServiceInterface
{
    public function adicionarAjuste()
    {
        $estoque = session()->get('Ajustes', []);

        $estoque[] = ['user_id' => Auth::id()];

        session()->put('Ajustes', $estoque);

        $estoque_id = sizeof($estoque);

        return $estoque_id;
    }
    
    public function adicionarMultiplos()
    {
        $estoque = session()->get('Multiplos', []);

        $estoque[] = ['user_id' => Auth::id()];

        session()->put('Multiplos', $estoque);

        $estoque_id = sizeof($estoque);

        return $estoque_id;
    }

    public function adicionarAjusteIndividuais($ajuste_id, $produto_id, $quantidade)
    {
        $estoque = session()->get('AjustesIndividuais', []);

        $estoque[] = ['user_id' => Auth::id(), 'ajuste_id' => $ajuste_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'created_at' => now()];

        session()->put('AjustesIndividuais', $estoque);
    }

    public function listarAjuste($ajuste_id, $provider_user, $provider_produto)
    {
        $estoque = session()->get('AjustesIndividuais', []);

        $array = [];

        foreach ($estoque as $key => $value) {
            
            if($ajuste_id == $value['ajuste_id'])
            {
                $user_id = $value['user_id'];
                $ajuste_id = $value['ajuste_id'];
                $produto_id = $value['produto_id'];
                $nome_usuario = $provider_user->buscarUsuario($user_id);
                $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
                $quantidade = $value['quantidade'];
                $created_at = $value['created_at'];

                $array[] = ['user_id' => $nome_usuario, 'ajuste_id' => $ajuste_id, 'produto_id' => $nome_produto, 'quantidade' => $quantidade, 'created_at' => $created_at];
            }
        }

        return $array;
    }

    public function atualizarEstoque($produto_id, $quantidade, $entrada_ou_saida, $observacao, $provider_entradas_saidas, $pedido_id,  $ajuste_id, $multiplo_id)
    {
        $produto = session()->get('Produtos', []);

        foreach ($produto as $key => $value) {
            if($produto_id == $key)
            {
                if(isset($entrada_ou_saida))
                {
                    $quantidade_anterior = $value['quantidade'];
                    $produto[$key]['quantidade'] += $quantidade;


                    if($entrada_ou_saida == 'entrada')
                    $provider_entradas_saidas->adicionarEntrada($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id);
                    else
                    $provider_entradas_saidas->adicionarSaida($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id);
                
                } else 
                    $produto[$key]['quantidade'] = $quantidade;
            }
        }

        session()->put('Produtos', $produto);
    }
}
