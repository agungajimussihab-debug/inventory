@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .glass-card {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.15);
        box-shadow: 0 10px 30px rgba(0,255,255,0.08), inset 0 1px 0 rgba(255,255,255,0.03);
        backdrop-filter: blur(14px) saturate(140%);
        -webkit-backdrop-filter: blur(14px) saturate(140%);
        border-radius: 1rem;
        position: relative;
        overflow: hidden;
    }
    .glass-card::before{
        content: '';
        position: absolute;
        top: -30%; left: -20%;
        width: 160%; height: 60%;
        background: linear-gradient(90deg, rgba(224,255,255,0.18), rgba(0,255,255,0.04));
        transform: rotate(-25deg);
        filter: blur(16px);
        opacity: 0.9;
        pointer-events:none;
    }
    .glass-glow {
        box-shadow: 0 8px 30px rgba(0,255,255,0.14), 0 2px 8px rgba(0,0,0,0.08) !important;
    }
    .glass-pill { background: rgba(255,255,255,0.06); border-radius: 9999px; padding: .25rem .6rem; }
    .glass-tag { background: rgba(0,255,255,0.08); color: #00343a; padding: .15rem .5rem; border-radius: .5rem; font-size: .75rem; }
</style>
<div class="min-h-screen bg-black text-black">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6 rounded-2xl glass-glow">
        <h3 class="text-black text-sm font-semibold">Total Produk</h3>
        <p class="text-3xl font-bold text-black">{{ $totalProducts }}</p>
    </div>
    <div class="glass-card p-6 rounded-2xl glass-glow">
        <h3 class="text-black text-sm font-semibold">Total Kategori</h3>
        <p class="text-3xl font-bold text-black">{{ $totalCategories }}</p>
    </div>
    <div class="glass-card p-6 rounded-2xl glass-glow">
        <h3 class="text-black text-sm font-semibold">Barang Stok Rendah</h3>
        <p class="text-3xl font-bold text-black">{{ $lowStockProducts->count() }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="glass-card p-6 rounded-2xl">
        <h2 class="text-xl font-bold mb-4 text-black ">⚠️ Produk Stok Rendah</h2>
        @if ($lowStockProducts->count() > 0)
            <table class="w-full text-sm glass-table">
                <thead class="bg-transparent">
                    <tr>
                        <th class="px-4 py-2 text-left text-black">Produk</th>
                        <th class="px-4 py-2 text-left text-black">Stok</th>
                        <th class="px-4 py-2 text-left text-black">Stok Min</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lowStockProducts as $product)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $product->name }}</td>
                            <td class="px-4 py-2">{{ $product->quantity }}</td>
                            <td class="px-4 py-2">{{ $product->minimum_stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-black">Semua produk memiliki stok yang cukup!</p>
        @endif
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h2 class="text-xl font-bold mb-4 text-black">📊 Statistik Singkat</h2>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-black">Rata-rata Harga Produk:</span>
                <span class="font-bold text-black">Rp {{ number_format(\App\Models\Product::avg('price') ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-black">Total Nilai Produk:</span>
                <span class="font-bold text-black">Rp {{ number_format(\App\Models\Product::sum(\DB::raw('price * quantity')) ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-black">Produk Habis:</span>
                <span class="font-bold text-black">{{ \App\Models\Product::where('quantity', 0)->count() }}</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 mt-8">
    <div class="glass-card p-6 rounded-2xl">
        <h2 class="text-xl font-bold mb-4 text-black">⬇️ Stok Masuk</h2>
        @if(isset($recentIn) && $recentIn->count() > 0)
            <table class="w-full text-sm glass-table">
                <thead class="bg-transparent">
                    <tr>
                        <th class="px-4 py-2 text-left text-black">Invoice</th>
                        <th class="px-4 py-2 text-left text-black">Produk</th>
                        <th class="px-4 py-2 text-left text-black">Jumlah</th>
                        <th class="px-4 py-2 text-left text-black">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentIn as $m)
                        <tr class="border-t border-black/10">
                            <td class="px-4 py-2">{{ $m->reference ?? '-' }}</td>
                            <td class="px-4 py-2">{{ optional($m->product)->name ?? '—' }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-block bg-green-900/40 text-black px-2 py-1 rounded-full text-xs">{{ $m->quantity }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $m->created_at ? $m->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-black">Belum ada stok masuk terbaru.</p>
        @endif
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h2 class="text-xl font-bold mb-4 text-black">⬆️ Stok Keluar</h2>
        @if(isset($recentOut) && $recentOut->count() > 0)
            <table class="w-full text-sm glass-table">
                <thead class="bg-transparent">
                    <tr>
                        <th class="px-4 py-2 text-left text-black">Invoice</th>
                        <th class="px-4 py-2 text-left text-black">Produk</th>
                        <th class="px-4 py-2 text-left text-black">Jumlah</th>
                        <th class="px-4 py-2 text-left text-black">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOut as $m)
                            <tr class="border-t border-black/10">
                            <td class="px-4 py-2">{{ $m->reference ?? '-' }}</td>
                            <td class="px-4 py-2">{{ optional($m->product)->name ?? '—' }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-block bg-red-900/40 text-black px-2 py-1 rounded-full text-xs">{{ $m->quantity }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $m->created_at ? $m->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-black">Belum ada stok keluar terbaru.</p>
        @endif
        
    </div>
        </div>
    </div>
</div>
@endsection
