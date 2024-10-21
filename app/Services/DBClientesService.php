<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Endereco;

use App\Services\ClientesServiceInterface;
use Illuminate\Support\Facades\Auth;

use \App\Services\DBUserService;

class DBClientesService implements ClientesServiceInterface
{
	function adicionarCliente($name,$email,$idade,$cidade, $cep, $rua, $numero, $estado, $contato)
	{
		$clientes = new Cliente;

        $clientes->create_by = Auth::id();
        $clientes->delete_by = null;
        $clientes->restored_by = null;
        $clientes->update_by = null;

        $clientes->name = $name;
       	$clientes->email = $email;
        $clientes->idade = $idade;
        $clientes->cidade = $cidade;
        $clientes->cep = $cep;
        $clientes->rua = $rua;
        $clientes->numero = $numero;
        $clientes->estado = $estado;
        $clientes->contato = $contato;

        $clientes->restored_at = null;

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

        $cliente->delete_by = Auth::id();
        $cliente->save();

		$cliente->delete($cliente_id);
	}

	function editarCliente($cliente_id,$name,$email,$idade, $contato)
	{
		$cliente = Cliente::find($cliente_id);

        $cliente->update_by = Auth::id();
		
        $cliente->name = $name;
       	$cliente->email = $email;
        $cliente->idade = $idade;
        $cliente->contato = $contato;
	
		$cliente->save();
	}

	function listarClientes($softDeletes)
	{
		$clientes = Cliente::all();

        if($softDeletes)
            $clientes = Cliente::withTrashed()->get();

		$listarClientes = [];

        $provider_user = new DBUserService;

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

            $deleted_at = isset($cliente->deleted_at) ? date_format($cliente->deleted_at,"d/m/Y H:i:s") : null;
            $delete_by = $cliente->delete_by;
            $nome_delete_by = $provider_user->buscarNome($delete_by);

            $created_at = isset($cliente->created_at) ? date_format($cliente->created_at, "d/m/Y H:i:s") : null;
            $create_by = $cliente->create_by;
            $nome_create_by = $provider_user->buscarNome($create_by);

            $restored_at = isset($cliente->restored_at) ? date_format($cliente->restored_at, "d/m/Y H:i:s") : null;
            $restored_by = $cliente->restored_by;
            $nome_restored_by = $provider_user->buscarNome($restored_by);

            $updated_at = isset($cliente->updated_at) ? date_format($cliente->updated_at, "d/m/Y H:i:s") : null;
            $update_by = $cliente->update_by;
            $nome_update_by = $provider_user->buscarNome($update_by);
            
            $listarClientes[$cliente->id] = [
                'create_by' => $nome_create_by,
                'update_by' => $nome_update_by,
                'restored_by' => $nome_restored_by,
                'deleted_by' => $nome_delete_by,
                'name' => $nome_cliente,
                'email' => $email_cliente,
                'idade' => $idade_cliente,
                'cidade' => $cidade_cliente,
                'cep' => $cep_cliente,
                'rua' => $rua_cliente,
                'numero' => $numero_cliente,
                'estado' => $estado_cliente,
                'contato' => $contato_cliente,
                'deleted_at' => $deleted_at,
                'restored_at' => $restored_at,
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ];       
        }


        return $listarClientes;
	}

    function searchClient($search)
    {
        $clientes = Cliente::where('name', 'like', $search .'%')->get();

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

            // $listarClientes[$cliente->id] = ['name' => $nome_cliente, 'email' => $email_cliente, 'idade' => $idade_cliente, 'cidade' => $cidade_cliente, 'cep' => $cep_cliente, 'rua' => $rua_cliente, 'numero' => $numero_cliente, 'estado' => $estado_cliente, 'contato' => $contato_cliente];   

            $listarClientes[$cliente->id] = ['name' => $nome_cliente];  


        }

        return $listarClientes;
    }
    
	function buscarCliente($cliente_id)
	{
		
        $cliente = Cliente::withTrashed()->where('id', $cliente_id)->get()[0];

        $provider_user = new DBUserService;

		foreach ($cliente as $clientes) {

			$nome_cliente = $cliente->name;
            $email_cliente = $cliente->email;
            $idade_cliente = $cliente->idade;
            $cidade_cliente = $cliente->cidade;
            $cep_cliente = $cliente->cep;
            $rua_cliente = $cliente->rua;
            $numero_cliente = $cliente->numero;
            $estado_cliente = $cliente->estado;
            $contato_cliente = $cliente->contato;

            $deleted_at = isset($cliente->deleted_at) ? date_format($cliente->deleted_at,"d/m/Y H:i:s") : null;
            $delete_by = $cliente->delete_by;
            $nome_delete_by = $provider_user->buscarNome($delete_by);

            $created_at = isset($cliente->created_at) ? date_format($cliente->created_at, "d/m/Y H:i:s") : null;
            $create_by = $cliente->create_by;
            $nome_create_by = $provider_user->buscarNome($create_by);

            $restored_at = isset($cliente->restored_at) ? date_format($cliente->restored_at, "d/m/Y H:i:s") : null;
            $restored_by = $cliente->restored_by;
            $nome_restored_by = $provider_user->buscarNome($restored_by);

            $updated_at = isset($cliente->updated_at) ? date_format($cliente->updated_at, "d/m/Y H:i:s") : null;
            $update_by = $cliente->update_by;
            $nome_update_by = $provider_user->buscarNome($update_by);

            return [ 
                'create_by' => $nome_create_by, 
                'update_by' => $nome_update_by, 
                'restored_by' => $nome_restored_by, 
                'deleted_by' => $nome_delete_by, 
                'name' => $nome_cliente, 
                'email' => $email_cliente, 
                'idade' => $idade_cliente, 
                'cidade' => $cidade_cliente, 
                'cep' => $cep_cliente, 
                'rua' => $rua_cliente, 
                'numero' => $numero_cliente, 
                'estado' => $estado_cliente, 
                'contato' => $contato_cliente, 
                'deleted_at' => $deleted_at, 
                'restored_at' => $restored_at, 
                'created_at' => $created_at, 
                'updated_at' => $updated_at
            ];
		}

		return [];
	}

    function restaurarCliente($cliente_id)
    {
        $cliente = Cliente::withTrashed()->where('id', $cliente_id)->get()[0];

        $cliente->restored_by = Auth::id();
        $cliente->restored_at = now();
        $cliente->deleted_at = null;
      
        $cliente->save();
    }
}
