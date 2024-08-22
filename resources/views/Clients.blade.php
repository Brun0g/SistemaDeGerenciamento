<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Clientes') }}
</h2>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
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
font-size: 13px;
}
</style>
<div class="py-12">
    <div class="max-w mx-auto sm:px-6 lg:px-8">
  
        <div style="display: flex; justify-content: center; margin-bottom: 10px;">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CadastrarClienteModal">Adicionar novo cliente</button>
        </div>

           
                <table id="table">
                    <thead class="thead">
                        <tr>
                            <th class="row-inform-item" style="width: 3%;">ID</th>
                            <th class="row-inform-item" style="width: 15%;">Nome</th>
                            <th class="row-inform-item">Email</th>
                            <th class="row-inform-item" style="width: 5%;">Idade</th>
                            <th class="row-inform-item" >Endereço</th>
                            <th class="row-inform-item">Contato</th>
                            <th class="row-inform-item">Total</th>
                            <th class="row-inform-item">Ação</th>
                            <th class="row-inform-item">Ação</th>
                            <th class="row-inform-item">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($tabela_clientes))
                        @foreach ($tabela_clientes as $key => $value )
                        <tr style="background: white;">
                            <td style="font-size: 16px;  color:white; background: black; font-weight: 900; border: 1px solid">{{  $key }}</td>
                            <td style ="border: 1px solid; ">{{  strtoupper($value['name']) }}</td>
                            <td style ="border: 1px solid;">{{  $value['email'] }}</td>
                            <td style ="border: 1px solid;">{{  $value['idade'] }}</td>
                            <td style="width: 30%;  text-align: left; border: 1px solid; border-right: none;">
                                <ol style="list-style-type: decimal; list-style-position: inside; ">
                                  
                                    @if($listar_enderecos != [])
                                    @foreach ($listar_enderecos as $id_endereco => $endereco)
                                    @if($key == $endereco['cliente_id'])
                                    
                                    <li style="  border-right: none; ">
                                        
                                        {{ strtoupper($endereco['rua'] . ", " . $endereco['numero'] . ", ". $endereco['cidade'] .", ".$endereco['estado'].", ". $endereco['cep'])}}
                                    </li>
                                    @endif
                                    @endforeach
                                </ol>
                                @endif
                            </td>
                            
                            
                            <td style ="border: 1px solid;">{{  $value['contato'] }}</td>
                            <td style="border: 1px solid; color:green;"> R$ {{isset($total[$key])  ? $total[$key]  : 0}}</td>
                            <td style="border: 1px solid;">
                                <form  action="/DeletarCliente/{{$key}}" method="POST" >
                                    @csrf
                                    @method('delete')
                                    <button    class="btn btn-danger"  style="padding: 5px;" type="submit">Deletar</button>
                                </form>
                            </td>
                            <td style="border: 1px solid black;">
                                <form  action="/Cliente/{{$key}}" method="GET" >
                                    @csrf
                                    <button    class="btn btn-primary"  style="padding: 5px;" type="submit">Visualizar pedidos</button>
                                </form>
                            </td>
                            <td style="border: 1px solid black;">
                                <form  action="/Editar/Cliente/{{$key}}" method="GET" >
                                    @csrf
                                    <button    class="btn btn-primary"  style="padding: 5px;" type="submit">Editar cliente</button>
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
<div class="modal fade" id="CadastrarClienteModal" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
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
        <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar novo cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="po-insert"  method="POST" action = "/cadastrarCliente">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input type="text" class="form-control" placeholder="Nome completo" name="name">
                </div>
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" class="form-control" placeholder="Email" name="email">
                </div>
                <div class="form-group col-md-6">
                    <label >Idade</label>
                    <input type="number" class="form-control" placeholder="Idade" name="idade">
                </div>
                <div class="form-group col-md-6">
                    <label>Contato</label>
                    <input type="text" class="form-control" placeholder="Contato" name="contato">
                </div>
            </div>
            <h4>Endereço</h4>
            <hr></hr>
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
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
    </div>
</div>
</div>