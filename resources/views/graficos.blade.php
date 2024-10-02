<x-app-layout>
<x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
  {{ __('Gráficos') }}
  </h2>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<script>
const ctx = document.getElementById('myChart');
new Chart(ctx, {
type: 'bar',
data: {
labels: [],
datasets: [{
label: 'Número de pedidos por Cliente',
data: [],
borderWidth: 1
}]
},
options: {
scales: {
y: {
beginAtZero: true
}
}
}
});
</script>
</x-app-layout>