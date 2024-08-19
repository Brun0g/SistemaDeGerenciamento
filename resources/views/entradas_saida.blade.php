<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Entradas e saídas de produtos') }}
</h2>



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
.caption-style {
background-color: royalblue;
border: 1px solid black;
color: white;
text-align: center;
font-weight: 900;
font-size: 20px;
padding: 10px;
}
</style>
       


<div style="color: white;" id="carouselExampleIndicators" class="carousel slide bg-dark" data-ride="carousel" data-bs-theme="dark">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    @foreach($entradas as $key => $value)  
        <li data-target="#carouselExampleIndicators" data-slide-to={{$key}}></li>
    @endforeach
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
        
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="..." alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="" alt="Third slide">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true" ></span>
    <span>Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only" >Next</span>
  </a>
</div>


@if(isset($produtos))
@foreach($produtos as $produto_id => $valor)
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="caption-style">
                {{strtoupper($valor['produto'])}}
            </div>
            <div style=" font-weight: 900; display: flex; justify-content: center; background: black; color: white"> 
            ENTRADAS
        </div>
            <table id="table">
                <thead class="thead">
                    <tr>
                        <th class="row-inform-item">Usuário ID</th>
                    
                        <th class="row-inform-item">Quantidade</th>
                        <th class="row-inform-item">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($entradas))
                    @foreach($entradas as $key => $value)
                    @if($value['produto_id'] == $produto_id)
                    <tr>
                        <td>{{  $value['user_id']}}</td>
                      
                        <td style="font-weight: 900; color: green">{{  $value['quantidade'] }}</td>
                        <td>{{  $value['data'] }}</td>
                    </tr>
                    @endif
                    @endforeach
                    @else
                    Sem dados de registro!
                    @endif
                </tbody>
            </table>
        <div style=" font-weight: 900; display: flex; justify-content: center; background: black; color: white"> 
            SAÍDAS
        </div>
        <table id="table">
            <thead class="thead">
                <tr>
                    <th class="row-inform-item">Usuário ID</th>
                    <th class="row-inform-item">Pedido ID</th>
             
                    <th class="row-inform-item">Quantidade</th>
                    <th class="row-inform-item">Data</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($saidas))
                @foreach($saidas as $key => $value)
                @if($value['produto_id'] == $produto_id)
                <tr>
                    <td>{{  $value['user_id']}}</td>
                    <td>{{  $value['pedido_id'] }}</td>
                  
                    <td style="font-weight: 900; color: red">{{  -$value['quantidade'] }}</td>
                    <td>{{  $value['data'] }}</td>
                </tr>
                @endif
                @endforeach
                @else
                Sem dados de registro!
                @endif
            </tbody>
        </table>
        </div>
    </div>
</div>

@endforeach
@endif

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</x-app-layout>