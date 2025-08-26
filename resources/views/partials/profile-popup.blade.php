{{-- PERBAIKAN: Hapus 'hidden', tambahkan x-show, dan x-cloak --}}
<div id="profilePopup" 
     class="fixed inset-0 bg-slate-900/70 flex items-center justify-center z-50"
     x-show="profilePopupOpen"
     x-cloak>
 
    <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-lg w-full max-w-md p-6 relative">
        {{-- PERBAIKAN: Ubah onclick menjadi @click --}}
        <button @click="profilePopupOpen = false" class="absolute top-3 right-4 text-slate-400 hover:text-white text-2xl">×</button>
        
        <h2 class="text-xl font-bold text-amber-500 mb-6 text-center">Update User</h2>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-300 p-3 rounded-md mb-4 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-300">Foto Profil</label>
                <input type="file" name="photo" accept="image/*" class="mt-1 block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300">Nama</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md py-2 px-3 text-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300">Email</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md py-2 px-3 text-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300">Password Lama</label>
                <input type="password" name="current_password" class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md py-2 px-3 text-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300">Password Baru</label>
                <input type="password" name="new_password" placeholder="Kosongkan jika tidak ubah password" class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md py-2 px-3 text-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <button type="submit" class="w-full bg-amber-500 text-slate-900 font-semibold py-2 rounded-md hover:bg-amber-600 transition duration-200">Update</button>
        </form>
    </div>
</div>
