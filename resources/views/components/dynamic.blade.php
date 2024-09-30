@props(['listarPedidosAprovados' => $listarPedidosAprovados, 'id' => $id, 'deletedAt' => $deletedAt ] )



@if(sizeof($listarPedidosAprovados) > 0)
@foreach ($listarPedidosAprovados as $pedido_id => $value)

@if ($value['cliente_id'] == $id )

<tr class="bg-white">
    <td style="color:white; background: black; font-weight: 900; ">{{  $pedido_id }}</td>
    <td>{{strtoupper($value['create_by'])}}</td>
    @if(isset($value['restored_by']))
    <td>{{strtoupper($value['restored_by'])}}</td>
    @else
    <td></td>
    @endif
    <td style="color:green;">R$ {{ number_format($value['total'], 2, ",", ".")  }}</td>
    @if($deletedAt == null)
    <td>
        <form  action="/ExcluirPedidoCliente/{{$value['cliente_id']}}/{{$pedido_id}}" method="POST" >
            @csrf
            @method('DELETE')
            <button    class="btn btn-danger"  type="submit">Excluir</button>
        </form>
    </td>
    @endif
      <td>
        <form  action="/pedidofinalizado/{{$pedido_id}}" method="GET" >
            @csrf
            <button    class="btn btn-primary"  type="submit">Visualizar pedido</button>
        </form>
    </td>

    <tr class="bg-white">
        <td style="background: #000; "></td>
        <td>{{$value['created_at']}}</td>
        @if(isset($value['restored_at']))
        <td>{{strtoupper($value['restored_at'])}}</td>
        @else
        <td></td>
        @endif
        <td></td>
        <td></td>
        @if($deletedAt == null)
        <td></td>
         @endif
    </tr>
  
    @endif
    @endforeach
    @else
    <td style="background: white;" colspan="6" >Sem dados de registro!</td>
    @endif
