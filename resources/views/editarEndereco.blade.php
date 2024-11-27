<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('Atualizar endereço') }}
		</h2>
	</x-slot>
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
								
								<div><strong>Nome</strong>: {{  $cliente['name'] }}</div>
								<div><strong>Email</strong>: {{  $cliente['email'] }}</div>
								<div><strong>Idade</strong>: {{  $cliente['idade'] }}</div>
								<div><strong>Contato</strong>: {{  $cliente['contato'] }}</div>
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
						<div style="display: flex; justify-content:center">
							<button class="btn btn-primary" type="submit">Atualizar endereço</button>
						</div>
					</form>
				</div>
			</div>
		</x-app-layout>
