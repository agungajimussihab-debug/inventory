@extends('layouts.app')

@section('title', 'Cek Stok')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Cek Stok</h2>

    <table class="w-full text-sm glass-table">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">SKU</th>
                <th class="px-4 py-2 text-left">Produk</th>
                <th class="px-4 py-2 text-left">Kategori</th>
                <th class="px-4 py-2 text-left">Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2 font-mono">{{ $p->sku }}</td>
                    <td class="px-4 py-2">{{ $p->name }}</td>
                    <td class="px-4 py-2">{{ $p->category->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $p->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
