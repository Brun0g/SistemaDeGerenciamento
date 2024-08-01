<?php

namespace App\Services;

use App\Services\EnderecoServiceInterface;

class SessionEnderecoService implements EnderecoServiceInterface
{
    public function adicionarEndereco($cliente_id, $cidade, $cep, $rua, $numero, $estado)
    {
        $enderecos = session()->get('enderecos', []);

        $enderecos[] = ['cliente_id' => $cliente_id, 'cidade' => $cidade, 'cep' => $cep, 'rua' => $rua, 'numero' => $numero, 'estado' => $estado];

        session()->put('enderecos', $enderecos);
    }

    public function listarEnderecos()
    {
        $enderecos = session()->get('enderecos', []);
        $listarEnderecos = [];

        foreach ($enderecos as $key => $value) 
        {
            $cliente_id = $value['cliente_id'];
            $cidade_endereco = $value['cidade'];
            $cep_endereco = $value['cep'];
            $rua_endereco = $value['rua'];
            $numero_endereco = $value['numero'];
            $estado_endereco = $value['estado'];
    
            $listarEnderecos[$key] = ['cliente_id' => $cliente_id, 'cidade' => $cidade_endereco, 'cep' => $cep_endereco, 'rua' => $rua_endereco, 'numero' => $numero_endereco, 'estado' => $estado_endereco];       
        }

        return $listarEnderecos;
    }

    public function deletarEndereco($endereco_id)
    {
        $endereco = session()->get('enderecos', []);

        unset($endereco[$endereco_id]);

        session()->put('enderecos', $endereco);
    }
    
    function editarEndereco($endereco_id, $cidade, $cep, $rua, $numero, $estado)
    {
        if(session()->has('enderecos'))
        {
            $endereco = session()->get('enderecos', []);

            if(array_key_exists($endereco_id, $endereco))
            {
                $endereco[$endereco_id]['cidade'] = $cidade;
                $endereco[$endereco_id]['cep'] = $cep;
                $endereco[$endereco_id]['rua'] = $rua;
                $endereco[$endereco_id]['numero'] = $numero;
                $endereco[$endereco_id]['estado'] = $estado;
                    
                session()->put('enderecos', $endereco);
            }
        }
    }

    function encontrarClienteID($endereco_id)
    {
        $enderecos = session()->get('enderecos', []);

        foreach ($enderecos as $endereco) {
            $cliente_id = $endereco['cliente_id'];
        }

        return $cliente_id;
    }
    
    function buscarEndereco($endereco_id)
    {
        $enderecos = session()->get('enderecos', []);

        foreach ($enderecos as $key => $endereco) {
            if($key == $endereco_id)
            {
                $endereco_client_id = $endereco['cliente_id'];
                $endereco_cidade = $endereco['cidade'];
                $endereco_cep = $endereco['cep'];
                $endereco_rua = $endereco['rua'];
                $endereco_numero = $endereco['numero'];
                $endereco_estado = $endereco['estado'];
            } 
        }

        $lista = $endereco_cidade . ", " . $endereco_cep . ", " . $endereco_rua . ", " . $endereco_numero . ", " . $endereco_estado;

        return $lista;
    }
}
