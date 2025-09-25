@extends('layouts.app')

@section('content')

    <div class="flex items-center justify-between mb-4">
        <form id="filterForm" action="{{ route('dashboard') }}" method="GET" class="flex items-center space-x-2">
            <div class="relative w-full max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari..." 
                    value="{{ $search ?? '' }}" 
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            
            <div class="flex items-center space-x-4">
                {{-- Dropdown Filter Tipe Laporan --}}
                <select name="tipe_laporan" id="tipeLaporanFilter" class="border rounded-lg text-sm font-semibold text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 py-2 px-3">
                    <option value="">Semua Laporan</option>
                    <option value="pemeliharaan" {{ ($tipe_laporan ?? '') == 'pemeliharaan' ? 'selected' : '' }}>
                        Pemeliharaan
                    </option>
                    <option value="tindak_lanjut" {{ ($tipe_laporan ?? '') == 'tindak_lanjut' ? 'selected' : '' }}>
                        Tindak Lanjut
                    </option>
                </select>
            </div>
        </form>

        {{-- Tombol Download --}}
        <div>
            <a href="{{ route('laporan.export') }}" class="flex items-center px-4 py-2 bg-yellow-400 text-gray-800 font-semibold rounded-lg hover:bg-yellow-500 text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($laporans as $laporan)
            <div class="bg-white rounded-lg shadow p-5 border-t-4 
                @if($laporan->tipe_laporan == 'pemeliharaan') border-green-500 
                @elseif($laporan->tipe_laporan == 'tindak_lanjut') border-red-500 
                @endif">
                
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-700 font-semibold">Aset: <span class="font-bold text-gray-800">{{ $laporan->aset->nama_aset }}</span></p>
                        <p class="text-sm text-gray-700 font-semibold">Lokasi: <span class="font-normal">{{ $laporan->aset->lokasi }}</span></p>
                        <p class="text-sm text-gray-700 font-semibold">Kategori: <span class="font-normal">{{ $laporan->aset->kategori->nama_kategori ?? 'N/A' }}</span></p>
                        <p class="text-sm text-gray-700 font-semibold">Teknisi: <span class="font-normal">{{ $laporan->nama_teknisi }}</span></p>
                        <p class="text-sm text-gray-700 font-semibold">Tanggal Laporan: <span class="font-normal">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d F Y') }}</span></p>
                    </div>
                    @if($laporan->tipe_laporan == 'tindak_lanjut' && $laporan->status)
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            @if(strtolower($laporan->status) == 'selesai') 
                            bg-green-100 text-green-800 
                        @else 
                            bg-red-100 text-red-800 
                        @endif">
                        {{ $laporan->status }}
                        </span>
                    @endif
                </div>

                <div class="mt-4">
                    <a href="{{ route('laporan.show', $laporan) }}" class="text-sm font-semibold text-blue-600 hover:underline">Lihat selengkapnya</a>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-lg shadow p-5 text-center text-gray-500">
                <p>Tidak ada laporan.</p>
            </div>
        @endforelse
    </div>

{{-- (BARU) Tambahkan blok script di bawah ini --}}
@push('scripts')
<script>
    // Cari elemen dropdown
    const tipeLaporanFilter = document.getElementById('tipeLaporanFilter');

    // Tambahkan event listener yang akan berjalan saat nilainya berubah
    tipeLaporanFilter.addEventListener('change', function() {
        // Cari form dan kirim (submit) secara otomatis
        document.getElementById('filterForm').submit();
    });
</script>
@endpush

@endsection

@push('scripts')
<script>
    // Cari elemen dropdown
    const tipeLaporanFilter = document.getElementById('tipeLaporanFilter');

    // Tambahkan event listener yang akan berjalan saat nilainya berubah
    tipeLaporanFilter.addEventListener('change', function() {
        // Cari form dan kirim (submit) secara otomatis
        document.getElementById('filterForm').submit();
    });
</script>
@endpush