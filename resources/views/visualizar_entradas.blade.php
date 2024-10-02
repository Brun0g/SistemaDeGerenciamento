<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Múltiplas entradas') }}
        </h2>
    </x-slot>


<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="caption-style" style="display: flex; flex-direction: column;">
                {{strtoupper('entradas no estoque')}}
                <div></div>
            </div>
            <table id="table">
                <thead class="thead">
                    <tr>
                        <th class="row-inform-item">Usuário</th>
                        <th class="row-inform-item">Ação</th>
                        <th class="row-inform-item">Observação</th>
                        <th class="row-inform-item">Data</th>
                        <th class="row-inform-item">Movimentação</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @if(isset($multiplos))
                    @foreach($multiplos as $key => $value)
                    
                    @if(isset($value['multiplo_id']))
                    <tr>
                        <td>{{  strtoupper($value['create_by'])}}</td>
                        
                        <td>
                            <form action="/detalhes_multiplos/{{$value['multiplo_id']}}" method="POST">
                                @csrf
                                @method('GET')
                                <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Múltipla entrada N° <span style="color: black; font-weight: 900;">{{$value['multiplo_id']}}</span></button>
                            </form>
                        </td>
                        <td>{{  $value['observacao'] }}</td>
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

</x-app-layout>