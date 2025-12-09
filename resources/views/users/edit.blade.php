@extends('layouts.app')

@section('title', 'Ubah User')

@section('content')
<div class="max-w-lg mx-auto">
    <form method="POST" action="{{ route('users.update', $user) }}" class="glass-card p-6 rounded-2xl">
        @csrf
        @method('PUT')
        <h2 class="text-xl font-bold mb-4 text-black-100">👤 Ubah User</h2>
        <div class="mb-3">
            <label class="block text-sm text-black-300">Nama</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="w-full border px-3 py-2 rounded bg-transparent text-black-100" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm text-black-300">Email</label>
            <input name="email" value="{{ old('email', $user->email) }}" type="email" class="w-full border px-3 py-2 rounded bg-transparent text-black-100" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm text-black-300">Role</label>
            <select name="role" class="w-full border px-3 py-2 rounded bg-transparent text-black-100">
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="cashier" {{ $user->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="gudang" {{ $user->role === 'gudang' ? 'selected' : '' }}>Gudang</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="block text-sm text-black-300">Password (isi untuk mengganti)</label>
            <input name="password" type="password" class="w-full border px-3 py-2 rounded bg-transparent text-slate-100">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('users.index') }}" class="block glass-menu-item">Batal</a>
            <button class="block glass-menu-item">Simpan</button>
        </div>
    </form>
</div>
@endsection
