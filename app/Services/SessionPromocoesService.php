<?php

namespace App\Services;


use App\Models\promocoes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


use App\Services\PromocoesServiceInterface;

class SessionPromocoesService implements PromocoesServiceInterface
{
	public function adicionarPromocao($produto_id, $quantidade, $porcentagem)
	{
        $promocoes = session()->get('promocoes', []);

        $promocoes[] = ['create_by' => Auth::id(), 'delete_by' => null, 'update_by' => null, 'relocate_by' => null, 'active_by' => null, 'produto_id' => (int)$produto_id, 'porcentagem' => (int)$porcentagem, 'quantidade'=> (int)$quantidade, 'ativo' => 0];

        session()->put('promocoes', $promocoes);
	}
    
    public function ativarPromocao($promocoes_id, $situacao)
    {
        $promocoes = session()->get('promocoes', []);

        foreach ($promocoes as $key => $value) {
            if($promocoes_id == $key)
            {
                $promocoes[$key]['active_by'] =  Auth::id();   
                $promocoes[$key]['ativo'] =  (int)$situacao;   
            }
        }

        session()->put('promocoes', $promocoes);
    }

    public function deletarPromocao($promocoes_id)
    {
        $promocoes = session()->get('promocoes', []);
        $array = [];

        foreach ($promocoes as $key => $value) {
            if($promocoes_id == $key)
            {
                $promocoes[$key]['delete_by'] = Auth::id();
                $promocoes[$key]['deleted_at'] = now();
            }
        }

        session()->put('promocoes', $promocoes);
    }

    public function editarPromocao($promocoes_id, $quantidade, $porcentagem)
    {
        $promocoes = session()->get('promocoes', []);

        foreach ($promocoes as $key => $value) {
            if($promocoes_id == $key)
            {
                $promocoes[$key]['quantidade'] =  (int)$quantidade;
                $promocoes[$key]['porcentagem'] = (int)$porcentagem;
                $promocoes[$key]['update_by'] = Auth::id();
            }
        }

        session()->put('promocoes', $promocoes);
    }
    
    public function listarPromocoes($provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $promocoes = session()->get('promocoes', []);
        $promocoeslist = [];

        foreach ($promocoes as $key => $value) 
        {
            if( !isset($value['deleted_at']) )
            {
            $produto_id = $value['produto_id'];
            $buscar = $provider_produto->buscarProduto($produto_id);
            $porcentagem = $value['porcentagem'];
            $quantidade = $value['quantidade'];
            $ativo = $value['ativo'];

            $preco_original = $buscar['valor'];
            $preco_desconto = $buscar['valor'] - ($buscar['valor'] / 100 * $porcentagem);
           
            return ['produto' => $buscar['produto'], 'produto_id' => $produto_id, 'preco_original' => $preco_original, 'quantidade' => $quantidade, 'preco_desconto' => $preco_desconto, 'porcentagem' => $porcentagem, 'ativo' => $ativo]; 
            }
                  
        }



        return $promocoeslist;
    }

    public function buscarQuantidade($produto_id, $quantidade)
    {
        $produto = session()->get('promocoes', []);
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
        $produto = session()->get('promocoes', []);
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


