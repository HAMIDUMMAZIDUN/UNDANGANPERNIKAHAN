<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan form profil pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil, foto, dan sandi pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Validasi semua input dari form
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // 2. Update nama dan email
        $user->fill($request->only('name', 'email'));

        // Jika email diubah, reset status verifikasi
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 3. Handle upload foto profil jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            // Simpan foto baru dan update path di database
            $user->photo = $request->file('photo')->store('profile-photos', 'public');
        }

        // 4. Update sandi jika sandi baru diisi
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->input('new_password'));
        }

        // 5. Simpan semua perubahan
        $user->save();

        return Redirect::back()->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
