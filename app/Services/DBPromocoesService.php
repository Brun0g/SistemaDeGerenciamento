<?php

namespace App\Services;


use App\Models\Promocoes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


use App\Services\PromocoesServiceInterface;

class DBPromocoesService implements PromocoesServiceInterface
{
	public function adicionarPromocao($produto_id, $quantidade, $porcentagem)
	{
        $promocoes = new Promocoes();

        $promocoes->create_by = Auth::id();
        $promocoes->delete_by = null;
        $promocoes->restored_by = null;
        $promocoes->update_by = null;
        $promocoes->active_by = null;
        $promocoes->deactivate_by = null;
        $promocoes->produto_id = $produto_id;
        $promocoes->quantidade = $quantidade;
        $promocoes->porcentagem = $porcentagem;
   
        $promocoes->ativo = 0;

        $promocoes->save();
	}
    
    public function ativarPromocao($promocoes_id, $situacao)
    {
        $promocoes = Promocoes::find($promocoes_id);

        $promocoes->ativo = $situacao;
        $promocoes->active_by = Auth::id();
        $promocoes->active_at = now();

        $promocoes->save();
    }

    public function desativarPromocao($promocoes_id, $situacao)
    {
        $promocoes = Promocoes::find($promocoes_id);

        $promocoes->ativo = $situacao;
        $promocoes->deactivate_by = Auth::id();
        $promocoes->deactivate_at = now();

        $promocoes->save();
    }

    public function deletarPromocao($promocoes_id)
    {
        $promocoes = Promocoes::find($promocoes_id);

        $promocoes->delete_by = Auth::id();
  
        $promocoes->save();

        $promocoes->delete($promocoes_id);
    }

    public function editarPromocao($promocoes_id, $quantidade, $porcentagem)
    {
        $promocoes = Promocoes::find($promocoes_id);

        $promocoes->update_by = Auth::id();
        $promocoes->quantidade =  $quantidade;
        $promocoes->porcentagem = $porcentagem;

        $promocoes->save();
    }
    
    public function listarPromocoes($softDeletes, $provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $promocoes = Promocoes::all();

        if($softDeletes)
            $promocoes = Promocoes::withTrashed()->get();

        $promocoeslist = [];

        foreach ($promocoes as $promocoes) 
        {
            $produto_id = $promocoes->produto_id;
            $buscar = $provider_produto->buscarProduto($produto_id);
            $porcentagem = $promocoes->porcentagem;
            $quantidade = $promocoes->quantidade;
            $ativo = $promocoes->ativo;
            $deleted_at = $promocoes->deleted_at;
            $deleted_by = $promocoes->delete_by;
            $create_by = $promocoes->create_by;
            $created_at = $promocoes->created_at;
            $restored_by = $promocoes->restored_by;
            $restored_at = $promocoes->restored_at;

            $active_by = $promocoes->active_by;
            $active_at = $promocoes->active_at;

            $deactivate_by = $promocoes->deactivate_by;
            $deactivate_at = $promocoes->deactivate_at;

            $nome_restored_by = $provider_user->buscarNome($restored_by);
            $nome_delete_by = $provider_user->buscarNome($deleted_by);
            $nome_create_by = $provider_user->buscarNome($create_by);

            $nome_active_by = $provider_user->buscarNome($active_by);
            $nome_deactivate_by = $provider_user->buscarNome($deactivate_by);

            $preco_original = $buscar['valor'];
            $preco_desconto = $buscar['valor'] - ($buscar['valor'] / 100 * $porcentagem);
           
            $promocoeslist[$promocoes->id] = ['create_by' => strtoupper($nome_create_by),'restored_by' => strtoupper($nome_restored_by), 'active_by' => strtoupper($nome_active_by), 'deactivate_by' => strtoupper($nome_deactivate_by), 'delete_by' => strtoupper($nome_delete_by), 'produto' => $buscar['produto'], 'produto_id' => $produto_id, 'preco_original' => $preco_original, 'preco_desconto' => $preco_desconto, 'porcentagem' => $porcentagem, 'quantidade' => $quantidade, 'ativo' => $ativo,

            'deleted_at' => isset($deleted_at) ? date_format($deleted_at,"d/m/Y H:i:s") : null, 
            'restored_at' => isset($restored_at) ? date_format($restored_at, "d/m/Y H:i:s") : null,
            'created_at' => isset($created_at) ? date_format($created_at, "d/m/Y H:i:s") : null,
            'active_at' => isset($active_at) ? date_format($active_at, "d/m/Y H:i:s") : null,
            'deactivate_at' => isset($deactivate_at) ? date_format($deactivate_at, "d/m/Y H:i:s") : null

            ];       
        }

        return $promocoeslist;
    }

    public function buscarQuantidade($produto_id, $quantidade)
    {
        $produto = Promocoes::where('produto_id', $produto_id)->get();
        $produto = $produto->toArray();

        $produtoEncontrado = [];
        $quantity = array_column($produto, 'quantidade');
        array_multisort($quantity, SORT_ASC, $produto);

        foreach ($produto as $key => $value) {
            if($produto_id == $value['produto_id'])
            {


                if($value['ativo'] == 1)
                {
                    $id = $value['produto_id'];
                    $porcentagem = $value['porcentagem'];
                    $quantidade_promocao = $value['quantidade'];
                    $ativo = $value['ativo'];

                
                    if($quantidade >= $quantidade_promocao)
                        $produtoEncontrado = ['produto_id' => $id, 'porcentagem' => $porcentagem, 'quantidade' => $quantidade_promocao, 'ativo' => $ativo];
                }
            }
        }

        return $produtoEncontrado;
    }

    public function buscarPromocao($produto_id)
    {
        $produto = Promocoes::where('produto_id', $produto_id)->get();
        $produtoEncontrado = [];
        $ativo = 0;

        foreach ($produto as $key => $value) {
            if($produto_id == $value['produto_id'])
            {
                $porcentagem = $value['porcentagem'];
                $quantidade_promocao = $value['quantidade'];
                
                if($value['ativo'] == 1)
                {
                    $ativo = $value['ativo'];
                   
                    $produtoEncontrado[] = ['produto_id' => $produto_id, 'porcentagem' => $porcentagem, 'quantidade' => $quantidade_promocao, 'ativo' => $ativo];
                }
            }
        }

        return ['promocao' => $produtoEncontrado, 'ativo' => $ativo];
    }

     function restaurarPromocao($promocao_id)
    {
        $promocao = Promocoes::withTrashed()->where('id', $promocao_id)->get()[0];

        $promocao->restored_by = Auth::id();
        $promocao->restored_at = now();
        $promocao->deleted_at = null;
      
        $promocao->save();
    }
}
