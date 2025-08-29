@extends('user.layouts.app')

@section('page_title', 'Laporan Kehadiran')

@section('content')
<div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
  <div class="max-w-7xl mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Laporan Kehadiran</h1>
        <p class="text-sm text-slate-500 mt-1">Pantau statistik dan daftar tamu yang hadir di acara Anda.</p>
    </div>

    <!-- Start: Bagian Statistik -->
    {{-- PERBAIKAN: Grid diubah menjadi 4 kolom --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Card Undangan -->
      <div class="bg-amber-500/50 p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-4xl font-bold text-amber-900">{{ $totalUndangan }}</h2>
        <p class="text-amber-700 uppercase tracking-wider mt-1">Undangan</p>
      </div>
      <!-- Card Hadir -->
      <div class="bg-green-500/50 p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-4xl font-bold text-green-900">{{ $totalHadir }}</h2>
        <p class="text-green-700 uppercase tracking-wider mt-1">Hadir</p>
      </div>
      <!-- Card Jml Tamu -->
      <div class="bg-blue-500/50 p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-4xl font-bold text-blue-900">{{ $jumlahTamuHadir }}</h2>
        <p class="text-blue-700 uppercase tracking-wider mt-1">Jml Tamu</p>
      </div>
      <!-- Card Tidak Hadir -->
      <div class="bg-red-500/50 p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-4xl font-bold text-red-900">{{ $totalTidakHadir }}</h2>
        <p class="text-red-700 uppercase tracking-wider mt-1">Tidak Hadir</p>
      </div>
    </div>
    <!-- End: Bagian Statistik -->

    <!-- Start: Tombol Filter -->
    <div class="flex items-center gap-2 mb-6">
        @php
            $filter = request()->query('filter', 'hadir');
        @endphp
        <a href="{{ route('kehadiran.index') }}" 
           class="py-2 px-6 rounded-lg text-sm font-medium transition duration-300
                  {{ $filter === 'hadir' ? 'bg-amber-500 text-slate-900 shadow-md' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-100' }}">
            Tamu Hadir
        </a>
        <a href="{{ route('kehadiran.index', ['filter' => 'tidak-hadir']) }}" 
           class="py-2 px-6 rounded-lg text-sm font-medium transition duration-300
                  {{ $filter === 'tidak-hadir' ? 'bg-amber-500 text-slate-900 shadow-md' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-100' }}">
            Tidak Hadir
        </a>
    </div>
    <!-- End: Tombol Filter -->

    <!-- Start: Konten Utama (Tabel Data) -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
          <!-- Tombol Aksi -->
          <div class="flex items-center gap-3">
            <a id="exportPdfLink" href="{{ route('kehadiran.export.pdf', ['filter' => $filter]) }}" class="flex items-center gap-2 bg-slate-700 text-white py-2 px-4 rounded-lg hover:bg-slate-800 transition duration-300 text-sm font-medium">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
              <span>Export PDF</span>
            </a>
            <a id="exportExcelLink" href="{{ route('kehadiran.export.excel', ['filter' => $filter]) }}" class="flex items-center gap-2 bg-slate-700 text-white py-2 px-4 rounded-lg hover:bg-slate-800 transition duration-300 text-sm font-medium">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
              <span>Export Excel</span>
            </a>
          </div>
          <!-- Input Pencarian -->
          <form id="searchForm" method="GET" action="{{ route('kehadiran.index') }}">
              <input type="hidden" name="filter" value="{{ $filter }}">
              <div class="relative w-full sm:w-auto">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari nama tamu..." class="w-full sm:w-64 pl-4 pr-10 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                  <button type="submit" class="cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                  </button>
                </div>
              </div>
          </form>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Tamu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Waktu Check-in</th>
                    </tr>
                </thead>
                <tbody id="guestTableBody" class="bg-white divide-y divide-slate-200">
                    @forelse ($guests as $guest)
                        <tr class="guest-row">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $loop->iteration + ($guests->currentPage() - 1) * $guests->perPage() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 guest-name">{{ $guest->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $guest->affiliation }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                @if($guest->check_in_time)
                                    {{ \Carbon\Carbon::parse($guest->check_in_time)->isoFormat('D MMMM YYYY, HH:mm') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-16 px-6">
                                <p class="text-slate-500 text-lg">Tidak ada data untuk ditampilkan.</p>
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
        {{ $guests->appends(request()->query())->links() }}
      </div>
      @endif
    </div>
    <!-- End: Konten Utama -->

  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const exportPdfLink = document.getElementById('exportPdfLink');
    const exportExcelLink = document.getElementById('exportExcelLink');

    function updateExportLinks() {
        const searchValue = searchInput.value;
        const currentUrlPdf = new URL(exportPdfLink.href);
        const currentUrlExcel = new URL(exportExcelLink.href);

        if (searchValue) {
            currentUrlPdf.searchParams.set('search', searchValue);
        } else {
            currentUrlPdf.searchParams.delete('search');
        }
        exportPdfLink.href = currentUrlPdf.toString();

        if (searchValue) {
            currentUrlExcel.searchParams.set('search', searchValue);
        } else {
            currentUrlExcel.searchParams.delete('search');
        }
        exportExcelLink.href = currentUrlExcel.toString();
    }

    updateExportLinks();
    searchInput.addEventListener('keyup', updateExportLinks);
});
</script>
@endpush
