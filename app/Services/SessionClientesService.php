<?php

namespace App\Services;

use App\Services\ClientesServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use \App\Services\DBUserService;

use App\Models\Endereco;

class SessionClientesService implements ClientesServiceInterface
{
	public function adicionarCliente($name,$email,$idade,$cidade, $cep, $rua, $numero, $estado, $contato)
	{
		$clientes = session()->get('Clientes', []);
        
		$clientes[] = [
            'create_by' => Auth::id(),
            'delete_by' => null, 
            'restored_by' => null, 
            'update_by' => null, 
            'name' => $name, 
            'email' => $email, 
            'idade' => $idade, 
            'cidade' => $cidade, 
            'cep' => $cep, 
            'rua' => $rua, 
            'numero' => $numero, 
            'estado' => $estado, 
            'contato' => $contato,
            'created_at' => now(), 
            'deleted_at' => null,
            'updated_at' => null,
            'restored_at' => null,
        ];
	
		session()->put("Clientes", $clientes);

        $enderecos = session()->get('enderecos', []);

        foreach ($clientes as $key => $value) {
            $cliente_id = $key;
        }

        $enderecos[] = ['cliente_id' => $cliente_id, 'cidade' => $cidade, 'cep' => $cep, 'rua' => $rua, 'numero' => $numero, 'estado' => $estado];

        session()->put('enderecos', $enderecos); 
	}

	public function editarCliente($cliente_id, $name,$email,$idade, $contato)
	{
		if(session()->has('Clientes'))
        {
            $clientes = session()->get('Clientes');

            if(array_key_exists($cliente_id, $clientes))
            {
                $clientes[$cliente_id]['update_by'] = Auth::id();
                $clientes[$cliente_id]['updated_at'] = now();
                $clientes[$cliente_id]['name'] = $name;
                $clientes[$cliente_id]['email'] = $email;
                $clientes[$cliente_id]['idade'] = $idade;
                $clientes[$cliente_id]['contato'] = $contato;
                    
                session()->put('Clientes', $clientes);
            }
        }
	}

    public function listarClientes($softDeletes)
    {
        $clientes = session()->get('Clientes', []);
        $listarClientes = [];

        $provider_user = new DBUserService;

        foreach ($clientes as $key => $value) 
        {
            $nome_cliente = $value['name'];
            $email_cliente = $value['email'];
            $idade_cliente = $value['idade'];
            $cidade_cliente = $value['cidade'];
            $cep_cliente = $value['cep'];
            $rua_cliente = $value['rua'];
            $numero_cliente = $value['numero'];
            $estado_cliente = $value['estado'];
            $contato_cliente = $value['contato'];

            $delete_by = $value['delete_by'];
            $nome_delete_by = $provider_user->buscarNome($delete_by);
            $deleted_at = $value['deleted_at'];

            $listarClientes[$key] = ['name' => $nome_cliente, 'email' => $email_cliente, 'idade' => $idade_cliente, 'cidade' => $cidade_cliente, 'cep' => $cep_cliente, 'rua' => $rua_cliente, 'numero' => $numero_cliente, 'estado' => $estado_cliente, 'contato' => $contato_cliente, 'deleted_by' => $nome_delete_by, 'deleted_at' => $deleted_at];       
        }

        return $listarClientes;
    }
          
    public function excluirCliente($cliente_id)
    {
        if(session()->has('Clientes'))
        {
            $clientes = session()->get('Clientes');

            if(array_key_exists($cliente_id, $clientes))
            {
                $clientes[$cliente_id]['delete_by'] = Auth::id();
                $clientes[$cliente_id]['deleted_at'] = now();
                session()->put('Clientes', $clientes);
            }
        }
    }   

    public function buscarCliente($cliente_id)
    {
        if(session()->has('Clientes'))
        {
            $clientes = session()->get('Clientes');
            $clienteID = [];

            foreach ($clientes as $key => $value) {

                if($cliente_id == $key)
                {
                    $nome_cliente = $value['name'];
                    $email_cliente = $value['email'];
                    $idade_cliente = $value['idade'];
                    $cidade_cliente = $value['cidade'];
                    $cep_cliente = $value['cep'];
                    $rua_cliente = $value['rua'];
                    $numero_cliente = $value['numero'];
                    $estado_cliente = $value['estado'];
                    $contato_cliente = $value['contato'];
                    $deleted_at = $value['deleted_at'];

                    $clienteID[$key] = ['name' => $nome_cliente, 'email' => $email_cliente, 'idade' => $idade_cliente, 'cidade' => $cidade_cliente, 'cep' => $cep_cliente, 'rua' => $rua_cliente, 'numero' => $numero_cliente, 'estado' => $estado_cliente, 'contato' => $contato_cliente, 'deleted_at' => $deleted_at];
                }
        
            }
        }

        return $clienteID;   
    }

    function restaurarCliente($cliente_id)
    {
        $cliente = session()->get('Clientes');

        foreach ($cliente as $key => $value) {
            if($cliente_id == $key)
            {
                $cliente[$key]['deleted_at'] = null;
                $cliente[$key]['restored_by'] = Auth::id();
                $cliente[$key]['restored_at'] = now();
            }
        }

        session()->put('Clientes', $cliente);
    }
}
