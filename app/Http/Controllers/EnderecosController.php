<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use \App\Services\ClientesServiceInterface;
use \App\Services\EnderecoServiceInterface;

class EnderecosController extends Controller
{
    public function index(Request $request, $endereco_id, ClientesServiceInterface $provider_cliente, EnderecoServiceInterface $provider_endereco)
    {
        $enderecos = $provider_endereco->listarEnderecos();
        $cliente_id = $provider_endereco->encontrarClienteID($endereco_id);
        $lista_clientes = $provider_cliente->listarClientes(true);
        $cliente = $provider_cliente->buscarCliente($cliente_id);

        return view('editarEndereco', ['cliente' => $cliente, 'id' => $cliente_id, 'endereco_id' => $endereco_id, 'enderecos' => $enderecos]);
    }

    public function store(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente, EnderecoServiceInterface $provider_endereco)
    {
        $cliente = $provider_cliente->buscarCliente($cliente_id);
        $url = url()->previous();

        $cidade = $request->input('cidade');
        $cep = $request->input('cep');
        $rua = $request->input('rua');
        $numero = $request->input('numero');
        $estado = $request->input('estado');

        $validator = Validator::make($request->all(), [
        'cidade' => 'required|string',
        'cep' => 'required|string',
        'rua' => 'required|string',
        'numero' => 'required|numeric',
        'estado' => 'required|string',
        ]);

        if($validator->fails())
            return redirect($url)->withErrors($validator);
        
        $provider_endereco->adicionarEndereco($cliente_id, $cidade, $cep, $rua, $numero, $estado);

        return redirect($url);
    }

    public function delete(Request $request, $endereco_id, EnderecoServiceInterface $provider_endereco)
    {
        $enderecos = $provider_endereco->listarEnderecos();
        $cliente_id = $provider_endereco->encontrarClienteID($endereco_id);
        $provider_endereco->deletarEndereco($endereco_id);
        $url = url()->previous();

        return redirect($url);
    }

    public function update(Request $request, $endereco_id, EnderecoServiceInterface $provider_endereco)
    {
        $enderecos = $provider_endereco->listarEnderecos();
        $url = url()->previous();

        $cidade = $request->input('cidade');
        $cep = $request->input('cep');
        $rua = $request->input('rua');
        $numero = $request->input('numero');
        $estado = $request->input('estado');

        $validator = Validator::make($request->all(), [
        'cidade' => 'required|string',
        'cep' => 'required|string',
        'rua' => 'required|string',
        'numero' => 'required|numeric',
        'estado' => 'required|string',
        ]);

        $cliente_id = $provider_endereco->encontrarClienteID($endereco_id);

        if($validator->fails())
            return redirect($url)->withErrors($validator);
        
        $provider_endereco->editarEndereco($endereco_id, $cidade, $cep, $rua, $numero, $estado);

        return redirect($url);
    }  
}
