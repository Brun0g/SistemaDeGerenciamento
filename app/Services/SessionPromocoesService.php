<?php

namespace App\Services;


use App\Models\promocoes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use \App\Services\DBUserService;



use App\Services\PromocoesServiceInterface;

class SessionPromocoesService implements PromocoesServiceInterface
{
	public function adicionarPromocao($produto_id, $quantidade, $porcentagem)
	{
        $promocoes = session()->get('promocoes', []);

        $promocoes[] = [
            'create_by' => Auth::id(), 
            'delete_by' => null, 
            'update_by' => null, 
            'restored_by' => null, 
            'active_by' => null, 
            'deactivate_by' => null, 
            'produto_id' => (int)$produto_id, 
            'porcentagem' => (int)$porcentagem, 
            'quantidade'=> (int)$quantidade, 
            'ativo' => 0,
            'created_at' => now(), 
            'deleted_at' => null
            'updated_at' => null
            'restored_at' => null
        ];

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
                $promocoes[$key]['active_at'] =  now();   
            }
        }

        session()->put('promocoes', $promocoes);
    }

    public function desativarPromocao($promocoes_id, $situacao)
    {
        $promocoes = session()->get('promocoes', []);

        foreach ($promocoes as $key => $value) {
            if($promocoes_id == $key)
            {
                $promocoes[$key]['deactivate_by'] =  Auth::id();   
                $promocoes[$key]['ativo'] =  (int)$situacao;   
                $promocoes[$key]['deactivate_at'] = now();   
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
    
    public function listarPromocoes($softDeletes, $provider_produto, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $promocoes = session()->get('promocoes', []);
        $promocoeslist = [];

        $provider_user = new DBUserService();

        foreach ($promocoes as $key => $value) 
        {
            
            $produto_id = $value['produto_id'];
            $buscar = $provider_produto->buscarProduto($produto_id);
            $porcentagem = $value['porcentagem'];
            $quantidade = $value['quantidade'];
            $ativo = $value['ativo'];

            $create_by = $value['create_by'];
            $created_at = $value['created_at'];
            $deleted_at = $value['deleted_at'];
            $delete_by = $value['delete_by'];

            $nome_create = $provider_user->buscarNome($create_by);
            $nome_delete = $provider_user->buscarNome($delete_by);
            
            $preco_original = $buscar['valor'];
            $preco_desconto = $buscar['valor'] - ($buscar['valor'] / 100 * $porcentagem);
           
            $promocoeslist[] = ['produto' => $buscar['produto'], 'produto_id' => $produto_id, 'preco_original' => $preco_original, 'quantidade' => $quantidade, 'preco_desconto' => $preco_desconto, 'porcentagem' => $porcentagem, 'ativo' => $ativo, 'delete_by' => strtoupper($nome_delete), 'deleted_at' => $deleted_at, 'create_by' => strtoupper($nome_create), 'created_at' => $created_at]; 
           
                  
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

    function restaurarPromocao($promocao_id)
    {
        $promocao = session()->get('promocoes', []);

        foreach ($promocao as $key => $value) {
                if($promocao_id == $key)
                    $promocao[$key]['deleted_at'] = null;
        }

        session()->put('promocoes', $promocao);
    }
}


