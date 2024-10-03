<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Clientes e Produtos') }}
</h2>

</x-slot>

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
                @if(isset($Clientes))
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