<?php

namespace App\Services;


use App\Models\Promotion;



use App\Services\PromotionsServiceInterface;

class SessionPromotionsService implements PromotionsServiceInterface
{
	public function adicionarPromocao($produto_id, $quantidade, $porcentagem)
	{
        $promotion = session()->get('promotions', []);

        $promotion[] = ['produto_id' => (int)$produto_id, 'porcentagem' => (int)$porcentagem, 'quantidade'=> (int)$quantidade, 'ativo' => 0];

        session()->put('promotions', $promotion);
	}
    
    public function ativarPromocao($promotion_id, $situacao)
    {
        $promotions = session()->get('promotions', []);

        foreach ($promotions as $key => $value) {
            if($promotion_id == $key)
                $promotions[$key]['ativo'] =  (int)$situacao;   
        }

        session()->put('promotions', $promotions);
    }

    public function deletarPromocao($promotion_id)
    {
        $promotions = session()->get('promotions', []);
        $array = [];

        foreach ($promotions as $key => $value) {
            if($promotion_id == $key)
                unset($promotions[$key]);
        }

        session()->put('promotions', $promotions);
    }

    public function editarPromocao($promotion_id, $quantidade, $porcentagem)
    {
        $promotions = session()->get('promotions', []);

        foreach ($promotions as $key => $value) {
            if($promotion_id == $key)
            {
                $promotions[$key]['quantidade'] =  (int)$quantidade;
                $promotions[$key]['porcentagem'] = (int)$porcentagem;

            }
        }

        session()->put('promotions', $promotions);
    }
    
    public function listarPromocoes($provider_produto, $provider_entradas, $provider_saida, $provider_user, $provider_pedidos)
    {
        $promotions = session()->get('promotions', []);
        $Promotionslist = [];

        foreach ($promotions as $key => $value) 
        {
            $produto_id = $value['produto_id'];
            $buscar = $provider_produto->buscarProduto($produto_id);
            $porcentagem = $value['porcentagem'];
            $quantidade = $value['quantidade'];
            $ativo = $value['ativo'];

            $preco_original = $buscar['valor'];
            $preco_desconto = $buscar['valor'] - ($buscar['valor'] / 100 * $porcentagem);
           
            $Promotionslist[$key] = ['produto' => $buscar['produto'], 'produto_id' => $produto_id, 'preco_original' => $preco_original, 'quantidade' => $quantidade, 'preco_desconto' => $preco_desconto, 'porcentagem' => $porcentagem, 'ativo' => $ativo];       
        }



        return $Promotionslist;
    }

    public function buscarQuantidade($produto_id, $quantidade)
    {
        $produto = session()->get('promotions', []);
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
        $produto = session()->get('promotions', []);
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


