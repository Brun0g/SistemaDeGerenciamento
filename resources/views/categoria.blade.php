<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Categorias') }}
        </h2>

    </x-slot>


<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
        
        <div style="display: flex; justify-content: center; margin-bottom: 10px;">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">Adicionar categoria</button>
        </div>
        <table id="table" class="rounded shadow-lg">
            <thead >
                <tr>
                    <th>ID</th>
                    <th>Categoria</th>
                    <th>Ação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($categorias))
                @foreach ($categorias as $key => $value )
                <tr style="background: white;">
                    <td style="color:white; background: black; font-weight: 900; border: 1px solid">
                    {{  $key }}</td>
                    <td>{{  strtoupper($value['categoria']) }}</td>
                    <td>
                        <form  action="/DeletarCategoria/{{$key}}" method="POST" >
                            @csrf
                            @method('delete')
                            <button    class="btn btn-primary"  type="submit">Deletar</button>
                        </form>
                    </td>
                    <td>
                        <form  action="/Categoria/{{$key}}" method="GET" >
                            @csrf
                            <button    class="btn btn-primary"  type="submit">Editar</button>
                        </form>
                    </td>
                </tr>
                
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
        <div class="modal-header">
            <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar categoria</h5>
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
                                    <th class="mt-3 list-disc list-inside text-sm text-red-600">
                                        @foreach ($errors->all() as $error )
                                    <td>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </table>
                        </div>
                        @endif
                        <form id="po-insert"  method="POST" action ="/CadastrarCategoria">
                            @csrf
                            <label  for = "name">Categoria do Produto</label>
                            <x-input class="input-margin-top" type="text"  name="categoriaEstoque"/>
                            <button class="btn btn-primary" >Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
    </div>
</div>
</div>
</div>