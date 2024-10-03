<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Produtos') }}
        </h2>
    </x-slot>


<div style="display: flex; justify-content: center; margin-top: 20px;">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">Adicionar novo produto</button>
</div>

<div >
    <div class="sub-container">
       
        @if(isset($Produtos))
        @foreach ($Produtos as $key => $value)
        
        <div class="sub-item">
            <div style="font-weight: 900">
                
                ID: <span style="font-weight: 300; font-size: 18px">{{$key}}</span>
                
            </div>
            
            <div class="image-com-desconto">
                @if($value['image_url'] == false)
                <img src="{{ asset('images/default.png') }}">
                @else
                <img  src="{{ $value['image_url'] }}">
                @endif
            </div>
            <div>
                <p>{{strtoupper($value['produto'])}}</p>
            </div>
            <p class="preco"><span style="color: black"></span>R$ {{  number_format($value['valor'], 2, ",", ".")}}</p>

            
            <p class="preco-desconto" style="margin-top: 15px;"><span style="color: black"></span>Quantidade no estoque: {{  $value['quantidade_estoque']}}</p>
            @if(count($Produtos[$key]['promocao']) != [])
            <div class="preco-desconto">Desconto por quantidade</div>
            <div style="display: flex; justify-content: center;">
                <table style="border: none;">
                    <tr>
                        <th class="preco-desconto">Quantidade</th>
                        <th class="preco-desconto">Preço</th>
                    </tr>
                    @foreach ($Produtos[$key]['promocao'] as $id => $valor)
                    @foreach($valor as $chave => $dado)
                    @if($dado['produto_id'] == $key)
                    <tr>
                        <td class="preco-desconto">{{$dado['quantidade']}}</td>
                        <td class="preco-desconto">R${{  number_format($value['valor'] - ($value['valor'] / 100 * $dado['porcentagem']), 2, ",", ".")}}</td>
                    </tr>
                    @endif
                    @endforeach
                    @endforeach
                </table>
            </div>
            @endif

            <table style="border: none;">
                <tbody>
                <tr>
                    <td>
                        <form action="/DeletarProduto/{{$key}}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-custom" type="submit">Deletar</button>
                        </form>
                    </td>
                    <td>
                        <form action="/detalhe_produto/{{$key}}" method="GET">
                            @csrf
                            <button class="btn btn-primary btn-custom" type="submit">Histórico</button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <form action="/EditarProduto/{{$key}}" method="GET">
                            @csrf
                            <button class="btn btn-warning btn-custom" type="submit">Editar</button>
                        </form>
                    </td>
                    <td>
                        <form action="/entradas_saidas/{{$key}}" method="GET">
                            @csrf
                            <button class="btn btn-success btn-custom" type="submit">Entrada/Saída</button>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        @endforeach
        @else
        <td colspan="10">Sem dados de registro!</td>
        @endif
    </div>
</div>
</x-app-layout>

<div class="modal fade" id="CadastrarClienteModal" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar novo produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div  class="p-6 bg-white border-b border-gray-200">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <form id="po-insert" method="POST" action="/CadastrarProduto" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="categoria-select" class="block text-sm font-medium text-gray-700">Escolha uma categoria:</label>
                                        <select name="categoria" id="categoria-select" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">--Por favor escolha uma categoria--</option>
                                            @if(isset($categorias))
                                            @foreach ($categorias as $categoria_id => $value)
                                            <option value="{{ $categoria_id }}">{{ $value['categoria'] }}</option>
                                            @endforeach
                                            @else
                                            <option disabled>Sem dados de registro!</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="produtoEstoque" class="block text-sm font-medium text-gray-700">Nome do Produto:</label>
                                        <input type="text" id="produtoEstoque" name="produtoEstoque" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                                    </div>
                                    <div class="mb-4">
                                        <label for="file" class="block text-sm font-medium text-gray-700">Escolha o arquivo para upload:</label>
                                        <input type="file" accept="image/*" name="imagem" id="imageFile" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" multiple/>
                                    </div>
                                    <div class="mb-4">
                                        <label for="quantidade_estoque" class="block text-sm font-medium text-gray-700">Quantidade:</label>
                                        <input type="number" id="quantidade_estoque" name="quantidade_estoque" step="any" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0" />
                                    </div>
                                    <div class="mb-4">
                                        <label for="valorEstoque" class="block text-sm font-medium text-gray-700">Valor:</label>
                                        <input min="1"; type="number" id="valorEstoque" name="valorEstoque" step="any" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  />
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-4">Cadastrar</button>
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