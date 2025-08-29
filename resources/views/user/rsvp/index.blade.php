@extends('user.layouts.app')

@section('page_title', 'Ucapan & Doa')

@section('content')
<div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
  <div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Ucapan & Doa</h1>
        <p class="text-sm text-slate-500 mt-1">Lihat semua ucapan dan konfirmasi kehadiran dari tamu Anda.</p>
    </div>

    <!-- Start: Bagian Statistik -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
      <div class="bg-slate-800 text-white p-4 rounded-lg shadow-lg text-center">
        <h2 class="text-3xl font-bold">{{ $stats['komentar'] }}</h2>
        <p class="text-slate-300 text-sm uppercase tracking-wider">Komentar</p>
      </div>
      <div class="bg-green-500/50 p-4 rounded-lg shadow-lg text-center">
        <h2 class="text-3xl font-bold text-green-900">{{ $stats['hadir'] }}</h2>
        <p class="text-green-700 text-sm uppercase tracking-wider">Hadir</p>
      </div>
      <div class="bg-red-500/50 p-4 rounded-lg shadow-lg text-center">
        <h2 class="text-3xl font-bold text-red-900">{{ $stats['tidak_hadir'] }}</h2>
        <p class="text-red-700 text-sm uppercase tracking-wider">Tidak Hadir</p>
      </div>
      <div class="bg-amber-500/50 p-4 rounded-lg shadow-lg text-center">
        <h2 class="text-3xl font-bold text-amber-900">{{ $stats['ragu'] }}</h2>
        <p class="text-amber-700 text-sm uppercase tracking-wider">Masih Ragu</p>
      </div>
    </div>
    <!-- End: Bagian Statistik -->

    <!-- Start: Filter dan Aksi -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
      <form id="filterForm" method="GET" action="{{ route('rsvp.index') }}" class="w-full sm:w-auto">
        <select name="filter" onchange="this.form.submit()" class="w-full appearance-none bg-white border border-slate-300 text-slate-700 py-2 pl-4 pr-8 rounded-lg focus:outline-none focus:bg-white focus:border-amber-500">
          <option value="semua" @if(request('filter', 'semua') == 'semua') selected @endif>Semua Ucapan</option>
          <option value="hadir" @if(request('filter') == 'hadir') selected @endif>Hadir</option>
          <option value="tidak_hadir" @if(request('filter') == 'tidak_hadir') selected @endif>Tidak Hadir</option>
          <option value="ragu" @if(request('filter') == 'ragu') selected @endif>Masih Ragu</option>
        </select>
      </form>
      <a href="{{ route('rsvp.export', ['filter' => request('filter', 'semua')]) }}" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-slate-700 text-white py-2 px-6 rounded-lg shadow-md hover:bg-slate-800 transition duration-300 text-sm font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
        <span>Export Excel</span>
      </a>
    </div>
    <!-- End: Filter dan Aksi -->

    <!-- Start: Daftar Komentar -->
    <div class="space-y-4">
        @php
            // Definisikan style untuk setiap status
            $statusStyles = [
                'hadir' => ['label' => 'Hadir', 'class' => 'bg-green-100 text-green-800'],
                'tidak_hadir' => ['label' => 'Tidak Hadir', 'class' => 'bg-red-100 text-red-800'],
                'ragu' => ['label' => 'Masih Ragu', 'class' => 'bg-yellow-100 text-yellow-800'],
            ];
            $avatarColors = ['bg-amber-500', 'bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-indigo-500', 'bg-purple-500'];
        @endphp

        @forelse ($rsvps as $rsvp)
            <div class="bg-white p-4 rounded-lg shadow-sm flex items-start gap-4">
                <div class="flex-shrink-0 h-10 w-10 rounded-full {{ $avatarColors[$loop->index % count($avatarColors)] }} flex items-center justify-center text-white font-bold text-xl">
                    {{ strtoupper(substr($rsvp->name, 0, 1)) }}
                </div>
                <div class="flex-grow">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-semibold text-slate-800">{{ $rsvp->name }}</span>
                            @if(isset($statusStyles[$rsvp->status]))
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full ml-2 {{ $statusStyles[$rsvp->status]['class'] }}">
                                    {{ $statusStyles[$rsvp->status]['label'] }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 text-slate-400">
                            <span class="text-xs">{{ $rsvp->created_at->isoFormat('D MMM YYYY, HH:mm') }}</span>
                            <form action="{{ route('rsvp.destroy', $rsvp) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="text-slate-600 mt-1 text-sm">{{ $rsvp->message }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-lg shadow-sm">
                <p class="text-slate-500 text-lg">Tidak ada ucapan untuk ditampilkan.</p>
            </div>
        @endforelse
    </div>
    <!-- End: Daftar Komentar -->

    <!-- Pagination -->
    @if ($rsvps->hasPages())
        <div class="mt-8">
            {{ $rsvps->appends(request()->query())->links() }}
        </div>
    @endif
  </div>
</div>
@endsection

@push('scripts')
{{-- Pastikan Anda sudah punya SweetAlert di layouts/app.blade.php --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Ucapan yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endpush
