<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Clientes e Produtos') }}
</h2>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</x-slot>
<style type="text/css">
    table {
    border-collapse: collapse;
    text-align: center;
    border: 1px solid;
    width: 100%;
    }
    thead {
    background-color: #e5e7eb;
    border: 2px solid;
    }
    caption{
    background-color: #e5e7eb;
    border: 2px solid;
    }
    td {
    text-align: center;
    }
    .caption-style{
    background-color: black;
    color: white;
    text-align: center;
    font-weight: 900;
    font-size: 20px;
    }
</style>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div id ="te" class="p-6 bg-white border-b border-gray-200">
                
                <table id="table">
                    <thead class="thead">
                        <td style="background-color: royalblue; border: 3px solid; font-weight: 900;">Clientes</td>
                        @if(isset($produtos))
                        @foreach($produtos as $produto)
                        <td style="background-color: royalblue; border: 3px solid; font-weight: 900;">{{strtoupper($produto['produto'])}}</td>

                        @endforeach
                           @endif
                    </thead>
                    <tbody>
                        @if(isset($Clientes))
                        @foreach($Clientes as $cliente => $valor)
                        <tr>
                            <td style="border: 3px solid;">{{strtoupper($valor['name'])}}</td>
                            @if(isset($produtos))
                            @foreach($produtos as $produto)
                            <td style= "border: 3px solid; font-weight: 900;">{{$clientes_produtos[$cliente][$produto['produto']] ?? 0}}</td>
                            @endforeach
                            @endif
                        </tr>
                        @endforeach
                            @endif
                    </tbody>
                    
                </table>
                
            </div>
        </div>
    </div>
</div>
</x-app-layout>
