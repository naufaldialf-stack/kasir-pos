<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir POS') - Smart POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between shadow-xl z-20">
            <div>
                <!-- Brand Logo -->
                <div class="p-5 flex items-center gap-3 border-b border-slate-800">
                    <div class="bg-blue-600 text-white p-2 rounded-lg font-black text-xl tracking-wider shadow-lg shadow-blue-500/30">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-lg tracking-wide text-white">SMART POS</h1>
                        <p class="text-xs text-slate-400">Point of Sale System</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1">
                    <div class="text-xs font-semibold text-slate-500 uppercase px-3 mb-2 tracking-wider">Menu Utama</div>
                    
                    <a href="{{ route('pos.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('pos.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-store w-5"></i>
                        <span>Menu Kasir (POS)</span>
                    </a>

                    <a href="{{ route('pos.history') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('pos.history') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-receipt w-5"></i>
                        <span>Riwayat Transaksi</span>
                    </a>

                    <div class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2 tracking-wider">Master Data</div>

                    <a href="{{ route('products.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-boxes-stacked w-5"></i>
                        <span>Kelola Produk</span>
                    </a>

                    <a href="{{ route('categories.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-tags w-5"></i>
                        <span>Kelola Kategori</span>
                    </a>
                </nav>
            </div>

            <!-- Footer Sidebar -->
            <div class="p-4 border-t border-slate-800 text-xs text-slate-500 text-center">
                &copy; {{ date('Y') }} Smart POS v1.0
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-full overflow-y-auto">
            <!-- Top Navbar -->
            <header class="bg-white border-b border-slate-200 py-3 px-8 flex justify-between items-center sticky top-0 z-10 shadow-sm">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                            K
                        </div>
                        <span class="text-sm font-semibold text-slate-700 pr-2">Kasir Admin</span>
                    </div>
                </div>
            </header>

            <!-- Main Page View Content -->
            <main class="p-8 flex-1">
                @yield('content')
            </main>
        </div>

    </div>
</body>
</html>