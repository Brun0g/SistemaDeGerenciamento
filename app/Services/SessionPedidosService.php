<?php

namespace App\Services;

use App\Services\PedidosServiceInterface;
use \App\Services\SessionProdutosService;
use \App\Services\SessionEstoqueService;
use App\Models\Pedido;
use App\Models\Pedidos_finalizados;

use \App\Services\DBUserService;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SessionPedidosService implements PedidosServiceInterface
{
    public function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total)
    {
        $Pedido_encerrado = session()->get('Pedido_encerrado', []);
        $index = count($Pedido_encerrado);
        $pedido_id = $index + 1;
        
        $Pedido_encerrado[$pedido_id] = [
            'create_by' => Auth::id(), 
            'delete_by' => null, 
            'restored_by' => null, 
            'pedido_id' => $pedido_id, 
            'cliente_id' => $cliente_id, 
            'endereco_id' => $endereco_id, 
            'total' => $valor_final, 
            'porcentagem' => $porcentagem, 
            'totalSemDesconto' => $valor_total, 
            'created_at' => now(), 
            'deleted_at' => null, 
            'restored_at' => null
        ];

        session()->put('Pedido_encerrado', $Pedido_encerrado);

        return $pedido_id;
    }

    function salvarItemPedido($pedido_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade)
    {
        $Pedido_encerrado_individual = session()->get('Pedido_encerrado_individual', []);
        $Pedido_encerrado = session()->get('Pedido_encerrado', []);

        $count = sizeof($Pedido_encerrado);
    
        $Pedido_encerrado_individual[] = [
            'create_by' => Auth::id(), 
            'delete_by' => null, 
            'restored_by' => null, 
            'pedido_id' => $pedido_id, 
            'produto_id' => $produto_id,
            'quantidade' => $quantidade, 
            'porcentagem' => $porcentagem_unidade, 
            'total' => $valor_final, 
            'preco_unidade' => $preco_unidade, 
            'totalSemDesconto' => $valor_total, 
            'created_at' => now(), 
            'deleted_at' => null, 
            'restored_at' => null
        ];

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

    public function listarQuantidadePedidos($cliente, $data_inicial, $data_final, $provider_estoque, $provider_user)
    {
        $service_pedidos = new SessionPedidosService();
        $service_clientes = new SessionClientesService();

        $pedidos  = session()->get('Pedido_encerrado_individual', []);

        $pedidos_por_data = [];
      

        foreach ($pedidos  as $key => $value) {

            $pedido_id = $value['pedido_id'];
            $cliente_id = $service_pedidos->buscarPedido($pedido_id)['cliente_id'];
            $nome = $service_clientes->buscarCliente($cliente_id)['name'];
            $created_at = $value['created_at'];
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];

            if($data_inicial && $data_inicial && $created_at->toDateString() >= $data_inicial && $created_at->toDateString() <= $data_final)
                $pedidos_por_data[$key] = ['nome' => $nome, 'cliente_id' => $cliente_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'created_at' => $created_at];

        } 

     
        return $pedidos_por_data; 
    }

    public function listarPedidos($cliente_id, $provider_estoque, $provider_user, $data_inicial, $data_final, $pagina_atual)
    {
        $array = [];

        $pedidos = session()->get('Pedido_encerrado',[]);

        $service_produtos = new SessionProdutosService();

        foreach ($pedidos as $key => $value) {

                $buscar = false;
                $pedido_id = $key;
                
                $nome_create = $pedidos[$key]['create_by'];
                $nome_create = $provider_user->buscarNome($nome_create);

                $nome_delete = $pedidos[$key]['delete_by'];
                $nome_delete = $provider_user->buscarNome($nome_delete);

                $nome_restored = $pedidos[$key]['restored_by'];
                $nome_restored = $provider_user->buscarNome($nome_restored);

                $id_cliente = $pedidos[$key]['cliente_id'];
                $endereco = $pedidos[$key]['endereco_id'];
                $total = $pedidos[$key]['total'];
                $porcentagem = $pedidos[$key]['porcentagem'];

                $deleted_at = null;
                $created_at = $pedidos[$key]['created_at'];
                $restored_at = isset($pedidos[$key]['restored_at']) ? date_format($pedidos[$key]['restored_at'], "d/m/Y H:i:s") : null;

                if (isset($cliente_id) && $id_cliente == $value['cliente_id'] && $value['deleted_at'] == null)
                    $buscar = true;
                elseif($data_inicial && $data_inicial && $created_at->toDateString() >= $data_inicial && $created_at->toDateString() <= $data_final && $value['deleted_at'] == null)
                    $buscar = true;
                
                if($buscar)
                {
                        $array[$pedido_id] = [
                        'create_by' => $nome_create,
                        'delete_by' => $nome_delete,
                        'restored_by' => $nome_restored,
                        'cliente_id' => $id_cliente,
                        'endereco' => $endereco,
                        'total' => $total,
                        'porcentagem' => $porcentagem,
                        'ano' => $created_at->year,
                        'dia_do_ano' => $created_at->dayOfYear,
                        'dia_da_semana' => $created_at->dayOfWeek,
                        'hora' => $created_at->hour,
                        'minuto' => $created_at->minute,
                        'segundo' => $created_at->second,
                        'mes' => $created_at->month,
                        'created_at' => isset($pedidos[$key]['created_at']) ? date_format($pedidos[$key]['created_at'], "d/m/Y H:i:s") : null,
                        'restored_at' => $restored_at,
                        'deleted_at' =>  null,
                        'pedido_id' => $pedido_id
                    ]; 
                }

                
        }

        $numero_paginas = 0;

        if($data_inicial && $data_final)
        {
            $row_limit = 5;
            $row = sizeof($array); 
            $pagina_atual = $pagina_atual * $row_limit;
            $array = array_slice($array, $pagina_atual, $row_limit); 

            $numero_paginas = ceil($row / $row_limit - 1);
        }
  
        return ['array' => $array, 'page'=> $numero_paginas];
    }

     public function listarPedidosExcluidos($provider_user, $data_inicial, $data_final, $pagina_atual)
    {
        $array = [];

        $pedidos = session()->get('Pedido_encerrado',[]);
        
        foreach ($pedidos as $key => $value) {

        $buscar_por_data = false;

        if($value['deleted_at'] != null)
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
            

            if($data_inicial && $data_inicial && $created_at->toDateString() >= $data_inicial && $created_at->toDateString() <= $data_final)
                $buscar_por_data = true;

                if($buscar_por_data)
                {
                    $created_at = isset($value['created_at']) ? date_format($value['created_at'], "d/m/Y H:i:s") : null;
                    $restored_at = isset($value['restored_at']) ? date_format($value['restored_at'], "d/m/Y H:i:s") : null;

                    $array[$pedido_id] = [
                        'create_by' => $nome_create, 
                        'delete_by' => $nome_delete, 
                        'restored_by' => $nome_restored, 
                        'cliente_id' => $id_cliente, 
                        'endereco' => $endereco, 
                        'total' => $total, 
                        'porcentagem' => $porcentagem, 
                        'ano' => $deleted_at->year, 
                        'dia_do_ano' => $deleted_at->dayOfYear, 
                        'dia_da_semana' => $deleted_at->dayOfWeek, 
                        'hora' => $deleted_at->hour, 
                        'minuto' => $deleted_at->minute, 
                        'segundo' => $deleted_at->second, 
                        'mes' => $deleted_at->month,
                        'created_at' => $created_at, 
                        'restored_at' => $restored_at,
                        'deleted_at' => isset($value['deleted_at']) ? date_format($value['deleted_at'], "d/m/Y H:i:s") : null,
                        'pedido_id' => $pedido_id
                    ]; 
                }
                

            }
        }


        $numero_paginas = 0;

        if($data_inicial && $data_final)
        {
            $row_limit = 5;
            $row = sizeof($array); 
            $pagina_atual = $pagina_atual * $row_limit;
            $array = array_slice($array, $pagina_atual, $row_limit); 
            $numero_paginas = ceil($row / $row_limit - 1);

        }
        
        return ['array' => $array, 'page'=> $numero_paginas];
    }

    public function buscarPedido($pedido_id)
    {
        $pedidoEncontrado = [];
        $pedidos = session()->get('Pedido_encerrado')[$pedido_id]; 

        $provider_user = new DBUserService;

        foreach ($pedidos as $pedido) {

            $cliente_id = $pedidos['cliente_id'];
            $endereco_id = $pedidos['endereco_id'];
            $total = $pedidos['total'];
            $totalSemDesconto = $pedidos['totalSemDesconto'];
            $porcentagem = $pedidos['porcentagem'];
            $created_at = $pedidos['created_at'];
            $create_by = $pedidos['create_by'];
            $nome_create_by = $provider_user->buscarNome($create_by);

            $deleted_at = isset($pedidos['deleted_at']) ? date_format($pedidos['deleted_at'],"d/m/Y H:i:s") : null;
            $created_at = isset($pedidos['created_at']) ? date_format($pedidos['created_at'], "d/m/Y H:i:s") : null;
            $restored_at = isset($pedidos['restored_at']) ? date_format($pedidos['restored_at'], "d/m/Y H:i:s") : null;

            $pedidoEncontrado = [
                'create_by' => $nome_create_by, 
                'cliente_id' => $cliente_id, 
                'endereco_id' => $endereco_id, 
                'total' => $total, 
                'totalSemDesconto' => $totalSemDesconto, 
                'porcentagem' => $porcentagem,
                'created_at' => $created_at, 
                'restored_at' => $restored_at,
                'deleted_at' => $deleted_at
            ];
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
