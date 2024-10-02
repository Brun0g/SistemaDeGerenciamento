<x-app-layout>
    <x-slot name="header">
    
    </x-slot>


        <div class="py-12">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="py-12">
        
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div  class="p-6 bg-white border-b border-gray-200">
                            <div style="display: flex; justify-content: left; flex-direction: column;">
                                <h4>N° PEDIDO: <span style=" font-weight: 900;color: blue">{{$pedido_id}}</span></h4>
                                
                                <h5 style="font-weight: 900;">Data do pedido:<span style="font-weight: 200;"> {{strtoupper($data_pedido)}}</span></h5>
                                <h5 style="font-weight: 900;">Criado por:<span style="font-weight: 200;"> {{strtoupper($create_by)}}</span></h5>
                            </div>
                                                        <table id="table">
                                <thead class="thead">
                                    <tr>
                                        <th style="border-right: 1px solid black;"class="row-inform-item">Produto</th>
                                        <th style="border-right: 1px solid black;"class="row-inform-item">Quantidade</th>
                                        <th style="border-right: 1px solid black;"class="row-inform-item">Preço unidade</th>
                                        <th style="border-right: 1px solid black;"class="row-inform-item">Preço da unidade c/ promoção</th>
                                        <th style="border-right: 1px solid black;"class="row-inform-item">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    @if(isset($array))
                                    @foreach ($array as $pedido_id => $value )
                                    <tr>
                                        <td style=" width: 10%; border: 1px solid black;">{{  strtoupper($value['produto']) }}</td>
                                        <td  style="width: 5%; border: 1px solid black;">{{  $value['quantidade'] }}</td>
                                        <td  style="color: green; width: 5%; border: 1px solid black;">R$ {{  number_format($value['preco_unidade'], 2, ",", ".")}}</td>
                                        @if($value['porcentagem'] > 0)
                                        <td style=" width: 10%; color:green; border: 1px solid black;">R$ {{  number_format($value['preco_unidade'] - ($value['preco_unidade'] / 100 * $value['porcentagem']), 2, ",", ".")}}
                                        </td>
                                        @else
                                        
                                        <td style="color:black; width: 5%; font-size: 14px; font-style: italic; border: 1px solid black;">NÃO APLICADO</td>
                                        @endif
                                        <td style="width: 10%; color:green; border: 1px solid black;">R$ {{  number_format($value['preco_unidade'] * $value['quantidade'] - ($value['preco_unidade'] * $value['quantidade'] / 100 * $value['porcentagem']), 2, ",", ".")  }}</td>
                                        @endforeach
                                        @else
                                        <td colspan="5" >Sem dados de registro!</td>
                                        @endif
                                    </tbody>
                                    <tr style = "border-top: black solid 1px;">
                                        <td style="border: black solid 1px; background: #e5e7eb; border-right: hidden; "></td>
                                        <td style="border: black solid 1px; background: #e5e7eb; border-right: hidden; "></td>
                                        <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden; border-top: black solid 1px;"></td>
                                        <td style="border: black solid 1px; font-weight: 900; text-align: right; background: #e5e7eb;">TOTAL</td>
                                        <td style="border-top: solid 1px black;  color: green; ">R$ {{  number_format($value['totalComDesconto'], 2, ",", ".") }}</td>
                                    </tr>
                                    <tr style = "border-top: black solid 1px; border-bottom: black solid 1px;">
                                        
                                        <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                        <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                        <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                        <td style=" font-weight: 900; text-align: right; background: #e5e7eb; border-top: hidden; ">DESCONTO GERAL <span style="color: indianred;">{{$porcentagem}}</span>%</td>
                                        <td style="border: black solid 1px; color:indianred; ; border-top: hidden;">R$ {{  number_format($value['totalComDesconto'] - ($value['totalComDesconto'] / 100 * $porcentagem) - $value['totalComDesconto'], 2, ",", ".") }}</td>
                                    </tr>
                                    <tr style = "border-top: black solid 1px; border-bottom: black solid 1px;">
                                        
                                        <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                        <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                        <td style="border: black solid 1px; background: #e5e7eb; border-top: hidden; border-right: hidden;"></td>
                                        <td style=" font-weight: 900; text-align: right; background: #e5e7eb; border-top: hidden; ">À PAGAR</td>
                                        <td style="border: black solid 1px; color:green; border-top: hidden;">R$ {{  number_format(abs($total), 2, ",", ".") }}</td>
                                    </tr>
                                </table>
                                 
                                <h5 style="margin-top: 10px; font-weight: 900;">Endereço de entrega:<span style="font-weight: 200;"> {{strtoupper($endereco)}}</span></h5>


                                <h5 style="font-weight: 900;">Destinatário:<span style="font-weight: 200;"> {{strtoupper($nome)}}</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </x-app-layout>