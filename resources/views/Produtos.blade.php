<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Produtos') }}
    </h2>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</x-slot>
<style type="text/css">

.container-geral {
    display: flex;
    justify-content: center;
    margin: 0 auto;
}

.sub-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin: 0 auto;
}

.sub-container-item {
    border: 1px solid #ccc;
    background: #fff;
    border-radius: 10px;
    margin: 32px;
    width: 256px;
    text-align: center;
    align-items: center;
    font-size: 18px;
    transition: all .3s ease-in-out;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 10px;

}

.sub-container-item:hover {
    transform: scale(1.05); 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.sub-container-id{
    color: black;
    font-weight: bold;
    font-size: 24px;
    padding: 10px;
}
.sub-container-name-product {
    color: black;
    font-weight: 600;
    font-size: 24px;
}
.sub-container-image {
    padding: 10px;
    width: 256px; 
    height: 200px; 
    display: flex;
    justify-content: center;
}
.sub-container-image-com-desconto {
    width: 250px;
    height: 140px; 
    display: flex;
    justify-content: center;
}

.sub-preco {
    color: black;
    font-weight: 600;
    font-size: 20px;
  
}
.sub-preco-desconto {
    font-size: 14px;
}

.sub-container-form {
    margin-top: 3px;
    display: flex;
    justify-content: space-around;
}

.sub-container-item-sem-desconto {
    border: 1px solid #ccc;
    background: #fff;
    border-radius: 10px;
    height: 425px;
    margin: 32px;
    width: 256px;
    text-align: center;
    align-items: center;
    font-size: 18px;
    transition: all .3s ease-in-out;

}
.sub-container-item-sem-desconto:hover {
    transform: scale(1.05); 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}


img {
    max-width: 100%;
    height: auto;
    object-fit: cover;
}

.btn-edit {
    display: inline-block;
    padding: 5px 10px;
    margin-left: 5px;
    font-size: 16px;
    width: 120px;
    text-align: center;
    color: #fff;
    background-color: #007bff; /
    border: none;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s; 
}

.btn-sucesso {
    background-color: #28a745;
}
.btn-deletar {
    background-color: #dc3545;
}
.btn-editar {
    background-color: #ffc107;
}

.btn-edit:hover {
    background-color: #0056b3; 
    transform: scale(1.05); 
}
.btn-sucesso:hover{
    background-color: #218838; 
    transform: scale(1.05); 
}
.btn-deletar:hover{
    background-color: #c82333; 
    transform: scale(1.05); 
}

.btn-editar:hover{
    background-color: #e0a800; 
    transform: scale(1.05); 
}

</style>

<div style="display: flex; justify-content: center; margin-top: 20px;">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">Adicionar novo produto</button>
</div>

        
<div class="container-geral">
    <div class="sub-container">

    @if(isset($EstoqueProdutos))
    @foreach ($EstoqueProdutos as $key => $value)
    
        <div class="sub-container-item">
            <div style="font-weight: 900">
        
                    ID: <span style="font-weight: 300; font-size: 18px">{{$key}}</span>
               
            </div>
    
            <div class="sub-container-image-com-desconto">
                @if($value['image_url'] == false)
                <img src="{{ asset('images/default.png') }}">
                @else
                <img  src="{{ $value['image_url'] }}">
                @endif
            </div>
            <div class="sub-container-name-product">
                <p>{{strtoupper($value['produto'])}}</p>
            </div>
            <p class="sub-preco"><span style="color: black"></span>R$ {{  number_format($value['valor'], 2, ",", ".")}}</p>
            <p class="sub-preco-desconto"><span style="color: black"></span>Quantidade no estoque: {{  $value['quantidade_estoque']}}</p>
            @if(count($EstoqueProdutos[$key]['promocao']) != [])
            <div class="sub-preco-desconto">Desconto por quantidade</div>
            <div style="display: flex; justify-content: center;">
                <table>
                    <tr>
                        <th class="sub-preco-desconto">Quantidade</th>
                        <th class="sub-preco-desconto">Preço</th>
                    </tr>
                  @foreach ($EstoqueProdutos[$key]['promocao'] as $id => $valor)
                    @foreach($valor as $chave => $dado)
                        @if($dado['produto_id'] == $key)
                    <tr>
                    <td class="sub-preco-desconto">{{$dado['quantidade']}}</td>
                    <td class="sub-preco-desconto">R${{  number_format($value['valor'] - ($value['valor'] / 100 * $dado['porcentagem']), 2, ",", ".")}}</td>
                    </tr>
            @endif
                  @endforeach
                  @endforeach
                </table>
            </div>
            @endif
            <div class="sub-container-form">
                <p>
                    <form  action="/DeletarProduto/{{$key}}" method="POST" >
                        @csrf
                        @method('delete')
                        <button class="btn-edit btn-deletar"  type="submit">Deletar</button>
                    </form>
                </p>
                <p>
                    <form  action="/Produto/{{$key}}" method="GET" >
                        @csrf
                        <button class="btn-edit"  type="submit">Visualizar</button>
                    </form>
                </p>
            </div>
            <div class="sub-container-form">
                <p>
                    <form  action="/EditarProduto/{{$key}}" method="GET" >
                        @csrf
                        <button class="btn-edit btn-editar"  type="submit">Editar</button>
                    </form>
                </p>
                <p>
                    <form  action="/entradas_saidas/{{$key}}" method="GET" >
                        @csrf
                        <button class="btn-edit btn-sucesso"  type="submit">Entrada/Saída</button>
                    </form>
                </p>
            </div>
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
                <div id="container-cadastro-cliente" class="p-6 bg-white border-b border-gray-200">
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