<x-app-layout>
  <x-slot name="header">
    <div style="display: flex; justify-content:space-between; ">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Pedido de ' . strtoupper($clienteID['name']) ) }}
      </h2>
      @if($deletedAt == null)
        <div style=" display: flex; justify-content: right; width: 60%;" >
          <a href={{'/carrinho/' . $cliente_id }}><i class="fa-solid fa-cart-shopping" style="font-size: 26px;"></i></a>
        </div>
      @endif
    </div>
  </x-slot>

  <style type="text/css">
  
  </style>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm">
        
        <table id="table">
          <thead >
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Email</th>
              <th>Idade</th>
              <th>Cidade</th>
              <th>Cep</th>
              <th>Rua</th>
              <th>Estado</th>
              <th>Contato</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($clienteID))
            <tr>
              <td style="color:white; background: black; font-weight: 900; border: 1px solid">{{  $cliente_id }}</td>
              <td>{{  $clienteID['name'] }}</td>
              <td>{{  $clienteID['email'] }}</td>
              <td>{{  $clienteID['idade'] }}</td>
              <td>{{  $clienteID['cidade'] }}</td>
              <td>{{  $clienteID['cep'] }}</td>
              <td>{{  $clienteID['rua'] }}</td>
              <td>{{  $clienteID['estado'] }}</td>
              <td>{{  $clienteID['contato'] }}</td>
            </tr>
            @else
            Sem dados de registro!
            @endif
          </tbody>
        </table>
        
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
          @if( $clienteID['deleted_at'] == null )
          <div style="display: flex; justify-content: center; margin-bottom: 20px">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalLongoExemplo" id="CadastrarProduto_x">Adicionar pedido</button>
          </div>
          @endif
          
          @if($deletedAt == null)
          <div style="display: flex; justify-content: center">
            <h1 style="font-size: 32px; margin-bottom: 10px;">Carrinho</h1>
          </div>
          <table id="table">
            <thead >
              <tr>
                {{-- <th>ID PEDIDO</th> --}}
                <th>ID CLIENTE</th>
                <th>Nome do Produto</th>
                <th>Quantidade</th>
                <th>Total</th>
                <th>Desconto</th>
              </tr>
            </thead>
            <tbody>
           
            
              @if(sizeof($listar_carrinho) > 0)
              
              @foreach ($listar_carrinho as $pedido_id => $value )
              @if ($value['cliente_id'] == $cliente_id )
              <tr class="bg-white">
                <td style="color:white; background: black; font-weight: 900; border: 1px solid">{{  $value['cliente_id'] }}</td>
                <td>{{  $value['produto'] }}</td>
                <td>{{  $value['quantidade'] }}</td>
                <td style="color:green;">R$ {{number_format($value['total_final'] - ($value['total_final'] / 100 * abs($porcentagem)), 2, ",", ".") }}</td>
                <td style="font-weight: 900;">{{ '-'. $porcentagem . "%" }}</td>
                @endif
                @endforeach
                @else
                @if($deletedAt == null)
                <td style="background: white;" colspan="5" >Sem dados de registro!</td>
                @endif
                @endif

              </tbody>
               @if($deletedAt == null)
              <tr style = "border-top: black solid 1px;">
                <td style="border-bottom: hidden; border-left: hidden;"></td>
                <td style="border-bottom: hidden"></td>
                <td style="border-bottom: hidden"></td>
                
                <td style="border-left: black solid 1px; color:white; background: black; font-weight: 900;">R$ {{isset($totalPedido) ? number_format($totalPedido - ($totalPedido / 100 * $porcentagem), 2, ",", ".") : 0}}</td>
                <td style="border-bottom: hidden; border-right: hidden;"></td>
              </tr>
              @endif
            </table>
            @if( $clienteID['deleted_at'] == null )
            <div style="display: flex; justify-content: center; margin-top: 15px;">
              <form method="GET" action="/carrinho/{{$cliente_id}}">
                @csrf
                <button class="btn btn-success"  type="submit">Ir até o carrinho</button>
              </form>
            </div>
            @endif
          </div>
        </div>

           @endif
   
        <div style="display: flex; justify-content: center;">
          <h1 style="font-size: 32px; margin-bottom: 10px;">Pedidos concluídos</h1>
        </div>
        <table id="table">
          <thead >
            <tr>
              <th>ID PEDIDO</th>
              <th>Criado por</th>
              <th>Restaurado por</th>
              <th>Total</th>
              <th>Ação</th>
         
              <th>Ação</th>
         
            </tr>
          </thead>

          <tbody>
            <x-dynamic :listarPedidosAprovados="$listar_pedidos" :id="$cliente_id" :deletedAt="$deletedAt"></x-dynamic>
          </tbody>
        </table>
        
        
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
                <form  method="POST" action="/CadastrarProduto/Cliente/{{$cliente_id}}" >
                  @csrf
                  <div class="container-select">
                    <div class = "select-container">
                      <label for="produto-select">Escolha uma categoria:</label>
                      <select  name="categoria" id="categoria-select" required>
                        <option value="">-- Por favor escolha uma categoria --</option>
                        @if($listar_categorias != [])
                        @foreach ($listar_categorias as $key => $value )
                        <option value="{{$value['categoria']}}">{{  $value['categoria'] }}</option>
                        @endforeach
                        @else
                        Sem dados de registro!
                        @endif
                      </select>
                    </div>
                    @if($listar_produtos != [])
                    <table style="margin-top: 20px;" class="produto-table">
                      <thead>
                        <tr>
                          <th>Produto</th>
                          <th>Quantidade</th>
                          <th>Estoque</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($listar_produtos as $produto_id => $value)
                        <tr>
                          <td class="produto-cell">{{ strtoupper($value['produto']) }}</td>
                          <td class="quantidade-cell">
                            <input
                            type="number"
                            value="0"
                            name="produto[{{$produto_id}}]"
                            class="form-control quantidade-input"
                            placeholder="0"
                            
                            min="0"
                            max="9999"
                            required
                            >
                          </td>
                          <td class="produto-cell">{{ $value['quantidade']}}</td>
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
        
        @if($total_paginas >= 0)
<div style="display: flex; justify-content: center; margin-top: 20px;">

    @if($pagina_atual > 0)
    <div style="display: flex; justify-content: center; ">
        <form action="/Cliente/{{$cliente_id}}" method="GET">
        @csrf

        <input type="hidden" name="page" value="{{$pagina_atual - 1}}">

        <div><button class="btn btn-info" type="submit" style="color: white; font-weight: 900;"><</span></button></div>
        </form>
    </div>
    @endif

    <div style="display: flex; justify-content: center; ">
        @for ($i = 0; $i <= $total_paginas; $i++)
            <form action="/Cliente/{{$cliente_id}}" method="GET">
            @csrf
            

            @if($total_paginas == 0 && $pagina_atual == 0)
            @else
            <button class="btn {{$pagina_atual == $i ? 'btn-secondary' : 'btn-dark'}} " name="page"  value="{{$i}}"type="submit" style="color: white; font-weight: 900;">{{$i}}</span></button>
            @endif
            </form>
        @endfor
    </div>
  
    @if($total_paginas  > $pagina_atual)
    <div style="display: flex; justify-content: center;">
        <form action="/Cliente/{{$cliente_id}}" method="GET">
            @csrf
            
            <input type="hidden" name="page" value="{{$pagina_atual + 1}}">

            <button class="btn btn-info" type="submit" style="color: white; font-weight: 900;">></span></button>
        </form>
    </div>
    @endif
</div>
@endif
</x-app-layout>
