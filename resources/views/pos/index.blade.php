<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Daftar Produk -->
        <div class="md:col-span-2 bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Menu Kasir</h1>
                <div class="space-x-2">
                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">Kelola Produk</a>
                    <a href="{{ route('categories.index') }}" class="text-blue-600 hover:underline">Kelola Kategori</a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @if(count($products) > 0)
                    @for($i = 0; $i < count($products);$i++)
                        <div onclick="addToCart({{ $products[$i]->id }}, '{{ $products[$i]->name }}', {{ $products[$i]->price }}, {{ $products[$i]->stock }})" 
                             class="border p-4 rounded-lg cursor-pointer hover:border-blue-500 hover:shadow-md transition">
                            <div class="font-bold text-lg">{{ $products[$i]->name }}</div>
                            <div class="text-sm text-gray-500">Stok: {{ $products[$i]->stock }}</div>
                            <div class="text-blue-600 font-semibold mt-2">Rp {{ number_format($products[$i]->price, 0, ',', '.') }}</div>
                        </div>
                    @endfor
                @else
                    <div class="col-span-3 text-center py-8 text-gray-500">
                        Belum ada produk siap jual. Tambahkan produk dulu di menu Kelola Produk.
                    </div>
                @endif
            </div>
        </div>

        <!-- Keranjang & Pembayaran -->
        <div class="bg-white p-6 rounded-lg shadow flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold mb-4">Keranjang Belanja</h2>
                <div id="cart-items" class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                    <p class="text-gray-400 text-center">Keranjang masih kosong</p>
                </div>
            </div>

            <div class="border-t pt-4">
                <div class="flex justify-between text-lg font-bold mb-4">
                    <span>Total:</span>
                    <span id="total-price">Rp 0</span>
                </div>

                <form action="{{ route('pos.store') }}" method="POST" onsubmit="prepareSubmit(event)">
                    @csrf
                    <input type="hidden" name="cart" id="cart-input">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1">Jumlah Bayar (Rp)</label>
                        <input type="number" id="pay-amount" name="pay_amount" required placeholder="0" 
                               class="border rounded w-full p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700">
                        Bayar Sekarang
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
                    alert('Stok tidak mencukupi!');
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

        function renderCart() {
            let container = document.getElementById('cart-items');
            let totalEl = document.getElementById('total-price');
            
            if (cart.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-center">Keranjang masih kosong</p>';
                totalEl.innerText = 'Rp 0';
                return;
            }

            let html = '';
            let total = 0;

            cart.forEach(item => {
                let subtotal = item.price * item.qty;
                total += subtotal;
                html += `
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <div class="font-semibold">${item.name}</div>
                            <div class="text-xs text-gray-500">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm">Rp ${subtotal.toLocaleString('id-ID')}</span>
                            <button type="button" onclick="removeFromCart(${item.id})" class="text-red-500 font-bold px-1">✕</button>
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
                alert('Keranjang masih kosong!');
                return;
            }
            document.getElementById('cart-input').value = JSON.stringify(cart);
        }
    </script>
</body>
</html>