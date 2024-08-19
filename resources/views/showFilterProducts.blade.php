<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Entradas e saídas de produtos') }}
</h2>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</x-slot>
<style type="text/css">
    #te {
    display: flex;
    justify-content: center;
    width: 100% ;
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
    text-align: center;
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
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="caption-style">
        Entradas
    </div>
            <div id ="te" class="p-6 bg-white border-b border-gray-200">

                <table id="table">

                    <thead class="thead">
                        <tr>
                            <th class="row-inform-item">Usuário ID</th>
                            <th class="row-inform-item">Produto</th>
                            <th class="row-inform-item">Quantidade</th>
                            <th class="row-inform-item">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                     
                        @if(isset($EstoqueProdutos))
                        @foreach($EstoqueProdutos['entradas'] as $key => $value)
                        @if($produto_id == $value['produto_id'])
                        <tr>
                            <td>{{  $value['user_id']}}</td>
                            <td>{{  $EstoqueProdutos['produto'] }}</td>
                            <td style="font-weight: 900; color: green">{{  $value['quantidade'] }}</td>
                            <td>{{  $value['data'] }}</td>
                        </tr>
                        @endif
                        @endforeach
                        @else
                        Sem dados de registro!
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="caption-style">
        Saídas
    </div>
            <div id ="te" class="p-6 bg-white border-b border-gray-200">

                <table id="table">

                    <thead class="thead">
                        <tr>
                            <th class="row-inform-item">Usuário ID</th>
                            <th class="row-inform-item">Pedido ID</th>
                            <th class="row-inform-item">Produto</th>
                            <th class="row-inform-item">Quantidade</th>
                            <th class="row-inform-item">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                  
                        @if(isset($EstoqueProdutos))
                        @foreach($EstoqueProdutos['saidas'] as $key => $value)
                        @if($produto_id == $value['produto_id'])
                        <tr>
                            <td>{{  $value['user_id']}}</td>
                            <td>{{  $value['pedido_id'] }}</td>
                            <td>{{  strtoupper($EstoqueProdutos['produto']) }}</td>
                            <td style="font-weight: 900; color: red">{{  -$value['quantidade'] }}</td>
                            <td>{{  $value['data'] }}</td>
                        </tr>
                        @endif
                        @endforeach
                        @else
                        Sem dados de registro!
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

