<?php

namespace App\Services;


use App\Models\Registro_multiplos;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


use App\Services\RegistroMultiplosServiceInterface;

class SessionRegistroMultiplosService implements RegistroMultiplosServiceInterface
{
    public function adicionarAjuste()
    {
        $registro = session()->get('Ajustes', []);

        $registro[] = ['user_id' => Auth::id()];

        session()->put('Ajustes', $registro);

        $registro_id = sizeof($registro);

        return $registro_id;
    }
    
    public function adicionarMultiplos()
    {
        $registro = session()->get('Multiplos', []);

        $registro[] = ['user_id' => Auth::id()];

        session()->put('Multiplos', $registro);

        $registro_id = sizeof($registro);

        return $registro_id;
    }

    public function adicionarAjusteIndividuais($ajuste_id, $produto_id, $quantidade)
    {
        $registro = session()->get('AjustesIndividuais', []);

        $registro[] = ['user_id' => Auth::id(), 'ajuste_id' => $ajuste_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'created_at' => now()];

        session()->put('AjustesIndividuais', $registro);
    }

    public function listarAjuste($ajuste_id, $provider_user, $provider_produto)
    {
        $registro = session()->get('AjustesIndividuais', []);

        $array = [];

        foreach ($registro as $key => $value) {
            
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
}
