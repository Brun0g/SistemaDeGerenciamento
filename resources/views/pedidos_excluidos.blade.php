<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Pedidos excluidos') }}
        </h2>
    </x-slot>
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
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="caption-style" style="display: flex; flex-direction: column;">
                PEDIDOS EXCLUIDOS
            </div>
            <table id="table">
                <thead  style="background: black">
                    <tr>
                        <th>Pedido criado</th>
                        <th>Pedido deletado</th>
                        <th>Tipo</th>
                        <th>Total</th>
                        <th>Excluido</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                @if(sizeof($excluidos) > 0)
                    @foreach($excluidos as $key => $value)
                    <tr style="border-top: 1px solid black">
                        <td>{{ strtoupper($value['create_by'])}}</td>
                        <td>{{ strtoupper($value['delete_by'])}}</td>
                        <td>
                            <form action="/pedidofinalizado/{{$key}}" method="GET">
                                @csrf
                 
                                <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Pedido N°: <span style="color: black; font-weight: 900;">{{$key}}</span></button>
                            </form>
                        </td>
                        <td style="color: green"> R$ {{  number_format($value['total'], 2, ",", ".")}}</td>
                        @if($data_atual['dia_do_ano'] > $value['dia_do_ano'] && $data_atual['mes'] == $value['mes'])
                        <td>{{$data_atual['dia_do_ano'] - $value['dia_do_ano']}} dia atrás</td>
                        @elseif($data_atual['mes'] - $value['mes'] == 1)
                        <td>{{$data_atual['mes'] - $value['mes']}} mês atrás</td>
                        @elseif($data_atual['mes'] > $value['mes'])
                        <td>{{$data_atual['mes'] - $value['mes']}} meses atrás</td>
                        @else
                        <td>Hoje</td>
                        @endif

                        <td>
                            <form action="/Restaurar_pedido/{{$key}}" method="POST">
                            @csrf
                            <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Restaurar</span></button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>{{  $value['created_at'] }}</td>
                        <td>{{  $value['deleted_at'] }}</td>
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

</x-app-layout>