@extends('layouts.app')

@section('title', 'Dashboard Analytics')
@section('page_title', 'Dashboard Overview')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-8">

    <!-- Top Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pendapatan Hari Ini</p>
                <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($todayEarnings ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Transaksi Hari Ini</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $todayTransactions ?? 0 }} <span class="text-xs font-normal text-slate-400">Trx</span></h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Produk</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalProducts ?? 0 }} <span class="text-xs font-normal text-slate-400">Item</span></h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Stok Menipis (≤5)</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $lowStockProducts ?? 0 }} <span class="text-xs font-normal text-slate-400">Item</span></h3>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

    </div>

    <!-- Grafik & Top Selling -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800">Tren Penjualan 7 Hari Terakhir</h3>
                <p class="text-xs text-slate-400">Grafik total nominal penjualan per hari</p>
            </div>
            <div class="h-72">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Produk Terlaris</h3>
            <p class="text-xs text-slate-400 mb-6">Barang paling sering dibeli</p>

            <div class="space-y-4">
                @forelse($topProducts ?? [] as $item)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-slate-200 overflow-hidden flex items-center justify-center">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-box text-slate-400"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm line-clamp-1">{{ $item->product->name ?? 'Produk Dihapus' }}</h4>
                                <span class="text-[11px] text-slate-400 font-medium">Rp {{ number_format($item->product->price ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <span class="bg-blue-50 text-blue-600 text-xs font-extrabold px-3 py-1 rounded-full whitespace-nowrap">
                            {{ $item->total_qty }} Terjual
                        </span>
                    </div>
                @empty
                    <p class="text-center py-8 text-slate-400 text-sm">Belum ada data penjualan.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? []) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartTotals ?? []) !!},
                borderColor: '#2563eb',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#2563eb'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endsection