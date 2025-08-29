@extends('user.layouts.app')

@section('page_title', 'Penukaran Souvenir')

@section('content')
<div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
  <div class="max-w-4xl mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Penukaran Souvenir</h1>
        <p class="text-sm text-slate-500 mt-1">Lacak data tamu yang telah menukarkan souvenir.</p>
    </div>

    <!-- Start: Bagian Statistik -->
    <div class="bg-slate-800 text-white p-6 rounded-lg shadow-md text-center mb-8">
      <h2 class="text-5xl font-bold">{{ $totalSouvenirsTaken }}</h2>
      <p class="text-slate-300 text-lg uppercase tracking-wider mt-1">Souvenir Diambil</p>
    </div>
    <!-- End: Bagian Statistik -->

    <!-- Start: Konten Utama -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6">
        <!-- Tombol Aksi & Pencarian -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('souvenir.export', request()->query()) }}" class="flex items-center gap-2 bg-slate-700 text-white py-2 px-4 rounded-lg hover:bg-slate-800 transition duration-300 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    <span>Export</span>
                </a>
                <a href="{{ route('souvenir.scan') }}" class="flex items-center gap-2 bg-amber-500 text-slate-900 py-2 px-4 rounded-lg hover:bg-amber-600 transition duration-300 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    <span>Scan QR</span>
                </a>
            </div>
            <form action="{{ route('souvenir.index') }}" method="GET" class="w-full sm:w-auto">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tamu..." class="w-full sm:w-64 pl-4 pr-10 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Area Data -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Tamu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Waktu Ambil</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($guests as $guest)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $loop->iteration + ($guests->currentPage() - 1) * $guests->perPage() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $guest->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($guest->souvenir_taken_at)->isoFormat('D MMM YYYY, HH:mm') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-16 px-6">
                                <p class="text-slate-500 text-lg">Belum ada tamu yang mengambil souvenir.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
      </div>

      <!-- Footer Kartu (Pagination) -->
      @if ($guests->hasPages())
      <div class="bg-slate-50 p-4 border-t border-slate-200">
        {{ $guests->links() }}
      </div>
      @endif
    </div>
    <!-- End: Konten Utama -->

  </div>
</div>
@endsection
