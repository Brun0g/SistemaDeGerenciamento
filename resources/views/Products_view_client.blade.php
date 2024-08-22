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

.container {
    width: 100%;
    max-width: 1700px;
    margin: 0 auto;
    padding: 20px;
}

.table-container {
    overflow-x: auto; 
    -webkit-overflow-scrolling: touch; 
}

table {
    border-collapse: collapse;
    width: 100%;
    table-layout: auto;
    min-width: 1000px;
}

thead {
    border: 2px solid #ddd;
}

th, td {
    border: 2px solid #ddd;
    padding: 15px;
    text-align: center;
    white-space: nowrap;
}

th {
    background-color: royalblue;
    color: white;
    font-weight: bold;
}

.caption-style {
    background-color: black;
    color: white;
    text-align: center;
    font-weight: 900;
    font-size: 20px;
    padding: 10px;
}

</style>

<div class="container">
    <div class="caption-style">
        Relatório de Clientes e Produtos
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Clientes</th>
                    @if(isset($produtos))
                    @foreach($produtos as $produto)
                    <th>{{ strtoupper($produto['produto']) }}</th>
                    @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(isset($Clientes))
                @foreach($Clientes as $cliente => $valor)
                <tr style="background: white;">
                    <td data-label="Clientes">{{ strtoupper($valor['name']) }}</td>
                    @if(isset($produtos))
                    @foreach($produtos as $produto)
                    <td data-label="{{ strtoupper($produto['produto']) }}">{{ $clientes_produtos[$cliente][$produto['produto']] ?? 0 }}</td>
                    @endforeach
                    @endif
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


</x-app-layout>