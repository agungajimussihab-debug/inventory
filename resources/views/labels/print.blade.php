@extends('layouts.app')

@section('title', 'Cetak Label')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-bold">Cetak Label</h2>
    <div class="flex items-center gap-3">
        <label class="text-sm font-medium">Tipe Cetak:</label>
        <select id="labelType" class="border px-3 py-1 rounded">
            <option value="barcode">Barcode (CODE128)</option>
            <option value="qrcode">QR Code</option>
            <option value="both">Keduanya</option>
        </select>
        <button id="printAllBtn" onclick="window.print()" class="bg-blue-600 text-white px-3 py-1 rounded">Cetak Semua</button>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">
    @foreach($products as $product)
    <div class="p-4 bg-white rounded shadow">
        <div class="flex items-center justify-between">
            <div>
                <div class="font-semibold">{{ $product->name }}</div>
                <div class="text-sm">SKU: {{ $product->sku }}</div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('labels.show', $product->id) }}?print=1" target="_blank" class="px-3 py-1 bg-green-600 text-white rounded">Cetak</a>
            </div>
        </div>
        <div class="mt-2" id="render-{{ $product->id }}">
            <svg id="barcode-{{ $product->id }}" style="display:none"></svg>
            <div id="qrcode-{{ $product->id }}" style="display:none"></div>
        </div>
    </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const products = [
            @foreach($products as $product)
                { id: {{ $product->id }}, sku: '{{ $product->sku }}', url: '{{ route("products.show", $product->id) }}' },
            @endforeach
        ];

        function renderFor(product, type) {
            const barcodeEl = document.getElementById('barcode-' + product.id);
            const qrcodeEl = document.getElementById('qrcode-' + product.id);

            if (!barcodeEl || !qrcodeEl) return;

            qrcodeEl.innerHTML = '';

            if (type === 'barcode' || type === 'both') {
                barcodeEl.style.display = '';
                try {
                    JsBarcode('#barcode-' + product.id, product.sku, {format: 'CODE128', displayValue: true, fontSize: 12});
                } catch (e) { console.warn(e); }
            } else {
                barcodeEl.style.display = 'none';
            }

            if (type === 'qrcode' || type === 'both') {
                qrcodeEl.style.display = '';
                try {
                    new QRCode(qrcodeEl, { text: product.url, width: 96, height: 96 });
                } catch (e) { console.warn(e); }
            } else {
                qrcodeEl.style.display = 'none';
            }
        }

        const select = document.getElementById('labelType');
        function renderAll() {
            const type = select.value;
            products.forEach(p => renderFor(p, type));
        }

        select.addEventListener('change', renderAll);
        renderAll();
    });
</script>

@endsection
