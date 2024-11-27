<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Entradas') }}
        </h2>
    </x-slot>
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
            
            <div class="table_entrada_saida">
                @if(isset($Produtos))
                <form method="POST" action="/entradas_saidas/{{$produto_id}}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        ID: {{$produto_id}}
                    </div>
                    <div class="form-group">
                        @if($Produtos['image_url'] == null)
                        <div style="position: relative; width: 80%;">
                            <img class="image-produto" src="{{ asset('images/default.png') }}">
                        </div>
                        @else
                        
                        <div class="container">
                            <img class="image-produto" src="{{ $Produtos['image_url'] }}">
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        
                        <h4>{{ strtoupper($Produtos['produto']) }}</h4>
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
                        <input type="text" class="form-control" id="produto" value="{{ $quantidade_estoque }}" readonly>
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
                <p>Sem dados de registro!</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
