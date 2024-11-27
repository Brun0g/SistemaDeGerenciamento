<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Adicionar múltiplas entradas') }}
        </h2>
    </x-slot>

<div  >
    <div class="sub-container" >
        <div class="sm:px-6 lg:px-8">
            <form method="POST" action="/novoMultiplo">
                @csrf
                @method('POST')
                <div class="mb-4">
                    <label for="categoria-select" class="block text-sm font-medium text-black-900">Escolha uma categoria:</label>
                    <select name="categoria" id="categoria-select" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Por favor escolha uma categoria . . .</option>
                        @if(isset($categorias))
                        @foreach ($categorias as $categoria_id => $value)
                        <option value="{{ $categoria_id }}">{{ $value['categoria'] }}</option>
                        @endforeach
                        @else
                        <option disabled>Sem dados de registro!</option>
                        @endif
                    </select>
                </div>
            </div>
            
        </div>
        @if (session('status'))
        <div style="display: flex; justify-content: center;">
            <div class="alert alert-success" style="display: flex; justify-content:center">
                {{ session('status') }}
            </div>
        </div>
        @endif
        <div class="sub-container ">
        </form>
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
</div>
<div class="table-estoque shadow-lg" >
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">ID</th>
                <th style="width: 3%;">Imagem</th>
                <th style="width: 10%;">Nome</th>
                <th style="width: 15%">Quantidade</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            
            @if(isset($listarMultiplos))
            <tr style="vertical-align: middle;">
                
                @foreach($listarMultiplos as $key => $value)
                <td style="width: 2%; background: black; color: white; font-weight: 900">
                {{$key}}</td>
                
                
                <td style="justify-content: center; width: 1%; text-align: center; vertical-align: middle; align: center;">
                    @if($value['image_url'] == null)
                    <div  style="position: relative; width: 80%;">
                        <img   class="image-produto" src="{{ asset('images/default.png') }}">
                    </div>
                    @else
                    <div class="container">
                        <img class="image-produto" src="{{ $value['image_url'] }}">
                    </div>
                    @endif
                </td>
                <form method="POST" action="/adicionarMultiplos">
                    @csrf
                    @method('POST')
                    <td style="width: 5%; font-weight: 900;">{{strtoupper($value['produto'])}}
                    </td>
                    <td style="width: 5%; font-weight: 900;">
                        <input style="text-align: center; width: 20%"
                        type="number"
                        name="quantidade[{{$key}}]"
                        value="0"
                        min="0"
                        max="9999"
                        required>
                    </td>
                </tr>
                @endforeach
                @else
                <td colspan="10">Sem dados de registro!</td>
                @endif
            </tbody>
        </table>
    </div>
    <div class="sub-container">
        <div class="mb-4" style="margin-top: 20px;">
            <label for="categoria-select" class="block text-sm font-medium text-black-900">Observação:</label>
            <textarea id="w3review" name="observacao" rows="1" cols="20"></textarea>
        </div>
    </div>
    
    <div style=" margin-top: 15px; display: flex; justify-content: center; margin-bottom: 15px">
        <button class="btn btn-success"  type="submit">Finalizar operação</button>
    </form>
</div>
</div>
</x-app-layout>
