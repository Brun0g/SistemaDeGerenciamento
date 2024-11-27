<?php

namespace App\Services;


use App\Models\Endereco;

use App\Services\EnderecoServiceInterface;

class DBEnderecosService implements EnderecoServiceInterface
{
    function encontrarClienteID($endereco_id)
    {
        $enderecos = Endereco::find($endereco_id);

        foreach ($enderecos as $id) {
            $cliente_id = $enderecos['cliente_id'];
        }

        return $cliente_id;
    }

    public function adicionarEndereco($cliente_id, $cidade, $cep, $rua, $numero, $estado)
    {
        $endereco = new Endereco;

        $endereco->cliente_id = $cliente_id;
        $endereco->cidade = $cidade;
        $endereco->cep = $cep;
        $endereco->rua = $rua;
        $endereco->numero = $numero;
        $endereco->estado = $estado;

        $endereco->save();
    }

    public function editarEndereco($endereco_id, $cidade, $cep, $rua, $numero, $estado)
    {
        $endereco= Endereco::find($endereco_id);

        $endereco->cidade = $cidade;
        $endereco->cep = $cep;
        $endereco->rua = $rua;
        $endereco->numero = $numero;
        $endereco->estado = $estado;
        
        $endereco->save();
    }
    
    public function deletarEndereco($endereco_id)
    {
        $endereco = Endereco::find($endereco_id);

        $endereco->delete($endereco_id);
    }

    public function listarEnderecos()
    {
        $enderecos = Endereco::all();
        $listarEnderecos = [];

        foreach ($enderecos as $endereco) 
        {
            $cliente_id_endereco = $endereco->cliente_id;
            $cidade_endereco = $endereco->cidade;
            $cep_endereco = $endereco->cep;
            $rua_endereco = $endereco->rua;
            $numero_endereco = $endereco->numero;
            $estado_endereco = $endereco->estado;
            
            $listarEnderecos[$endereco->id] = ['cliente_id' => $cliente_id_endereco, 'cidade' => $cidade_endereco, 'cep' => $cep_endereco, 'rua' => $rua_endereco, 'numero' => $numero_endereco, 'estado' => $estado_endereco];       
        }

        return $listarEnderecos;
    }

    function buscarEndereco($endereco_id)
    {
        $enderecos = Endereco::find($endereco_id);

        $lista = [];

        foreach ($enderecos as $endereco) {
            $endereco_client_id = $enderecos->cliente_id;
            $endereco_cidade = $enderecos->cidade;
            $endereco_cep = $enderecos->cep;
            $endereco_rua = $enderecos->rua;
            $endereco_numero = $enderecos->numero;
            $endereco_estado = $enderecos->estado;
        }

        $lista = $endereco_cidade . ", " . $endereco_cep . ", " . $endereco_rua . ", " . $endereco_numero . ", " . $endereco_estado;

        return $lista;
    }
}
