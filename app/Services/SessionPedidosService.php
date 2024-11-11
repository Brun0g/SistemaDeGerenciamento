<?php

namespace App\Services;

use App\Services\PedidosServiceInterface;
use \App\Services\SessionProdutosService;
use \App\Services\SessionEstoqueService;
use \App\Services\SessionClientesService;

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

    public function listarPedidos($search, $cliente_id, $data_inicial, $data_final, $pagina_atual, $order_by, $escolha, $maximo, $minimo, $categoria_id, $quantidade_maxima, $quantidade_minima, $provider_user)
    {
        $array = [];
        $valores = [];

        $pedidos = session()->get('Pedido_encerrado',[]);

        $provider_produto = new SessionProdutosService();
        $provider_clientes = new SessionClientesService();
        $provider_pedidos = new SessionPedidosService();

        $maximo = !$maximo ? 0 : (int)$maximo;
        $minimo = !$minimo ? 0 : (int)$minimo;

        $quantidade_maxima = !$quantidade_maxima ? 0 : (int)$quantidade_maxima;
        $quantidade_minima = !$quantidade_minima ? 0 : (int)$quantidade_minima;

        $filtro_min_max = $maximo > 0 || $minimo > 0 ? true : null;
        $array_pedidos = array_column($pedidos, 'total', 'pedido_id');
        $max_valor =  sizeof($pedidos) > 0 ? max($array_pedidos) : null;

         $valores = ['max' => $maximo, 'min' => $minimo, 'quantidade_max' => $quantidade_maxima, 'quantidade_min' => $quantidade_minima, 'max_valor' => $max_valor, 'max_quantidade' => $max_quantidade];
         
        $escolha = !$escolha ? 1 : $escolha;

        if($minimo > $maximo)
            $maximo = $max_valor;

        $filtro_categoria = null;

        foreach ($pedidos as $key => $value) {

            $buscar = false;
            $pedido_id = $key;

            $nome_create = $value['create_by'];
            $nome_create = $provider_user->buscarNome($nome_create);

            $nome_delete = $value['delete_by'];
            $nome_delete = $provider_user->buscarNome($nome_delete);

            $nome_restored = $value['restored_by'];
            $nome_restored = $provider_user->buscarNome($nome_restored);

            $endereco = $value['endereco_id'];
            $total = $value['total'];

            $porcentagem = $value['porcentagem'];

            $created_at = $value['created_at'];
            $deleted_at = $value['deleted_at'];

            $restored_at = isset($value['restored_at']) ? date_format($value['restored_at'], "d/m/Y H:i:s") : null;

            $nome_do_cliente = $provider_clientes->buscarCliente($value['cliente_id'])['name'];

            $filtro_data_inicial_final = isset($data_inicial, $data_final) ? $created_at->toDateString() >= $data_inicial && $created_at->toDateString() <= $data_final : null;
            $filtro_carbon = $value['created_at'];
            $filtro_not_trashed = $escolha == 1 && $deleted_at == null;
            $filtro_trashed = $escolha == 2 && $deleted_at != null;
            $filtro_search = stripos($nome_do_cliente, $search) === 0;

            if(isset($categoria_id))
            {
                $ped[$key] = $provider_pedidos->buscarItemPedido($key);

                foreach ($ped as $pe) 
                {
                    foreach ($pe as $key_pedido => $val_ped) 
                    {
                        $arr[$key_pedido] = $val_ped;
                        $produto_id = $arr[$key_pedido]['produto_id'];
                        $id_categoria = $provider_produto->buscarProduto($produto_id)['categoria'];

                        if($id_categoria == $categoria_id)
                            $filtro_categoria = true;
                        else
                            $filtro_categoria = null;
                    }
                }
            }

            if($filtro_data_inicial_final)
            {
                if(
                    !$filtro_min_max && !$search && $filtro_not_trashed && $filtro_categoria
                    || $filtro_search && $filtro_not_trashed 
                    || $filtro_not_trashed && $total >= $minimo && $total <= $maximo )
                    $buscar = true;
                elseif(
                    !$filtro_min_max && !$search && $filtro_trashed 
                    || $filtro_search && $filtro_trashed 
                    || $filtro_trashed && $total >= $minimo && $total <= $maximo)
                {
                    $buscar = true;
                    $filtro_carbon = $value['deleted_at'];
                }

            } else
                $buscar = true;


            if($buscar)
            {
                $array[$pedido_id] = [
                    'create_by' => $nome_create,
                    'delete_by' => $nome_delete,
                    'restored_by' => $nome_restored,
                    'cliente_id' => $value['cliente_id'],
                    'endereco' => $endereco,
                    'total' => $total,
                    'porcentagem' => $porcentagem,
                    'nome_cliente' => $nome_do_cliente,
                    
                    'ano' => $filtro_carbon->year,
                    'dia_do_ano' => $filtro_carbon->dayOfYear,
                    'dia_da_semana' => $filtro_carbon->dayOfWeek,
                    'hora' => $filtro_carbon->hour,
                    'minuto' => $filtro_carbon->minute,
                    'segundo' => $filtro_carbon->second,
                    'mes' => $filtro_carbon->month,

                    'created_at' => isset($value['created_at']) ? date_format($value['created_at'], "d/m/Y H:i:s") : null,
                    'restored_at' => $restored_at,
                    'deleted_at' =>  isset($value['deleted_at']) ? date_format($value['deleted_at'], "d/m/Y H:i:s") : null,
                    'pedido_id' => $pedido_id
                ]; 
            }  
        }

        $total_paginas = 0;


        if($order_by)
        {
            $key = key($order_by);

            $order = $order_by[$key] == 0 ? 'asc' : 'desc';

            if($key == 'id')
                $key = 'pedido_id';
            
            $sort = array_column($array, $key);

            if($order == 'asc')
                array_multisort($sort, SORT_ASC, $array);
            else
                array_multisort($sort, SORT_DESC, $array);
        }

        if($data_inicial && $data_final)
        {
            $row_limit = 5;
            $row = sizeof($array);
            $pagina_atual = $pagina_atual * $row_limit;
            $array = array_slice($array, $pagina_atual, $row_limit);
            $total_paginas = ceil($row / $row_limit - 1);

        }


        
        return [ 'array' => $array, 'total_paginas'=> $total_paginas, 'maximo_minimo' => $valores];
    }

    public function buscarPedido($pedido_id)
    {
        $pedidoEncontrado = [];
        $pedidos = session()->get('Pedido_encerrado')[$pedido_id]; 

        $provider_user = new DBUserService;
        $provider_clientes = new SessionClientesService;

        foreach ($pedidos as $key => $value) {

            $cliente_id = $pedidos['cliente_id'];
            $endereco_id = $pedidos['endereco_id'];
            $total = $pedidos['total'];
            $totalSemDesconto = $pedidos['totalSemDesconto'];
            $porcentagem = $pedidos['porcentagem'];
            $created_at = $pedidos['created_at'];
            $create_by = $pedidos['create_by'];
            $nome_create_by = $provider_user->buscarNome($create_by);

            $nome_delete = $pedidos['delete_by'];
            $nome_delete = $provider_user->buscarNome($nome_delete);

            $nome_restored = $pedidos['restored_by'];
            $nome_restored = $provider_user->buscarNome($nome_restored);

            $nome_cliente = $provider_clientes->buscarCliente($cliente_id)['name'];

            $deleted_at = isset($pedidos['deleted_at']) ? date_format($pedidos['deleted_at'],"d/m/Y H:i:s") : null;
            $created_at = isset($pedidos['created_at']) ? date_format($pedidos['created_at'], "d/m/Y H:i:s") : null;
            $restored_at = isset($pedidos['restored_at']) ? date_format($pedidos['restored_at'], "d/m/Y H:i:s") : null;

            $filtro_carbon = $pedidos['created_at'];


            $pedidoEncontrado = [
                'create_by' => $nome_create_by, 
                'delete_by' => $nome_delete,
                'restored_by' => $nome_restored,

                'cliente_id' => $cliente_id, 
                'endereco_id' => $endereco_id, 
                'total' => $total, 
                'totalSemDesconto' => $totalSemDesconto, 
                'porcentagem' => $porcentagem,
                'created_at' => $created_at, 
                'restored_at' => $restored_at,
                'deleted_at' => $deleted_at,

                'nome_cliente' => $nome_cliente,

                'ano' => $filtro_carbon->year,
                'dia_do_ano' => $filtro_carbon->dayOfYear,
                'dia_da_semana' => $filtro_carbon->dayOfWeek,
                'hora' => $filtro_carbon->hour,
                'minuto' => $filtro_carbon->minute,
                'segundo' => $filtro_carbon->second,
                'mes' => $filtro_carbon->month,

                'created_at' => isset($pedidos['created_at']) ? date_format($pedidos['created_at'], "d/m/Y H:i:s") : null,
                'restored_at' => $restored_at,
                'deleted_at' =>  isset($pedidos['deleted_at']) ? date_format($pedidos['deleted_at'], "d/m/Y H:i:s") : null,
                'pedido_id' => $pedido_id

            ];
        }

        return $pedidoEncontrado;
    }

    public function buscarItemPedido($pedido_id)
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
