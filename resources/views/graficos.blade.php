<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Gráficos') }}
    </h2>
  </x-slot>

<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div id ="te" class="p-6 bg-white border-b border-gray-200">
          <div>
            <canvas id="myChart"></canvas>
          </div>
        </div>
      </div>
    </div>
</div>

@push('scripts')
  <script>
    const data = {
      labels: @json($data->map(fn ($data) => $data->date)),
      datasets: [{
          label: 'Quantidade de pedidos feito por dia',
          backgroundColor: 'rgba(255, 99, 132, 0.3)',
          borderColor: 'rgb(255, 99, 132)',
          data: @json($data->map(fn ($data) => $data->aggregate)),
      }]
    };

    const config = {
        type: 'line',
        data: data
    };

    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );

  </script>
@endpush

</x-app-layout>
