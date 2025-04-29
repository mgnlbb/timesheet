<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Update Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-6 sm:px-10 lg:px-16">
            <div class="bg-white rounded-2xl shadow p-10">
                <form action="{{ route('profile.user_update') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                    @csrf
                    @method('PATCH')
                
                    {{-- Informasi Umum --}}
                    <div>

                        @if (session('success'))
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Umum</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="full_name" class="form-input w-full rounded-md border-gray-300 shadow-sm mr-1" value="{{ old('full_name', $profile->full_name) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <input type="text" name="department" class="form-input w-full rounded-md border-gray-300 shadow-sm" value="{{ old('department', $profile->department) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                                <input type="text" name="project" class="form-input w-full rounded-md border-gray-300 shadow-sm" value="{{ old('project', $profile->project) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <input type="text" name="role" class="form-input w-full rounded-md border-gray-300 shadow-sm" value="{{ old('role', $profile->role) }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <input type="text" name="location" class="form-input w-full rounded-md border-gray-300 shadow-sm" value="{{ old('location', $profile->location) }}">
                            </div>
                        </div>
                    </div>
                
                    {{-- Acknowledger --}}
                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold text-gray-900 mt-3">Acknowledger</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" name="acknowledger_name" class="form-input w-full rounded-md border-gray-300 shadow-sm" value="{{ old('acknowledger_name', $profile->acknowledger_name) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                <input type="text" name="acknowledger_position" class="form-input w-full rounded-md border-gray-300 shadow-sm" value="{{ old('acknowledger_position', $profile->acknowledger_position) }}">
                            </div>
                        </div>
                    </div>
                
                    {{-- Approver --}}
                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold text-gray-900 mt-3">Approver</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" name="approver_name" class="form-input w-full rounded-md border-gray-300 shadow-sm" value="{{ old('approver_name', $profile->approver_name) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                <input type="text" name="approver_position" class="form-input w-full rounded-md border-gray-300 shadow-sm" value="{{ old('approver_position', $profile->approver_position) }}">
                            </div>
                        </div>
                    </div>
                
                    {{-- Signature --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 mt-3">Signature</h3>
                        @if ($profile->signature_path)
                            <div>
                                <img src="{{ asset('storage/' . $profile->signature_path) }}" alt="Current Signature" class="h-24">
                            </div>
                        @endif
                        <div>
                            <input type="file" name="signature" class="form-input w-full rounded-md border-gray-300 shadow-sm">
                            <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah tanda tangan.</p>
                        </div>
                    </div>
                
                    {{-- Submit --}}
                    <div class="pt-4 border-t">
                        <x-primary-button>
                            {{ __('Update Profile') }}
                        </x-primary-button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>
