@extends('layouts.app')

@section('page_title', 'Data Tamu')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
  {{-- Header Acara --}}
  <div class="text-center mb-6">
    <h1 class="text-2xl font-bold text-pink-600">Ages & April</h1>
    <p class="text-gray-600 text-sm">Minggu, 21 September 2025</p>
  </div>

  {{-- Info Undangan --}}
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-gray-700">Data Tamu ({{ $guests->count() }} Undangan)</h2>
    <div class="flex gap-2">
      <a href="{{ route('tamu.create') }}" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">+ Tambah Tamu</a>
      <button class="bg-gray-200 text-gray-700 px-3 py-2 rounded hover:bg-gray-300">
        <i class="fas fa-cog"></i>
      </button>
    </div>
  </div>

  {{-- Search --}}
  <div class="mb-4">
    <input type="text" placeholder="Cari nama tamu..." class="w-full px-4 py-2 border rounded-md focus:ring-pink-500 focus:border-pink-500">
  </div>

  {{-- Tabel Tamu --}}
  <div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-pink-50">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">No</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Kategori</th>
          <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach ($guests as $index => $guest)
        <tr>
          <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
          <td class="px-4 py-3 text-sm text-gray-800 font-medium">{{ $guest->name }}</td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ $guest->affiliation }}</td>
          <td class="px-4 py-3 text-right text-sm">
            <div class="relative inline-block text-left">
              <button onclick="toggleDropdown({{ $guest->id }})" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <div id="dropdown-{{ $guest->id }}" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 hidden">
                <ul class="py-2 text-sm text-gray-700">
                  <li><a href="#" class="block px-4 py-2 hover:bg-pink-100">Undangan</a></li>
                  <li><a href="#" class="block px-4 py-2 hover:bg-pink-100">Share Undangan</a></li>
                  <li><a href="#" class="block px-4 py-2 hover:bg-pink-100">Copy Pesan</a></li>
                  <li><a href="#" class="block px-4 py-2 hover:bg-pink-100">Download QRCode</a></li>
                  <li><a href="{{ route('tamu.edit', $guest->id) }}" class="block px-4 py-2 hover:bg-pink-100">Edit</a></li>
                  <li>
                    <form method="POST" action="{{ route('tamu.destroy', $guest->id) }}">
                      @csrf @method('DELETE')
                      <button type="submit" onclick="return confirm('Yakin ingin menghapus tamu ini?')" class="w-full text-left px-4 py-2 hover:bg-pink-100">Hapus</button>
                    </form>
                  </li>
                </ul>
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function toggleDropdown(id) {
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
    const dropdown = document.getElementById('dropdown-' + id);
    dropdown.classList.toggle('hidden');
  }

  document.addEventListener('click', function (e) {
    if (!e.target.closest('[id^="dropdown-"]') && !e.target.closest('button[onclick^="toggleDropdown"]')) {
      document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
    }
  });
</script>
@endpush
@section('scripts')
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('translate-x-0');
      sidebar.classList.toggle('-translate-x-full');
    }

    function toggleProfile() {
      const popup = document.getElementById('profilePopup');
      popup.classList.toggle('hidden');
    }
  </script>