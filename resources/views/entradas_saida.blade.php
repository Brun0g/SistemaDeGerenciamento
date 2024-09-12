<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Entradas') }}
</h2>
</x-slot>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style>
.container_edit {
border: 1px solid #dee2e6;
border-radius: .375rem;
padding: 2rem;
background-color: #ffffff;
box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
max-width: 300px;
width: 100%;
display: flex;
justify-content: center;
text-align: center;
}

.container_edit h3 {
font-size: 1.5rem;
margin-bottom: 1rem;

color: #007bff;
}
.container_edit .form-group label {
font-weight: 600;
}
.container_edit button {
width: 100%;
}
.alert {
margin-bottom: 1.5rem;
}
.text-center {
text-align: center;
}
</style>
<div class="container py-5">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="d-flex justify-content-center">
        
        <div class="container_edit">
            @if(isset($EstoqueProdutos))
            <form method="POST" action="/entradas_saidas/{{$produto_id}}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    ID: {{$produto_id}}
                </div>
                <div class="form-group">
                    @if($EstoqueProdutos['image_url'] == null)
                    <div class="container-default">
                        <img class="image" src="{{ asset('images/default.png') }}">
                    </div>
                    @else
                    
                    <div class="container">
                        <img class="image" src="{{ $EstoqueProdutos['image_url'] }}">
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    
                    <h4>{{ strtoupper($EstoqueProdutos['produto']) }}</h4>
                </div>
                <div class="form-group">
                    @if (session('status'))
                    <div class="alert alert-success" style="display: flex; justify-content:center">
                        {{ session('status') }}
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger" style="display: flex; justify-content:center">
                        {{ session('error') }}
                    </div>
                    @endif
                    <label for="produto">Quantidade no estoque:</label>
                    <input type="text" class="form-control" id="produto" value="{{ strtoupper($EstoqueProdutos['quantidade_estoque']) }}" readonly>
                    <label for="produto">Quantidade no carrinho:</label>
                    <input type="text" class="form-control" id="produto" value="{{$carrinho}}" readonly>
                </div>
                <div class="form-group">
                    <fieldset >
                        <legend style="font-size: 15px;">Selecione entrada ou saída:</legend>
                        <div style="display: flex; justify-content: space-evenly;">
                            <div>
                                <input type="radio" id="huey" name="escolha" value="entrada" checked />
                                <label for="huey">Entrada</label>
                            </div>
                            <div >
                                <input type="radio" id="dewey" name="escolha" value="saida" />
                                <label for="dewey">Saída</label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="form-group">
                    <label for="quantidade">Quantidade</label>
                    <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="0" min="1" required>
                </div>
                <div class="form-group">
                    <legend style="font-size: 15px;">Observação:</legend>
                    <textarea id="w3review" name="observacao" rows="1" cols="20"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Confirmar</button>
            </form>
            @else
            <p class="text-center">Sem dados de registro!</p>
            @endif
        </div>
    </div>
</div>
</x-app-layout>