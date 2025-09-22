@extends('admin.layouts.app')

@section('title', 'Kelola Desain Tersimpan')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Kelola Desain Tersimpan</h1>
            <a href="{{ route('admin.design.index') }}" class="text-orange-500 hover:text-orange-600 font-semibold transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Editor</span>
            </a>
        </div>
        <p class="text-gray-600 mt-4">
            Ini adalah halaman placeholder untuk daftar desain yang sudah disimpan.
            Untuk menampilkan data di sini, Anda perlu mengambilnya dari database di `DesignController` dan mengirimkannya ke view ini.
        </p>
    </div>
</div>
@endsection
