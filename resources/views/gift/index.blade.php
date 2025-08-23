@extends('layouts.app')

@section('page_title', 'Kado Digital')

@section('content')
<div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
  <div class="max-w-4xl mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Kado Digital</h1>
        <p class="text-sm text-slate-500 mt-1">Lacak data pengirim kado digital untuk event Anda.</p>
    </div>

    <!-- Start: Bagian Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="bg-slate-800 text-white p-6 rounded-lg shadow-md text-center">
            <h2 class="text-5xl font-bold">{{ $totalGifts }}</h2>
            <p class="text-slate-300 text-lg uppercase tracking-wider mt-1">Jumlah Pengirim</p>
        </div>
        <div class="bg-amber-500/50 p-6 rounded-lg shadow-md text-center">
            <h2 class="text-5xl font-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h2>
            <p class="text-amber-700 text-lg uppercase tracking-wider mt-1">Total Nominal</p>
        </div>
    </div>
    <!-- End: Bagian Statistik -->

    <!-- Start: Konten Utama -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6">
        <!-- Tombol Aksi & Pencarian -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
            <div class="flex items-center gap-3">
                {{-- [PENTING] Link ini sekarang menyertakan query pencarian saat diekspor --}}
                <a href="{{ route('gift.export', request()->query()) }}" class="flex items-center gap-2 bg-slate-700 text-white py-2 px-4 rounded-lg hover:bg-slate-800 transition duration-300 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    <span>Export</span>
                </a>
            </div>
            <form action="{{ route('gift.index') }}" method="GET" class="w-full sm:w-auto">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengirim..." class="w-full sm:w-64 pl-4 pr-10 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Pengirim</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nominal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($gifts as $gift)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $loop->iteration + ($gifts->currentPage() - 1) * $gifts->perPage() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $gift->sender_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">Rp {{ number_format($gift->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($gift->created_at)->isoFormat('D MMM YYYY, HH:mm') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-16 px-6">
                                <p class="text-slate-500 text-lg">
                                    @if(request('search'))
                                        Tidak ada pengirim yang cocok dengan pencarian Anda.
                                    @else
                                        Belum ada data kado digital yang diterima.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
      </div>

      <!-- Footer Kartu (Pagination) -->
      @if ($gifts->hasPages())
      <div class="bg-slate-50 p-4 border-t border-slate-200">
        {{ $gifts->links() }}
      </div>
      @endif
    </div>
    <!-- End: Konten Utama -->

  </div>
</div>
@endsection
