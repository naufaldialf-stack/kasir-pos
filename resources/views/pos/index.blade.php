@extends('layouts.app')

@section('title', 'Menu Kasir POS')
@section('page_title', 'Kasir Penjualan Direct')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Kolom Kiri: Katalog Produk -->
    <div class="lg:col-span-2 space-y-4">
        
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                <span class="text-sm font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-800">Pilih Produk</h3>
                <span class="text-xs text-slate-400">Klik produk untuk menambah ke keranjang</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <?php if (count($products) === 0): ?>
                    <div class="col-span-full py-12 text-center text-slate-400 text-sm">
                        Belum ada data produk aktif atau stok habis.
                    </div>
                <?php else: ?>
                    <?php foreach ($products as$product): ?>
                        <div onclick="addToCart(<?= $product->id ?>, '<?= addslashes($product->name) ?>', <?= $product->price ?>, <?= $product->stock ?>)"
                             class="group bg-white rounded-2xl border border-slate-200 p-3 hover:border-blue-500 hover:shadow-md transition cursor-pointer flex flex-col justify-between">
                            
                            <div class="relative w-full h-32 bg-slate-100 rounded-xl overflow-hidden mb-3 flex items-center justify-center">
                                <?php if ($product->image): ?>
                                    <img src="<?= asset('storage/' . $product->image) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <?php else: ?>
                                    <i class="fa-regular fa-image text-3xl text-slate-300"></i>
                                <?php endif; ?>
                                <span class="absolute top-2 right-2 bg-slate-900/70 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    Stok: <?= $product->stock ?>
                                </span>
                            </div>

                            <div>
                                <h4 class="font-bold text-slate-800 text-sm truncate"><?= e($product->name) ?></h4>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xs text-slate-400">Harga</span>
                                    <span class="font-extrabold text-blue-600 text-sm">Rp <?= number_format($product->price, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Keranjang Belanja & Checkout -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between h-fit sticky top-4">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping text-blue-600"></i>
                    <h3 class="font-bold text-slate-800">Keranjang Belanja</h3>
                </div>
                <button type="button" onclick="clearCart()" class="text-xs font-semibold text-rose-500 hover:underline">Reset</button>
            </div>

            <!-- List Item Keranjang -->
            <div id="cartItems" class="divide-y divide-slate-100 max-h-80 overflow-y-auto py-2">
                <p id="emptyCartText" class="text-center py-8 text-xs text-slate-400">Keranjang masih kosong</p>
            </div>
        </div>

        <!-- Form Pembayaran -->
        <form action="<?= route('pos.store') ?>" method="POST" onsubmit="return validateCheckout()" class="pt-4 border-t border-slate-100 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="cart" id="cartInput">

            <div class="flex justify-between items-baseline">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tagihan:</span>
                <span id="totalText" class="text-2xl font-black text-blue-600">Rp 0</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jumlah Pembayaran (Rp)</label>
                <input type="number" name="pay_amount" id="payAmountInput" min="0" placeholder="0" required
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/20 text-sm">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Process Payment</span>
            </button>
        </form>
    </div>

</div>

<script>
    let cart = [];

    function addToCart(id, name, price, stock) {
        let item = cart.find(p => p.id === id);

        if (item) {
            if (item.qty < stock) {
                item.qty++;
            } else {
                alert('Jumlah melebihi stok yang tersedia (' + stock + ')!');
                return;
            }
        } else {
            if (stock < 1) {
                alert('Stok produk ini habis!');
                return;
            }
            cart.push({ id: id, name: name, price: price, stock: stock, qty: 1 });
        }

        renderCart();
    }

    function updateQty(id, change) {
        let item = cart.find(p => p.id === id);
        if (!item) return;

        item.qty += change;

        if (item.qty > item.stock) {
            item.qty = item.stock;
            alert('Jumlah mencapai batas stok maksimum!');
        }

        if (item.qty <= 0) {
            removeItem(id);
            return;
        }

        renderCart();
    }

    function removeItem(id) {
        cart = cart.filter(p => p.id !== id);
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function renderCart() {
        const cartContainer = document.getElementById('cartItems');
        const cartInput = document.getElementById('cartInput');
        const totalText = document.getElementById('totalText');

        if (cart.length === 0) {
            cartContainer.innerHTML = '<p class="text-center py-8 text-xs text-slate-400">Keranjang masih kosong</p>';
            totalText.innerText = 'Rp 0';
            cartInput.value = '';
            return;
        }

        let html = '';
        let total = 0;

        for (let i = 0; i < cart.length; i++) {
            const item = cart[i];
            const subtotal = item.price * item.qty;
            total += subtotal;

            html += `
                <div class="py-3 flex items-center justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <h5 class="text-xs font-bold text-slate-800 truncate">${item.name}</h5>
                        <p class="text-[11px] text-slate-400 mt-0.5">Rp ${item.price.toLocaleString('id-ID')} / item</p>
                        
                        <div class="flex items-center gap-2 mt-2">
                            <button type="button" onclick="updateQty(${item.id}, -1)" 
                                    class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold transition">
                                -
                            </button>
                            <span class="text-xs font-bold text-slate-800 w-6 text-center">${item.qty}</span>
                            <button type="button" onclick="updateQty(${item.id}, 1)" 
                                    class="w-6 h-6 rounded-lg bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-600 flex items-center justify-center text-xs font-bold transition">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="text-right shrink-0">
                        <span class="block text-xs font-extrabold text-slate-800 mb-1">Rp ${subtotal.toLocaleString('id-ID')}</span>
                        <button type="button" onclick="removeItem(${item.id})" class="text-[11px] text-rose-500 hover:text-rose-700">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            `;
        }

        cartContainer.innerHTML = html;
        totalText.innerText = 'Rp ' + total.toLocaleString('id-ID');
        cartInput.value = JSON.stringify(cart);
    }

    function validateCheckout() {
        if (cart.length === 0) {
            alert('Keranjang belanja masih kosong!');
            return false;
        }
        return true;
    }
</script>
@endsection