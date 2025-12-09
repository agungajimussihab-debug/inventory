@extends('layouts.app')

@section('title', 'Label - ' . ($product->name ?? 'Product'))

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-xl font-bold">Label Produk</h2>
            <div class="text-sm text-gray-600">{{ $product->name }}</div>
        </div>
        <div>
            <button onclick="window.print()" class="bg-blue-600 text-white px-3 py-1 rounded">Print</button>
        </div>
    </div>

    <div class="flex gap-8 items-center">
        <div>
            <label class="text-sm font-medium">Tipe Cetak:</label>
            <select id="labelTypeSingle" class="border px-2 py-1 rounded mb-3">
                <option value="barcode">Barcode (CODE128)</option>
                <option value="qrcode">QR Code</option>
                <option value="both">Keduanya</option>
            </select>

            <div id="render-area">
                <svg id="barcode"></svg>
                <div id="qrcode" class="mt-2"></div>
            </div>

            <div class="text-sm mt-2">SKU: {{ $product->sku }}</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const product = { id: {{ $product->id }}, sku: '{{ $product->sku }}', url: '{{ route("products.show", $product->id) }}' };
        const barcodeEl = document.getElementById('barcode');
        const qrcodeEl = document.getElementById('qrcode');
        const select = document.getElementById('labelTypeSingle');

        function render(type) {
            qrcodeEl.innerHTML = '';

            if (type === 'barcode' || type === 'both') {
                barcodeEl.style.display = '';
                try { JsBarcode('#barcode', product.sku, {format: 'CODE128', displayValue: true, fontSize: 14}); } catch (e) { console.warn(e); }
            } else {
                barcodeEl.style.display = 'none';
            }

            if (type === 'qrcode' || type === 'both') {
                qrcodeEl.style.display = '';
                try { new QRCode(qrcodeEl, { text: product.url, width: 128, height: 128 }); } catch (e) { console.warn(e); }
            } else {
                qrcodeEl.style.display = 'none';
            }
        }

        select.addEventListener('change', function(){ render(select.value); });
        render(select.value);

        // Auto-print when opened with ?print=1
        try {
            const params = new URLSearchParams(window.location.search);
            if (params.get('print') === '1') {
                // slight delay to ensure barcode/qr rendered
                setTimeout(() => { window.print(); }, 300);
            }
        } catch (e) { /* ignore */ }
    });
</script>

@endsection
