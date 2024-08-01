<?php

namespace App\Services;

use App\Services\ClientesServiceInterface;

use App\Models\Endereco;

class SessionClientesService implements ClientesServiceInterface
{
	public function adicionarCliente($name,$email,$idade,$cidade, $cep, $rua, $numero, $estado, $contato)
	{
		$clientes = session()->get('Clients', []);
        
		$clientes[] = ['name' => $name, 'email' => $email,'idade' => $idade, 'cidade' => $cidade, 'cep' => $cep, 'rua' => $rua, 'numero' => $numero, 'estado' => $estado, 'contato' => $contato];
	
		session()->put("Clients", $clientes);

        $enderecos = session()->get('enderecos', []);

        foreach ($clientes as $key => $value) {
            $cliente_id = $key;
        }

        $enderecos[] = ['cliente_id' => $cliente_id, 'cidade' => $cidade, 'cep' => $cep, 'rua' => $rua, 'numero' => $numero, 'estado' => $estado];

        session()->put('enderecos', $enderecos); 
	}

	public function editarCliente($cliente_id, $name,$email,$idade, $contato)
	{
		if(session()->has('Clients'))
        {
            $EditClientID = session()->get('Clients');

            if(array_key_exists($cliente_id, $EditClientID))
            {
                $EditClientID[$cliente_id]['name'] = $name;
                $EditClientID[$cliente_id]['email'] = $email;
                $EditClientID[$cliente_id]['idade'] = $idade;
                $EditClientID[$cliente_id]['contato'] = $contato;
                    
                session()->put('Clients', $EditClientID);
            }
        }
	}

    public function listarClientes()
    {
        $clientes = session()->get('Clients', []);
        $listarClientes = [];

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

            $listarClientes[$key] = ['name' => $nome_cliente, 'email' => $email_cliente, 'idade' => $idade_cliente, 'cidade' => $cidade_cliente, 'cep' => $cep_cliente, 'rua' => $rua_cliente, 'numero' => $numero_cliente, 'estado' => $estado_cliente, 'contato' => $contato_cliente];       
        }

        return $listarClientes;
    }
          
    public function excluirCliente($cliente_id)
    {
        if(session()->has('Clients'))
        {
            $clientes = session()->get('Clients');

            if(array_key_exists($cliente_id, $clientes))
            {
                unset($clientes[$cliente_id]);
                session()->put('Clients', $clientes);
            }
        }
    }   

    public function buscarCliente($cliente_id)
    {
        if(session()->has('Clients'))
        {
            $clientes = session()->get('Clients');
            $clienteID = [];

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

                if($key == $cliente_id) {
                    $clienteID[$key] = ['name' => $nome_cliente, 'email' => $email_cliente, 'idade' => $idade_cliente, 'cidade' => $cidade_cliente, 'cep' => $cep_cliente, 'rua' => $rua_cliente, 'numero' => $numero_cliente, 'estado' => $estado_cliente, 'contato' => $contato_cliente];
                } 
            }   
        }

        return $clienteID;
    }
}
