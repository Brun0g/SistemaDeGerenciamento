<?php

namespace App\Services;


use App\Services\PedidosServiceInterface;
use App\Services\ProdutosServiceInterface;
use \App\Services\DBProdutosService;
use \App\Services\DBClientesService;
use \App\Services\DBUserService;
use App\Models\PedidosIndividuais;
use App\Models\Pedidos;
use App\Models\Entradas_saidas;


use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

 use Illuminate\Support\Carbon;


class DBPedidosService implements PedidosServiceInterface
{
    public function salvarPedido($cliente_id, $endereco_id, $valor_final, $porcentagem, $valor_total)
    {
        $pedido = new Pedidos;

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

    public function buscarItemPedido($pedido_id, $provider_entradas_saidas, $provider_user, $provider_pedidos)
    {
        $pedidos = PedidosIndividuais::withTrashed()->where('pedido_id', $pedido_id)->get();

        $service_produtos = new DBProdutosService();

        $lista = [];
        $total = 0;

        foreach ($pedidos as $key => $value) 
        {
            $produto_id = $value['produto_id'];
            $quantidade = $value['quantidade'];
            $valor = $value['total'];
            $preco_unidade = $value['preco_unidade'];
            $porcentagem = $value['porcentagem'];
            $produto = $service_produtos->buscarProduto($produto_id);

            $total += $valor;
           
            $lista[] = ['pedido_id' => $pedido_id, 'produto_id' => $produto_id, 'produto' => $produto['produto'], 'quantidade' => $quantidade, 'total' => $valor, 'preco_unidade' => $preco_unidade, 'totalComDesconto' => $total, 'porcentagem' => $porcentagem];
        }

        return $lista;          
    }

    public function listarPedidos($cliente_id, $provider_estoque, $provider_user, $data_inicial, $data_final, $pagina_atual)
    {
        $array = [];

        $numero_paginas = 0;

        if($data_inicial && $data_final)
        {
            $row = Pedidos::whereDate('created_at', '>=', $data_inicial)->whereDate('created_at', '<=', $data_final)->count();
            $row_limit = 5;
            $pagina_atual = $pagina_atual * $row_limit;                
            
            $numero_paginas = ceil($row / $row_limit - 1);
        }

        
        if($cliente_id)
            $pedidos = Pedidos::where('cliente_id', $cliente_id)->get();
        else
            $pedidos = Pedidos::whereDate('created_at', '>=', $data_inicial)->whereDate('created_at', '<=', $data_final)->limit($row_limit)->offset($pagina_atual)->get();

        
        foreach ($pedidos as $key => $value) 
        {
            $pedido_id = $pedidos[$key]->id;
    
            $nome_create = $pedidos[$key]->create_by;
            $nome_create = $provider_user->buscarNome($nome_create);

            $nome_delete = $pedidos[$key]->delete_by;
            $nome_delete = $provider_user->buscarNome($nome_delete);

            $nome_restored = $pedidos[$key]->restored_by;
            $nome_restored = $provider_user->buscarNome($nome_restored);

            $id_cliente = $pedidos[$key]->cliente_id;
            $endereco = $pedidos[$key]->endereco_id;
            $total = $pedidos[$key]->total;
            $porcentagem = $pedidos[$key]->porcentagem;

            $deleted_at = null;
            $created_at = $pedidos[$key]->created_at;
            $restored_at = isset($pedidos[$key]->restored_at) ? date_format($pedidos[$key]->restored_at, "d/m/Y H:i:s") : null;

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
                'created_at' => isset($pedidos[$key]->created_at) ? date_format($pedidos[$key]->created_at, "d/m/Y H:i:s") : null, 
                'restored_at' => $restored_at,
                'deleted_at' => isset($pedidos[$key]->deleted_at) ? date_format($pedidos[$key]->deleted_at,"d/m/Y H:i:s") : null
            ];  
        }

        return ['array' => $array, 'page'=> $numero_paginas];
    }

    public function listarPedidosExcluidos($provider_user, $data_inicial, $data_final, $pagina_atual)
    {
        $array = [];

        $numero_paginas = 0;

        if($data_inicial && $data_final)
        {
            $row = Pedidos::withTrashed()->where('deleted_at', '!=', null)->whereDate('created_at', '>=', $data_inicial)->whereDate('created_at', '<=', $data_final)->count();
            $row_limit = 5;
            $pagina_atual = $pagina_atual * $row_limit;
            
            $numero_paginas = ceil($row / $row_limit - 1);
        }

        $pedidos = Pedidos::withTrashed()->where('deleted_at', '!=', null)->whereDate('created_at', '>=', $data_inicial)->whereDate('created_at', '<=', $data_final)->limit($row_limit)->offset($pagina_atual)->get();
     
        foreach ($pedidos as $key => $value) {

            $pedido_id = $pedidos[$key]->id;
    
            $nome_create = $pedidos[$key]->create_by;
            $nome_create = $provider_user->buscarNome($nome_create);

            $nome_delete = $pedidos[$key]->delete_by;
            $nome_delete = $provider_user->buscarNome($nome_delete);

            $nome_restored = $pedidos[$key]->restored_by;
            $nome_restored = $provider_user->buscarNome($nome_restored);

            $id_cliente = $pedidos[$key]->cliente_id;
            $endereco = $pedidos[$key]->endereco_id;
            $total = $pedidos[$key]->total;
            $porcentagem = $pedidos[$key]->porcentagem;

            $deleted_at = $pedidos[$key]->deleted_at;
            $created_at = isset($pedidos[$key]->created_at) ? date_format($pedidos[$key]->created_at, "d/m/Y H:i:s") : null;
            $restored_at = isset($pedidos[$key]->restored_at) ? date_format($pedidos[$key]->restored_at, "d/m/Y H:i:s") : null;

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
                'deleted_at' => isset($pedidos[$key]->deleted_at) ? date_format($pedidos[$key]->deleted_at,"d/m/Y H:i:s") : null
            ]; 
            
        }



        return ['array' => $array, 'page' => $numero_paginas];
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
