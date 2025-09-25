@extends('layouts.app')

@section('content')

{{-- Kode yang sudah disesuaikan dengan Tailwind CSS --}}
<div class="p-6 bg-white rounded-lg shadow-md max-w-md mx-auto">
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">
            QR Code untuk: {{ $aset->nama_aset }}
        </h2>

        {{-- Wrapper untuk QR Code --}}
        <div class="inline-block p-4 border border-gray-200 rounded-lg">
            {!! $qrCode !!}
        </div>

        <p class="mt-4 text-gray-600">
            Scan QR code ini untuk membuka formulir laporan.
        </p>

        <a href="{{ route('aset.index') }}" class="mt-6 inline-block px-6 py-2 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500">
            Kembali
        </a>
    </div>
</div>

@endsection