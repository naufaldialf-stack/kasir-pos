<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS Kasir')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 text-slate-800 antialiased flex h-screen overflow-hidden font-sans">

    <!-- Sidebar Kiri -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between shrink-0 shadow-xl z-20">
        <div>
            <!-- Logo Header -->
            <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-800 bg-slate-950/40">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-blue-500/30">
                    <i class="fa-solid fa-cash-register"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-white text-base leading-tight">Kasir POS</h1>
                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-wider">Point of Sale</p>
                </div>
            </div>

            <!-- Navigasi Menu -->
            <nav class="p-4 space-y-1.5">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('pos.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pos.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-cart-shopping w-5"></i>
                    <span>Kasir POS</span>
                </a>

                <a href="{{ route('pos.history') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pos.history') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5"></i>
                    <span>Riwayat Transaksi</span>
                </a>

                <div class="pt-4 pb-2 px-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Master Data</div>

                <a href="{{ route('products.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-box-archive w-5"></i>
                    <span>Kelola Produk</span>
                </a>

                <a href="{{ route('categories.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-tags w-5"></i>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('inventory.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('inventory.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white' }}">
    <i class="fa-solid fa-warehouse w-5"></i>
    <span>Inventory & Stok</span>
</a>

            </nav>
        </div>

        <!-- Info User & Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/30 flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-[10px] text-slate-400 capitalize">{{ Auth::user()->role ?? 'kasir' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white flex items-center justify-center transition">
                    <i class="fa-solid fa-power-off text-xs"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Konten Utama Kanan -->
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-slate-200 px-8 flex items-center justify-between shrink-0 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800">@yield('page_title', 'Kasir POS')</h2>
            <div class="text-xs font-semibold text-slate-400">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </header>

        <!-- Dynamic Body Content -->
        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>