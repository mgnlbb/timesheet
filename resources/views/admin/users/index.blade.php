@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold p-8">👥 Manajemen User</h1>
<div x-data="userModal()" class="max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow p-6 dark:text-gray-100">

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-400 text-green-900 dark:bg-green-600 dark:text-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel User --}}
    <div class="overflow-x-auto rounded-lg">
        <table class="min-w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Username</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                <tr class="border-t border-gray-200 dark:border-gray-600 rounded-lg">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $user->username }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                    <td class="px-4 py-2">
                        @if (!$user->is_super_admin)
                            <button
                                class="text-blue-600 dark:text-blue-400 hover:underline"
                                @click="open = true;
                                        userId = {{ $user->id }};
                                        username = '{{ $user->username }}';
                                        email = '{{ $user->email }}';
                                        role = '{{ $user->role }}';
                                        formAction = '/admin/users/' + {{ $user->id }};"
                            >
                                Edit
                            </button>
                        @else
                            <span class="text-gray-400 dark:text-gray-500 italic">Super Admin</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal pakai Alpine --}}
    <div
        x-show="open" x-transition x-cloak 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        
        <div @click.outside="open = false" class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl p-6 shadow-xl relative text-gray-900 dark:text-gray-100">
            <h3 class="text-xl font-bold mb-4">Edit User</h3>
            <form :action="formAction" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm mb-1">Username</label>
                    <input type="text" name="username" x-model="username"
                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" name="email" x-model="email"
                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Role</label>
                    <select name="role" x-model="role"
                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    
                    <button type="button" @click="open = false"
                    class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:underline">
                    Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function userModal() {
        return {
            open: false,
            userId: null,
            username: '',
            email: '',
            role: '',
            formAction: '',
        }
    }
</script>
@endpush
