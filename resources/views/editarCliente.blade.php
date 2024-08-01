<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">

</h2>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
.lg {
width: 70%;
margin: 0 auto;
padding: 25px;
border-radius: 2%;
background: white;
}
.container-center {
display: flex;
justify-content: center;
}
</style>
<div class="py-12">
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
<div class="lg">
    <div>
        <h2 class="id-cliente-container">Cliente: {{strtoupper($cliente[$id]['name'])}}</h2>
        <hr></hr>
        @if(isset($cliente))
        <form method="POST" action="/EditarCliente/{{$id}}" >
            @csrf
            @method('PATCH')
            
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label >Nome</label>
                    <input type="text" class="form-control" id="validationServer01" name="name" value="{{  $cliente[$id]['name'] }}" >
                </div>
                <div class="col-md-3 mb-3">
                    <label >Email</label>
                    <input type="email" class="form-control" id="validationServer02" name="email" value="{{  $cliente[$id]['email'] }}" >
                </div>
                <div class="col-md-2 mb-3">
                    <label >Contato</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="validationServerUsername" name="contato" aria-describedby="inputGroupPrepend3" value="{{  $cliente[$id]['contato'] }}" >
                    </div>
                </div>
                <div class="col-md-2 mb-3" >
                    <label >Idade</label>
                    <div class="input-group">
                        <input style="text-align: center;" type="text" class="form-control" id="validationServerUsername" name="idade" aria-describedby="inputGroupPrepend3" value="{{  $cliente[$id]['idade'] }}" >
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: center;">
                <button class="btn btn-primary" type="submit">Atualizar</button>
            </div>
        </form>
        
        @else
        Sem dados de registro!
        @endif
        
    </div>
    
    <hr></hr>
    <h2 style="display: flex; justify-content: center;">Endereços</h2>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">+</button>
    <table id="table">
        <thead class="thead">
            <tr>
                <th class="row-inform-item">ID</th>
                <th class="row-inform-item">Cidade</th>
                <th class="row-inform-item">Rua</th>
                <th class="row-inform-item">Número</th>
                <th class="row-inform-item">Estado</th>
                <th class="row-inform-item">CEP</th>
                <th class="row-inform-item">Ação</th>
                @if(count($listarEnderecos) > 1)
                <th class="row-inform-item">Ação</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <tr>
                @if($listarEnderecos != [])
                @foreach ($listarEnderecos as $endereco_id => $endereco)
                <td style="border: 1px solid; background: black; color: #fff;">{{$endereco_id}}</td>
                <td style="border: 1px solid;">{{$endereco['cidade']}}</td>
                <td style="border: 1px solid;">{{$endereco['rua']}}</td>
                <td style="border: 1px solid;">{{$endereco['numero']}}</td>
                <td style="border: 1px solid;">{{$endereco['estado']}}</td>
                <td style="border: 1px solid;">{{$endereco['cep']}}</td>
                 @if(count($listarEnderecos) > 1)
                <td style="border: 1px solid;">
                 
                    <form action="/DeletarEndereco/{{$endereco_id}}" method="POST" >
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger"  type="submit" ;>Deletar</button>
                    </form>
                @endif
                </td>
           
                <td style="border: 1px solid;">
                    <form action="/VisualizarEndereco/{{$endereco_id}}" method="GET" >
                        @csrf
                        <button class="btn btn-primary" type="submit">Editar</button>
                    </form>
          
                 
                    
                </td>
                
            </tr>
          

            @endforeach

            @endif
        </tbody>
    </table>
</div>


<div class="modal fade" id="CadastrarClienteModal" tabindex="-1" role="dialog" aria-labelledby="2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
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
        <div class="modal-header">
            <h5 class="modal-title" id="TituloModalCentralizado">Novo endereço</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="po-insert"  method="POST" action = "/novoEndereco/{{$id}}">
                @csrf
                
                <div class="form-row">
                    <div class="form-group col-md-9">
                        <label >Cidade:</label>
                        <input type="text" class="form-control" placeholder="Cidade" name="cidade">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Rua:</label>
                        <input type="text" class="form-control" placeholder="Rua" name="rua">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Número:</label>
                        <input type="text" class="form-control" placeholder="Número" name="numero">
                    </div>
                    
                    
                </div>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="inputState">Estado:</label>
                        <select id="inputState" class="form-control" name="estado">
                            <option selected>Escolha um estado</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputZip">CEP:</label>
                        <input type="text" class="form-control" id="inputZip" name="cep" placeholder="Cep">
                    </div>
                </div>
                <div style="display: flex; justify-content: center;">
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
    </div>
</div>



</x-app-layout>

