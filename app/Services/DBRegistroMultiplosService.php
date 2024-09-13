<?php

namespace App\Services;


use App\Models\Ajuste;
use App\Models\MultiplaEntradas;
use App\Models\AjusteIndividuais;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


use App\Services\RegistroMultiplosServiceInterface;

class DBRegistroMultiplosService implements RegistroMultiplosServiceInterface
{
    public function adicionarAjuste()
    {
        $registro = new Ajuste();

        $registro->user_id = Auth::id();
 

        $registro->save();

        return $registro->id;
    }

    public function adicionarMultiplos()
    {
        $registro = new MultiplaEntradas();

        $registro->user_id = Auth::id();

        $registro->save();

        return $registro->id;
    }

    public function adicionarAjusteIndividuais($ajuste_id, $produto_id, $quantidade)
    {
        $registro = new AjusteIndividuais();

   
        $registro->produto_id = $produto_id;
        $registro->quantidade = $quantidade;
        $registro->ajuste_id = $ajuste_id;

        $registro->save();

        return $registro->id;
    }

    public function listarAjuste($ajuste_id, $provider_user, $provider_produto)
    {
        $registro = AjusteIndividuais::where('ajuste_id', $ajuste_id)->get();

        $array = [];

        $service = new DBRegistroMultiplosService;

        foreach ($registro as $key => $value) 
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
        $registro = Ajuste::where('id', $ajuste_id)->get();

        return $registro[0]->user_id;
    }
}
