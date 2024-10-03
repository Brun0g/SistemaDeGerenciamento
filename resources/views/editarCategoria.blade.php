<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
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
                        <th>Categoria</th>
                        <th>Ação</th>
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