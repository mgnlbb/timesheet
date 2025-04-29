<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('profile')->get();
        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        // Cegah perubahan pada Super Admin
        if ($user->is_super_admin) {
            return redirect()->back()->with('error', 'Data Super Admin tidak dapat diubah.');
        }
    
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,admin',
        ]);
    
        // Cegah mengubah admin terakhir menjadi user
        if (
            $user->role === 'admin' &&
            $request->role === 'user' &&
            User::where('role', 'admin')->where('id', '!=', $user->id)->count() === 0
        ) {
            return redirect()->back()->with('error', 'Minimal harus ada satu admin tersisa.');
        }
    
        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
        ]);
    
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }
    
}
