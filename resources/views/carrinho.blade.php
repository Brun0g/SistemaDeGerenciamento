<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ ('Carrinho de ' .  strtoupper($visualizarCliente[$id]['name'] ) ) }}
</h2>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</x-slot>
<style type="text/css">
table {
border-collapse: collapse;
text-align: center;
border: 1px solid;
width: 100%;
}
thead {
background-color: #e5e7eb;
border: 2px solid;
}
caption{
background-color: #e5e7eb;
border: 2px solid;
}
td {
text-align: center;
}
.caption-style{
background-color: black;
color: white;
text-align: center;
font-weight: 900;
font-size: 20px;
}

input[type="radio"]:checked+label {
  background-color: hsl(172, 67%, 45%);
}

.radio {
  
  color: red;
}
</style>
<div class="py-12">
    <div class="max-w-8xl mx-auto sm:px-8 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div id ="te" class="p-6 bg-white border-b border-gray-200">
                <table id="table">
                    <thead class="thead">
                        <td style=" color: white; background-color: black; border: 3px solid #000; font-weight: 900;">ID</td>
                        <td style=" color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Produto</td>
                        <td style=" color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Quantidade</td>
                        <td style=" color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Preço da unidade</td>
                        <td style=" color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Preço da unidade  c/ promoção</td>
                        {{-- <td style=" color: black; background-color: #e5e7eb; border: 1px solid #000; font-weight: 900;">Preço da unidade</td> --}}
                   
                        <td style=" color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Valor</td>
                      
                        <td style=" color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Ação</td>
                    
                        @if(isset($produtos))
                        @foreach($produtos as $produto)
                        <td style="background-color: #e5e7eb; border: 1px solid; font-weight: 900;">{{strtoupper($produto['produto'])}}</td>
                        @endforeach
                        @endif
                    </thead>
                    <tbody>
                        @foreach($pedidosSession as $pedido_id => $value)
                        @if($value['cliente_id'] == $id)
                        <tr>

                            <form action="/atualizarPedido/{{$id}}" method="POST" id = "atualizarQuantidade">
                                @csrf
                                @method('PATCH')
                                <td style="border: 1px solid; width: 3%">{{$pedido_id}}</td>
                                <td style="border: 1px solid; width: 11%">{{ strtoupper($value['produto']) }}</td>
                                <td style="font-weight: normal;border: 1px solid; width: 7%">
                                    <input type="number" style="border: hidden; text-align: center; width: 70%;" name="atualizar[{{$pedido_id}}]" value="{{ $value['quantidade'] }}" min="1" max="9999">
                                </td>
                                 <td style="font-weight: normal;border: 1px solid black; width: 10%; color: green;">
                                R$ {{ number_format($value['preco_unidade'], 2, ',', '.') }}
                            </td>
                            @if($value['preco_unidade'] != $value['unidade_desconto'])
                            <td style="font-weight: normal;border: 1px solid black; width: 15%; color: green;">
                                R$ {{ number_format($value['unidade_desconto'], 2, ',', '.') }}
                            </td>
                            @else
                            <td style="font-weight: normal;border: 1px solid black; font-style: italic; width: 15%; color: black;">
                                 NÃO APLICADO
                            </td>
                            @endif
                                <td style="font-weight: normal; color: green; border: 1px solid black; width: 15%">R$ {{ strtoupper(number_format($value['total_final'] , 2, ',', '.')) }}</td>
                                <td style=" width: 5%; border: 1px solid;">
                                <div class="btn btn-danger"><a style="text-decoration: none; color: white;"; href="https://laravel.dev.localhost/ExcluirProdutoCliente/{{$value['cliente_id']}}/{{$pedido_id}}">Excluir</a></div>
                                </td>
                        </tr>
                        @endif
                        @endforeach
                        
                       
                          {{--  <form action="/ExcluirProdutoCliente/" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Excluir</button>
                                </form> --}}
                                <tr style = "border-top: black solid 1px; border-bottom: black solid 1px;">
                                  
                                    <td style="border: black solid 1px; background: #e5e7eb;  border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb;  border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: black solid 1px; border-right: hidden;"></td>
                                    <td style=" font-weight: 900; text-align: right; background: #e5e7eb; border-top: black solid 1px;  ">TOTAL:</td>
                                    <td style="border: black solid 1px; color: green; ">R$ {{ isset($totalComDesconto) ? number_format($totalComDesconto, 2, ',', '.') : 0 }}</td>
                                    <td style="border: 1px solid black;"></td>
                                    <td style="border: hidden; border-top: solid black 1px"></td>
                                </tr>
                                <tr style = "border-top: black solid 1px; border-bottom: black solid 1px;">
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style=" font-weight: 900; text-align: right; background: #e5e7eb; border-top: hidden; border-right: black solid 1px; ">DESCONTO GERAL <span style="color: indianred;">{{$porcentagem}}</span>% : </td>
                                    <td style=" border-top: hidden; border-right: 1px solid black; color: indianred;">R${{ isset($totalComDesconto) ? number_format($totalComDesconto - ($totalComDesconto / 100 * $porcentagem) - $totalComDesconto, 2, ',', '.') : 0 }}</td>
                                    <td style=" border-top: hidden; border-right: 1px solid black; border-bottom: hidden;"></td>
                                    <td style="border: hidden;"></td>
                                </tr>
                                <tr style = "border-bottom: black solid 1px;">
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden; "></td>
                                  
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden; "></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style=" font-weight: 900; text-align: right; background: #e5e7eb; border-top: hidden; ">À PAGAR:</td>
                                    <td style="border: black solid 1px; border-top: hidden; color: green;">R$ {{ isset($totalComDesconto) ? number_format($totalComDesconto - ($totalComDesconto / 100 * $porcentagem), 2, ',', '.') : 0 }}</td>
                                    <td style="border-right: 1px solid black; border-bottom: hidden;"></td>
                                    <td style="border: hidden;"></td>
                                </tr>
                    </tbody>
                </table>
                <div style="margin-top: 20px; display: flex; justify-content: center;">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                </div>
                </form>
                <table style=" margin-top: 10px; width: 10%;">
                    <thead>
                        <td style=" color: black; background-color: #e5e7eb; border: 1px solid #000; font-weight: 900;">Desconto em %</td>
                        <td style=" color: black; background-color: #e5e7eb; border: 1px solid #000; font-weight: 900;">Ação</td>
                    </thead>
                    <tbody>
                        <form action="/atualizarPorcentagem/{{$id}}" method="POST">
                            @csrf
                            @method('PATCH')
                        <tr>
                            <td style="border: 1px solid;">
                                <input type="number" style="border: hidden; text-align: center; color: darkgreen; font-weight: 900;" name="porcentagem" value="{{$porcentagem}}" min="0" max="100">
                                </td>
                            </td>
                            <td style="border: 1px solid;">
                            <button class="btn btn-primary" type="submit">Aplicar</button>
                        </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-row">
                    <div class = "select-container">
                        <div class="modal-body">
                               <form method="POST" action="/finalizarPedido/{{$id}}">
                                @csrf

                                <hr></hr>
                                <label style="font-weight: 900;">Selecione um endereço de entrega:</label>

                                <div class= "select-container" >
                                    @if($enderecos != [])
                                    @foreach ($enderecos as $key => $value )
                                    @if ($value['cliente_id'] == $id)       
                                    <div style="font-size: 20px; border: 1px solid black; font-size: 16px; margin-top: 8px; width: 110%; padding: 5px;">    
                                    <input name="endereco_id" class="radio";  type="radio"; value="{{$key}}">
                                        {{   $value['cidade'] . ", " . $value['rua']. ", " . $value['numero']. ", " . ", " . $value['cep'] . ", " . $value['estado']}}
                                    </div>
                                    @endif
                                    @endforeach
                                    @else
                                    Sem dados de registro!
                                    @endif
                              
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div style=" margin-top: 15px; display: flex; justify-content: center; margin-bottom: 15px">
                        <button class="btn btn-success"  type="submit">Finalizar Pedido</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  
 {{--    <script type="text/javascript">
        $(document).ready(function() {
            $('#atualizarQuantidade').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "{{ url('atualizarPedido/') }}",
                    data: $('#atualizarQuantidade').serialize(),
                    type: 'patch',
                    success: function(result) {
                    },
                    error: function(xhr, status, error) {
                    }
                });
            });
        });
    </script> --}}
    </x-app-layout>