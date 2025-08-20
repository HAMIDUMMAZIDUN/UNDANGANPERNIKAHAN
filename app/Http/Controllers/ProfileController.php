<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
   
public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => ['required'],
        'email' => ['required', 'email'],
        'current_password' => ['nullable'],
        'new_password' => ['nullable', 'min:6'],
        'photo' => ['nullable', 'image', 'max:5000'],
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    // Ganti password jika diisi
    if ($request->filled('new_password')) {
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak cocok']);
        }
        $user->password = Hash::make($request->new_password);
    }

    // Ganti foto jika diupload
    if ($request->hasFile('photo')) {
        $path = public_path('poto-profile');
        $filename = 'user-' . $user->id . '.jpg';

        // Hapus foto lama jika ada
        if (File::exists($path . '/' . $filename)) {
            File::delete($path . '/' . $filename);
        }

        // Simpan foto baru
        $request->file('photo')->move($path, $filename);
    }
    if (!File::exists($path)) {
    File::makeDirectory($path, 0755, true);
}
logger('Upload berhasil: ' . $filename);
    $user->photo = 'poto-profile/' . $filename;
    $user->save();

    return redirect()->back()->with('status', 'Profil berhasil diperbarui');
}
}
