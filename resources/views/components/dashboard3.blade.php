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
</style>

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
    <form id="po-insert"  method="POST" action = "/cadastrarCliente">
        @csrf
       {{--  <label  for = "name">Nome</label>
        <x-input class="input-margin-top" name="name"/>
        <label  for = "email">Email</label>
        <x-input class="input-margin-top" name="email"  />
        <label  for = "idade">Idade</label> --}}
      {{--   <x-input class="input-margin-top"  name="idade"/>
        <label  for = "cidade">Cidade</label> --}}
        <x-input class="input-margin-top" name="cidade"/>
        <label  for = "cep">CEP</label>
        <x-input class="input-margin-top" name="cep"/>
        <label  for = "rua">Rua</label>
        <x-input class="input-margin-top" name="rua"/>,
        <label  for = "estado">Estado</label>
        <x-input class="input-margin-top" name="estado"/>
        <label  for = "contato">Contato</label>
        <x-input class="input-margin-top"  name="contato"/>
        <button class="btn btn-primary">Enviar</button>
    </form> 
    
</div>


