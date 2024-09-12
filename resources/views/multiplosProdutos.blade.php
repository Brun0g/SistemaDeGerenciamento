<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Adicionar múltiplas entradas') }}
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
flex-direction: column;
margin: 0 auto;
}
.sub-container {
display: flex;
justify-content: center;
flex-wrap: wrap;
width: 900px;
margin: 0 auto;
margin-top: 20px;
}
label {
font-weight: 900;
}
.but {
margin-top: 0.75rem;
}
caption {
background-color: #e5e7eb;
}
table {
border-collapse: collapse;
text-align: center;
border: 1px solid;
width: 100%;
}
thead {
background-color: #e5e7eb;
position: sticky;
top: -15px;
justify-content: center;
text-align: center;
font-size: 16px;
border: 5px solid;
}
td {

}
.container {
position: relative;
width: 80%;
}
.container-default {
position: relative;
width: 80%;
}
.image {
opacity: 1;
display: block;
width: 100%;
height: auto;
transition: .5s ease;
backface-visibility: hidden;
}
.middle {
transition: .5s ease;
opacity: 0;
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
-ms-transform: translate(-50%, -50%);
text-align: center;
cursor: pointer;
}
.container:hover .image {
opacity: 0.3;
}
.container:hover .middle {
opacity: 0.8;
}
.text {
background-color: indianred;
color: white;
font-weight: 900;
font-size: 16px;
padding: 49px 80px;
}
</style>
<div class="container-geral">
    <div class="sub-container">
        <div class="sm:px-6 lg:px-8">
            <form method="POST" action="/novoMultiplo">
                @csrf
                @method('POST')
                <div class="mb-4" style="margin-top: 20px;">
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
        <div class="sub-container">
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

<div class="sub-container">
    <table id="table">
        <thead class="thead">
            <tr>
                <th class="row-inform-item" style="width: 3%;">ID</th>
                <th class="row-inform-item" style="width: 3%;">Imagem</th>
                <th class="row-inform-item" style="width: 10%;">Nome</th>
                <th class="row-inform-item" style="width: 15%">Quantidade</th>
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
                    <div  class="container-default">
                        <img   class="image" src="{{ asset('images/default.png') }}">
                    </div>
                    @else
                    <div class="container">
                        <img class="image" src="{{ $value['image_url'] }}">
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