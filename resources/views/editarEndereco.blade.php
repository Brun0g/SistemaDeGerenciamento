<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Atualizar endereço') }}
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
.lg {
width: 50%;
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
		<h3 class="id-cliente-container">Cliente</h3>
		<hr></hr>
		
		@if($id == $enderecos[$endereco_id]['cliente_id'])
		<form method="POST" action="/EditarEndereco/{{$endereco_id}}" >
			@csrf
			@method('PATCH')
			<div class="form-row">
				<div class="col">
					
					<div><strong>Nome</strong>: {{  $cliente[$id]['name'] }}</div>
					<div><strong>Email</strong>: {{  $cliente[$id]['email'] }}</div>
					<div><strong>Idade</strong>: {{  $cliente[$id]['idade'] }}</div>
					<div><strong>Contato</strong>: {{  $cliente[$id]['contato'] }}</div>
				</div>
			</div>
			<hr></hr>
			<h4>Editar endereço</h4>
			<div>
				
				<div><strong>Cidade</strong>: {{  $enderecos[$endereco_id]['cidade'] }}</div>
				<div><strong>CEP</strong>: {{  $enderecos[$endereco_id]['cep'] }}</div>
				<div><strong>Rua</strong>: {{  $enderecos[$endereco_id]['rua'] }}</div>
				<div><strong>Numero</strong>: {{  $enderecos[$endereco_id]['numero'] }}</div>
				<div><strong>Estado</strong>: {{  $enderecos[$endereco_id]['estado'] }}</div>
			</div>
			<hr></hr>
			<div class="form-row">
				<div class="col-md-6 mb-3">
					<label for="cidade">Cidade</label>
					<input type="text" class="form-control" name="cidade" value="{{$enderecos[$endereco_id]['cidade']}}">
					<label>Rua</label>
					<input type="text" class="form-control" name="rua" value="{{$enderecos[$endereco_id]['rua']}}">
					<div class="form-row">
						<div class="col-md-7 mb-4">
							<label>Estado</label>
							<select id="inputState" class="form-control" name="estado">
								<option selected>{{$enderecos[$endereco_id]['estado']}}</option>
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
					</div>
				</div>
				<div class="col-md-3 mb-3">
					<label>Cep</label>
					<input type="text" class="form-control"  name="cep" value="{{$enderecos[$endereco_id]['cep']}}">
					<label>Número</label>
					<input type="number" class="form-control" name="numero" value="{{$enderecos[$endereco_id]['numero']}}">
				</div>
				@endif
				
				
				
			</div>
			<div class="container-center">
				<button class="btn btn-primary" type="submit">Atualizar endereço</button>
			</div>
		</form>
		
		
	</div>
</div>
</x-app-layout>