<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clientes') }}
        </h2>

    </x-slot>
    
    <div class="py-12 ">
        <div class="max-w mx-auto sm:px-6 lg:px-8 " >
         
            <div class="input-group" style="width: 20%">
                <form  action="/Clientes" method="GET" >
                    @csrf

                    <input value="{{$search}}" class="form-control rounded" placeholder="Procurar clientes" name="search" />
                    <button type="submit" class="btn btn-outline-primary" data-mdb-ripple-init>Procurar</button>
                </form>
            </div>
            <div style="display: flex; justify-content: center; margin-bottom: 10px;">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">Adicionar novo cliente</button>
            </div>
            <table id="table" class="rounded shadow-lg ">
                <thead class="thead ">
                    <tr>
                        <th  style="width: 3%;">ID</th>
                        <th  style="width: 15%;">Cliente</th>
                        <th  style="width: 1%;">Email</th>
                        <th  style="width: 2%;">Idade</th>
                        <th  style="width: 30%;">Endereço</th>
                        <th>Contato</th>
                        <th  style="width: 10%;">Total</th>
                        <th  style="width: 4%;">Ação</th>
                        <th  style="width: 10%;">Ação</th>
                        <th  style="width: 4%;">Ação</th>
                    </tr>
                </thead>
                <tbody>

                    @if(isset($tabela_clientes))

                    @foreach ($tabela_clientes as $cliente_id => $value )
                    @if( $value['deleted_at'] == null)
                    <tr style="background: white;">
                        <td style="font-size: 16px;  color:white; background: black; font-weight: 900; border: 1px solid">{{  $cliente_id }}</td>
                        <td style ="width: 15%; border: 1px solid; ">{{  strtoupper($value['name']) }}</td>
                        <td style ="width: 15%; border: 1px solid;">{{  $value['email'] }}</td>
                        <td style ="width: 2%; border: 1px solid;">{{  $value['idade'] }}</td>
                        <td style="width: 28%;  text-align: left; border: 1px solid; border-right: none;">
                            <ol style="list-style-type: decimal; list-style-position: inside; ">
                                
                                @if($listar_enderecos != [])
                                @foreach ($listar_enderecos as $id_endereco => $endereco)
                                @if($cliente_id == $endereco['cliente_id'])
                                
                                <li style="  border-right: none; ">
                                    
                                    {{ strtoupper($endereco['rua'] . ", " . $endereco['numero'] . ", ". $endereco['cidade'] .", ".$endereco['estado'].", ". $endereco['cep'])}}
                                </li>
                                @endif
                                @endforeach
                            </ol>
                            @endif
                        </td>
                        
                        <td style ="border: 1px solid;">{{  $value['contato'] }}</td>
                        <td style="border: 1px solid; color:green;"> R$ {{$value['total_pedido']}}</td>
                        <td style="width: 2%; border: 1px solid;">
                            <form  action="/DeletarCliente/{{$cliente_id}}" method="POST" >
                                @csrf
                                @method('delete')
                                <button    class="btn btn-danger"  style="padding: 5px;" type="submit">Deletar</button>
                            </form>
                        </td>
                        <td style="width: 7%; border: 1px solid black;">
                            <form  action="/Cliente/{{$cliente_id}}" method="GET" >
                                @csrf
                                <button    class="btn btn-primary"  style="padding: 5px;" type="submit">Visualizar pedidos</button>
                            </form>
                        </td>
                        <td style="width: 7%; border: 1px solid black;">
                            <form  action="/Editar/Cliente/{{$cliente_id}}" method="GET" >
                                @csrf
                                <button    class="btn btn-primary"  style="padding: 5px;" type="submit">Editar cliente</button>
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


<div class="modal fade" id="CadastrarClienteModal" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            @if ($errors->any())
            <div class="alert alert-danger">
                <table>
                    <th class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error )
                        <td>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </table>
                </div>
                @endif
                <div class="modal-header">
                    <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar novo cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="po-insert"  method="POST" action = "/cadastrarCliente">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nome</label>
                                <input type="text" class="form-control" placeholder="Nome completo" name="name">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="Email" name="email">
                            </div>
                            <div class="form-group col-md-6">
                                <label >Idade</label>
                                <input type="number" class="form-control" placeholder="Idade" name="idade">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Contato</label>
                                <input type="text" class="form-control" placeholder="Contato" name="contato">
                            </div>
                        </div>
                        <h4>Endereço</h4>
                        <hr></hr>
                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <label >Cidade:</label>
                                <input type="text" class="form-control" placeholder="Cidade" name="cidade">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Rua:</label>
                                <input type="text" class="form-control" placeholder="Rua" name="rua">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Número:</label>
                                <input type="text" class="form-control" placeholder="Número" name="numero">
                            </div>
                            
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label for="inputState">Estado:</label>
                                <select id="inputState" class="form-control" name="estado">
                                    <option selected>Escolha um estado</option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputZip">CEP:</label>
                                <input type="text" class="form-control" id="inputZip" name="cep" placeholder="Cep">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
