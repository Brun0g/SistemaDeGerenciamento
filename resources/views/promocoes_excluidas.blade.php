<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Promoções excluidas') }}
        </h2>
    </x-slot>


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

            
                            <td style="border: 1px solid black; width: 10%">{{  $value['produto'] }}</td>
                            <td style="border: 1px solid black; width: 10%; color: green;">R$ {{  number_format($value['preco_original'], 2, ",", ".") }}</td>
                            <td style="border: 1px solid black; width: 10%; color: green;">R${{  number_format($value['preco_desconto'], 2, ",", ".") }}</td>
                   
                            <td style="border: 1px solid; width: 5%">
                                {{ $value['porcentagem'] }}
                            </td>
                            <td style="border: 1px solid; width: 5%">
                                {{ $value['quantidade'] }}
                            </td>
                    
                        
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