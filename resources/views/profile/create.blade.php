<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow-md mt-8">
        <h2 class="text-xl font-bold mb-4">Lengkapi Data Profil</h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 p-4 rounded">
                <ul class="list-disc pl-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Informasi Umum --}}
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="full_name"class="form-input w-full rounded-md border-gray-300 shadow-sm mr-1" required>
                    </div>
    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input type="text" name="department" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                        <input type="text" name="project" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
    
                    <div>
                        <label  class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <input type="text" name="role" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" class="form-input w-full rounded-md border-gray-300 shadow-sm"  required>
                    </div>
                </div>
            </div>

            {{-- Acknowledger --}}
            <div class="space-y-6">
                <h4 class="text-lg font-semibold text-gray-900 mt-3">Acknowledger</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="acknowledger_name" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <input type="text" name="acknowledger_position" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                </div>
            </div>
                            

            {{-- Approver --}}
            <div class="space-y-6">
                <h4 class="text-lg font-semibold text-gray-900 mt-3">Approver</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="approver_name" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <input type="text" name="approver_position" class="form-input w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                </div>
            </div>


            {{-- Signature --}}
            <div class="space-y-4 mt-6">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Signature (Upload Image)</label>
                    <input type="file" name="signature" accept="image/*" class="form-input w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-500">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
