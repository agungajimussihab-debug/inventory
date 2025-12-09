@extends('layouts.app')

@section('title', 'Daftar - Sistem Inventory')

@section('content')

<style>
    /* Keep same styles as login (copied for isolation) */
    :root { --c1: #6366f1; --c5: #8b5cf6; }
    body.auth-page { background: linear-gradient(135deg,#f8fafc 0%,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%,#f8fafc 100%); min-height:100vh; }
    .glass-card{background:linear-gradient(135deg,rgba(255,255,255,.92),rgba(255,255,255,.96));border-radius:24px;backdrop-filter:blur(25px);box-shadow:0 25px 50px rgba(0,0,0,.08);} 
    .glass-input{background:rgba(255,255,255,.95);border:1.5px solid rgba(226,232,240,.8);border-radius:14px;padding:1rem 1.25rem}
    .glass-btn{background:linear-gradient(135deg,var(--c1),var(--c5));color:white;padding:1rem 1.5rem;border-radius:14px}
    .password-toggle{position:absolute;right:1rem;top:50%;transform:translateY(-50%);background:transparent;border:none}
</style>

<div class="min-h-screen flex items-center justify-center p-6 auth-page">
    <div class="glass-card p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl shadow-lg mx-auto mb-4">📦</div>
            <h1 class="text-2xl font-bold">Buat Akun</h1>
            <p class="text-gray-600">Daftarkan akun baru untuk mengakses sistem inventory</p>
        </div>

        @if ($errors->any())
            <div class="glass-alert px-4 py-3 rounded-lg mb-6">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full glass-input">
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full glass-input">
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input id="password" name="password" type="password" required class="w-full glass-input pr-12">
                    <button type="button" onclick="togglePasswordReg()" class="password-toggle" aria-label="Toggle password visibility"><span id="toggleIconReg">👁️</span></button>
                </div>
                @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full glass-input">
            </div>

            <button type="submit" class="w-full glass-btn">Daftar</button>
        </form>

        <div class="text-center mt-4 text-sm text-gray-600">Sudah punya akun? <a href="{{ route('login') }}" class="font-medium text-indigo-600">Masuk</a></div>
    </div>
</div>

<script>
    function togglePasswordReg(){ const p = document.getElementById('password'); const i = document.getElementById('toggleIconReg'); if(p.type==='password'){p.type='text'; i.textContent='👁️‍🗨️'}else{p.type='password'; i.textContent='👁️'} }
    document.addEventListener('DOMContentLoaded', function(){ const el = document.getElementById('name'); if(el) el.focus(); });
</script>

@endsection
