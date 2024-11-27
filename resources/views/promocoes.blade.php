<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Promoções') }}
        </h2>
    </x-slot>
    
    @if ($errors->any())
    <div  class="p-6 bg-white border-b border-gray-200">
        <div class="alert alert-danger">
            <table>
                @foreach ($errors->all() as $error )
                <td>{{ $error }}</td>
                @endforeach
            </table>
        </div>
        @endif
        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 ">
                
                <div style="display: flex; justify-content: center; margin-bottom: 10px;">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">Adicionar promoção</button>
                </div>
                <table id="table" class="shadow-lg">
                    <thead >
                        <tr>
                            <th>ID</th>
                            <th>Criado por</th>
                            <th>Restaurado por</th>
                            <th>Ativado por</th>
                            <th>Desativado por</th>
                            <th>Situação</th>
                            <th  style="width: 2%;">Nome do Produto</th>
                            <th  style="width: 5%">Preço original</th>
                            <th  style="width: 5%">Preço com desconto</th>
                            
                            <th>Porcentagem</th>
                            <th>Quantidade</th>
                            <th style="width: 1%">Ação</th>
                            <th style="width: 1%">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($listaPromocoes))
                        @foreach ($listaPromocoes as $key => $value)

                        @if($value['deleted_at'] == null)
                        <tr style="background: white;">
                            <td style="color:white; background: black; font-weight: 900; border: 1px solid; width: 2%">
                            {{  $key }}</td>

                            <td style="font-size: 14px; border: 1px solid; width: 5%">
                            {{  $value['create_by'] }} </br> {{$value['created_at']}}</td>
                            <td style="font-size: 14px; border: 1px solid; width: 5%">
                            {{  $value['restored_by'] }} </br> {{$value['restored_at']}}</td>
                            <td style="font-size: 14px; border: 1px solid; width: 5%">
                            {{  $value['active_by'] }} </br> {{$value['active_at']}}</td>
                            <td style="font-size: 14px; border: 1px solid; width: 5%">
                            {{  $value['deactivate_by'] }} </br> {{$value['deactivate_at']}}</td>
                            @if($value['ativo'] == 0)
                            <td style="border: 1px solid; width: 1%">
                                <form  action="/ativarpromocao/{{$key}}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button name= 'situacao' value="{{$value['ativo'] + 1}}" class="{{$value['ativo'] == 0 ? 'btn btn-danger' : 'btn btn-success'}}"  type="submit">{{  $value['ativo'] == 0 ? 'DESATIVADO' : 'ATIVADO' }}</button>
                                </form>
                            </td>
                            @else
                            <td style="border: 1px solid; width: 1%">
                                <form action="/ativarpromocao/{{$key}}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button name= 'situacao' value="{{$value['ativo'] - 1}}" class="{{$value['ativo'] == 0 ? 'btn btn-danger' : 'btn btn-success'}}"  type="submit">{{  $value['ativo'] == 0 ? 'DESATIVADO' : 'ATIVADO' }}</button>
                                </form>
                            </td>
                            @endif
                            <form action="/atualizarpromocao/{{$key}}" method="POST">
                                @csrf
                                @method('PATCH')
                                <td style="border: 1px solid; width: 5%">{{  strtoupper($value['produto']) }}</td>
                                <td style="border: 1px solid black; width: 5%; color: green;">R$ {{  number_format($value['preco_original'], 2, ",", ".") }}</td>
                                <td style="border: 1px solid black; width: 5%; color: green;">R${{  number_format($value['preco_desconto'], 2, ",", ".") }}</td>
                                
                                <td style="width: 2%; border: 1px solid;">
                                    <input type="number" style="width: 80%; border: hidden; text-align: center;" name="atualizarPorcentagem" value="{{ $value['porcentagem'] }}" min="0" max="100">
                                </td>

                                <td style="width: 2%; border: 1px solid;">
                                    <input type="number" style="width: 80%; border: hidden; text-align: center;" name="atualizarQuantidade" value="{{ $value['quantidade'] }}" min="0" max="9999">
                                </td>

                                
                                <td style="width: 1%; border: 1px solid;">
                                    <button class="btn btn-primary" type="submit">Atualizar</button>
                                </td>
                            </form>
                            
                            <td style="border: 1px solid; width: 1%">
                                <form  action="/deletarPromocao/{{$key}}" method="POST" >
                                    @csrf
                                    @method('delete')
                                    <button    class="btn btn-danger"  type="submit">Deletar</button>
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
</div>
</x-app-layout>
<div class="modal fade" id="CadastrarClienteModal" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar nova promoção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div id ="container-cadastro-cliente" class="p-6 bg-white border-b border-gray-200">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <table>
                                        @foreach ($errors->all() as $error )
                                        <td>{{ $error }}</td>
                                        @endforeach
                                    </table>
                                </div>
                                @endif
                                <form id="po-insert"  method="POST" action ="/adicionarpromocao">
                                    @csrf
                                    
                                    <label for="produto-select">Escolha um produto:</label>
                                    <select  name="produto_id" id="categoria-select" style="width: 100%">
                                        <option value="">Por favor escolha um produto--</option>
                                        @if($produtos != [])
                                        @foreach ($produtos as $key => $value)
                                        <option value={{$key}}>{{  strtoupper($value['produto']) }}</option>
                                        @endforeach
                                        @else
                                        Sem dados de registro!
                                        @endif
                                    </select>
                                    <div>
                                        <label>Porcentagem de desconto</label>
                                        <input type="number" step="any" class="input-margin-top" name="porcentagem"/>
                                    </div>
                                    <div>
                                        <label>Quantidade</label>
                                        <input type="number" step="any" class="input-margin-top" name="quantidade"/>
                                    </div>
                                    
                                    <button class="btn btn-primary" >Cadastrar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error )
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
