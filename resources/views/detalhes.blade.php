<x-app-layout>
<x-slot name="header">
<div style="display: flex;">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">

</h2>
<div style=" display: flex; justify-content: right; width: 60%;" >
</div>
<div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> 
<script src="https://kit.fontawesome.com/aaf17c2ff6.js" crossorigin="anonymous"></script>
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
}
.container-center {
display: flex;
justify-center: center;
}

select{
width: 67%;
padding: 5px;
}

.box {
  float: left;
  height: 20px;
  width: 20px;
  margin-bottom: 15px;
  border: 1px solid black;
  clear: both;
}

.red {
  background-color: red;
}

.green {
  background-color: green;
}

.blue {
  background-color: blue;
}

</style>
<div class="py-12">
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="py-12">
          
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div  class="p-6 bg-white border-b border-gray-200">
                        <div style="display: flex; justify-content: left">
                                <h4 style="">N° AJUSTES: <span style=" font-weight: 900;color: blue">{{$registro_id}}</span></h4>
                        </div>
                        <table id="table">
                            <thead class="thead">
                                <tr>
                                    <th style="border-right: 1px solid black;"class="row-inform-item">Usúario</th>
                                    <th style="border-right: 1px solid black;"class="row-inform-item">Produto</th>
                                    <th style="border-right: 1px solid black;"class="row-inform-item">Ajuste</th>
                                    {{-- <th style="border-right: 1px solid black;"class="row-inform-item">Quantidade anterior</th> --}}
                                    <th style="border-right: 1px solid black;"class="row-inform-item">Data</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(isset($multiplos))
                                @foreach ($multiplos as $key => $value )
                                <tr>
                                    <td style=" width: 10%; border: 1px solid black;">{{strtoupper($value['user_id'])}}</td>
                                    <td  style="width: 5%; border: 1px solid black;">{{strtoupper($value['produto'])}}</td>
                                    @if($value['tipo'] == 'ENTRADA')
                                    <td  style="color: green; font-weight: 900; width: 5%; border: 1px solid black;">{{$value['quantidade']}}</td>
                                    @else
                                    <td  style="color: red; font-weight: 900; width: 5%; border: 1px solid black;">{{$value['quantidade_anterior'] - $value['quantidade']}}</td>
                                    @endif
                                    {{-- <td  style="color: black; font-weight: 900; width: 5%; border: 1px solid black;">{{$value['quantidade_anterior']}}</td> --}}
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