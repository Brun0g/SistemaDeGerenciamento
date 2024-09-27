<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Entradas e saídas') }}
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
    @if(isset($produtos))

     <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
            <table>
                <thead>
                    <tr>
                        <th  style="color: black">Criado por</th>
                        <th  style="color: black">Editado por</th>
                        <th  style="color: black">Restaurado por</th>  
                    </tr>

                </thead>
                <tbody>
                    <tr class="bg-white">

                        <td style="color: black;">{{ strtoupper( $produtos['create_by'] ) }}</td>
                        <td style="color: black;">{{ isset($produtos['update_by']) ? strtoupper( $produtos['update_by'] ) : ''  }}</td>
                        <td style="color: black;">{{ isset($produtos['restored_by']) ? strtoupper( $produtos['restored_by'] ) : ''  }}</td>
                    </tr>

                    <tr class="bg-white">

                        <td style="color: black;">{{ $produtos['created_at'] }}</td>
                        <td style="color: black;">{{ isset($produtos['updated_at']) ? strtoupper( $produtos['updated_at']) : ''  }}</td>
                        <td style="color: black;">{{ isset($produtos['restored_at']) ? strtoupper( $produtos['restored_at']) : ''  }}</td>
                        
                    </tr>
                </tbody>
            </table>

    
            </div>
        </div>
    </div>
    @endif
    

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="caption-style" style="display: flex; flex-direction: column;">
                ENTRADAS E SAÍDAS
                <div>{{strtoupper($produtos['produto'])}}</div>
            </div>
            
            <table id="table">
                <thead class="thead">
                    <tr>
                        <th class="row-inform-item">Usuário</th>
                        <th class="row-inform-item">Tipo</th>
                        <th class="row-inform-item">Quantidade</th>
                        <th class="row-inform-item">Observação</th>
                        
                        <th class="row-inform-item">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($entradas_saidas))
                    @foreach($entradas_saidas['entradas_array'] as $key => $value)
                    @if($value['produto_id'] == $produto_id)

                    @if(isset($value['pedido_id']) && $value['quantidade'] < 0)
                    <tr>
                        <td>{{  strtoupper($value['create_by'])}}</td>
                        <form action="/pedidofinalizado/{{$value['pedido_id']}}" method="POST">
                            @csrf
                            @method('GET')
                            <td><button type="submit">Saída realizada Pedido N°: <span style="color: purple; font-weight: 900"> {{$value['pedido_id']}}</span></button></td>
                        </form>
                        <td style=" color: red">{{  $value['quantidade'] }}</td>
                        <td>{{  $value['observacao'] }}</td>
                        <td>{{  $value['data'] }}</td>
                    </tr>
                    @elseif( isset($value['pedido_id']) && $value['quantidade'] > 0)
                    <tr>
                        <td>{{  strtoupper($value['create_by'])}}</td>
                        <form action="/pedidofinalizado/{{$value['pedido_id']}}" method="POST">
                            @csrf
                            @method('GET')
                            <td><button type="submit">Pedido excluido N°: <span style="color: purple; font-weight: 900"> {{$value['pedido_id']}}</span></button></td>
                        </form>
                        <td style="font-weight: 900; color: black;     text-decoration: line-through;" >{{  $value['quantidade'] }}</td>
                        <td>{{  $value['observacao'] }}</td>
                        <td>{{  $value['data'] }}</td>
                    </tr>
                    @elseif($value['quantidade'] > 0)
                    <tr>
                        <td>{{  strtoupper($value['create_by'])}}</td>
                        @if($value['multiplo_id'] != null)
                        <form action="/detalhes_multiplos/{{$value['multiplo_id']}}" method="POST">
                            @csrf
                            @method('GET')
                            <td><button type="submit">Multipla entrada N°:  <span style="color: purple; font-weight: 900;">{{$value['multiplo_id']}}</span></button></td>
                        </form>
                        @elseif($value['ajuste_id'] != null)
                        <form action="/detalhes_ajuste/{{$value['ajuste_id']}}" method="POST">
                            @csrf
                            @method('GET')
                            <td ><button type="submit">Ajuste entrada N°: <span style="color: purple; font-weight: 900;">{{$value['ajuste_id']}}</span></button></td>
                        </form>
                        @else
                        <td>Entrada</td>
                        @endif
                        <td style="color:green">{{$value['quantidade'] }}</td>
                        <td>{{  $value['observacao'] }}</td>
                        <td>{{  $value['data'] }}</td>
                    </tr>
                    @elseif($value['quantidade'] < 0)
                    <tr>
                        <td>{{  strtoupper($value['create_by'])}}</td>
                        @if($value['ajuste_id'] != null)
                        <form action="/detalhes_ajuste/{{$value['ajuste_id']}}" method="POST">
                            @csrf
                            @method('GET')
                            <td ><button type="submit">Ajuste saída N°: <span style="color: purple; font-weight: 900;">{{$value['ajuste_id']}}</span></button></td>
                        </form>
                        @else
                        <td>Saída</td>
                        @endif
                        <td style="color:red">{{  $value['quantidade'] }}</td>
                        <td>{{  $value['observacao'] }}</td>
                        <td>{{  $value['data'] }}</td>
                    </tr>
                    @endif
                    @endif
                    @endforeach
                    <tr>
                        <td style="border: black solid 1px; background: #e5e7eb; border-right: hidden; "></td>
                        <td style="border: black solid 1px; font-weight: 900; text-align: right; background: #e5e7eb;">TOTAL: </td>
                        <td style="border-top: 1px solid black; border-left: hidden;background: #e5e7eb; border-bottom: 1px solid black; border-right: 1px solid right; font-weight: 900">{{$resultado}}</td>
                        <td style="border-top: 1px solid black; border-left: 1px solid black; background-color: rgb(243 244 246); border-bottom: hidden; border-right: hidden"></td>
                        <td style="border-top: 1px solid black; border-left: 1px solid black; background-color: rgb(243 244 246); border-bottom: hidden; border-right: hidden"></td>
                    </tr>
                    @else
                    Sem dados de registro!
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
