<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Pedidos excluidos') }}
        </h2>
    </x-slot>


<div class="py-12">
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="caption-style" style="display: flex; flex-direction: column;">
                PEDIDOS EXCLUIDOS
                <div></div>
            </div>
            <table id="table">
                <thead >
                    <tr>
                        <th>Usuário</th>
                        <th>Tipo</th>
                        <th>Total</th>
                        <th>Excluido</th>
                        <th>Data</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @if(sizeof($excluidos) > 0)
                    @foreach($excluidos as $key => $value)
                    
                    @if(isset($value['deleted_at']))
                    <tr>
                        <td>{{ strtoupper($value['create_by'])}}</td>
                        
                        <td>
                            <form action="/pedidofinalizado/{{$value['pedido_id']}}" method="POST">
                                @csrf
                                @method('GET')
                                <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Pedido N°: <span style="color: black; font-weight: 900;">{{$value['pedido_id']}}</span></button>
                            </form>
                        </td>
                        <td style="color: green"> R$ {{  number_format($totalComDesconto[$key], 2, ",", ".")}}</td>


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
                        <td><form action="/Restaurar_pedido/{{$value['pedido_id']}}" method="POST">
                                @csrf
               
                                <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Restaurar</span></button>
                            </form></td>
                    </tr>
                    
                    @endif
                    @endforeach
                    @else
                    <td colspan="6">Sem dados de registro!</td>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-app-layout>
