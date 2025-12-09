@extends('layouts.app')

@section('title', 'Berhasil')

@section('content')
<div class="max-w-2xl mx-auto text-center">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Berhasil</h2>
        <p class="mb-4">Stok berhasil ditambahkan.</p>
        <a href="{{ route('instock.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah Lagi</a>
        <a href="{{ route('dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 ml-2">Kembali</a>
    </div>
</div>
@endsection
