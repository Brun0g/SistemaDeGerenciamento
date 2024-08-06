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
.sub-container-form {
    display: flex;
    justify-content: space-between;
}

.sub-preco {
    color: green; 
    font-weight: 900; 
    font-size: 18px;
}
.sub-container-item {
    border: 2px solid black;
    background: white;
    margin: 32px 32px 32px 32px;
    width: 256px;
    text-align: center;
    align-items: center;
    font-size: 18px;
    transition: all .2s ease-in-out; 
}
.sub-container-item:hover {
    transform: scale(1.1);
    box-shadow: 6px 6px 6px 1px rgba(0, 0, 0, 0.2);
}

.sub-container-image {

    width: 254.4px;
    height: 250px;
    display: flex;
    justify-content: center;
}
.sub-container-name-product {
    background: black;
    color: white;
    font-weigth: bold;
}

img {
    
    object-fit: cover;
}

</style>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">+</button>
<div class="container-geral">
    <div class="sub-container">
    @if(isset($EstoqueProdutos))
    @foreach ($EstoqueProdutos as $key => $value)
        <div class="sub-container-item">
            <div class="sub-container-name-product">
                <p>ID: {{$key}}</p>
                <p>{{strtoupper($value['produto'])}}</p>
            </div>
            <div class="sub-container-image">
                @if($value['image_url'] == false)
                <img src="{{ asset('images/default.png') }}">
                @else
                <img  src="{{ $value['image_url'] }}">
                @endif
            </div>
            <p class="sub-preco">R$: {{  number_format($value['valor'], 2, ",", ".")}}</p>
            <div class="sub-container-form">
                <p>
                    <form  action="/DeletarProduto/{{$key}}" method="POST" >
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger"  type="submit">Deletar</button>
                    </form>
                </p>
                <p>
                    <form  action="/Produto/{{$key}}" method="GET" >
                        @csrf
                        <button class="btn btn-primary"  type="submit">Visualizar</button>
                    </form>
                </p>
                <p>
                    <form  action="/EditarProduto/{{$key}}" method="GET" >
                        @csrf
                        <button class="btn btn-primary"  type="submit">Editar</button>
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
                                @foreach ($categorias as $value)
                                <option value="{{ $value['categoria'] }}">{{ $value['categoria'] }}</option>
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
                            <label for="valorEstoque" class="block text-sm font-medium text-gray-700">Valor:</label>
                            <input type="number" id="valorEstoque" name="valorEstoque" step="any" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
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