<?php

namespace App\Services;


use App\Models\Ajuste;
use App\Models\MultiplaEntradas;
use App\Models\Produto;
use App\Models\Entradas_saidas;
use App\Models\AjusteIndividuais;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;



use App\Services\EstoqueServiceInterface;

class DBEstoqueService implements EstoqueServiceInterface
{
    public function adicionarAjuste()
    {
        $estoque = new Ajuste();

        $estoque->user_id = Auth::id();

        $estoque->save();

        return $estoque->id;
    }

    public function adicionarMultiplos()
    {
        $estoque = new MultiplaEntradas();

        $estoque->user_id = Auth::id();

        $estoque->save();

        return $estoque->id;
    }

    public function adicionarAjusteIndividuais($ajuste_id, $produto_id, $quantidade)
    {
        $estoque = new AjusteIndividuais();

        $estoque->produto_id = $produto_id;
        $estoque->quantidade = $quantidade;
        $estoque->ajuste_id = $ajuste_id;

        $estoque->save();

        return $estoque->id;
    }

    public function listarAjuste($ajuste_id, $provider_user, $provider_produto)
    {
        $estoque = AjusteIndividuais::where('ajuste_id', $ajuste_id)->get();

        $array = [];

        $service = new DBEstoqueService;

        foreach ($estoque as $key => $value) 
        {
            $ajuste_id = $value['ajuste_id'];
            $produto_id = $value['produto_id'];
            $user_id = $service->buscarAjuste($ajuste_id);
            $nome_usuario = $provider_user->buscarUsuario($user_id);
            $nome_produto = $provider_produto->buscarProduto($produto_id)['produto'];
            $quantidade = $value['quantidade'];
            $created_at = $value['created_at'];

            $array[] = ['user_id' => $nome_usuario, 'ajuste_id' => $ajuste_id, 'produto_id' => $nome_produto, 'quantidade' => $quantidade, 'created_at' => $created_at];
        }

        return $array;
    }

    public function buscarAjuste($ajuste_id)
    {
        $estoque = Ajuste::where('id', $ajuste_id)->get();

        return $estoque[0]->user_id;
    }

    public function atualizarEstoque($produto_id, $quantidade, $entrada_ou_saida, $observacao, $provider_entradas_saidas, $pedido_id,  $ajuste_id, $multiplo_id)
    {
        $produto = Produto::find($produto_id);

        $produto->quantidade += $quantidade;

        if($entrada_ou_saida == 'entrada')
            $provider_entradas_saidas->adicionarEntrada($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id);
        else
            $provider_entradas_saidas->adicionarSaida($produto_id, $quantidade,  $observacao, $ajuste_id, $multiplo_id, $pedido_id);

        $produto->save();
    }

    public function buscarEstoque($produto_id)
    {
        $estoque = Entradas_saidas::where('produto_id', $produto_id)->get();

        $total = 0;

        foreach ($estoque as $key => $value) {

            $quantidade = $value['quantidade']; 
            $total += $quantidade;
        }

        return $total;
    }
    
    function pedidoExcluido($pedido_id)
    {
        $estoque = Entradas_saidas::where('pedido_id', $pedido_id)->get();

        foreach ($estoque as $key => $value) {

            $estoque[$key]->delete($pedido_id);
        }

    }

    public function pedidosAprovados($pedido_id)
    {
        $estoque = Entradas_saidas::withTrashed()->where('pedido_id', $pedido_id)->get();

        $situacao = false;

        foreach ($estoque as $key => $value) {
            
            if( isset($value['deleted_at']) )
                $situacao = true;

        }

        return $situacao;
    }
}
