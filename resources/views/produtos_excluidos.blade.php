<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Produtos excluidos') }}
        </h2>
    </x-slot>
<div>
    <div class="sub-container">
  
        @if(isset($Produtos))
        @foreach ($Produtos as $key => $value)

        @if( $value['deleted_at'] != null )
        <div class="sub-item">
            <div style="font-weight: 900">
                
                ID: <span style="font-weight: 300; font-size: 18px">{{$key}}</span>
                
            </div>
            
            <div class="image-com-desconto">
                @if($value['image_url'] == false)
                <img src="{{ asset('images/default.png') }}">
                @else
                <img  src="{{ $value['image_url'] }}">
                @endif
            </div>
            <div class="sub-container-name-product">
                <p>{{strtoupper($value['produto'])}}</p>
            </div>
            <p class="preco"><span style="color: black"></span>R$ {{  number_format($value['valor'], 2, ",", ".")}}</p>

            <table>
                <thead>
                    <tr>
                        <th class="preco-desconto">Deletado por</th>
                        <th class="preco-desconto">Data</th>
                    </tr>

                </thead>
                <tbody>
                    <tr>
                        <td class="preco-desconto" style="font-size: 12px;">{{  strtoupper($value['delete_by'])}}</td>
                        <td class="preco-desconto" style="font-size: 12px;">{{  $value['deleted_at']}}</td>
                    </tr>
                </tbody>
            </table>

            <div  style="margin-top: 25px;">
                <p>
                    <form action="/RestaurarProduto/{{$key}}" method="POST" >
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success" type="submit">Restaurar</button>
                    </form>
                </p>

            </div>
            
        </div>
        @endif
        @endforeach
        @else
        <td colspan="10">Sem dados de registro!</td>
        @endif
    </div>
</div>

</x-app-layout>
