<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="py-12 ">
                <div class="overflow-hidden shadow-sm sm:rounded-lg ">
                        <div style="display: flex; justify-content: left">
                            <h4 style="">AJUSTE N°: <span style=" font-weight: 900;color: blue">{{$estoque_id}}</span></h4>
                        </div>
                    <table id="table" class="bg-white">
                        <thead >
                            <tr>
                                <th >Usúario</th>
                                <th >Produto</th>
                                <th >Ajuste</th>
                                <th >Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($multiplos))
                            @foreach ($multiplos as $key => $value )
                            <tr>
                                <td style=" width: 10%; border: 1px solid black;">{{strtoupper($value['create_by'])}}</td>
                                <td  style="width: 5%; border: 1px solid black;">{{strtoupper($value['produto_id'])}}</td>
                                <td  style="color: black; font-weight: 900; width: 5%; border: 1px solid black;">{{$value['quantidade'] }}</td>
                                <td  style=" width: 5%; border: 1px solid black;">{{$value['created_at']}}</td>
                                @endforeach
                                @else
                                <td colspan="5" >Sem dados de registro!</td>
                                @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
