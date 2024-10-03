<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Entradas e saídas') }}
        </h2>
    </x-slot>

<div class="py-12">
    @if(isset($produtos))
     <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="margin-bottom: 15px;">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
            <table>
                <thead>
                    <tr>
                        <th  style="color: white;">Criado por</th>
                        <th  style="color: white;">Editado por</th>
                        <th  style="color: white;">Restaurado por</th>  
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
                        <td style="color: black;">{{ isset($produtos['update_by']) ? $produtos['updated_at'] : ''  }}</td>
                        <td style="color: black;">{{ isset($produtos['restored_at']) ? $produtos['restored_at'] : ''  }}</td>
                    </tr>
                </tbody>
            </table>
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
                <thead  style="background: black">
                    <tr>
                        <th>Usuário</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Observação</th>
                        
                        <th>Data</th>
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

</x-app-layout>
