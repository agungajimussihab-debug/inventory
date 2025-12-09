@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="max-w-lg mx-auto">
    <form method="POST" action="{{ route('users.store') }}" class="glass-card p-6 rounded-2xl">
        @csrf
        <h2 class="text-xl font-bold mb-4 text-black-100">👤 Tambah User</h2>

        <div class="mb-3">
            <label class="block text-sm text-black-300">Nama</label>
            <input name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded bg-transparent text-slate-100" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm text-black-300">Email</label>
            <input name="email" value="{{ old('email') }}" type="email" class="w-full border px-3 py-2 rounded bg-transparent text-slate-100" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm text-black-300">Role</label>
            <select name="role" class="w-full border px-3 py-2 rounded bg-transparent text-black-100">
                <option value="admin">Admin</option>
                <option value="cashier">Cashier</option>
                <option value="gudang">Gudang</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="block text-sm text-black-300">Password (kosongkan untuk default 'password')</label>
            <input name="password" type="password" class="w-full border px-3 py-2 rounded bg-transparent text-slate-100">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('users.index') }}" class="block glass-menu-item text-black">Batal</a>
            <button class="block glass-menu-item text-black">Simpan</button>
        </div>
    </form>
</div>
@endsection
