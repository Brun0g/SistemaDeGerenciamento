<x-app-layout>
    <x-slot name="header">

    </x-slot> 

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         <div class="caption-style" style="display: flex; flex-direction: column; ">
                HISTORICO DO CLIENTE
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
            <table >
                <thead>
                    <tr>
                        <th>Criado por</th>
                        <th>Editado por</th>
                        <th>Restaurado por</th>  
                    </tr>
                </thead>
                <tbody>

                    <tr class="bg-white">
                        <td style="color: black;">{{ strtoupper( $cliente['create_by'] ) }}</td>
                        <td style="color: black;">{{ isset($cliente['update_by']) ? strtoupper( $cliente['update_by'] ) : ''  }}</td>
                        <td style="color: black;">{{ isset($cliente['restored_by']) ? strtoupper( $cliente['restored_by'] ) : ''  }}</td>
                    </tr>

                    <tr class="bg-white">

                        <td style="color: black;">{{ $cliente['created_at'] }}</td>
                        <td style="color: black;">{{ isset($cliente['update_by']) ? $cliente['updated_at'] : ''  }}</td>
                        <td style="color: black;">{{ isset($cliente['restored_at']) ? $cliente['restored_at'] : ''  }}</td>      
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
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
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="margin-top: 10px;">

    <form method="POST" action="/atualizarCliente/{{$id}}" >
        @csrf
        @method('PATCH')

                <div class="caption-style" style="display: flex; flex-direction: column;">
                EDITAR DADOS DO CLIENTE
                <div></div>
            </div>
        <div style="display: flex; justify-content: center; margin-bottom: 20px;">
            
            <table id="table">
                <thead >
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Idade</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white">
                        <td>
                            <input type="text" class="form-control" name="name" value="{{  $cliente['name'] }}" >
                        </td>
                        <td>
                            <input type="email" class="form-control" name="email" value="{{  $cliente['email'] }}" >
                        </td>
                        <td>
                            <input type="text" class="form-control" name="contato" value="{{  $cliente['contato'] }}" >
                        </td>
                        <td>
                            <input style="text-align: center;" type="text" class="form-control" name="idade" value="{{  $cliente['idade'] }}" >
                        </td>
                        <td> <button class="btn btn-success" type="submit">Atualizar</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    
    <div class="max-w-8xl mx-auto sm:px-7 lg:px-9">

        <div style="display: flex; justify-content: center; margin-bottom: 20px; margin-top: 20px;">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">Adicionar Endereço</button>
        </div>
        <table id="table">
            <thead >
                <tr>
                    <th>ID</th>
                    <th>Cidade</th>
                    <th>Rua</th>
                    <th>Número</th>
                    <th>Estado</th>
                    <th>CEP</th>
                    <th>Ação</th>
                    @if(count($listarEnderecos) > 1)
                    <th>Ação</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white">
                    @if($listarEnderecos != [])
                    @foreach ($listarEnderecos as $endereco_id => $endereco)
                    <td style="border: 1px solid; background: black; color: #fff;">{{$endereco_id}}</td>
                    <td style="border: 1px solid;">{{$endereco['cidade']}}</td>
                    <td style="border: 1px solid;">{{$endereco['rua']}}</td>
                    <td style="border: 1px solid;">{{$endereco['numero']}}</td>
                    <td style="border: 1px solid;">{{$endereco['estado']}}</td>
                    <td style="border: 1px solid;">{{$endereco['cep']}}</td>
                    @if(count($listarEnderecos) > 1)
                    <td style="border: 1px solid;" class="bg-white">
                        
                        <form action="/DeletarEndereco/{{$endereco_id}}" method="POST" >
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger"  type="submit">Deletar</button>
                        </form>
                        @endif
                    </td>
                    
                    <td style="border: 1px solid;">
                        <form action="/VisualizarEndereco/{{$endereco_id}}" method="GET" >
                            @csrf
                            <button class="btn btn-primary" type="submit">Editar</button>
                        </form>
                        
                        
                        
                    </td>
                    
                </tr>
                
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="CadastrarClienteModal" tabindex="-1" role="dialog" aria-labelledby="2" aria-hidden="true">
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
                <h5 class="modal-title" id="TituloModalCentralizado">Novo endereço</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="po-insert"  method="POST" action = "/novoEndereco/{{$id}}">
                    @csrf
                    
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
                    <div style="display: flex; justify-content: center;">
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</x-app-layout>
    