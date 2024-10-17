<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Pedidos excluidos') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <div style="display: flex; justify-content: center; margin-top: 20px">
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

    @if (session('date_error'))
    <div style="display: flex; justify-content: center; margin-top: 20px">
        <div class="alert alert-danger" style="text-align: center; width: 25%; margin-right: 10px; font-weight: 600;">
            
            {{ session('date_error') }}
        </div>
    </div>
    @endif

    <div style="display: flex; justify-content: center;   text-align: center; margin-top: 20px;">
        <form  action="/pedidos_excluidos" method="GET" >
        @csrf
        <div style="display: flex; justify-content: center;">
            <div style="margin-right: 15px;">
                <label for="cliente-select" class="block text-sm font-medium text-gray-700">Escolha uma opção:</label>
                <select style="width: 315px;" name="pedidos" id="cliente-select" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required >
                    @if(!$escolha)
                        <option  value="1">Pedidos aprovados</option>
                        <option  value="2">Pedidos aprovados excluidos</option>
                    @else
                        @if($escolha == 1)
                            <option  selected value="1">Pedidos aprovados</option>
                            <option  value="2">Pedidos aprovados excluidos</option>
                        @else
                            <option  value="1">Pedidos aprovados</option>
                            <option  selected value="2">Pedidos aprovados excluidos</option>
                        @endif
                    @endif
                </select>
            </div>
            <div> 
                <label for="cliente-select" class="block text-sm font-medium text-gray-700">Escolha a data inicial e data final:</label>
                <div style="display: flex; justify-content: center;">
                    <div style="text-align: left; ">
                        <label for="data_inicial">Data inicial:</label>
                        <input type="date" name="data_inicial" value="{{$data_inicial}}" />
                    </div>
                    <div style="text-align: right; margin-left: 15px;">
                        <label for="data_final">Data final:</label>
                        @if(!$data_final)
                            <input style="text-align" type="date" name="data_final" value="{{$data_inicial}}" required />
                        @else
                            <input style="text-align" type="date" name="data_final" value="{{$data_final}}" required />
                        @endif
                    </div> 
                </div> 
            </div>
        </div>
        <div style="margin-bottom: 15px; margin-top: 20px;"><button type="submit" class="btn btn-success">Confirmar</button></div>
    </div>

        <input type="hidden" name="page" value="0">
</form>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            
            @if($escolha == 1 || $escolha == null)
                <div class="caption-style" style="display: flex; flex-direction: column;">PEDIDOS APROVADOS</div>
            @else
                <div class="caption-style" style="display: flex; flex-direction: column;">PEDIDOS APROVADOS EXCLUIDOS</div>
            @endif
                <table id="table">
                @if($escolha == 2)
                <thead  style="background: black">
                    <tr>
                        <th>Pedido criado</th>
                        <th>Pedido deletado</th>
                                                        <th>
                                    <form action="/pedidos_excluidos/{{$pagina_atual}}" method="POST">
                                        <span style="color: white; font-weight: 900;">Tipo</span>
                                        @csrf
                                        
                                    
                                            <button class="btn btn-{{!$order_by['id'] ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['id'] ? 'down' : 'up'}}"></i></button>
                                            <input type="hidden" name="ordernar_id" value={{$order_by['id'] == 0 ? 1 : 0}}>
                                            
                                            <input type="hidden" name="ordernar_data" value={{null}}>
                                            <input type="hidden" name="ordernar_total" value={{null}}>

                                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                                            <input type="hidden" name="data_final" value="{{$data_final}}">
                                    </form>
                                </th>
                                <th>
                                    <form action="/pedidos_excluidos/{{$pagina_atual}}" method="POST">
                                        <span style="color: white; font-weight: 900;">Total</span>

                                        @csrf
                                        
                                       
                                            <button class="btn btn-{{!$order_by['total'] ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['total'] ? 'down' : 'up'}}"></i></button>
                                            <input type="hidden" name="ordernar_total" value={{$order_by['total'] == 0 ? 1 : 0}}>
                                            
                                            <input type="hidden" name="ordernar_data" value={{null}}>
                                            <input type="hidden" name="ordernar_id" value={{null}}>

                                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                                            <input type="hidden" name="data_final" value="{{$data_final}}">
                                    </form>
                                </th>
                                <th>
                                    <form action="/pedidos_excluidos/{{$pagina_atual}}" method="POST">
                                        <span style="color: white; font-weight: 900;">Data</span>

                                        @csrf
                                        
                                        
                                            <button class="btn btn-{{!$order_by['deleted_at'] ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['deleted_at'] ? 'down' : 'up'}}"></i></button>
                                            <input type="hidden" name="ordernar_data" value={{$order_by['deleted_at'] == 0 ? 1 : 0}}>
                                            
                                            <input type="hidden" name="ordernar_total" value={{null}}>
                                            <input type="hidden" name="ordernar_id" value={{null}}>

                                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                                            <input type="hidden" name="data_final" value="{{$data_final}}">

                                    </form>
                                </th>
                        <th>Ação</th>
                    </tr>
                </thead>
                @else
                    <thead style="background: black">
                            <tr>
                                <th>
                                    <span style="color: white; font-weight: 900;">Pedido criado</span>
                                </th>
                                <th>
                                    <form action="/pedidos_excluidos/{{$pagina_atual}}" method="POST">
                                        <span style="color: white; font-weight: 900;">Tipo</span>
                                        @csrf
                                        
                                    
                                            <button class="btn btn-{{!$order_by['id'] ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['id'] ? 'down' : 'up'}}"></i></button>
                                            <input type="hidden" name="ordernar_id" value={{$order_by['id'] == 0 ? 1 : 0}}>
                                            
                                            <input type="hidden" name="ordernar_data" value={{null}}>
                                            <input type="hidden" name="ordernar_total" value={{null}}>

                                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                                            <input type="hidden" name="data_final" value="{{$data_final}}">
                                    </form>
                                </th>
                                <th>
                                    <form action="/pedidos_excluidos/{{$pagina_atual}}" method="POST">
                                        <span style="color: white; font-weight: 900;">Total</span>

                                        @csrf
                                        
                                       
                                            <button class="btn btn-{{!$order_by['total'] ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['total'] ? 'down' : 'up'}}"></i></button>
                                            <input type="hidden" name="ordernar_total" value={{$order_by['total'] == 0 ? 1 : 0}}>
                                            
                                            <input type="hidden" name="ordernar_data" value={{null}}>
                                            <input type="hidden" name="ordernar_id" value={{null}}>

                                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                                            <input type="hidden" name="data_final" value="{{$data_final}}">
                                    </form>
                                </th>
                                <th>
                                    <form action="/pedidos_excluidos/{{$pagina_atual}}" method="POST">
                                        <span style="color: white; font-weight: 900;">Data</span>

                                        @csrf
                                        
                                          
                                            <button class="btn btn-{{!$order_by['created_at'] ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['created_at'] ? 'down' : 'up'}}"></i></button>
                                            <input type="hidden" name="ordernar_data" value={{$order_by['created_at'] == 0 ? 1 : 0}}>
                                            
                                            <input type="hidden" name="ordernar_total" value={{null}}>
                                            <input type="hidden" name="ordernar_id" value={{null}}>

                                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                                            <input type="hidden" name="data_final" value="{{$data_final}}">

                                    </form>
                                </th>
                                <th>Ação</th>
                            </tr>
                    </thead>
                @endif
                    <tbody>
                    @if($escolha == 2)
                    @if(sizeof($excluidos) > 0 )
                    @foreach($excluidos as $key => $value)

                    <tr style="border-top: 1px solid black">
                        <td>{{ strtoupper($value['create_by'])}}</td>
                        <td>{{ strtoupper($value['delete_by'])}}</td>
                        <td>
                            <form action="/pedidofinalizado/{{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}" method="GET">
                                @csrf
                                <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Pedido N°: <span style="color: black; font-weight: 900;">{{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}</span></button>
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
                            <form action="/Restaurar_pedido" method="POST">
                            @csrf
                                <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Restaurar</span></button>
                                <input type="hidden" name="pagina_atual" value={{$pagina_atual}}>
                                <input type="hidden" name="pedido_id" value={{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}>
                                <input type="hidden" name="pedidos" value="{{$escolha}}">
                                <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                                <input type="hidden" name="data_final" value="{{$data_final}}">
                                <input type="hidden" name="page" value="{{$pagina_atual}}">

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
                @elseif($escolha == 1)
                    @if(sizeof($excluidos) > 0 )
                    @foreach($excluidos as $key => $value)

                    <tr style="border-top: 1px solid black">
                        <td>{{ strtoupper($value['create_by'])}}</td>
                               
                        <td>
                            <form action="/pedidofinalizado/{{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}" method="GET">
                                @csrf
                                <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Pedido N°: <span style="color: black; font-weight: 900;">{{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}</span></button>
                            </form>
                        </td>

                        <td style="color: green"> R$ {{  number_format($value['total'], 2, ",", ".")}}</td>

                        @if($data_atual['dia_do_ano'] > $value['dia_do_ano'] && $data_atual['mes'] == $value['mes'])
                        <td>{{$data_atual['dia_do_ano'] - $value['dia_do_ano']}} dia atrás</td>
                        @elseif($data_atual['mes'] == $value['mes'] && $data_atual['dia_do_ano'] == $value['dia_do_ano'])
                            <td>Hoje</td>
                        @elseif($data_atual['mes'] == $value['mes'] - 1 && $data_atual['dia_do_ano'] < $value['dia_do_ano'])
                            <td>Hoje</td>
                        @elseif($data_atual['mes'] - $value['mes'] == 1)
                            <td>1 mês atrás</td>
                        @elseif($data_atual['mes'] > $value['mes'])
                            <td>{{$data_atual['mes'] - $value['mes']}} meses atrás</td>
                        @else
                            <td>Futuro</td>
                        @endif

                        <td>
                            <form action="/excluirPedido/{{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" style="color: white; font-weight: 900;">Excluir</span></button>
                            <input type="hidden" name="pagina_atual" value="{{$pagina_atual}}">
                            <input type="hidden" name="pedido_id" value="{{$pagina_atual}}">

                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="ordernar_total" value={{$order_by['total']}}>
                            <input type="hidden" name="ordernar_id" value={{$order_by['id']}}>
                            <input type="hidden" name="ordernar_data" value={{$escolha == 1 ? $order_by['created_at'] : $order_by['deleted_at']}}>
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>{{  $value['created_at'] }}</td>
                    </tr>
                    @endforeach
                    @else
                    <td colspan="6">Sem dados de registro!</td>
                    @endif
                </tbody>
                @endif
            </table>
        </div>
    </div>

@if($total_paginas != 0)
<div style="display: flex; justify-content: center; margin-top: 20px;">

    @if($pagina_atual > 0)
    <div style="display: flex; justify-content: center; ">
        <form action="/pedidos_excluidos/{{$pagina_atual - 1}}" method="POST">
        @csrf
        <input type="hidden" name="pedidos" value="{{$escolha}}">
        <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
        <input type="hidden" name="data_final" value="{{$data_final}}">
        <input type="hidden" name="ordernar_total" value={{$order_by['total']}}>
        <input type="hidden" name="ordernar_id" value={{$order_by['id']}}>
        <input type="hidden" name="page" value="{{$pagina_atual}}">
        <input type="hidden" name="ordernar_data" value={{$escolha == 1 ? $order_by['created_at'] : $order_by['deleted_at']}}>
        <div><button class="btn btn-info" type="submit" style="color: white; font-weight: 900;"><</span></button></div>
        </form>
    </div>
    @endif

    <div style="display: flex; justify-content: center; ">
        @for ($i = 0; $i <= $total_paginas; $i++)
            <form action="/pedidos_excluidos/{{$i}}" method="POST">
            @csrf
                <input type="hidden" name="pedidos" value="{{$escolha}}">
                <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                <input type="hidden" name="data_final" value="{{$data_final}}">
            <input type="hidden" name="ordernar_total" value={{$order_by['total']}}>
            <input type="hidden" name="ordernar_id" value={{$order_by['id']}}>
            <input type="hidden" name="ordernar_data" value={{$escolha == 1 ? $order_by['created_at'] : $order_by['deleted_at']}}>
            <input type="hidden" name="page" value="{{$pagina_atual}}">
                <button class="btn {{$pagina_atual == $i ? 'btn-secondary' : 'btn-dark'}} " name="page"  value="{{$i}}"type="submit" style="color: white; font-weight: 900;">{{$i}}</span></button>
            </form>
        @endfor
    </div>

    @if($total_paginas  > $pagina_atual)
    <div style="display: flex; justify-content: center;">
        <form action="/pedidos_excluidos/{{$pagina_atual + 1}}" method="POST">
            @csrf
            <input type="hidden" name="pedidos" value="{{$escolha}}">
            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
            <input type="hidden" name="data_final" value="{{$data_final}}">
            <input type="hidden" name="ordernar_total" value={{$order_by['total']}}>
            <input type="hidden" name="ordernar_id" value={{$order_by['id']}}>
            <input type="hidden" name="ordernar_data" value={{$escolha == 1 ? $order_by['created_at'] : $order_by['deleted_at']}}>
            <input type="hidden" name="page" value="{{$pagina_atual}}">
            <button class="btn btn-info" type="submit" style="color: white; font-weight: 900;">></span></button>
        </form>
    </div>
    @endif
</div>
@endif

</x-app-layout>