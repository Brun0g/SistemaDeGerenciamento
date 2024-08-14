<x-app-layout>
<x-slot name="header">
<div style="display: flex;">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
  {{ __('Pedido de ' . strtoupper($clienteID[$id]['name']) ) }}
  </h2>
  <div style=" display: flex; justify-content: right; width: 60%;" >
    <a href={{'/carrinho/' . $id }}><i class="fa-solid fa-cart-shopping" style="font-size: 26px;"></i></a>
  </div>
  <div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/041db088fc.js" crossorigin="anonymous"></script>
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

    .produto-container {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    }

    </style>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div id ="te" class="p-6 bg-white border-b border-gray-200">
            <table id="table">
              <thead class="thead">
                <tr>
                  <th class="row-inform-item">ID</th>
                  <th class="row-inform-item">Nome</th>
                  <th class="row-inform-item">Email</th>
                  <th class="row-inform-item">Idade</th>
                  <th class="row-inform-item">Cidade</th>
                  <th class="row-inform-item">Cep</th>
                  <th class="row-inform-item">Rua</th>
                  <th class="row-inform-item">Estado</th>
                  <th class="row-inform-item">Contato</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($clienteID[$id]))
                <tr>
                  <td style="color:white; background: black; font-weight: 900; border: 1px solid">{{  $id }}</td>
                  <td>{{  $clienteID[$id]['name'] }}</td>
                  <td>{{  $clienteID[$id]['email'] }}</td>
                  <td>{{  $clienteID[$id]['idade'] }}</td>
                  <td>{{  $clienteID[$id]['cidade'] }}</td>
                  <td>{{  $clienteID[$id]['cep'] }}</td>
                  <td>{{  $clienteID[$id]['rua'] }}</td>
                  <td>{{  $clienteID[$id]['estado'] }}</td>
                  <td>{{  $clienteID[$id]['contato'] }}</td>
                </tr>
                @else
                Sem dados de registro!
                @endif
              </tbody>
            </table>
          </div>
        </div>
        <div class="py-12" >
          <div class="">
            @if (session('status'))
            <div class="alert alert-success" style="display: flex; justify-content:center">
                {{ session('status') }}
            </div>
        @endif
          @if (session('error_estoque'))
              <div class="alert alert-danger" style="display: flex; justify-content:center">
                  {{ session('error_estoque') }}
              </div>
          @endif
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalLongoExemplo" id="CadastrarProduto_x">+</button>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div  class="p-6 bg-white border-b border-gray-200">
                <div style="display: flex; justify-content: center">
                  <h3>Pedidos no carrinho</h3>
                </div>
                <table id="table">
                  <thead class="thead">
                    <tr>
                      {{-- <th class="row-inform-item">ID PEDIDO</th> --}}
                      <th class="row-inform-item">ID CLIENTE</th>
                      <th class="row-inform-item">Nome do Produto</th>
                      <th class="row-inform-item">Quantidade</th>
                      <th class="row-inform-item">Total</th>
                      <th class="row-inform-item">Desconto</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    @if(isset($listarPedidos))
                    
                    @foreach ($listarPedidos as $id_pedido => $value )
                    @if ($value['cliente_id'] == $id )
                    <tr>
                      {{-- <td style="color:white; background: black; font-weight: 900; border: 1px solid">{{  $id_pedido }}</td> --}}
                      <td style="color:white; background: black; font-weight: 900; border: 1px solid">{{  $value['cliente_id'] }}</td>
                      <td>{{  $value['produto'] }}</td>
                      <td>{{  $value['quantidade'] }}</td>
                      <td style="color:green;">R$ {{number_format($value['total_final'] - ($value['total_final'] / 100 * abs($porcentagem)), 2, ",", ".") }}</td>
                      <td style="font-weight: 900;">{{ '-'. $porcentagem . "%" }}</td>
                      @endif
                      @endforeach
                      @else
                      <td colspan="5" >Sem dados de registro!</td>
                      @endif
                    </tbody>
                    <tr style = "border-top: black solid 1px;">
                      <td style="border-bottom: hidden; border-left: hidden;"></td>
                      <td style="border-bottom: hidden"></td>
                      {{-- <td style="border-bottom: hidden"></td> --}}
                      <td style="border-bottom: hidden"></td>
                      
                      <td style="border-left: black solid 1px; color:white; background: black; font-weight: 900;">R$ {{isset($totalPedido) ? number_format($totalPedido - ($totalPedido / 100 * $porcentagem), 2, ",", ".") : 0}}</td>
                      <td style="border-bottom: hidden; border-right: hidden;"></td>
                    </tr>
                  </table>
                </div>

                <div class="container-center" style="display: flex; justify-content: center; margin-bottom: 15px;">
                  <form method="GET" action="/carrinho/{{$id}}">
                    @csrf
                    <button class="btn btn-success"  type="submit">Ir até o carrinho</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div  class="p-6 bg-white border-b border-gray-200">
                <h3 style="display:flex; justify-content: center;">Pedidos</h3>
                <table id="table">
                  <thead class="thead">
                    <tr>
                      <th class="row-inform-item">ID PEDIDO</th>
                      <th class="row-inform-item">Total</th>
                      <th class="row-inform-item">Ação</th>
                      <th class="row-inform-item">Ação</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(!session()->has('PedidosTotalValor'))
                    <x-dynamic :listarPedidosAprovados="$listarPedidosAprovados" :id="$id"></x-dynamic>
                    @else
                    <x-dynamic2 :listarPedidosAprovados="$listarPedidosAprovados" :id="$id"></x-dynamic2>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="ModalLongoExemplo" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="TituloModalLongoExemplo">Cadastrar Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form  method="POST" action="/CadastrarProduto/Cliente/{{$id}}" >
                  @csrf
                  <div class="container-select">
                    <div class = "select-container">
                      <label for="produto-select">Escolha uma categoria:</label>
                      <select  name="categoria" id="categoria-select" required>
                        <option value="">-- Por favor escolha uma categoria --</option>
                        @if($categorias != [])
                        @foreach ($categorias as $key => $value )
                        <option value="{{$value['categoria']}}">{{  $value['categoria'] }}</option>
                        @endforeach
                        @else
                        Sem dados de registro!
                        @endif
                      </select>
                    </div>
                    @if($produtosEstoque != [])
                    <table style="margin-top: 20px;" class="produto-table">
                      <thead>
                        <tr>
                          <th>Produto</th>
                          <th>Quantidade</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($produtosEstoque as $id => $value)
                        <tr>
                          <td class="produto-cell">{{ strtoupper($value['produto']) }}</td>
                          <td class="quantidade-cell">
                            <input
                            type="number"
                            value="0"
                            name="produto[{{$id}}]"
                            class="form-control quantidade-input"
                            placeholder="0"
                            
                            min="0"
                            max="9999"
                            required
                            >
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    @else
                    <p class="no-data-message">Sem dados de registro!</p>
                    @endif
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputPassword1">Valor:</label>
                    <div class="result" style="font-weight: 900;">R$ <span style=" font-weight: 300; color: green">XXXX</span></div>
                  </div>
                  <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
              </div>
              @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                  @foreach ($errors->all() as $error )
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif
              <div class="modal-footer">
                <button  type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              </div>
            </div>
          </div>
        </div>
        <script>
        
        </script>
        </x-app-layout>
