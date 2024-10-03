<x-app-layout>
    <x-slot name="header">

    </x-slot>
        <div class="py-12">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="py-12">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div  class="p-6 bg-white border-b border-gray-200">
                            <div style="display: flex; justify-content: left">
                                <h4 style="">N° AJUSTES: <span style=" font-weight: 900;color: blue">{{$estoque_id}}</span></h4>
                            </div>
                            <table id="table" >
                                <thead >
                                    <tr>
                                        <th style="border-right: 1px solid black;">Usúario</th>
                                        <th style="border-right: 1px solid black;">Produto</th>
                                        <th style="border-right: 1px solid black;">Ajuste</th>
                                        <th style="border-right: 1px solid black;">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @if(isset($multiplos))
                                    @foreach ($multiplos as $key => $value )
                                    <tr>
                                        <td style=" width: 10%; border: 1px solid black;">{{strtoupper($value['create_by'])}}</td>
                                        <td  style="width: 5%; border: 1px solid black;">{{strtoupper($value['produto'])}}</td>
                                        @if($value['tipo'] == 'ENTRADA')
                                        <td  style="color: green; font-weight: 900; width: 5%; border: 1px solid black;">{{$value['quantidade']}}</td>
                                        @else
                                        <td  style="color: red; font-weight: 900; width: 5%; border: 1px solid black;">{{$value['quantidade']}}</td>
                                        @endif
                                        <td  style=" width: 5%; border: 1px solid black;">{{$value['data']}}</td>
                                        
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
            </div>
</x-app-layout>