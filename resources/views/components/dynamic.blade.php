@props(['listarPedidosAprovados' => $listarPedidosAprovados, 'id' => $id]);


@if(isset($listarPedidosAprovados))
@foreach ($listarPedidosAprovados as $id_pedido => $value )

@if ($value['cliente_id'] == $id )
<tr>
    <td style="color:white; background: black; font-weight: 900; border: 1px solid">{{  $id_pedido }}</td>
    <td style="color:green;">R$ {{ number_format($value['total'], 2, ",", ".")  }}</td>
    <td> 
        <form  action="/ExcluirPedidoCliente/{{$value['cliente_id']}}/{{$id_pedido}}" method="POST" >
            @csrf
            @method('DELETE')
            <button    class="btn btn-primary"  type="submit">Excluir</button>
        </form>
    </td>
    <td>
        <form  action="/pedidofinalizado/{{$id_pedido}}/{{$value['cliente_id']}}" method="GET" >
            @csrf
            
            <button    class="btn btn-primary"  type="submit">Visualizar pedido</button>
        </form>
    </td>
 
    @endif
    @endforeach
    @else
    <td colspan="5" >Sem dados de registro!</td>
    @endif
  

