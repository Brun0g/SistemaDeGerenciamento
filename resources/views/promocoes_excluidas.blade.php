<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Promoções excluidas') }}
</h2>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</x-slot>
<style type="text/css">
#container-cadastro-cliente{
display: flex;
justify-content: center;
text-align: center;
flex-wrap: wrap;
margin: 0 auto;
width: 20%;
}
label {
font-weight: 900;
}
.input-margin-top {
margin-top: 0.35rem;
margin-bottom: 0.35rem;
}
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
#categoria-select {
width: 99%;
}
</style>
@if ($errors->any())
<div  class="p-6 bg-white border-b border-gray-200">
    <div class="alert alert-danger">
        <table>
            @foreach ($errors->all() as $error )
            <td>{{ $error }}</td>
            @endforeach
        </table>
    </div>
    @endif
    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-7 lg:px-8">
            <table id="table">
                <thead class="thead">
                    <tr>
                        <th class="row-inform-item">ID</th>
                        <th class="row-inform-item">Excluido por</th>
                        {{-- <th class="row-inform-item">Situação</th> --}}
                        <th class="row-inform-item">Nome do Produto</th>
                        <th class="row-inform-item">Preço original</th>
                        <th class="row-inform-item">Preço com desconto</th>
                        <th class="row-inform-item">Porcentagem</th>
                        <th class="row-inform-item">Quantidade</th>
                        {{-- <th class="row-inform-item">Ação</th> --}}
                        <th class="row-inform-item">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($listaPromocoes))
                    @foreach ($listaPromocoes as $key => $value)

                    @if( isset($value['deleted_at']) )
                    <tr style="background: white;">
                        <td style="color:white; background: black; font-weight: 900; border: 1px solid; width: 5%">
                        {{  $key }}</td>
                        <td style=" width: 100%; border: 1px solid black; width: 10%">
                        {{  $value['delete_by'] }} </br> {{  $value['deleted_at'] }}</td>

                        {{-- @if($value['ativo'] == 0)
                        <td style="border: 1px solid black; width: 5%">
                            <form  action="/ativarpromocao/{{$key}}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button name= 'situacao' value="{{$value['ativo'] + 1}}" class="{{$value['ativo'] == 0 ? 'btn btn-danger' : 'btn btn-success'}}"  type="submit">{{  $value['ativo'] == 0 ? 'DESATIVADO' : 'ATIVADO' }}</button>
                            </form>
                        </td>
                        @else
                        <td style="border: 1px solid; width: 5%">
                            <form action="/ativarpromocao/{{$key}}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button name= 'situacao' value="{{$value['ativo'] - 1}}" class="{{$value['ativo'] == 0 ? 'btn btn-danger' : 'btn btn-success'}}"  type="submit">{{  $value['ativo'] == 0 ? 'DESATIVADO' : 'ATIVADO' }}</button>
                            </form>
                        </td>
                        @endif --}}
                    {{--  <form action="/atualizarpromocao/{{$key}}" method="POST">
                            @csrf
                            @method('PATCH') --}}
                            <td style="border: 1px solid black; width: 10%">{{  $value['produto'] }}</td>
                            <td style="border: 1px solid black; width: 10%; color: green;">R$ {{  number_format($value['preco_original'], 2, ",", ".") }}</td>
                            <td style="border: 1px solid black; width: 10%; color: green;">R${{  number_format($value['preco_desconto'], 2, ",", ".") }}</td>
                   
                            <td style="border: 1px solid; width: 5%">
                                {{ $value['porcentagem'] }}
                            </td>
                            <td style="border: 1px solid; width: 5%">
                                {{ $value['quantidade'] }}
                            </td>
                          {{--   <td style="border: 1px solid;">
                                <button class="btn btn-primary" type="submit">Atualizar</button>
                            </td> --}}
                        {{-- </form> --}}
                        
                        <td style="border: 1px solid; width: 5%">
                            <form  action="/restaurarPromocao/{{$key}}" method="POST" >
                                @csrf
    
                                <button    class="btn btn-success"  type="submit">Restaurar</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @else
                    <td colspan="10">Sem dados de registro!</td>
                    @endif
                </tbody>
            </table>
            
        </div>
    </div>
</div>
</div>
</x-app-layout>