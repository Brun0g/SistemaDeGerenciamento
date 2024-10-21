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


class ClientesController extends Controller
{
    public function index(Request $request, ClientesServiceInterface $provider_cliente, PedidosServiceInterface $provider_pedido, EnderecoServiceInterface $provider_endereco, EstoqueServiceInterface $provider_estoque, UserServiceInterface $provider_user)
    {
        $buscar_pedido_cliente = [];
        $valor_total_pedido = [];
        $tabela_clientes = $provider_cliente->listarClientes( false );
        $listar_enderecos = $provider_endereco->listarEnderecos();

        if($request->search_string != null)
            $tabela_clientes = $provider_cliente->searchCliente($request->search_string);

        foreach ($tabela_clientes as $cliente_id => $valor) {
            $valor_total_pedido[$cliente_id] = 0;
            $buscar_pedido_cliente = $provider_pedido->listarPedidos(null, $cliente_id, null, null, null, null, null, $provider_user)['array'];

            foreach ($buscar_pedido_cliente as $value) {
                if($cliente_id == $value['cliente_id'])
                $valor_total_pedido[$cliente_id] += $value['total'];
            }
        }





        return view('Clientes', ['listar_enderecos'=> $listar_enderecos, "tabela_clientes" => $tabela_clientes, 'total' => $valor_total_pedido]);
    }

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
    public function softDeletesView(Request $request, ClientesServiceInterface $provider_cliente, PedidosServiceInterface $provider_pedido, EnderecoServiceInterface $provider_endereco, EstoqueServiceInterface $provider_estoque, UserServiceInterface $provider_user)
    {
        $buscar_pedido_cliente = [];
        $valor_total_pedido = [];
        $tabela_clientes = $provider_cliente->listarClientes(true);
        $listar_enderecos = $provider_endereco->listarEnderecos();

        if($request->search_string != null)
            $tabela_clientes = $provider_cliente->searchCliente($request->search_string);


        foreach ($tabela_clientes as $cliente_id => $value) {
            $valor_total_pedido[$cliente_id] = 0;
            $buscar_pedido_cliente = $provider_pedido->listarPedidos(null, $cliente_id, null, null, null, null, null, $provider_user)['array'];

            foreach ($buscar_pedido_cliente as $value) {
                if($cliente_id == $value['cliente_id'])
                    $valor_total_pedido[$cliente_id] += $value['total'];
            }
        }

        return view('clientes_excluidos', ['listar_enderecos'=> $listar_enderecos, "tabela_clientes" => $tabela_clientes, 'total' => $valor_total_pedido]);
    }

    public function restoredClient(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente)
    {
        $provider_cliente->restaurarCliente($cliente_id);

        $url = url()->previous();

        return redirect($url);
    }

    public function show(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente, PedidosServiceInterface $provider_pedidos, ProdutosServiceInterface $provider_produto, CategoriaServiceInterface $provider_categoria, CarrinhoServiceInterface $provider_carrinho, PromocoesServiceInterface $provider_promocoes, UserServiceInterface $provider_user, EstoqueServiceInterface $provider_estoque)
    {
        $buscar_total = $provider_carrinho->calcularDesconto($cliente_id, $provider_carrinho, $provider_promocoes, $provider_produto);

        $cliente_array = $provider_cliente->buscarCliente($cliente_id);
        $porcentagem = $provider_carrinho->visualizarPorcentagem($cliente_id);
        $listar_carrinho = $provider_carrinho->visualizar($cliente_id, $provider_produto, $provider_promocoes, $provider_carrinho, $provider_estoque);  
        $listar_produtos = $provider_produto->listarProduto($provider_promocoes, $provider_estoque, false);
        $listar_categorias = $provider_categoria->listarCategoria();
        $listar_pedidos = $provider_pedidos->listarPedidos(null, $cliente_id, null, null, null, null, null, $provider_user)['array'];



        return view('listar_pedidos_aprovados', ['listar_produtos' => $listar_produtos, 'listar_carrinho'=> $listar_carrinho, 'listar_categorias' => $listar_categorias, 'listar_pedidos' => $listar_pedidos, 'clienteID' => $cliente_array, 'totalPedido'=> $buscar_total['totalComDesconto'], 'porcentagem' => $porcentagem, 'deletedAt' => $cliente_array['deleted_at'], 'cliente_id' => $cliente_id,]);
    }

    public function deleteClient(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente)
    {
        $provider_cliente->excluirCliente($cliente_id);

        return redirect('Clientes');
    }

    public function update(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $idade = $request->input('idade');
        $contato = $request->input('contato');

        $url = url()->previous();

        $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|string|email',
        'idade' => 'required|integer|',
        'contato' => 'required|string',
        ]);

        if($validator->fails())
            return redirect($url)->withErrors($validator);
    
        $provider_cliente->editarCliente($cliente_id,$name,$email,$idade, $contato);

        return redirect($url);
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
