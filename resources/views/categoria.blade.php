<x-app-layout>

<x-slot name="header">

<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Categorias') }}
</h2>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</x-slot>

<style type="text/css">

    #container-cadastro-cliente{
    display: flex;
    justify-content: center;
    text-align: center;
    flex-wrap: wrap;
    margin: 0 auto;
    width: 20%;
    }
    label {
    font-weight: 900;
    }
    .input-margin-top {
    margin-top: 0.35rem;
    margin-bottom: 0.35rem;
    }
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

</style>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      
        <div style="display: flex; justify-content: center; margin-bottom: 10px;">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">Adicionar categoria</button>
        </div>   
                <table id="table">
                    <thead class="thead">
                        <tr>
                            <th class="row-inform-item">ID</th>
                            <th class="row-inform-item">Categoria</th>
                            <th class="row-inform-item">Ação</th>
                            <th class="row-inform-item">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                         @if(isset($categorias))
                        @foreach ($categorias as $key => $value )
                        <tr style="background: white;">
                            <td style="color:white; background: black; font-weight: 900; border: 1px solid">
                            {{  $key }}</td>
                            <td>{{  strtoupper($value['categoria']) }}</td>
                            <td>
                                <form  action="/DeletarCategoria/{{$key}}" method="POST" >
                                      @csrf
                                    @method('delete')
                                    <button    class="btn btn-primary"  type="submit">Deletar</button>
                                </form>
                            </td>
                             <td>
                                <form  action="/Categoria/{{$key}}" method="GET" >
                                    @csrf
                                    <button    class="btn btn-primary"  type="submit">Editar</button>
                                </form>
                            </td>
                        </tr>
                      
                        @endforeach
                        @else
                        <td colspan="10">Sem dados de registro!</td>
                        @endif
                    </tbody>
                </table>
         
        
    </div>

</div>
</div>
</x-app-layout>


<!-- Botão para acionar modal -->


<!-- Modal -->
<div class="modal fade" id="CadastrarClienteModal" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar categoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div id ="container-cadastro-cliente" class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <table>
                            <th class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error )
                                <td>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </table>
                    </div>
                    @endif 
                <form id="po-insert"  method="POST" action ="/CadastrarCategoria">
                    @csrf
                    <label  for = "name">Categoria do Produto</label>
                    <x-input class="input-margin-top" type="text"  name="categoriaEstoque"/>
                    <button class="btn btn-primary" >Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>






