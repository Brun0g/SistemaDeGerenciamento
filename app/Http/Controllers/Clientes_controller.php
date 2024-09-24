<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use \App\Services\ClientesServiceInterface;
use \App\Services\CategoriaServiceInterface;
use \App\Services\PedidosServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\EnderecoServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\PromocoesServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\EstoqueServiceInterface;
use \App\Services\UserServiceInterface;


class Clientes_controller extends Controller
{
    public function registerClient(Request $request, ClientesServiceInterface $provider_cliente)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $idade = $request->input('idade');
        $cidade = $request->input('cidade');
        $cep = $request->input('cep');
        $rua = $request->input('rua');
        $numero = $request->input('numero');
        $estado = $request->input('estado');
        $contato = $request->input('contato');

        $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|string|email',
        'idade' => 'required|integer|',
        'cidade' => 'required|string',
        'cep' => 'required|string',
        'rua' => 'required|string',
        'numero' => 'required|numeric',
        'estado' => 'required|string',
        'contato' => 'required|numeric|digits:11',
        ]);

        if($validator->fails())
            return redirect('Clientes')->withErrors($validator);

        $provider_cliente->adicionarCliente($name,$email,$idade,$cidade, $cep, $rua, $numero, $estado, $contato);

        return redirect('Clientes');
    }
    
    public function mainViewClient(Request $request, ClientesServiceInterface $provider_cliente, PedidosServiceInterface $provider_pedido, EnderecoServiceInterface $provider_endereco, EstoqueServiceInterface $provider_estoque)
    {
        $buscarPedidoCliente = [];
        $valorTotalPorPedido = [];
        $tabela_clientes = $provider_cliente->listarClientes();
        $listar_enderecos = $provider_endereco->listarEnderecos();

        if($request->search_string != null)
            $tabela_clientes = $provider_cliente->searchCliente($request->search_string);


        foreach ($tabela_clientes as $cliente_id => $value) {
            $valorTotalPorPedido[$cliente_id] = 0;
            $buscarPedidoCliente = $provider_pedido->listarPedidos($cliente_id, $provider_estoque);

            foreach ($buscarPedidoCliente as $value) {
                if($cliente_id == $value['cliente_id'])
                $valorTotalPorPedido[$cliente_id] += $value['total'];
            }
        }

        return view('Clientes', ['listar_enderecos'=> $listar_enderecos, "tabela_clientes" => $tabela_clientes, 'total' => $valorTotalPorPedido]);
    }

    public function show(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente, PedidosServiceInterface $provider_pedidos, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, CarrinhoServiceInterface $provider_carrinho, PromocoesServiceInterface $provider_promocoes, EntradasServiceInterface $provider_entradas_saidas, UserServiceInterface $provider_user, EstoqueServiceInterface $provider_estoque)
    {
        $cliente = $provider_cliente->buscarCliente($cliente_id);
        $listarPedidos = $provider_carrinho->visualizar($cliente_id, $provider_produto, $provider_promocoes, $provider_carrinho, $provider_estoque);  
        $porcentagem = $provider_carrinho->visualizarPorcentagem($cliente_id);
        $buscarValores = $provider_carrinho->calcularDesconto($cliente_id, $provider_carrinho, $provider_promocoes, $provider_produto);

        $softDelete = false;
        $listarProduto = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, $softDelete);
        $listarCategoria = $provider_categoria->listarCategoria();
        $listarPedidosAprovados = $provider_pedidos->listarPedidos($cliente_id, $provider_estoque);

        $totalPedido = $buscarValores['totalComDesconto'];

        return view('produtosPorCliente', ['listarPedidos'=> $listarPedidos, 'categorias' => $listarCategoria, 'clienteID' => $cliente, 'id' => $cliente_id, 'listarPedidosAprovados' => $listarPedidosAprovados, 'totalPedido'=> $totalPedido, 'produtosEstoque' => $listarProduto, 'porcentagem' => $porcentagem]);
    }

    public function deleteClient(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente)
    {
        $provider_cliente->excluirCliente($cliente_id);

        return redirect('Clientes');
    }

    public function editClient(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $idade = $request->input('idade');
        $contato = $request->input('contato');

        $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|string|email',
        'idade' => 'required|integer|',
        'contato' => 'required|string',
        ]);

        if($validator->fails())
            return redirect('Editar/Cliente/'. $cliente_id)->withErrors($validator);
    
        $provider_cliente->editarCliente($cliente_id,$name,$email,$idade, $contato);

        return redirect('Editar/Cliente/'. $cliente_id);
    }

    public function viewClient(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente, EnderecoServiceInterface $provider_endereco)
    {
        $cliente = $provider_cliente->buscarCliente($cliente_id);
        $enderecos= $provider_endereco->listarEnderecos();

        foreach ($enderecos as $endereco_id => $endereco)
        {
            if($cliente_id == $endereco['cliente_id'])
                $listarEnderecos[$endereco_id] = $enderecos[$endereco_id];
        }
             
        return view('editarCliente', ['cliente' => $cliente , 'id'=> $cliente_id, 'listarEnderecos' => $listarEnderecos]);
    }
}
