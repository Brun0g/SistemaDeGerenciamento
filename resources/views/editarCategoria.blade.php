<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Dashboard') }}
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
                        <th class="row-inform-item">Categoria</th>
                        <th class="row-inform-item">Ação</th>
                    </tr>
                </thead>
                <tbody >
                    @if(isset($categoria))
                    <tr>
                        <form method="POST" action="/editarCategoria/{{$categoria_id}}" >
                            @csrf
                            @method('PATCH')
                            <td>{{$categoria_id}}</td>
                            <td><input type="text" name="categoria" value={{$categoria[$categoria_id]['categoria'] }}></td>
                            <td><button class="btn btn-primary" type="submit">Atualizar</button></td>
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
</x-app-layout>