<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Clientes e Produtos') }}
</h2>

</x-slot>

<div class="container">
    <div class="caption-style">
        Relatório de Clientes e Produtos
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Clientes</th>
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
                    <td data-label="Clientes">{{ strtoupper($valor['name']) }}</td>
                    @if(isset($produtos))
                    @foreach($produtos as $produto)
                    <td data-label="{{ strtoupper($produto['produto']) }}">{{ $clientes_produtos[$cliente][$produto['produto']] ?? 0 }}</td>
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