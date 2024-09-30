<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Clientes excluidos') }}
</h2>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
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
font-size: 13px;
}
</style>
<div class="py-12">
    <div class="max-w mx-auto sm:px-6 lg:px-8">
        
        <div class="input-group" style="width: 20%; margin-bottom: 25px;">
            <input   id= "search" type="search" class="form-control rounded" placeholder="Procurar clientes" aria-label="Search" aria-describedby="search-addon" />
            <button type="button" class="btn btn-outline-primary" data-mdb-ripple-init>Procurar</button>
        </div>
       
        <table id="table">
            <thead class="thead">
                <tr>
                    <th class="row-inform-item" style="width: 3%;">ID</th>
                    <th class="row-inform-item" style="width: 3%;">Excluido por</th>
                    <th class="row-inform-item" style="width: 15%;">Nome</th>
                    <th class="row-inform-item">Email</th>
                    <th class="row-inform-item" style="width: 5%;">Idade</th>
                    <th class="row-inform-item" >Endereço</th>
                    <th class="row-inform-item">Contato</th>
                    <th class="row-inform-item">Total</th>
                    <th class="row-inform-item">Ação</th>
                    <th class="row-inform-item">Ação</th>
                    {{-- <th class="row-inform-item">Ação</th> --}}
                </tr>
            </thead>
            <tbody>
                @if(isset($tabela_clientes))
                @foreach ($tabela_clientes as $key => $value )

                @if($value['deleted_at'] != null)
                <tr style="background: white;">
                    <td style="font-size: 16px;  color:white; background: black; font-weight: 900; border: 1px solid">{{  $key }}</td>
                    <td style ="width: 10%; border: 1px solid; ">{{  strtoupper($value['deleted_by']) }}</br>{{  strtoupper($value['deleted_at']) }}</td>
                    <td style ="border: 1px solid; ">{{  strtoupper($value['name']) }}</td>
                    <td style ="border: 1px solid;">{{  $value['email'] }}</td>
                    <td style ="border: 1px solid;">{{  $value['idade'] }}</td>
                    <td style="width: 30%;  text-align: left; border: 1px solid; border-right: none;">
                        <ol style="list-style-type: decimal; list-style-position: inside; ">
                            
                            @if($listar_enderecos != [])
                            @foreach ($listar_enderecos as $id_endereco => $endereco)
                            @if($key == $endereco['cliente_id'])
                            
                            <li style="  border-right: none; ">
                                
                                {{ strtoupper($endereco['rua'] . ", " . $endereco['numero'] . ", ". $endereco['cidade'] .", ".$endereco['estado'].", ". $endereco['cep'])}}
                            </li>
                            @endif
                            @endforeach
                        </ol>
                        @endif
                    </td>
                    
                    
                    <td style ="border: 1px solid;">{{  $value['contato'] }}</td>
                    <td style="border: 1px solid; color:green;"> R$ {{isset($total[$key])  ? $total[$key]  : 0}}</td>
                    <td style="border: 1px solid;">
                        <form  action="/restaurarCliente/{{$key}}" method="POST" >
                            @csrf
                      
                            <button    class="btn btn-success"  style="padding: 5px;" type="submit">Restaurar</button>
                        </form>
                    </td>
                    <td style="border: 1px solid black;">
                        <form  action="/Cliente/{{$key}}" method="GET" >
                            @csrf
                            <button    class="btn btn-primary"  style="padding: 5px;" type="submit">Visualizar pedidos</button>
                        </form>
                    </td>
                  {{--   <td style="border: 1px solid black;">
                        <form  action="/Editar/Cliente/{{$key}}" method="GET" >
                            @csrf
                            <button    class="btn btn-primary"  style="padding: 5px;" type="submit">Editar cliente</button>
                        </form>
                    </td> --}}
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
<script>
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
</script>
<script>
$(document).ready(function(){
$(document).on('keyup', function (e){
e.preventDefault();
let search_string = $('#search').val();
console.log(search_string);
$.ajax({
url:"{{ route('Clientes') }}",
method: 'GET',
data:{search_string:search_string},
success: function(res){
$('#table').html(res);
}
});
})
});
</script>
</x-app-layout>