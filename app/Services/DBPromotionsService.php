<?php

namespace App\Services;


use App\Models\Promotion;
use Illuminate\Support\Collection;



use App\Services\PromotionsServiceInterface;

class DBPromotionsService implements PromotionsServiceInterface
{
	public function adicionarPromocao($produto_id, $quantidade, $porcentagem)
	{
        $promotion = new Promotion();

        $promotion->produto_id = $produto_id;
        $promotion->porcentagem = $porcentagem;
        $promotion->quantidade = $quantidade;
        $promotion->ativo = 0;

        $promotion->save();
	}
    
    public function ativarPromocao($promotion_id, $situacao)
    {
        $promotion = Promotion::find($promotion_id);

        $promotion->ativo = $situacao;
        $promotion->save();
    }

    public function deletarPromocao($promotion_id)
    {
        $promotion = Promotion::find($promotion_id);

        $promotion->delete($promotion_id);
    }

    public function editarPromocao($promotion_id, $quantidade, $porcentagem)
    {
        $promotion = Promotion::find($promotion_id);

        $promotion->quantidade =  $quantidade;
        $promotion->porcentagem = $porcentagem;

        $promotion->save();
    }
    
    public function listarPromocoes($provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $promotions = Promotion::all();
        $Promotionslist = [];

        foreach ($promotions as $promotion) 
        {
            $produto_id = $promotion->produto_id;
            $buscar = $provider_produto->buscarProduto($produto_id);
            $porcentagem = $promotion->porcentagem;
            $quantidade = $promotion->quantidade;
            $ativo = $promotion->ativo;

            $preco_original = $buscar['valor'];
            $preco_desconto = $buscar['valor'] - ($buscar['valor'] / 100 * $porcentagem);
           
            $Promotionslist[$promotion->id] = ['produto' => $buscar['produto'], 'produto_id' => $produto_id, 'preco_original' => $preco_original, 'preco_desconto' => $preco_desconto, 'porcentagem' => $porcentagem, 'quantidade' => $quantidade, 'ativo' => $ativo];       
        }

        return $Promotionslist;
    }

    public function buscarQuantidade($produto_id, $quantidade)
    {
        $produto = Promotion::where('produto_id', $produto_id)->get();
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
        $produto = Promotion::where('produto_id', $produto_id)->get();
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
