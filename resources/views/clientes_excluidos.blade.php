<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Clientes excluidos') }}
        </h2>

    </x-slot>
<div class="py-12">
    <div class="max-w mx-auto sm:px-6 lg:px-8">
        
        <div class="input-group" style="width: 20%; margin-bottom: 25px;">
            <input   id= "search" type="search" class="form-control rounded" placeholder="Procurar clientes" aria-label="Search" aria-describedby="search-addon" />
            <button type="button" class="btn btn-outline-primary" data-mdb-ripple-init>Procurar</button>
        </div>
       
        <table id="table">
            <thead >
                <tr>
                    <th style="width: 3%;">ID</th>
                    <th style="width: 3%;">Excluido por</th>
                    <th style="width: 15%;">Nome</th>
                    <th>Email</th>
                    <th style="width: 5%;">Idade</th>
                    <th>Endereço</th>
                    <th>Contato</th>
                    <th>Total</th>
                    <th>Ação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($tabela_clientes))
                @foreach ($tabela_clientes as $key => $value )

                @if($value['deleted_at'] != null)
                <tr style="background: white;">
                    <td style="font-size: 16px;  color:white; background: black; font-weight: 900; border: 1px solid">{{  $key }}</td>
                    <td style ="width: 10%; border: 1px solid; ">{{  strtoupper($value['deleted_by']) }}</br>{{  strtoupper($value['deleted_at']) }}</td>
                    <td style ="border: 1px solid; ">{{  strtoupper($value['name']) }}</td>
                    <td style ="border: 1px solid;">{{  $value['email'] }}</td>
                    <td style ="border: 1px solid;">{{  $value['idade'] }}</td>
                    <td style="width: 30%;  text-align: left; border: 1px solid; border-right: none;">
                        <ol style="list-style-type: decimal; list-style-position: inside; ">
                            
                            @if($listar_enderecos != [])
                            @foreach ($listar_enderecos as $id_endereco => $endereco)
                            @if($key == $endereco['cliente_id'])
                            
                            <li style="  border-right: none; ">
                                
                                {{ strtoupper($endereco['rua'] . ", " . $endereco['numero'] . ", ". $endereco['cidade'] .", ".$endereco['estado'].", ". $endereco['cep'])}}
                            </li>
                            @endif
                            @endforeach
                        </ol>
                        @endif
                    </td>
                    <td style ="border: 1px solid;">{{  $value['contato'] }}</td>
                    <td style="border: 1px solid; color:green;"> R$ {{isset($total[$key])  ? $total[$key]  : 0}}</td>
                    <td style="border: 1px solid;">
                        <form  action="/restaurarCliente/{{$key}}" method="POST" >
                            @csrf
                            <button class="btn btn-success"  style="padding: 5px;" type="submit">Restaurar</button>
                        </form>
                    </td>
                    <td style="border: 1px solid black;">
                        <form  action="/Cliente/{{$key}}" method="GET" >
                            @csrf
                            <button    class="btn btn-primary"  style="padding: 5px;" type="submit">Visualizar pedidos</button>
                        </form>
                    </td>
                </tr>
                @endif
                @endforeach
                @else
                <td colspan="10">Sem dados de registro!</td>
                @endif
            </tbody>
        </table>
    </div>
    
</div>
</div>
</x-app-layout>
