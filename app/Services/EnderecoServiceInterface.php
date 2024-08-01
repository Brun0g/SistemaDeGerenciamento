<?php

namespace App\Services;

interface EnderecoServiceInterface
{
	function adicionarEndereco($cliente_id, $cidade, $cep, $rua, $numero, $estado);
	function editarEndereco($endereco_id, $cidade, $cep, $rua, $numero, $estado);
	function deletarEndereco($endereco_id);
	function listarEnderecos();
	function buscarEndereco($endereco_id);
	function encontrarClienteID($endereco_id);
}
