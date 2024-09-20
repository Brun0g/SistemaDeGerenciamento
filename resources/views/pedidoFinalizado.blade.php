<x-app-layout>
<x-slot name="header">
<div style="display: flex;">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    </h2>
    <div style=" display: flex; justify-content: right; width: 60%;" >
    </div>
    <div>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/aaf17c2ff6.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        </x-slot>
        <style type="text/css">
        #te {
        display: flex;
        justify-content: center;
        width: 100% ;
        }
        label {
        font-weight: 900;
        }
        .but {
        margin-top: 0.75rem;
        }
        caption {
        background-color: #e5e7eb;
        }
        table {
        border-collapse: collapse;
        text-align: center;
        border: 1px solid;
        width: 100%;
        }
        thead {
        background-color: #e5e7eb;
        position: sticky;
        top: -15px;
        justify-content: center;
        text-align: center;
        font-size: 16px;
        border: 5px solid;
        }
        td {
        text-align: center;
        }
        .container-center {
        display: flex;
        justify-center: center;
        }
        select{
        width: 67%;
        padding: 5px;
        }
        .box {
        float: left;
        height: 20px;
        width: 20px;
        margin-bottom: 15px;
        border: 1px solid black;
        clear: both;
        }
        .red {
        background-color: red;
        }
        .green {
        background-color: green;
        }
        .blue {
        background-color: blue;
        }
        </style>
        <div class="py-12">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="py-12">
           
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div  class="p-6 bg-white border-b border-gray-200">
                            <div style="display: flex; justify-content: left">
                                <h4>N° PEDIDO: <span style=" font-weight: 900;color: blue">{{$pedido_id}}</span></h4>
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
                              
                                <div style="font-weight: 900;">Endereço de entrega:<span style="font-weight: 200;"> {{strtoupper($endereco)}}</span></div>
                                <div style="font-weight: 900;">Destinatário:<span style="font-weight: 200;"> {{$nome}}</span></div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </x-app-layout>