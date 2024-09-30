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
            'deleted_at' => null,
            'updated_at' => null,
            'restored_at' => null,
            'active_at' => null,
            'deactivate_at' => null
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
            $deleted_at = $value['deleted_at'];
            $deleted_by = $value['delete_by'];
            $create_by = $value['create_by'];
            $created_at = $value['created_at'];
            $restored_by = $value['restored_by'];
            $restored_at = $value['restored_at'];

            $active_by = $value['active_by'];
            $active_at = $value['active_at'];

            $deactivate_by = $value['deactivate_by'];
            $deactivate_at = $value['deactivate_at'];

            $nome_restored_by = $provider_user->buscarNome($restored_by);
            $nome_delete_by = $provider_user->buscarNome($deleted_by);
            $nome_create_by = $provider_user->buscarNome($create_by);

            $nome_active_by = $provider_user->buscarNome($active_by);
            $nome_deactivate_by = $provider_user->buscarNome($deactivate_by);

            $preco_original = $buscar['valor'];
            $preco_desconto = $buscar['valor'] - ($buscar['valor'] / 100 * $porcentagem);
           
            $promocoeslist[$key] = ['create_by' => strtoupper($nome_create_by),'restored_by' => strtoupper($nome_restored_by), 'active_by' => strtoupper($nome_active_by), 'deactivate_by' => strtoupper($nome_deactivate_by), 'delete_by' => strtoupper($nome_delete_by), 'produto' => $buscar['produto'], 'produto_id' => $produto_id, 'preco_original' => $preco_original, 'preco_desconto' => $preco_desconto, 'porcentagem' => $porcentagem, 'quantidade' => $quantidade, 'ativo' => $ativo,

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
                if($promocao_id == $key){
                    $promocao[$key]['restored_by'] = Auth::id();
                    $promocao[$key]['restored_at'] = now();
                    $promocao[$key]['deleted_at'] = null;
                }
        }

        session()->put('promocoes', $promocao);
    }
}


