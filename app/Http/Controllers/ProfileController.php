<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Import the Storage facade
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

        // Logika untuk update foto menggunakan Storage facade
        if ($request->hasFile('photo')) {
            // 1. Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            // 2. Tentukan nama file baru berdasarkan ID user
            $filename = 'user-' . $user->id . '.' . $request->file('photo')->getClientOriginalExtension();
            
            // 3. Simpan file baru ke 'storage/app/public/profile-photos'
            // dan simpan path-nya ke variabel $path
            $path = $request->file('photo')->storeAs('profile-photos', $filename, 'public');

            // 4. Simpan path baru ke database
            $user->photo = $path;
        }

        // Simpan semua perubahan (nama, email, password, foto)
        $user->save();

        return redirect()->back()->with('status', 'Profil berhasil diperbarui');
    }
}
