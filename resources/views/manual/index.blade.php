@extends('layouts.app')

@section('page_title', 'Check-in Manual')

@section('content')
<div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8 flex items-center justify-center">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Check-in Tamu Manual</h1>
            <p class="text-sm text-slate-500 mt-1">Masukkan data tamu yang hadir tanpa undangan.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <form action="{{ route('manual.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    
                    {{-- Nama Tamu --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Nama Tamu</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('name') border-red-500 @enderror" required>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Kategori/Alamat --}}
                    <div>
                        <label for="affiliation" class="block text-sm font-medium text-slate-700">Kategori / Keterangan</label>
                        <div class="mt-1">
                            <input type="text" name="affiliation" id="affiliation" value="{{ old('affiliation') }}" placeholder="Contoh: Rekan Kerja Ayah (Opsional)" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('affiliation') border-red-500 @enderror">
                        </div>
                        @error('affiliation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jumlah Tamu --}}
                    <div>
                        <label for="guest_count" class="block text-sm font-medium text-slate-700">Jumlah Tamu Hadir</label>
                        <div class="mt-1">
                            <input type="number" name="guest_count" id="guest_count" value="{{ old('guest_count', 1) }}" min="1" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-slate-300 rounded-md @error('guest_count') border-red-500 @enderror" required>
                        </div>
                         @error('guest_count')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                       <button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-slate-900 bg-amber-500 hover:bg-amber-600">
                           Simpan Kehadiran
                       </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Menampilkan notifikasi sukses
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
    @endif

    // Menampilkan notifikasi error
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            // PERBAIKAN: Menghapus tanda kutip yang tidak perlu
            text: `{!! session('error') !!}`,
            confirmButtonColor: '#d33'
        });
    @endif
});
</script>
@endpush
