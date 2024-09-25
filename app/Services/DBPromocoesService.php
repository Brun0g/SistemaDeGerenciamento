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
        $promocoes->relocate_by = null;
        $promocoes->update_by = null;
        $promocoes->active_by = null;
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
    
    public function listarPromocoes($provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $promocoes = Promocoes::all();
        $promocoeslist = [];

        foreach ($promocoes as $promocoes) 
        {
            $produto_id = $promocoes->produto_id;
            $buscar = $provider_produto->buscarProduto($produto_id);
            $porcentagem = $promocoes->porcentagem;
            $quantidade = $promocoes->quantidade;
            $ativo = $promocoes->ativo;

            $preco_original = $buscar['valor'];
            $preco_desconto = $buscar['valor'] - ($buscar['valor'] / 100 * $porcentagem);
           
            $promocoeslist[$promocoes->id] = ['produto' => $buscar['produto'], 'produto_id' => $produto_id, 'preco_original' => $preco_original, 'preco_desconto' => $preco_desconto, 'porcentagem' => $porcentagem, 'quantidade' => $quantidade, 'ativo' => $ativo];       
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
}
