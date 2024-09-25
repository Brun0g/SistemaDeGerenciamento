<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Pedidos excluidos') }}
</h2>
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
border: 2px solid black;
}
td {
text-align: center;
}
.caption-style {
background-color: royalblue;
border: 1px solid black;
color: white;
text-align: center;
font-weight: 900;
font-size: 18px;
padding: 10px;
}
:root {
--bs-box-shadow-sm: 0;
}
</style>
<div class="py-12">
     @if (session('status'))
                <div style="display: flex; justify-content: center;">
                    <div class="alert alert-success" style="display: flex; justify-content:center">
                        {{ session('status') }}
                    </div>
                </div>
                @endif
                
                @if (session('error_estoque'))
                <div style="display: flex; justify-content: center;">
                    <div class="alert alert-danger" style="text-align: center; width: 25%; margin-right: 10px; font-weight: 600;">
                        
                        {{ session('error_estoque') }}
                    </div>
                </div>
                @endif
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="caption-style" style="display: flex; flex-direction: column;">
                PEDIDOS EXCLUIDOS
                <div></div>
            </div>
            <table id="table">
                <thead class="thead">
                    <tr>
                        <th class="row-inform-item">Pedido criado</th>
                        <th class="row-inform-item">Pedido deletado</th>
                        <th class="row-inform-item">Tipo</th>
                        <th class="row-inform-item">Total</th>
                        <th class="row-inform-item">Excluido</th>
                        <th class="row-inform-item">Data</th>
                        <th class="row-inform-item">Ação</th>
                    </tr>
                </thead>
                <tbody>
                @if(sizeof($excluidos) > 0)
                    @foreach($excluidos as $key => $value)
                    
        
                    <tr>
                        <td>{{ strtoupper($value['create_by'])}}</td>
                        <td>{{ strtoupper($value['delete_by'])}}</td>
                        <td>
                            <form action="/pedidofinalizado/{{$key}}" method="GET">
                                @csrf
                 
                                <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Pedido N°: <span style="color: black; font-weight: 900;">{{$key}}</span></button>
                            </form>
                        </td>
                        <td style="color: green"> R$ {{  number_format($value['total'], 2, ",", ".")}}</td>


                        <td>{{  $value['data'] }}</td>

                        @if($data_atual['dia_do_ano'] > $value['dia_do_ano'] && $data_atual['mes'] == $value['mes'])
                        <td>{{$data_atual['dia_do_ano'] - $value['dia_do_ano']}} dia atrás</td>
                        @elseif($data_atual['mes'] - $value['mes'] == 1)
                        <td>{{$data_atual['mes'] - $value['mes']}} mês atrás</td>
                        @elseif($data_atual['mes'] > $value['mes'])
                        <td>{{$data_atual['mes'] - $value['mes']}} meses atrás</td>
                        @else
                        <td>Hoje</td>
                        @endif

                        <td><form action="/realocar_pedido/{{$key}}" method="POST">
                                @csrf
               
                                <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Realocar</span></button>
                            </form></td>
                    </tr>
                    
                  
                    @endforeach

                    @else
                    <td colspan="6">Sem dados de registro!</td>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</x-app-layout>