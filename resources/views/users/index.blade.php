@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="glass-card p-6 rounded-2xl">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-black-100">👥 Kelola User</h2>
        <a href="{{ route('users.create') }}" class="block glass-menu-item text-black">Tambah User</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm glass-table">
            <thead class="bg-transparent">
                <tr>
                    <th class="px-4 py-2 text-left text-black-300">Nama</th>
                    <th class="px-4 py-2 text-left text-black-300">Email</th>
                    <th class="px-4 py-2 text-left text-black-300">Role</th>
                    <th class="px-4 py-2 text-left text-black-300">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-2 text-black-100">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-black-100">{{ $user->email }}</td>
                        <td class="px-4 py-2 text-black-100">{{ ucfirst($user->role) }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('users.edit', $user) }}" class="glass-badge bg-green-400/50 text-black-300">Ubah</a>

                            <form action="{{ route('users.resetPassword', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Reset password ke default untuk user ini?');">
                                @csrf
                                <button type="submit" class="tglass-badge bg-yellow-400/50 text-black-300">Reset Password</button>
                            </form>

                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="glass-badge bg-red-400/50 text-black-300">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-slate-300">
        {{ $users->links() }}
    </div>
</div>
@endsection
