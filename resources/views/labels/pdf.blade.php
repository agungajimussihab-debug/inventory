<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Labels</title>
    <style>
        /* A4 3x8 label sheet layout
           Page: A4 (210 x 297 mm)
           Margins: 6mm top/bottom, 5mm left/right
           Grid: 3 columns x 8 rows
           Gutters: 3mm between columns and rows
           Label size approx: 64.67mm x 33.00mm
        */
        @page { size: A4; margin: 6mm 5mm; }
        html, body { width: 210mm; height: 297mm; margin: 0; padding: 0; font-family: DejaVu Sans, Helvetica, Arial, sans-serif; }

        .sheet { width: 100%; }

        .label {
            box-sizing: border-box;
            display: inline-block;
            width: 64.67mm;
            height: 33mm;
            margin-right: 3mm;
            margin-bottom: 3mm;
            padding: 4mm 3mm;
            vertical-align: top;
            border: 0.5pt solid #ddd; /* thin border to help alignment when test-printing */
            overflow: hidden;
        }

        /* remove right margin on every 3rd label to fit exactly */
        .label:nth-child(3n) { margin-right: 0; }

        .label .name { font-weight: bold; font-size: 11pt; margin-bottom: 2mm; }
        .label .sku { font-size: 9pt; margin-bottom: 2mm; font-family: monospace; }
        .label .barcode { margin-bottom: 2mm; }
        .label .qrcode { float: right; }

        /* Force page-break after 24 labels (3x8) */
        .label:nth-child(24n) { page-break-after: always; }

        /* small helper to center barcode */
        .barcode img { max-height: 16mm; display: block; }
        .qrcode img { width: 18mm; height: 18mm; }
    </style>
</head>
<body>
    @foreach($products as $product)
        <div class="label">
            <div class="name">{{ $product->name }}</div>
            <div class="sku">SKU: {{ $product->sku }}</div>
            <div class="barcode">
                @if(!empty($barcodeImages[$product->id] ?? null))
                    <img src="{{ $barcodeImages[$product->id] }}" alt="barcode" style="max-height:48px;" />
                @else
                    <div style="font-family: monospace; font-size:14px;">{{ $product->sku }}</div>
                @endif
            </div>
            <div class="qrcode">
                {{-- Use Google Chart API as fallback QR generator (requires remote image fetch) --}}
                <img src="https://chart.googleapis.com/chart?cht=qr&chs=120x120&chl={{ urlencode(route('products.show', $product->id)) }}" alt="qr" />
            </div>
        </div>
    @endforeach
</body>
</html>
