<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Clientes e Produtos') }}
</h2>

</x-slot>

@if (session('date_error'))
    <div style="display: flex; justify-content: center; margin-top: 20px">
        <div class="alert alert-danger" style="text-align: center; width: 25%; margin-right: 10px; font-weight: 600;">

        {{ session('date_error') }}
        </div>
    </div>
@endif
                  
<div style="display: flex; justify-content: center;   text-align: center; margin-top: 20px;">
<form  action="/produtos_vendidos" method="GET" >
@csrf
    <div style="display: flex; justify-content: center;   ">

        <div style="margin-right: 15px;">

            <label for="cliente-select" class="block text-sm font-medium text-gray-700">Escolha um cliente:</label>
            <select style="width: 315px;"name="procurar_cliente" id="cliente-select" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option  value="">Todos os clientes</option>
                @if(isset($Clientes))
                    @foreach ($Clientes as $cliente_id => $value)
                <option value="{{ $cliente_id }}">{{ strtoupper($value['name']) }}</option>
                    @endforeach
                @else
                <option disabled>Sem dados de registro!</option>
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
   <div style="margin-top: 15px;">
            <button type="submit" class="btn btn-success">Confirmar</button>
        </div>
</div>
 </form>



<div class="container" style="margin-top: 20px">
    <div class="caption-style">
        Produtos vendidos
    </div>
    <div class="rounded shadow-lg" style="position: relative; overflow-x: auto; overflow-y: auto; max-height: 600px;">
        <table style="width: 100%;">
            <thead style="background: black">
                <tr>
                    <th style="width: 25%">Clientes</th>
                    @if(isset($produtos))
                    @foreach($produtos as $produto)
                    <th>{{ strtoupper($produto['produto']) }}</th>
                    @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>

                @if($search != null)
               
                    @foreach($Clientes as $cliente => $valor)
                    <tr style="background: white;">
                        @if($search == $cliente)
                        <td style="width: 35%; border: 1px solid black" data-label="Clientes">{{ strtoupper($valor['name']) }}</td>
                        @if(isset($produtos))
                        @foreach($produtos as $produto)
                        <td style="border: 1px solid black" data-label="{{ strtoupper($produto['produto']) }}">{{ $clientes_produtos[$cliente][$produto['produto']] ?? 0 }}</td>
                        @endforeach
                        @endif
                        @endif
                    </tr>
                    @endforeach
                @else
                    @foreach($Clientes as $cliente => $valor)
                    <tr style="background: white;">
                        <td style="width: 35%; border: 1px solid black" data-label="Clientes">{{ strtoupper($valor['name']) }}</td>
                        @if(isset($produtos))
                        @foreach($produtos as $produto)
                        <td style="border: 1px solid black" data-label="{{ strtoupper($produto['produto']) }}">{{ $clientes_produtos[$cliente][$produto['produto']] ?? 0 }}</td>
                        @endforeach
                        @endif
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>
