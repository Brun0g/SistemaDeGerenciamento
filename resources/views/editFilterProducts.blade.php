<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Editar Produto') }}
        </h2>
    </x-slot>

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
                <thead >
                    <tr>
                        <th>ID</th>
                        <th></th>
                        <th>Alterar nome </th>
                        <th>Alterar valor</th>
                        <th>Alterar imagem</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($Produtos))
                    <tr style="vertical-align: middle;">
                        <form method="POST" action="/EditarProduto/{{$produto_id}}" enctype="multipart/form-data" >
                            @csrf
                            @method('PATCH')
                            <td style="width: 3%; background: black; color: white; font-weight: 900">{{$produto_id}}
                            </td>
                            <td style="width: 18%; text-align: center; vertical-align: middle; ">
                                @if($Produtos['image_url'] == null)
                                <div style="position: relative; width: 80%;">
                                    <img class="image-produto" src="{{ asset('images/default.png') }}">
                                </div>
                                @else
                                
                                <div class="container">
                                    <img class="image-produto" src="{{ $Produtos['image_url'] }}">
                                    
                                    <div class="middle" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                        <div class="text">Excluir imagem</div>
                                    </div>
                                </a>
                            </div>
                            @endif
                        </td>
                        <td style="width: 20%; font-weight: 900;"><input style="text-align: center; width: 60%" type="text" name="produto" value="{{strtoupper($Produtos['produto']) }}">
                    </td>
                    <td style="width: 15%; font-weight: 900; color: green">R$ <input style="text-align: center; width: 50%" type="text" name="valor" value="{{$Produtos['valor'] }}">
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
<img style="width: 50%" src="{{ $Produtos['image_url'] }}">
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
