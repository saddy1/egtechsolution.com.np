@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h2 class="text-3xl font-bold text-gray-900">Dashboard Overview</h2>
      <form method="GET" class="flex items-end gap-2">
        <div>
          <label class="text-xs text-gray-600">From</label>
          <input type="date" name="from" value="{{ $filters['from'] }}" class="rounded-xl border px-3 py-2">
        </div>
        <div>
          <label class="text-xs text-gray-600">To</label>
          <input type="date" name="to" value="{{ $filters['to'] }}" class="rounded-xl border px-3 py-2">
        </div>
        <button class="rounded-xl bg-gray-100 px-3 py-2 text-sm font-semibold hover:bg-gray-200">Filter</button>
      </form>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      {{-- Sales --}}
      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Total Sales</p>
            <p class="text-3xl font-bold text-gray-800 counter" data-target="{{ $kpis['sales'] }}">0</p>
            <p class="{{ $kpis['sales_mom']>=0 ? 'text-green-600' : 'text-red-600' }} text-sm">
              {{ $kpis['sales_mom']>=0 ? '+' : '−' }}{{ number_format(abs($kpis['sales_mom']),1) }}% vs last mo
            </p>
          </div>
          <div class="bg-blue-100 p-3 rounded-full">
            <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
          </div>
        </div>
      </div>

      {{-- Orders --}}
      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Total Orders</p>
            <p class="text-3xl font-bold text-gray-800 counter" data-target="{{ $kpis['orders'] }}">0</p>
            <p class="{{ $kpis['orders_mom']>=0 ? 'text-green-600' : 'text-red-600' }} text-sm">
              {{ $kpis['orders_mom']>=0 ? '+' : '−' }}{{ number_format(abs($kpis['orders_mom']),1) }}% vs last mo
            </p>
          </div>
          <div class="bg-emerald-100 p-3 rounded-full">
            <i class="fas fa-receipt text-emerald-600 text-xl"></i>
          </div>
        </div>
      </div>

      {{-- Products --}}
      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Products</p>
            <p class="text-3xl font-bold text-gray-800 counter" data-target="{{ $kpis['products'] }}">0</p>
            <p class="text-gray-400 text-sm">catalog size</p>
          </div>
          <div class="bg-yellow-100 p-3 rounded-full">
            <i class="fas fa-box text-yellow-600 text-xl"></i>
          </div>
        </div>
      </div>

      {{-- Customers --}}
      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Customers</p>
            <p class="text-3xl font-bold text-gray-800 counter" data-target="{{ $kpis['customers'] }}">0</p>
            <p class="text-gray-400 text-sm">registered</p>
          </div>
          <div class="bg-purple-100 p-3 rounded-full">
            <i class="fas fa-users text-purple-600 text-xl"></i>
          </div>
        </div>
      </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Sales Trend</h3>
        <canvas id="salesLine"></canvas>
      </div>
      <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Top Categories</h3>
        <canvas id="categoryDoughnut"></canvas>
      </div>
      <div class="bg-white rounded-xl shadow-md p-6 lg:col-span-2">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Top Products</h3>
        <canvas id="topProductsBar"></canvas>
      </div>
    </div>

    {{-- Low Stock --}}
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
      <h3 class="text-lg font-semibold text-red-800 mb-3"><i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Alerts</h3>
      @if($stock['low']->isEmpty())
        <p class="text-sm text-red-700">All good! No low stock items.</p>
      @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
          @foreach($stock['low'] as $item)
            <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
              <span class="font-medium">{{ $item->name }}</span>
              <span class="text-red-700 font-semibold heartbeat">{{ $item->quantity }}</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Stock Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Products</h3>
        <p class="text-3xl font-bold text-blue-600">{{ number_format($kpis['products']) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">In Stock</h3>
        <p class="text-3xl font-bold text-green-600">{{ number_format($stock['in']) }}</p>
      </div>
      <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Out of Stock</h3>
        <p class="text-3xl font-bold text-red-600">{{ number_format($stock['out']) }}</p>
      </div>
    </div>

    {{-- Recent Bills --}}
    <div class="bg-white rounded-xl shadow-md p-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Bills</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left">Bill No</th>
              <th class="px-4 py-3 text-left">Customer</th>
              <th class="px-4 py-3 text-right">Amount</th>
              <th class="px-4 py-3 text-left">Date</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200" id="recentBillsBody">
            @forelse($recentBills as $b)
              <tr>
                <td class="px-4 py-3 font-semibold">{{ $b->bill_no }}</td>
                <td class="px-4 py-3">{{ $b->billing_name }}</td>
                <td class="px-4 py-3 text-right">Rs. {{ number_format($b->subtotal,2) }}</td>
                <td class="px-4 py-3">{{ $b->created_at->format('d M, Y') }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No bills yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

{{-- Charts + animations + (optional) realtime --}}
<script>
// Counter animation
document.querySelectorAll('.counter').forEach(el=>{
  const target = parseFloat(el.dataset.target || '0');
  let cur = 0;
  const steps = 30, inc = target/steps;
  const tick = () => {
    cur += inc;
    if ((inc >= 0 && cur >= target) || (inc < 0 && cur <= target)) cur = target;
    el.textContent = (typeof target === 'number')
      ? (cur.toLocaleString(undefined, {maximumFractionDigits: 0}))
      : cur;
    if (cur !== target) requestAnimationFrame(tick);
  };
  requestAnimationFrame(tick);
});

// Charts data from PHP
const trendLabels = @json($trend['labels']);
const trendSales  = @json($trend['sales']);
const trendOrders = @json($trend['orders']);

const catLabels   = @json($categories['labels']);
const catValues   = @json($categories['values']);

const topProdLabels = @json($topProducts['labels']);
const topProdValues = @json($topProducts['values']);

// Line chart: Sales
new Chart(document.getElementById('salesLine'), {
  type: 'line',
  data: {
    labels: trendLabels,
    datasets: [
      { label: 'Sales', data: trendSales, borderWidth: 2, tension: .3 },
      { label: 'Orders', data: trendOrders, borderWidth: 2, tension: .3 },
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'top' } },
    scales: { y: { beginAtZero: true } }
  }
});

// Doughnut: Categories
new Chart(document.getElementById('categoryDoughnut'), {
  type: 'doughnut',
  data: { labels: catLabels, datasets: [{ data: catValues }] },
  options: { responsive: true }
});

// Bar: Top Products
new Chart(document.getElementById('topProductsBar'), {
  type: 'bar',
  data: { labels: topProdLabels, datasets: [{ label: 'Revenue', data: topProdValues }] },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

// Optional: lightweight polling to simulate realtime if Echo isn't set
setInterval(()=> {
  // example: poll a tiny endpoint to get latest bill subtotal to animate the counter, etc.
  // You can implement /api/dashboard/ping to return {sales: 12345.67, latestBills: [...]}
}, 30000);
</script>

<style>
@keyframes heartbeat { 0%{transform:scale(1)} 25%{transform:scale(1.12)} 40%{transform:scale(1)} 60%{transform:scale(1.06)} 100%{transform:scale(1)} }
.heartbeat { animation: heartbeat 1.4s infinite; }
</style>
@endsection
