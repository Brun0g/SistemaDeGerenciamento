<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pedidos') }}
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

            <input type="hidden" name="ordernar_quantidade" value="{{$quantidade}}">
            <input type="hidden" name="ordernar_total" value={{$order_by['total']}}>
            <input type="hidden" name="ordernar_id" value={{$order_by['id']}}>
            <input type="hidden" name="ordernar_deleted_at" value={{ $order_by['deleted_at'] }}>
            <input type="hidden" name="ordernar_created_at" value={{ $order_by['created_at'] }}>

            <div style="display: flex; justify-content: center;">
                <div style="margin-right: 15px;">
                    <label class="block text-sm font-medium text-gray-700">Escolha uma opção:</label>
                    <select style="width: 250px;" name="pedidos" id="cliente-select" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required >
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
                <div style="margin-right: 15px;">
                    <label class="block text-sm font-medium text-gray-700">Escolha uma categoria:</label>
                    <select style="width: 250px;"  name="categoria" id="cliente-select" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  >

                        <option value={{null}} >TODAS AS CATEGORIAS</option>
                        
                        @if($categorias != [])
                        @foreach($categorias as $categoria_id => $cat_value)
                        @if($categoria_id == $categoria && $categoria != null)
                        <option  value="{{$categoria_id}}" selected>{{strtoupper($cat_value['categoria']) }}</option>
                        @else
                        <option  value="{{$categoria_id}}">{{strtoupper($cat_value['categoria']) }}</option>
                        @endif

                        @endforeach
                        @endif
                    </select>
                </div>

                <div style="margin-right: 15px;">
                    <label class="block text-sm font-medium text-gray-700">Escolha um cliente:</label>
                    <input class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{$search}}"  name="search">

                </div>
                <div style="width: 13%; margin-right: 15px;">
                    <label class="block text-sm font-medium text-gray-700">Valor mínimo:</label>
                    <input class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{$valores['min']}}" type="number" name="valor_minimo">
                </div>
                <div style="width: 13%; margin-right: 15px;">
                    <label class="block text-sm font-medium text-gray-700">Valor máximo:</label>
                    <input class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{$valores['max']}}" type="number" name="valor_maximo">
                </div>
                <div style="width: 13%; margin-right: 15px;">
                    <label class="block text-sm font-medium text-gray-700">Quantidade mínima:</label>


                    <input class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{$valores['quantidade_min']}}" type="number" name="quantidade_minima">
                    
                </div>

                <div style="width: 13%; margin-right: 15px;">
                    <label class="block text-sm font-medium text-gray-700">Quantidade máxima:</label>
                    
                    <input class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{$valores['quantidade_max']}}" type="number" name="quantidade_maxima" >
                </div>
                <div> 
                    <div style="display: flex; justify-content: center;">
                        <div style="text-align: center; ">
                            <label class="block text-sm font-medium text-gray-700" for="data_inicial">Data inicial:</label>
                            <input class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="date" name="data_inicial" value="{{$data_inicial}}" />
                        </div>
                        <div style="text-align: center; margin-left: 15px;">
                            <label class="block text-sm font-medium text-gray-700" for="data_final">Data final:</label>
                            @if(!$data_final)
                            <input class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" style="text-align" type="date" name="data_final" value="{{$data_inicial}}" required />
                            @else
                            <input class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" style="text-align" type="date" name="data_final" value="{{$data_final}}" required />
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
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Nome do cliente</span>

                            @csrf

                            @if($order_by['cliente_id'] != null)
                            <button class="btn btn-{{$order_by['cliente_id'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['cliente_id'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['cliente_id'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value="{{null}}">
                            <input type="hidden" name="ordernar_total" value={{null}}>
                            <input type="hidden" name="ordernar_id" value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}

                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="cliente_id" value={{$order_by['cliente_id'] == 0 ? 1 : 0}}>

                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">

                        </form>
                    </th>   
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Pedido criado</span>

                            @csrf
                            @if($order_by['created_at'] != null)
                            <button class="btn btn-{{$order_by['created_at'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['created_at'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['created_at'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value="{{null}}">
                            <input type="hidden" name="ordernar_total" value={{null}}>
                            <input type="hidden" name="ordernar_id" value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{$order_by['created_at'] == 0 ? 1 : 0}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>

                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="cliente_id" value={{null}}>
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                        </form>
                    </th>                          
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Pedido deletado</span>

                            @csrf
                            @if($order_by['deleted_at'] != null)
                            <button class="btn btn-{{$order_by['deleted_at'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['deleted_at'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['deleted_at'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value="{{null}}">
                            <input type="hidden" name="ordernar_total" value={{null}}>
                            <input type="hidden" name="ordernar_id" value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{$order_by['deleted_at'] == 0 ? 1 : 0}}>


                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="cliente_id" value={{null}}>

                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                        </form>
                    </th>
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Tipo</span>
                            @csrf
                            @if($order_by['id'] != null)
                            <button class="btn btn-{{$order_by['id'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['id'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['id'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value="{{null}}">
                            <input type="hidden" name="ordernar_total" value={{null}}>
                            <input type="hidden" name="ordernar_id" value={{$order_by['id'] == 0 ? 1 : 0}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>
                            
                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="cliente_id" value={{null}}>
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                        </form>
                    </th>
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Total</span>

                            @csrf
                            @if($order_by['total'] != null)
                            <button class="btn btn-{{$order_by['total'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['total'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['total'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value={{null}}>
                            <input type="hidden" name="ordernar_total" value={{$order_by['total'] == 0 ? 1 : 0}}>
                            <input type="hidden" name="ordernar_id" value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>

                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="cliente_id" value={{null}}>
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                        </form>
                    </th>
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Quantidade</span>

                            @csrf
                            @if($order_by['quantidade'] != null)
                            <button class="btn btn-{{$order_by['quantidade'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['quantidade'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['quantidade'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value={{$order_by['quantidade'] == 0 ? 1 : 0}}>
                            <input type="hidden" name="ordernar_total" value={{null}}>
                            <input type="hidden" name="ordernar_id" value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>

                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="cliente_id" value={{null}}>
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                        </form>
                    </th> 
                    <th>Data</th>
                    <th>Ação</th>
                </tr>
            </thead>
            @else
            <thead style="background: black">
                <tr>
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Nome do cliente</span>

                            @csrf
                            @if($order_by['cliente_id'] != null)
                            <button class="btn btn-{{$order_by['cliente_id'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['cliente_id'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['cliente_id'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value="{{null}}">
                            <input type="hidden" name="ordernar_total" value={{null}}>
                            <input type="hidden" name="ordernar_id" value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>

                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="cliente_id" value={{$order_by['cliente_id'] == 0 ? 1 : 0}}>
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{!$valores['quantidade_max'] ? 0 : $valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                        </form>
                    </th> 
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Pedido criado</span>

                            @csrf
                            @if($order_by['created_at'] != null)
                            <button class="btn btn-{{$order_by['created_at'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['created_at'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['created_at'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value="{{null}}">
                            <input type="hidden" name="ordernar_total" value={{null}}>
                            <input type="hidden" name="ordernar_id" value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{$order_by['created_at'] == 0 ? 1 : 0}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>

                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="cliente_id" value={{null}}>
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">

                        </form>
                    </th>
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Tipo</span>
                            @csrf
                            @if($order_by['id'] != null)
                            <button class="btn btn-{{$order_by['id'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['id'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['id'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value="{{null}}">
                            <input type="hidden" name="ordernar_total" value={{null}}>
                            <input type="hidden" name="ordernar_id" value={{$order_by['id'] == 0 ? 1 : 0}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>

                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="cliente_id" value={{null}}>
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                        </form>
                    </th>
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Total</span>

                            @csrf
                            
                            @if($order_by['total'] != null)
                            <button class="btn btn-{{$order_by['total'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['total'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['total'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value="{{null}}">
                            <input type="hidden" name="ordernar_total"      value={{$order_by['total'] == 0 ? 1 : 0}}>
                            <input type="hidden" name="ordernar_id"         value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>

                            <input type="hidden" name="pedidos"     value="{{$escolha}}">
                            <input type="hidden" name="categoria"   value="{{$categoria}}">
                            <input type="hidden" name="search"      value="{{$search}}">
                            <input type="hidden" name="cliente_id"  value={{null}}>
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                        </form>
                    </th>
                    <th>
                        <form action="/pedidos_excluidos" method="GET">
                            <span style="color: white; font-weight: 900;">Quantidade</span>

                            @csrf
                            @if($order_by['quantidade'] != null)
                            <button class="btn btn-{{$order_by['quantidade'] == 0 ? 'danger' : 'success'}}" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['quantidade'] ? 'down' : 'up'}}"></i></button>
                            @else
                            <button class="btn btn-secondary" type="submit" style="color: white; font-size: 12px; font-weight: 900;"><i class="las la-angle-{{!$order_by['quantidade'] ? 'down' : 'up'}}"></i></button>
                            @endif

                            <input type="hidden" name="ordernar_quantidade" value={{$order_by['quantidade'] == 0 ? 1 : 0}}>
                            <input type="hidden" name="ordernar_total"      value={{null}}>
                            <input type="hidden" name="ordernar_id"         value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>

                            <input type="hidden" name="pedidos"             value="{{$escolha}}">
                            <input type="hidden" name="categoria"           value="{{$categoria}}">
                            <input type="hidden" name="search"              value="{{$search}}">
                            <input type="hidden" name="cliente_id"          value={{null}}>
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">

                        </form>
                    </th> 
                    <th>Data</th>
                    <th>Ação</th>
                </tr>
            </thead>
            @endif
            <tbody>
                @if($escolha == 2)
                @if(sizeof($excluidos) > 0 )
                @foreach($excluidos as $key => $value)

                <tr style="border-top: 1px solid black">
                    <td>{{ strtoupper($value['nome_cliente'])}}</td>
                    <td>{{ strtoupper($value['create_by'])}}</td>
                    <td>{{ strtoupper($value['delete_by'])}}</td>
                    <td>
                        <form action="/pedidofinalizado/{{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}" method="GET">
                            @csrf
                            <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Pedido N°: <span style="color: black; font-weight: 900;">{{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}</span></button>
                        </form>
                    </td>

                    <td style="color: green"> R$ {{  number_format($value['total'], 2, ",", ".")}}</td>
                    <td>{{ $value['quantidade_total_pedido'] }}</td>

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

                            <input type="hidden" name="ordernar_quantidade" value={{null}}>
                            <input type="hidden" name="ordernar_total" value={{null}}>
                            <input type="hidden" name="ordernar_id" value={{null}}>
                            <input type="hidden" name="ordernar_created_at" value={{null}}>
                            <input type="hidden" name="ordernar_deleted_at" value={{null}}>

                            <input type="hidden" name="categoria" value="{{$categoria}}">
                            <input type="hidden" name="cliente_id" value={{$cliente_id}}>
                            <input type="hidden" name="search" value="{{$search}}">
                            <input type="hidden" name="pedido_id" value={{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}>
                            <input type="hidden" name="pedidos" value="{{$escolha}}">
                            
                            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
                            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
                            <input type="hidden" name="quantidade_max" value="{{$valores['quantidade_max']}}">
                            <input type="hidden" name="quantidade_min" value="{{$valores['quantidade_min']}}">

                            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
                            <input type="hidden" name="data_final" value="{{$data_final}}">
                            <input type="hidden" name="page" value="{{$pagina_atual}}">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td></td>
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
                <td style="font-weight: 600">{{ strtoupper($value['nome_cliente'])}}</td>

                <td>{{ strtoupper($value['create_by'])}}</td>
                
                <td>
                    <form action="/pedidofinalizado/{{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}" method="GET">
                        @csrf
                        <button class="btn btn-success" type="submit" style="color: white; font-weight: 900;">Pedido N°: <span style="color: black; font-weight: 900;">{{isset($value['pedido_id']) ? $value['pedido_id'] : $key}}</span></button>
                    </form>
                </td>

                <td style="color: green"> R$ {{  number_format($value['total'], 2, ",", ".")}}</td>

                <td>{{ $value['quantidade_total_pedido'] }}</td>

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
                    </form>
                </td>

            </tr>
            <tr>
                <td></td>
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

@if($total_paginas >= 0)
<div style="display: flex; justify-content: center; margin-top: 20px;">

    @if($pagina_atual > 0)
    <div style="display: flex; justify-content: center; ">
        <form action="/pedidos_excluidos" method="GET">
            @csrf

            <input type="hidden" name="ordernar_quantidade" value="{{$quantidade}}">
            <input type="hidden" name="ordernar_total" value={{$order_by['total']}}>
            <input type="hidden" name="ordernar_id" value={{$order_by['id']}}>

            <input type="hidden" name="ordernar_deleted_at" value={{ $order_by['deleted_at'] }}>
            <input type="hidden" name="ordernar_created_at" value={{ $order_by['created_at'] }}>


            <input type="hidden" name="categoria" value="{{$categoria}}">
            <input type="hidden" name="cliente_id" value={{$cliente_id}}>
            <input type="hidden" name="search" value="{{$search}}">
            <input type="hidden" name="pedidos" value="{{$escolha}}">
            
            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
            <input type="hidden" name="quantidade_maxima" value="{{$quantidade_maxima}}">
            <input type="hidden" name="quantidade_minima" value="{{$quantidade_minima}}">


            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
            <input type="hidden" name="data_final" value="{{$data_final}}">
            <input type="hidden" name="page" value="{{$pagina_atual - 1}}">

            <div><button class="btn btn-info" type="submit" style="color: white; font-weight: 900;"><</span></button></div>
        </form>
    </div>
    @endif

    <div style="display: flex; justify-content: center; ">
        @for ($i = 0; $i <= $total_paginas; $i++)
        <form action="/pedidos_excluidos" method="GET">
            @csrf
            <input type="hidden" name="ordernar_quantidade" value="{{$quantidade}}">
            <input type="hidden" name="ordernar_total" value={{$order_by['total']}}>
            <input type="hidden" name="ordernar_id" value={{$order_by['id']}}>
            <input type="hidden" name="ordernar_deleted_at" value={{ $order_by['deleted_at'] }}>
            <input type="hidden" name="ordernar_created_at" value={{ $order_by['created_at'] }}>
            <input type="hidden" name="categoria" value="{{$categoria}}">
            <input type="hidden" name="cliente_id" value={{$cliente_id}}>
            <input type="hidden" name="search" value="{{$search}}">
            <input type="hidden" name="pedidos" value="{{$escolha}}">
            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">
            <input type="hidden" name="quantidade_maxima" value="{{$quantidade_maxima}}">
            <input type="hidden" name="quantidade_minima" value="{{$quantidade_minima}}">
            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
            <input type="hidden" name="data_final" value="{{$data_final}}">

            @if($total_paginas == 0 && $pagina_atual == 0)
            @else
            <button class="btn {{$pagina_atual == $i ? 'btn-secondary' : 'btn-dark'}} " name="page"  value="{{$i}}"type="submit" style="color: white; font-weight: 900;">{{$i}}</span></button>
            @endif
        </form>
        @endfor
    </div>
    
    @if($total_paginas  > $pagina_atual)
    <div style="display: flex; justify-content: center;">
        <form action="/pedidos_excluidos" method="GET">
            @csrf
            <input type="hidden" name="ordernar_quantidade" value="{{$quantidade}}">
            <input type="hidden" name="ordernar_total" value={{$order_by['total']}}>
            <input type="hidden" name="ordernar_id" value={{$order_by['id']}}>

            <input type="hidden" name="ordernar_deleted_at" value={{ $order_by['deleted_at'] }}>
            <input type="hidden" name="ordernar_created_at" value={{ $order_by['created_at'] }}>

            <input type="hidden" name="categoria" value="{{$categoria}}">
            <input type="hidden" name="cliente_id" value={{$cliente_id}}>
            <input type="hidden" name="search" value="{{$search}}">
            <input type="hidden" name="pedidos" value="{{$escolha}}">

            <input type="hidden" name="valor_maximo" value="{{$valores['max']}}">
            <input type="hidden" name="valor_minimo" value="{{$valores['min']}}">

            <input type="hidden" name="quantidade_maxima" value="{{$quantidade_maxima}}">
            <input type="hidden" name="quantidade_minima" value="{{$quantidade_minima}}">

            <input type="hidden" name="data_inicial" value="{{$data_inicial}}">
            <input type="hidden" name="data_final" value="{{$data_final}}">
            <input type="hidden" name="page" value="{{$pagina_atual + 1}}">

            <button class="btn btn-info" type="submit" style="color: white; font-weight: 900;">></span></button>
        </form>
    </div>
    @endif
</div>
@endif
</x-app-layout>
