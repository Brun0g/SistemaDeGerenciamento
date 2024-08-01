<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Endereco;

use App\Services\ClientesServiceInterface;

class DBClientesService implements ClientesServiceInterface
{
	function adicionarCliente($name,$email,$idade,$cidade, $cep, $rua, $numero, $estado, $contato)
	{
		$clientes = new Cliente;

        $clientes->name = $name;
       	$clientes->email = $email;
        $clientes->idade = $idade;
        $clientes->cidade = $cidade;
        $clientes->cep = $cep;
        $clientes->rua = $rua;
        $clientes->numero = $numero;
        $clientes->estado = $estado;
        $clientes->contato = $contato;

      	$clientes->save();

        if(isset($clientes->id)){
        $cliente_id = $clientes->id;

        $endereco = new Endereco;

        $endereco->cliente_id = $cliente_id;
        $endereco->cidade = $cidade;
        $endereco->cep = $cep;
        $endereco->rua = $rua;
        $endereco->numero = $numero;
        $endereco->estado = $estado;

        $endereco->save();

        }
	}

	function excluirCliente($cliente_id)
	{
		$cliente = Cliente::find($cliente_id);

		$cliente->delete($cliente_id);
	}

	function editarCliente($cliente_id,$name,$email,$idade, $contato)
	{
		$cliente = Cliente::find($cliente_id);

		$cliente->name = $name;
       	$cliente->email = $email;
        $cliente->idade = $idade;
        $cliente->contato = $contato;
	
		$cliente->save();
	}

	function listarClientes()
	{
		$clientes = Cliente::all();
		$listarClientes = [];

		foreach ($clientes as $cliente) 
        {
            $nome_cliente = $cliente->name;
            $email_cliente = $cliente->email;
            $idade_cliente = $cliente->idade;
            $cidade_cliente = $cliente->cidade;
            $cep_cliente = $cliente->cep;
            $rua_cliente = $cliente->rua;
            $numero_cliente = $cliente->numero;
            $estado_cliente = $cliente->estado;
            $contato_cliente = $cliente->contato;

            $listarClientes[$cliente->id] = ['name' => $nome_cliente, 'email' => $email_cliente, 'idade' => $idade_cliente, 'cidade' => $cidade_cliente, 'cep' => $cep_cliente, 'rua' => $rua_cliente, 'numero' => $numero_cliente, 'estado' => $estado_cliente, 'contato' => $contato_cliente];       
        }

        return $listarClientes;
	}
    
	function buscarCliente($cliente_id)
	{
		$cliente = Cliente::find($cliente_id);

		foreach ($cliente as $key => $value) {

			$nome_cliente = $cliente->name;
            $email_cliente = $cliente->email;
            $idade_cliente = $cliente->idade;
            $cidade_cliente = $cliente->cidade;
            $cep_cliente = $cliente->cep;
            $rua_cliente = $cliente->rua;
            $numero_cliente = $cliente->numero;
            $estado_cliente = $cliente->estado;
            $contato_cliente = $cliente->contato;

            $cliente[$cliente->id] = ['name' => $nome_cliente, 'email' => $email_cliente, 'idade' => $idade_cliente, 'cidade' => $cidade_cliente, 'cep' => $cep_cliente, 'rua' => $rua_cliente, 'numero' => $numero_cliente, 'estado' => $estado_cliente, 'contato' => $contato_cliente];
		}

		return $cliente;
	}
}
