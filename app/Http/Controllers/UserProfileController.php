<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function create()
    {
        return view('profile.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'department' => 'required',
            'project' => 'required',
            'role' => 'required',
            'location' => 'required',
            'acknowledger_name' => 'required',
            'acknowledger_position' => 'required',
            'approver_name' => 'required',
            'approver_position' => 'required',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($request->hasFile('signature')) {
            $user = Auth::user();
            $file = $request->file('signature');
            $filename = 'signature_' . $user->id . '.' . $file->getClientOriginalExtension();
    
            // Simpan ke public/signatures
            $file->move(public_path('signatures'), $filename);
    
            $data['signature_path'] = 'signatures/' . $filename;
        }
    
        $data['user_id'] = Auth::id();
        UserProfile::create($data);
    
        return redirect()->route('dashboard')->with('success', 'Profil berhasil disimpan.');
    }

    public function edit()
    {
        $profile = UserProfile::where('user_id', Auth::id())->firstOrFail();
        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'department' => 'required',
            'project' => 'required',
            'role' => 'required',
            'location' => 'required',
            'acknowledger_name' => 'required',
            'acknowledger_position' => 'required',
            'approver_name' => 'required',
            'approver_position' => 'required',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $profile = UserProfile::where('user_id', Auth::id())->firstOrFail();
    
        if ($request->hasFile('signature')) {
            // Hapus file lama jika ada
            $oldFile = public_path($profile->signature_path);
            if ($profile->signature_path && file_exists($oldFile)) {
                unlink($oldFile);
            }
    
            $user = Auth::user();
            $file = $request->file('signature');
            $filename = 'signature_' . $user->id . '.' . $file->getClientOriginalExtension();
    
            $file->move(public_path('signatures'), $filename);
            $data['signature_path'] = 'signatures/' . $filename;
        }
    
        $profile->update($data);
    
        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

}
