<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ ('Carrinho de ' .  strtoupper($visualizarCliente[$id]['name'] ) ) }}
        </h2>
    </x-slot>

    @if($pedidosSession != [])
    <div class="py-12" >
        <div class="max-w-8xl mx-auto sm:px-8 lg:px-8" >
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" >
                <div class="p-6 bg-white border-b border-gray-200" style="display: flex; flex-direction: column;">
                    @if (session('status'))
                    <div style="display: flex; justify-content: center;">
                        <div class="alert alert-success" style="display: flex; justify-content:center">
                            {{ session('status') }}
                        </div>
                    </div>
                    @endif
                    @if (session('error_estoque'))
                    <div style="display: flex; justify-content: center;">
                        <div class="alert alert-danger" style="text-align: center; width: 25%; margin-right: 10px; font-weight: 600;">
                            
                            {{ session('error_estoque') }}
                        </div>
                    </div>
                    @endif
                    @if(session('array_erros'))
                    <ul style="display: flex; justify-content:center;">
                        @foreach(session('array_erros') as $key => $value)
                        <li class="alert alert-danger" style="text-align: center; width: 20%; margin-right: 10px; font-weight: 600;">
                            {{ $value }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <table id="table">
                        <thead>
                            <tr>
                                <th style="color: white; background-color: black; border: 3px solid #000; font-weight: 900;">ID</th>
                                <th style="color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Produto</th>
                                <th style="color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Quantidade</th>
                                <th style="color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Preço da unidade</th>
                                <th style="color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Preço da unidade c/ promoção</th>
                                <th style="color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Valor</th>
                                <th style="color: black; background-color: #e5e7eb; border: 3px solid #000; font-weight: 900;">Ação</th>
                                @if(isset($produtos))
                                @foreach($produtos as $produto)
                                <th style="background-color: #e5e7eb; border: 1px solid; font-weight: 900;">{{ strtoupper($produto['produto']) }}</th>
                                @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidosSession as $pedido_id => $value)
                            @if($value['cliente_id'] == $id)
                            <tr>
                                <form action="/atualizarPedido/{{$id}}" method="POST" id="atualizarQuantidade">
                                    @csrf
                                    @method('PATCH')
                                    <td style="border: 1px solid; width: 3%">{{$pedido_id}}</td>
                                    <td style="border: 1px solid; width: 7%">{{ strtoupper($value['produto']) }}</td>
                                    @if($value['deleted_at'] != null || $value['quantidade'] == 0)
                                    <td style="font-weight: 900; color: red; border: 1px solid black; width: 7%">
                                        FORA DE ESTOQUE
                                    </td>
                                    @else
                                    <td style="font-weight: normal; border: 1px solid; width: 5%">
                                        <input type="number" style="border: hidden; text-align: center; width: 50%;" name="atualizar[{{$pedido_id}}]" value="{{ $value['quantidade'] }}" min="1" max="9999">
                                    </td>
                                    @endif
                                    <td style="font-weight: normal; border: 1px solid black; width: 8%; color: green;">
                                        R$ {{ number_format($value['preco_unidade'], 2, ',', '.') }}
                                    </td>
                                    @if($value['total'] != $value['total_final'])
                                    <td style="font-weight: normal; border: 1px solid black; width: 5%; color: green;">
                                        R$ {{ number_format(floor($value['unidade_desconto'] *100)/100,2, '.', '') }}
                                    </td>
                                    @else
                                    <td style="font-weight: normal; border: 1px solid black; font-style: italic; width: 8%; color: black;">
                                        NÃO APLICADO
                                    </td>
                                    @endif
                                    <td style="font-weight: normal; color: green; border: 1px solid black; width: 10%">R$ {{ strtoupper(number_format($value['total_final'], 2, ',', '.')) . "  /  " . $value['total']}}</td>
                                    <td style="width: 5%; border: 1px solid;">
                                        <div class="btn btn-danger"><a style="text-decoration: none; color: white;" href="https://laravel.dev.localhost/ExcluirProdutoCliente/{{$value['cliente_id']}}/{{$value['produto_id']}}">Excluir</a></div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                                <tr style="border-top: black solid 1px; border-bottom: black solid 1px;">
                                    <td style="border: black solid 1px; background: #e5e7eb; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: black solid 1px; border-right: hidden;"></td>
                                    <td style="font-weight: 900; text-align: right; background: #e5e7eb; border-top: black solid 1px;">TOTAL BRUTO:</td>
                                    <td style="border: black solid 1px; color: green;">R$ {{ isset($value['totalComDesconto']) ? number_format($value['totalSemDesconto'], 2, ',', '.') : 0 }}</td>
                                    <td style="border: 1px solid black; border-right: hidden;"></td>
                                </tr>
                                {{-- <tr style="border-top: black solid 1px; border-bottom: black solid 1px;">
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="font-weight: 900; text-align: right; background: #e5e7eb; border-top: hidden; border-right: black solid 1px;">DESCONTO GERAL <span style="color: indianred;">{{$value['porcentagem_geral']}}</span>% :</td>
                                    @if($value['totalComDesconto'] != $value['totalSemDesconto'])
                                    <td style="border-top: hidden; border-right: 1px solid black; color: indianred;">R$ {{ number_format(($value['totalComDesconto'] / 100 * $value['porcentagem_geral']) + $value['desconto_total_promocao'], 2, ',', '.')}}</td>
                                    @else
                                    <td style="border-top: hidden; border-right: 1px solid black; color: indianred;">R$ {{  number_format($value['totalSemDesconto'] / 100 * $value['porcentagem_geral'], 2, ',', '.')  }}</td>
                                    @endif
                                    <td style="border-right: hidden; border-bottom: hidden; border-top: hidden;"></td>
                                </tr> --}}
                                <tr style="border-top: black solid 1px; border-bottom: black solid 1px;">
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="font-weight: 900; text-align: right; background: #e5e7eb; border-top: hidden; border-right: black solid 1px;">DESCONTO PROMOCIONAL:</td>
                                    <td style="border-top: hidden; border-right: 1px solid black; color: indianred;">R$ {{ number_format($value['desconto_total_promocao'], 2, ',', '.')}}</td>
                                    <td style="border-right: hidden; border-bottom: hidden; border-top: hidden;"></td>
                                </tr>
                                <tr style="border-top: black solid 1px; border-bottom: black solid 1px;">
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="font-weight: 900; text-align: right; background: #e5e7eb; border-top: hidden; border-right: black solid 1px;">DESCONTO GERAL <span style="color: indianred;">{{$value['porcentagem_geral']}}</span>% :</td>
                                    @if($value['totalComDesconto'] != $value['totalSemDesconto'])
                                    <td style="border-top: hidden; border-right: 1px solid black; color: indianred;">R$ {{ number_format(($value['totalComDesconto'] / 100 * $value['porcentagem_geral']), 2, ',', '.')}}</td>
                                    @else
                                    <td style="border-top: hidden; border-right: 1px solid black; color: indianred;">R$ {{  number_format($value['totalSemDesconto'] / 100 * $value['porcentagem_geral'], 2, ',', '.')  }}</td>
                                    @endif
                                    <td style="border-right: hidden; border-bottom: hidden; border-top: hidden;"></td>
                                </tr>
                                <tr style="border-bottom: black solid 1px;">
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                    <td style="font-weight: 900; text-align: right; background: #e5e7eb; border-top: hidden;">À PAGAR:</td>
                                    <td style="border: black solid 1px; border-top: hidden; color: green;">R$ {{ number_format($value['valor_final'], 2, ',', '.') }}</td>
                                    <td style="border-right: hidden; border-bottom: hidden;"></td>
                                </tr>
                            </tbody>
                        </table>
                        @if($pedidosSession)
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
                                        <input type="number" style="border: hidden; text-align: center; color: darkgreen; font-weight: 900;" name="porcentagem" value="{{$value['porcentagem_geral']}}" min="0" max="100">
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
                                        <input name="endereco_id" class="radio";  type="radio"; value="{{$key}}" required>
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
    @endif
    @elseif(!session('status') || $pedidosSession == [])
    <div style="display: flex; justify-content: center; margin-top: 20px;">
        <div class="alert alert-danger"  type="submit">Não há produtos no carrinho!</div>
    </div>
    @endif
</x-app-layout>
