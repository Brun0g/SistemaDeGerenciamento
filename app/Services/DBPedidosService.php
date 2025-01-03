<?php

namespace App\Services;

use App\Services\PedidosServiceInterface;
use App\Services\ProdutosServiceInterface;
use \App\Services\DBProdutosService;
use \App\Services\DBClientesService;
use \App\Services\DBUserService;
use App\Models\PedidosIndividuais;
use App\Models\Pedidos;
use App\Models\Produto;
use App\Models\Entradas_saidas;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DBPedidosService implements PedidosServiceInterface
{
    public function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total)
    {
        $pedido = new Pedidos;

        //dd(['total' => $valor_final, 'totalSemDesconto' => $valor_total]);

        $pedido->create_by = Auth::id();
        $pedido->delete_by = null;
        $pedido->restored_by = null;
        $pedido->cliente_id = $cliente_id;
        $pedido->endereco_id = $endereco_id;
        $pedido->total = $valor_final;
        $pedido->porcentagem = $porcentagem;
        $pedido->totalSemDesconto = $valor_total;
        $pedido->restored_at = null;

        $pedido->save();

        $pedido_id = $pedido->id;

        return $pedido_id;
    }

    function salvarItemPedido($pedido_id, $produto_id, $quantidade, $porcentagem_unidade, $valor_total, $valor_final, $preco_unidade)
    {
        $pedido = new PedidosIndividuais;

        $pedido->create_by = Auth::id();
        $pedido->delete_by = null;
        $pedido->restored_by = null;

        $pedido->pedido_id = $pedido_id;
        $pedido->produto_id = $produto_id;
        $pedido->quantidade = $quantidade;
        $pedido->porcentagem = $porcentagem_unidade;
        $pedido->preco_unidade = $preco_unidade;
        $pedido->total = $valor_final;
        $pedido->totalSemDesconto = $valor_total;
        $pedido->restored_at = null;

        $pedido->save();

        return $pedido->id;
    }
    
    public function excluirPedido($pedido_id, $provider_entradas_saidas)
    {
        $pedido_geral = Pedidos::all()->where('id', $pedido_id);

        $provider_pedidos = new DBPedidosService();
        
        foreach ($pedido_geral as $key => $value) {
          
            $pedido_geral[$key]->delete_by = Auth::id();
            $pedido_geral[$key]->save();
            $pedido_geral[$key]->delete($pedido_id);
        }

        $provider_pedidos->excluirPedidoIndividual($pedido_id);
        $provider_entradas_saidas->deletarSaida($pedido_id);
    }

    public function excluirPedidoIndividual($pedido_id)
    {
        $pedidos_individual = PedidosIndividuais::all()->where('pedido_id', $pedido_id);

        foreach ($pedidos_individual as $key => $value) {
            
            $pedidos_individual[$key]->delete_by = Auth::id();
            $pedidos_individual[$key]->save();
            $pedidos_individual[$key]->delete($pedido_id);
        }
    }

    public function RestaurarPedido($pedido_id, $provider_entradas_saidas)
    {
        $pedido_geral = Pedidos::withTrashed()->where('id', $pedido_id)->get();

        $provider_pedidos = new DBPedidosService();

        foreach ($pedido_geral as $key => $value) {

            $pedido_geral[$key]->restored_by = Auth::id();
            $pedido_geral[$key]->restored_at = now();

            $pedido_geral[$key]->deleted_at = null;
            $pedido_geral[$key]->save();
        }

        $provider_pedidos->RestaurarPedidoIndividual($pedido_id);
        $provider_entradas_saidas->RestaurarSaida($pedido_id);
    }

    public function RestaurarPedidoIndividual($pedido_id)
    {
        $pedidos_individual = PedidosIndividuais::withTrashed()->where('pedido_id', $pedido_id)->get();

        foreach ($pedidos_individual as $key => $value) {

            $pedidos_individual[$key]->restored_by = Auth::id();
            $pedidos_individual[$key]->restored_at = now();
            $pedidos_individual[$key]->deleted_at = null;
            $pedidos_individual[$key]->save();
        }
    }

    public function listarQuantidadePedidos($cliente, $data_inicial, $data_final, $provider_estoque, $provider_user)
    {
        $service_pedidos = new DBPedidosService();
        $service_clientes = new DBClientesService();

        $pedidos = PedidosIndividuais::withTrashed()->whereDate('created_at', '>=', $data_inicial)->whereDate('created_at', '<=', $data_final)->get();

        $pedidos_por_data = [];

        foreach ($pedidos as $key => $value) {

            $pedido_id = $value['pedido_id'];
            $cliente_id = $service_pedidos->buscarPedido($pedido_id)['cliente_id'];
            $nome = $service_clientes->buscarCliente($cliente_id)['name'];
            $created_at = $value['created_at'];
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];

            $pedidos_por_data[] = ['nome' => $nome, 'cliente_id' => $cliente_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade, 'created_at' => $created_at];
        } 

        return $pedidos_por_data; 
    }

    public function buscarItemPedido($pedido_id)
    {
        $pedidos = PedidosIndividuais::withTrashed()->where('pedido_id', $pedido_id)->get();

        $service_produtos = new DBProdutosService();

        $lista = [];
        $total = 0;
        $calcular_quantidade_total = 0;

        foreach ($pedidos as $key => $value) 
        {
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];
            $preco_unidade = $value['preco_unidade'];
            $porcentagem = $value['porcentagem'];
            $produto = $service_produtos->buscarProduto($produto_id);
            $calcular_quantidade_total += $quantidade;
            $total += $valor;
            
            $lista[] = ['pedido_id' => $pedido_id, 'produto_id' => $produto_id, 'produto' => $produto['produto'], 'quantidade' => $quantidade, 'total' => $valor, 'preco_unidade' => $preco_unidade, 'totalComDesconto' => $total, 'porcentagem' => $porcentagem];

            $array_quantidade[$pedido_id] = [ 'calcular_quantidade_total' => $calcular_quantidade_total ];
        }

        $max_quantidade = max($array_quantidade);

        return ['lista' => $lista, 'array_quantidade' => $array_quantidade, 'max_quantidade' => $max_quantidade];        
    }

      public function listarPedidos($search, $cliente_id, $data_inicial, $data_final, $pagina_atual, $order_by, $escolha, $maximo, $minimo, $categoria_id, $quantidade_maxima, $quantidade_minima, $desconto_minimo, $desconto_maximo, $provider_user)
    {
        $array = [];
      
        $total_paginas = 0;
        $total_pedido = 0;

        $provider_cliente = new DBClientesService;
        $provider_pedidos = new DBPedidosService;

        if(isset($cliente_id))
        {
            $pedidos = Pedidos::where('cliente_id', $cliente_id);

            $total_pedido = Pedidos::selectRaw('sum(total) as total_pedido')->where('cliente_id', $cliente_id)->value('total_pedido');
        }
        else
            $pedidos = Pedidos::withTrashed();

        if(!$cliente_id)
        {
            $pedidos = $pedidos->select(
            'pedidos.id', 
            'clientes.name', 
            'pedidos.create_by', 
            'pedidos.delete_by', 
            'pedidos.restored_by', 
            'pedidos.cliente_id', 
            'pedidos.endereco_id', 
            'pedidos.total', 
            'pedidos.totalSemDesconto', 
            'pedidos.porcentagem',  
            'pedidos.restored_at', 
            'pedidos.deleted_at', 
            'pedidos.created_at', 
            'pedidos.updated_at')
            ->join('clientes', 'pedidos.cliente_id', '=', 'clientes.id')
            ->whereDate('pedidos.created_at', '>=', $data_inicial)
            ->whereDate('pedidos.created_at', '<=', $data_final);

            $segunda_query = PedidosIndividuais::selectRaw('pedidos_individuais.pedido_id as id_segunda')
            ->join('produtos', 'pedidos_individuais.produto_id', '=', 'produtos.id')
            ->when($categoria_id, function ($query) use ($categoria_id) {
                    
                $query->where('produtos.categoria_id', '=', $categoria_id);

            })->groupBy('id_segunda');
                
            $pedidos = $pedidos
            ->joinSub($segunda_query, 'segunda_query', function ($join) {
                $join->on('pedidos.id', '=', 'segunda_query.id_segunda');
            });

            $buscar_total_quantidade = PedidosIndividuais::selectRaw('pedidos_individuais.pedido_id as id_terceira, sum(pedidos_individuais.quantidade) as total_quantidade')
                ->groupBy('id_terceira');

            $pedidos = $pedidos
            ->joinSub($buscar_total_quantidade, 'terceira_query', function ($join) {
                $join->on('pedidos.id', '=', 'terceira_query.id_terceira');
            })->selectRaw('(pedidos.totalSemDesconto - pedidos.total) as desconto, total_quantidade');
        }

        if($data_inicial && $data_final)
        {
            $pedidos = $pedidos->when($escolha, function($query) use ($escolha, $maximo, $minimo, $search, $categoria_id, $quantidade_minima, $quantidade_maxima, $desconto_minimo, $desconto_maximo) 
            {
                if($minimo)
                    $query->where('total', '>=', $minimo);

                if($maximo)
                    $query->where('total', '<=', $maximo);

                if($quantidade_minima)
                    $query->where('total_quantidade', '>=', $quantidade_minima);

                if($quantidade_maxima)
                    $query->where('total_quantidade', '<=', $quantidade_maxima);

                if($desconto_minimo)
                    $query->having('desconto', '>=', (int)$desconto_minimo);
                
                if($desconto_maximo)
                    $query->having('desconto', '<=', (int)$desconto_maximo);

                if($search)
                    $query->where('clientes.name', 'LIKE', $search .'%');
                
                if($escolha == 1)
                    $query->whereNull('pedidos.deleted_at');
                else
                    $query->whereNotNull('pedidos.deleted_at');
            });
        }

        $row = $pedidos->count();
        $row_limit = 5;
        $pagina_atual = $pagina_atual * $row_limit;                
        $total_paginas = ceil($row / $row_limit - 1);        
        $pedidos = $pedidos->limit($row_limit)->offset($pagina_atual);
        
        if($order_by)
        {
            $key = key($order_by);
            $order = $order_by[$key] == 0 ? 'asc' : 'desc';
            $pedidos = $pedidos->orderBy($key, $order)->get();
        } else 
            $pedidos = $pedidos->get();

        foreach ($pedidos as $key => $value) 
        {
            $pedido_id = $value->id;
            $nome_create = $value->create_by;
            $nome_create = $provider_user->buscarNome($nome_create);

            $nome_delete = $value->delete_by;
            $nome_delete = $provider_user->buscarNome($nome_delete);

            $nome_restored = $value->restored_by;
            $nome_restored = $provider_user->buscarNome($nome_restored);

            $id_cliente = $value->cliente_id;
            $nome_cliente = $provider_cliente->buscarCliente($id_cliente)['name'];
            $endereco = $value->endereco_id;
            $total = $value->total;
            $porcentagem = $value->porcentagem;
            $calcular_quantidade_total = $value['total_quantidade'];

            $created_at = Carbon::parse($value->created_at);
            $deleted_at = Carbon::parse($value->deleted_at);
            $restored_at = Carbon::parse($value->restored_at);

            if(!$cliente_id)
                $total_pedido += $total;

            if($escolha == 2)
                $filtro_data = $deleted_at;
            else
                $filtro_data = $created_at;

            $desconto = $value->desconto;

            $array[$pedido_id] = [
                'create_by'     => $nome_create, 
                'delete_by'     => $nome_delete, 
                'restored_by'   => $nome_restored,
                'nome_cliente'  => $nome_cliente,   
                'cliente_id'    => $id_cliente, 
                'endereco'      => $endereco, 
                'total'         => $total, 
                'porcentagem'   => $porcentagem,
                'total_pedido' => $total_pedido,
                'quantidade_total_pedido' => $calcular_quantidade_total,
                'ano' => $filtro_data->year, 
                'dia_do_ano' => $filtro_data->dayOfYear, 
                'dia_da_semana' => $filtro_data->dayOfWeek, 
                'hora' => $filtro_data->hour, 
                'minuto' => $filtro_data->minute, 
                'segundo' => $filtro_data->second, 
                'mes' => $filtro_data->month,
                'created_at' => isset($created_at) ? date_format($created_at, "d/m/Y H:i:s") : null, 
                'restored_at' => isset($restored_at) ? date_format($restored_at, "d/m/Y H:i:s") : null,
                'deleted_at' => isset($deleted_at) ? date_format($deleted_at,"d/m/Y H:i:s") : null,
                'desconto' => $desconto
            ];  
        }

        $filtros = [
            'max' => $maximo, 
            'min' => $minimo, 
            'quantidade_max' => $quantidade_maxima, 
            'quantidade_min' => $quantidade_minima,
            'desconto_max' => $desconto_maximo, 
            'desconto_min' => $desconto_minimo
        ];

        return ['array' => $array, 'total_paginas'=> $total_paginas, 'filtros' => $filtros, 'total_pedido' => $total_pedido];
    }

    public function buscarPedido($pedido_id)
    {
        $pedidoEncontrado = [];
        $pedido = Pedidos::withTrashed()->where('id', $pedido_id)->get()[0];

        $provider_user = new DBUserService;
        
        foreach ($pedido as $key => $value) {

            $cliente_id = $pedido->cliente_id;
            $endereco_id = $pedido->endereco_id;
            $total = $pedido->total;
            $totalSemDesconto = $pedido->totalSemDesconto;
            $porcentagem = $pedido->porcentagem;
            $created_at = $pedido->created_at;
            $create_by = $pedido->create_by;
            $nome_create_by = $provider_user->buscarNome($create_by);

            $deleted_at = isset($pedido->deleted_at) ? date_format($pedido->deleted_at,"d/m/Y H:i:s") : null;
            $created_at = isset($pedido->created_at) ? date_format($pedido->created_at, "d/m/Y H:i:s") : null;
            $restored_at = isset($pedido->restored_at) ? date_format($pedido->restored_at, "d/m/Y H:i:s") : null;

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

    public function reativarPedido($pedido_id)
    {
        $pedidos = PedidosIndividuais::withTrashed()->where('pedido_id', $pedido_id)->get();
        
        $service = new DBEstoqueService;

        foreach ($pedidos as $key => $value) {
            $produto_id = $value['produto_id'];
            $estoque_atual = $service->buscarEstoque($produto_id);
            $quantidade_pedido = $value['quantidade'];
            
            if($estoque_atual < $quantidade_pedido)
                return false;
        }

        return true;
    }
}
