<?php

namespace App\Services;

interface ClientesServiceInterface
{
	function adicionarCliente($name,$email,$idade,$cidade, $cep, $rua, $numero, $estado, $contato);
	function excluirCliente($cliente_id);
	function editarCliente($cliente_id,$name,$email,$idade, $contato);
	function listarClientes($softDeletes);
	function buscarCliente($cliente_id);
}
