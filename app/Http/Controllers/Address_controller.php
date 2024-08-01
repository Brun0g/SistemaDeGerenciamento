<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use \App\Services\ClientesServiceInterface;
use \App\Services\EnderecoServiceInterface;

class Address_controller extends Controller
{
    public function addressView(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente ) 
    {
        $cliente = $provider_cliente->buscarCliente($cliente_id);

        return view('cadastrarEndereco', ['cliente' => $cliente , 'id'=> $cliente_id]);
    }
   
    public function newAddress(Request $request, $cliente_id, ClientesServiceInterface $provider_cliente, EnderecoServiceInterface $provider_endereco)
    {
        $cliente = $provider_cliente->buscarCliente($cliente_id);

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
            return redirect('Editar/Cliente/'. $cliente_id)->withErrors($validator);
        
        $provider_endereco->adicionarEndereco($cliente_id, $cidade, $cep, $rua, $numero, $estado);

        return redirect('Editar/Cliente/' . $cliente_id);
    }

    public function deleteAddress(Request $request, $endereco_id, EnderecoServiceInterface $provider_endereco)
    {
        $enderecos = $provider_endereco->listarEnderecos();
        $cliente_id = $provider_endereco->encontrarClienteID($endereco_id);
        $provider_endereco->deletarEndereco($endereco_id);

        return redirect('Editar/Cliente/' . $cliente_id);
    }

    public function editAddress(Request $request, $endereco_id, EnderecoServiceInterface $provider_endereco)
    {
        $enderecos = $provider_endereco->listarEnderecos();

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
            return redirect('Clients')->withErrors($validator);
        
        $provider_endereco->editarEndereco($endereco_id, $cidade, $cep, $rua, $numero, $estado);

        return redirect('Editar/Cliente/' . $cliente_id);
    }

    public function viewAddress(Request $request, $endereco_id, ClientesServiceInterface $provider_cliente, EnderecoServiceInterface $provider_endereco)
    {
        $enderecos = $provider_endereco->listarEnderecos();
        $cliente_id = $provider_endereco->encontrarClienteID($endereco_id);
        $lista_clientes = $provider_cliente->listarClientes();
        $cliente = $provider_cliente->buscarCliente($cliente_id);

        return view('editarEndereco', ['cliente' => $cliente, 'id' => $cliente_id, 'endereco_id' => $endereco_id, 'enderecos' => $enderecos]);
    }    
}
