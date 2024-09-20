@props(['listarPedidosAprovados' => $listarPedidosAprovados, 'id' => $id])


@if(sizeof($listarPedidosAprovados) > 0)
@foreach ($listarPedidosAprovados as $pedido_id => $value )

@if ($value['cliente_id'] == $id )

<tr style="background: white;">
    <td style="color:white; width: 8%; background: black; font-weight: 900; border: 1px solid">{{  $pedido_id }}</td>
    <td style="color:green;">R$ {{ number_format($value['total'], 2, ",", ".")  }}</td>
    <td> 
        <form  action="/ExcluirPedidoCliente/{{$value['cliente_id']}}/{{$pedido_id}}" method="POST" >
            @csrf
            @method('DELETE')
            <button    class="btn btn-danger"  type="submit">Excluir</button>
        </form>
    </td>
    <td>
        <form  action="/pedidofinalizado/{{$pedido_id}}" method="GET" >
            @csrf
            
            <button    class="btn btn-primary"  type="submit">Visualizar pedido</button>
        </form>
    </td>
 
    @endif
    @endforeach
    @else
    <td style="background: white" colspan="5" >Sem dados de registro!</td>
    @endif
  

