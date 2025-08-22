<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Logika untuk update password
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Logika untuk update foto (hanya berjalan jika ada file baru)
        if ($request->hasFile('photo')) {
            $path = public_path('poto-profile');
            $filename = 'user-' . $user->id . '.' . $request->file('photo')->getClientOriginalExtension();

            // Buat direktori jika belum ada
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            // Hapus foto lama jika ada (pastikan path foto lama benar)
            if ($user->photo && File::exists(public_path($user->photo))) {
                File::delete(public_path($user->photo));
            }

            // Simpan foto baru
            $request->file('photo')->move($path, $filename);

            // Simpan path baru ke user
            $user->photo = 'poto-profile/' . $filename;
        }

        // Simpan semua perubahan (nama, email, password, foto)
        $user->save();

        return redirect()->back()->with('status', 'Profil berhasil diperbarui');
    }
}
