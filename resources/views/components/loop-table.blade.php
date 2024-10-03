<table id="table">
    <thead >
        
    </thead>
    <tbody>
        @if(isset($produtosPorCliente))
        @foreach ($produtosPorCliente as $key => $value )
        <tr>
            <td style="background-color: royalblue; border: 3px solid; font-weight: 900;">CLIENTE </td>
            <td style="border: 3px solid;">
                @foreach ($nomeCliente as $chave => $val)
                {{ $value['id_cliente'] == $chave ? $nomeCliente[$chave]['name'] : ''}}
                @endforeach
            </td>
            <td style="border: 3px solid;">{{ $value['Produto']}}</td>
            <td style="border: 3px solid;">Quantidade: {{ $value['Quantidade']}}</td>
        </tr>
        @endforeach
        @else
        <td colspan="4">Sem dados de registro!</td>
        @endif
        
    </tbody>
</table>