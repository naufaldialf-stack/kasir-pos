@extends('layouts.app')

@section('title', 'Menu Kasir POS')
@section('page_title', 'Kasir Penjualan Direct')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Area Katalog Produk -->
    <div class="lg:col-span-2 space-y-6">
        
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-xl text-emerald-600"></i>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-xl text-rose-600"></i>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <!-- List Grid Produk -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-800">Pilih Produk</h3>
                <span class="text-xs text-slate-400 font-medium">Klik produk untuk menambah ke keranjang</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @if(count($products) > 0)
                    @for($i = 0; $i < count($products);$i++)
                        @php
                            $imgUrl = $products[$i]->image ? asset('storage/' . $products[$i]->image) : null;
                        @endphp
                        <div onclick="addToCart({{ $products[$i]->id }}, '{{ $products[$i]->name }}', {{ $products[$i]->price }}, {{ $products[$i]->stock }})" 
                             class="group border border-slate-200 hover:border-blue-500 bg-slate-50 hover:bg-blue-50/30 rounded-2xl overflow-hidden cursor-pointer transition-all duration-200 shadow-sm hover:shadow-md flex flex-col justify-between">
                            
                            <!-- Foto Produk -->
                            <div class="h-32 w-full bg-slate-200 relative overflow-hidden">
                                @if($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $products[$i]->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                        <i class="fa-solid fa-image text-3xl"></i>
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 bg-slate-900/70 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    Stok: {{ $products[$i]->stock }}
                                </span>
                            </div>

                            <!-- Info Produk -->
                            <div class="p-3.5 flex flex-col justify-between flex-1">
                                <div class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition line-clamp-1 mb-2">
                                    {{ $products[$i]->name }}
                                </div>
                                <div class="flex justify-between items-center border-t border-slate-200/60 pt-2.5">
                                    <span class="text-[11px] font-medium text-slate-400">Harga</span>
                                    <span class="text-blue-600 font-extrabold text-sm">Rp {{ number_format($products[$i]->price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                        </div>
                    @endfor
                @else
                    <div class="col-span-3 text-center py-12 text-slate-400">
                        <i class="fa-solid fa-box-open text-4xl mb-3 text-slate-300"></i>
                        <p class="font-medium">Belum ada produk siap jual.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Area Keranjang Belanja -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between h-full min-h-[500px]">
        <div>
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping text-blue-600"></i> Keranjang Belanja
                </h3>
                <button type="button" onclick="clearCart()" class="text-xs font-bold text-rose-500 hover:text-rose-700 transition">Reset</button>
            </div>

            <div id="cart-items" class="space-y-3 max-h-80 overflow-y-auto pr-1">
                <p class="text-slate-400 text-center py-8 text-sm">Keranjang masih kosong</p>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-6 mt-6 space-y-4">
            <div class="bg-slate-50 p-4 rounded-xl flex justify-between items-center border border-slate-100">
                <span class="text-sm font-semibold text-slate-500">Total Tagihan:</span>
                <span id="total-price" class="text-2xl font-black text-blue-600">Rp 0</span>
            </div>

            <form action="{{ route('pos.store') }}" method="POST" onsubmit="prepareSubmit(event)">
                @csrf
                <input type="hidden" name="cart" id="cart-input">
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Jumlah Pembayaran (Rp)</label>
                    <input type="number" id="pay-amount" name="pay_amount" required placeholder="0" 
                           class="bg-slate-50 border border-slate-300 rounded-xl w-full p-3 font-bold text-lg text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition">
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Process Payment
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    let cart = [];

    function addToCart(id, name, price, maxStock) {
        let item = cart.find(i => i.id === id);
        if (item) {
            if (item.qty < maxStock) {
                item.qty++;
            } else {
                alert('Stok barang terbatas!');
            }
        } else {
            cart.push({ id, name, price, qty: 1, maxStock });
        }
        renderCart();
    }

    function removeFromCart(id) {
        cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function renderCart() {
        let container = document.getElementById('cart-items');
        let totalEl = document.getElementById('total-price');
        
        if (cart.length === 0) {
            container.innerHTML = '<p class="text-slate-400 text-center py-8 text-sm">Keranjang masih kosong</p>';
            totalEl.innerText = 'Rp 0';
            return;
        }

        let html = '';
        let total = 0;

        cart.forEach(item => {
            let subtotal = item.price * item.qty;
            total += subtotal;
            html += `
                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div>
                        <div class="font-bold text-slate-800 text-sm">${item.name}</div>
                        <div class="text-xs text-slate-400 font-medium">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-sm text-slate-700">Rp ${subtotal.toLocaleString('id-ID')}</span>
                        <button type="button" onclick="removeFromCart(${item.id})" class="text-rose-500 hover:bg-rose-100 w-6 h-6 rounded-full flex items-center justify-center text-xs transition">✕</button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        totalEl.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function prepareSubmit(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Keranjang belanja masih kosong!');
            return;
        }
        document.getElementById('cart-input').value = JSON.stringify(cart);
    }
</script>
@endsection