@extends('admin.layouts.app')

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush

@section('content')
<div x-data="{ showModal: false, selectedRequest: null }">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Request Client</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold">Terjadi Kesalahan</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold">Input Tidak Valid!</p>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li class="list-disc list-inside">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex border-b border-gray-200 flex-wrap">
            <a href="{{ route('request.client.index') }}" class="px-4 py-2 text-sm font-semibold {{ !$status ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">All</a>
            <a href="{{ route('request.client.index', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-semibold {{ $status == 'pending' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Pending</a>
            <a href="{{ route('request.client.index', ['status' => 'waiting_for_payment']) }}" class="px-4 py-2 text-sm font-semibold {{ $status == 'waiting_for_payment' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Menunggu Bayar</a>
            <a href="{{ route('request.client.index', ['status' => 'waiting_for_approval']) }}" class="px-4 py-2 text-sm font-semibold {{ $status == 'waiting_for_approval' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Menunggu Acc</a>
            <a href="{{ route('request.client.index', ['status' => 'approve']) }}" class="px-4 py-2 text-sm font-semibold {{ $status == 'approve' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500' }}">Approve</a>
        </div>
    </div>

    <div class="space-y-8">
        
        @if($pending->isNotEmpty())
        <div x-data="{ open: true }">
            <h3 @click="open = !open" class="text-lg font-semibold text-gray-600 cursor-pointer">> Pending ({{ $pending->count() }})</h3>
            <div x-show="open" class="space-y-4 mt-4 pl-4 border-l-2 border-gray-200" x-transition>
                @foreach($pending as $req)
                <div class="bg-white p-4 rounded-lg border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold text-white bg-orange-500 px-2 py-1 rounded">{{ $req->title }}</span>
                        <div>
                            <p class="font-semibold">{{ $req->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $req->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-sm text-gray-500">waiting-for-approval</span>
                        <button @click="selectedRequest = {{ $req }}; showModal = true" class="text-sm font-semibold text-red-600 hover:text-red-800">
                            PROCESS TO PAYMENT <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        @if($waiting_for_payment->isNotEmpty())
        <div x-data="{ open: true }">
            <h3 @click="open = !open" class="text-lg font-semibold text-gray-600 cursor-pointer">> Menunggu Pembayaran ({{ $waiting_for_payment->count() }})</h3>
            <div x-show="open" class="space-y-4 mt-4 pl-4 border-l-2 border-yellow-400" x-transition>
                @foreach($waiting_for_payment as $req)
                <div class="bg-white p-4 rounded-lg border border-gray-200 flex justify-between items-center">
                     <div class="flex items-center gap-4">
                        <span class="text-xs font-bold text-white bg-yellow-500 px-2 py-1 rounded">{{ $req->title }}</span>
                        <div>
                            <p class="font-semibold">{{ $req->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">Telp: {{ $req->user->phone ?? 'N/A' }}</p> 
                            <p class="text-xs text-gray-500">{{ $req->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-sm font-bold text-yellow-600">Rp {{ number_format($req->price, 0, ',', '.') }}</span>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Menunggu Pembayaran Klien</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($waiting_for_approval->isNotEmpty())
        <div x-data="{ open: true }">
            <h3 @click="open = !open" class="text-lg font-semibold text-gray-600 cursor-pointer">> Menunggu Persetujuan ({{ $waiting_for_approval->count() }})</h3>
            <div x-show="open" class="space-y-4 mt-4 pl-4 border-l-2 border-cyan-400" x-transition>
                @foreach($waiting_for_approval as $req)
                <div class="bg-white p-4 rounded-lg border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold text-white bg-cyan-500 px-2 py-1 rounded">{{ $req->title }}</span>
                        <div>
                            <p class="font-semibold">{{ $req->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $req->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-sm font-bold text-green-600"><i class="fas fa-check-circle"></i> LUNAS</span>
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" class="px-4 py-2 bg-teal-600 text-white text-sm rounded-md hover:bg-teal-700">
                                Tindakan <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10" x-transition>
                                <form action="{{ route('admin.request.approve', $req) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                      <i class="fas fa-check mr-2"></i> Approve
                                    </button>
                                </form>
                                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100"><i class="fas fa-times mr-2"></i> Tolak</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        </div>

    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div @click.away="showModal = false" class="bg-white rounded-lg p-8 w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Proses ke Pembayaran</h3>
            <p class="mb-6 text-gray-600">Masukkan nominal harga untuk request dari <strong x-text="selectedRequest?.user?.name"></strong>.</p>
            <form :action="`/admin/request-client/${selectedRequest?.id}/generate-payment`" method="POST">
                @csrf
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Harga (IDR)</label>
                    <input type="number" name="price" id="price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500" placeholder="Contoh: 150000" required>
                </div>
                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">Buat Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection