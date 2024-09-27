<?php

namespace App\Services;

use App\Services\PedidosServiceInterface;
use \App\Services\SessionProdutosService;
use \App\Services\SessionEstoqueService;
use App\Models\Pedido;
use App\Models\Pedidos_finalizados;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SessionPedidosService implements PedidosServiceInterface
{
    public function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total)
    {
        $Pedido_encerrado = session()->get('Pedido_encerrado', []);
        $index = count($Pedido_encerrado);
        $pedido_id = $index + 1;
        
        $Pedido_encerrado[$pedido_id] = ['create_by' => Auth::id(), 'delete_by' => null, 'restored_by' => null, 'pedido_id' => $pedido_id, 'cliente_id' => $cliente_id, 'endereco_id' => $endereco_id, 'total' => $valor_final, 'porcentagem' => $porcentagem, 'totalSemDesconto' => $valor_total, 'excluido' => 0, 'created_at' => now(), 'deleted_at' => null, 'restored_at' => null];

        session()->put('Pedido_encerrado', $Pedido_encerrado);

        return $pedido_id;
    }

    function salvarItemPedido($pedido_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade)
    {
        $Pedido_encerrado_individual = session()->get('Pedido_encerrado_individual', []);
        $Pedido_encerrado = session()->get('Pedido_encerrado', []);

        $count = sizeof($Pedido_encerrado);
    
        $Pedido_encerrado_individual[] = ['create_by' => Auth::id(), 'delete_by' => null, 'restored_by' => null, 'pedido_id' => $pedido_id, 'produto_id' => $produto_id,'quantidade' => $quantidade, 'porcentagem' => $porcentagem_unidade, 'total' => $valor_final, 'preco_unidade' => $preco_unidade, 'totalSemDesconto' => $valor_total, 'created_at' => now(), 'deleted_at' => null, 'restored_at' => null];


        session()->put('Pedido_encerrado_individual', $Pedido_encerrado_individual);

        return $count;
    }

    public function excluirPedido($pedido_id, $provider_entradas_saidas)
    {
        $pedido_geral = session()->get('Pedido_encerrado',[]);

        $provider_pedidos = new SessionPedidosService();
            
        foreach ($pedido_geral as $key => $value) {
            if($pedido_id == $key)
            {
                $pedido_geral[$key]['delete_by'] = Auth::id();
                $pedido_geral[$key]['deleted_at'] = now();
            }
        }

        session()->put('Pedido_encerrado', $pedido_geral);

        $provider_pedidos->excluirPedidoIndividual($pedido_id);
        $provider_entradas_saidas->deletarSaida($pedido_id);
    }

    public function excluirPedidoIndividual($pedido_id)
    {
        $pedidos_individual = session()->get('Pedido_encerrado_individual', []);

        foreach ($pedidos_individual as $key => $value) {
            if($pedido_id == $value['pedido_id']){
                $pedidos_individual[$key]['delete_by'] = Auth::id();
                $pedidos_individual[$key]['deleted_at'] = now();
            }
        }

        session()->put('Pedido_encerrado_individual', $pedidos_individual);
    }

    public function RestaurarPedido($pedido_id, $provider_entradas_saidas)
    {
        $pedido_geral = session()->get('Pedido_encerrado',[]);

        $provider_pedidos = new SessionPedidosService();

        foreach ($pedido_geral as $key => $value) {
            if($pedido_id == $key){
                $pedido_geral[$key]['restored_by'] = Auth::id();
                $pedido_geral[$key]['restored_at'] = now();
                $pedido_geral[$key]['deleted_at'] = null;
            }
        }

        session()->put('Pedido_encerrado', $pedido_geral);

        $provider_pedidos->RestaurarPedidoIndividual($pedido_id);
        $provider_entradas_saidas->RestaurarSaida($pedido_id);
    }

    public function RestaurarPedidoIndividual($pedido_id)
    {
        $pedidos_individual = session()->get('Pedido_encerrado_individual', []);

        foreach ($pedidos_individual as $key => $value) {
            if($pedido_id == $value['pedido_id'])
            {
                $pedidos_individual[$key]['restored_by'] = Auth::id();
                $pedidos_individual[$key]['restored_at'] = now();
                $pedidos_individual[$key]['deleted_at'] = null;
            }
        }

        session()->put('Pedido_encerrado_individual', $pedidos_individual);
    }

    public function listarQuantidadePedidos()
    {
        $pedidosPorClientes = session()->get('Pedido_encerrado_individual', []);
        
        $service_pedidos = new SessionPedidosService();

        foreach ($pedidosPorClientes as $pedidoKey => $value) {
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];
            $pedido_id = $value['pedido_id'];
            $cliente_id = $service_pedidos->buscarPedido($pedido_id)['cliente_id'];
            

            $pedidosPorClientes[$pedidoKey] = ['cliente_id' => $cliente_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade]; 
        } 

        return $pedidosPorClientes; 
    }

    public function listarPedidos($cliente_id, $provider_estoque, $provider_user)
    {
        $listaPedidos = [];
        $pedidos = session()->get('Pedido_encerrado',[]);

        $service_produtos = new SessionProdutosService();

        foreach ($pedidos as $key => $value) {
            if($cliente_id == $value['cliente_id'])
            {
                $pedido_id = $value['pedido_id'];
                
                $nome_create = $value['create_by'];
                $nome_create = $provider_user->buscarNome($nome_create);

                $nome_delete = $value['delete_by'];
                $nome_delete = $provider_user->buscarNome($nome_delete);

                $nome_restored = $value['restored_by'];
                $nome_restored = $provider_user->buscarNome($nome_restored);

                $id_cliente = $value['cliente_id'];
                $endereco = $value['endereco_id'];
                $total = $value['total'];
                $porcentagem = $value['porcentagem'];
                // $data = $value['deleted_at'];
                $created_at = $value['created_at']; 
                $deleted_at = $value['deleted_at']; 
                $restored_at = $value['restored_at'];
                $excluido = $value['excluido'];
                $listaPedidos[$pedido_id] = ['create_by' => $nome_create, 'delete_by' => $nome_delete, 'restored_by' => $nome_restored, 'cliente_id' => $id_cliente, 'endereco' => $endereco, 'total' => $total, 'porcentagem' => $porcentagem, 'excluido' => $excluido,

                'created_at' => date_format($created_at,"d/m/Y H:i:s"),
                'delete_at' => isset($deleted_at) ? date_format($deleted_at,"d/m/Y H:i:s") : null,
                'restored_at' => isset($restored_at) ? date_format($restored_at, "d/m/Y H:i:s") : null
            ];  
                
            }
        }
     
        return $listaPedidos;
    }

    public function listarPedidosExcluidos($provider_user)
    {
        $array = [];

        $pedidos = session()->get('Pedido_encerrado',[]);


        foreach ($pedidos as $key => $value) {

            if( isset($value['deleted_at']) )
            {
                $pedido_id = $key;
        
                $nome_create = $value['create_by'];
                $nome_create = $provider_user->buscarNome($nome_create);

                $nome_delete = $value['delete_by'];
                $nome_delete = $provider_user->buscarNome($nome_delete);

                $nome_restored = $value['restored_by'];
                $nome_restored = $provider_user->buscarNome($nome_restored);

                $id_cliente = $value['cliente_id'];
                $endereco = $value['endereco_id'];
                $total = $value['total'];
                $porcentagem = $value['porcentagem'];
                $deleted_at = $value['deleted_at']; 
                $created_at = $value['created_at']; 

                $array[$pedido_id] = ['create_by' => $nome_create, 'delete_by' => $nome_delete, 'restored_by' => $nome_restored, 'cliente_id' => $id_cliente, 'endereco' => $endereco, 'total' => $total, 'porcentagem' => $porcentagem, 'ano' => $deleted_at->year, 'dia_do_ano' => $deleted_at->dayOfYear, 'dia_da_semana' => $deleted_at->dayOfWeek, 'hora' => $deleted_at->hour, 'minuto' => $deleted_at->minute, 'segundo' => $deleted_at->second, 'mes' => $deleted_at->month,
                  
                    'created_at' => date_format($created_at,"d/m/Y H:i:s"),
                    'deleted_at' => isset($deleted_at) ? date_format($deleted_at,"d/m/Y H:i:s") : null,
                    'restored_at' => isset($restored_at) ? date_format($restored_at, "d/m/Y H:i:s") : null
                ]; 
            }  
        }

        return $array;
    }

    public function buscarPedido($pedido_id)
    {
        $pedidoEncontrado = [];
        $pedidos = session()->get('Pedido_encerrado')[$pedido_id]; 

        foreach ($pedidos as $pedido) {
           
            $cliente_id = $pedidos['cliente_id'];
            $endereco_id = $pedidos['endereco_id'];
            $total = $pedidos['total'];
            $totalSemDesconto = $pedidos['totalSemDesconto'];
            $porcentagem = $pedidos['porcentagem'];
        
            $pedidoEncontrado = ['cliente_id' => $cliente_id, 'endereco_id' => $endereco_id, 'total' => $total,'porcentagem' => $porcentagem, 'totalSemDesconto' => $totalSemDesconto];
          
        }

        return $pedidoEncontrado;
    }

    public function buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $Pedido_encerrado_individual = session()->get('Pedido_encerrado_individual', []);
        $service_produtos = new SessionProdutosService();
        $total = 0;

        foreach ($Pedido_encerrado_individual as $pedidoKey => $pedido) 
        {
            if($pedido['pedido_id'] == $pedido_id)
            {
                $pedido_id = $pedido['pedido_id'];
                $produto_id = $pedido['produto_id'];
                $quantidade = $pedido['quantidade'];
                $porcentagem = $pedido['porcentagem'];
                $valor = $pedido['total'];
                $produto = $service_produtos->buscarProduto($produto_id)['produto'];
                $preco_unidade = $pedido['preco_unidade'];

                $total += $valor;

    
            $lista[$pedidoKey] = ['produto_id' => $produto_id, 'produto' => $produto, 'pedido_id' => $pedido_id, 'quantidade' => $quantidade, 'total' => $valor, 'preco_unidade' => $preco_unidade, 'porcentagem' => $porcentagem, 'totalComDesconto' => $total];  
            } 
        }

        return $lista; 
    }

    public function reativarPedido($pedido_id)
    {
        $pedidos = session()->get('Pedido_encerrado_individual', []);
        
        $service = new SessionEstoqueService;

        foreach ($pedidos as $key => $value) {
            if($pedido_id == $value['pedido_id'])
            {
                $produto_id = $value['produto_id'];
                $estoque_atual = $service->buscarEstoque($produto_id);
                $quantidade_pedido = $value['quantidade'];
            
                if($estoque_atual < $quantidade_pedido)
                    return false;
            }
        }

        return true;
    }
}
