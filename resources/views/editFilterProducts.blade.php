<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Editar Produto') }}
</h2>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</x-slot>
<style type="text/css">

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
   
    .container {
      position: relative;
      width: 80%;
    }

    .container-default {
        position: relative;
      width: 80%;
    }

.image {
  opacity: 1;
  display: block;
  width: 100%;
  height: auto;
  transition: .5s ease;
  backface-visibility: hidden;
}

.middle {
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
  cursor: pointer;
}

.container:hover .image {
  opacity: 0.3;

}

.container:hover .middle {
  opacity: 0.8;
}

.text {
  background-color: indianred;
  color: white;
  font-weight: 900;
  font-size: 16px;
  padding: 49px 80px;
}

</style>
<div class="py-12">

    <div class="">
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
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div  class="p-6 bg-white border-b border-gray-200">
                    <table id="table">
                        <thead class="thead">
                            <tr>
                                <th class="row-inform-item">ID</th>
                                <th class="row-inform-item"></th>
                                <th class="row-inform-item">Alterar nome </th>
                                <th class="row-inform-item">Alterar valor</th>
                                <th class="row-inform-item">Alterar imagem</th>
                                <th class="row-inform-item">Ação</th>
                            </tr>
                        </thead>
                        <tbody>


                            @if(isset($EstoqueProdutos))
                            <tr style="vertical-align: middle;">
                                <form method="POST" action="/EditarProduto/{{$produto_id}}" enctype="multipart/form-data" >
                                @csrf
                                @method('PATCH')
                                <td style="width: 3%; background: black; color: white; font-weight: 900">{{$produto_id}}
                                </td>
                                <td style="width: 18%; text-align: center; vertical-align: middle; ">

                                @if($EstoqueProdutos['image_url'] == null)  
                                <div class="container-default">
                                <img class="image" src="{{ asset('images/default.png') }}">
                                </div>

                                @else
                               

                                <div class="container">
                                <img class="image" src="{{ $EstoqueProdutos['image_url'] }}">

                                
                                <div class="middle" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                <div class="text">Excluir imagem</div>
                                </div>
                                </a>
                                </div>

                                @endif
                                </td>

                                <td style="width: 20%; font-weight: 900;"><input style="text-align: center; width: 60%" type="text" name="produto" value="{{strtoupper($EstoqueProdutos['produto']) }}">
                                </td>
                                <td style="width: 15%; font-weight: 900; color: green">R$ <input style="text-align: center; width: 50%" type="text" name="valor" value="{{$EstoqueProdutos['valor'] }}">
                                </td>

                                <td style="width: 10%">
                                    <input type="file" accept="image/*" name="imagem" id="imageFile"/>
                                </td>
                                <td style="width: 20%">
                                    <button class="btn btn-success" type="submit">Aplicar mudanças</button>
                                </td>
                            </tr>
                           </form>
                            @else
                            Sem dados de registro!
                            @endif
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
        
    </div>
   
</div>
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Tem certeza que deseja excluir?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div style="display: flex; justify-content: center;">
         <img style="width: 50%" src="{{ $EstoqueProdutos['image_url'] }}">
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <form method="POST" action="/excluirImagem/{{$produto_id}}" enctype="multipart/form-data" >
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Sim</button>
        </form>
      </div>
    </div>
  </div>
</div>



</x-app-layout>

