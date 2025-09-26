@extends('layouts.app')

@section('content')

{{-- Kode yang sudah disesuaikan dengan Tailwind CSS --}}
<div class="p-6 bg-white rounded-lg shadow-md max-w-md mx-auto">
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">
            QR Code untuk: {{ $aset->nama_aset }}
        </h2>

        {{-- [MODIFIKASI] Wrapper untuk QR Code diberi ID agar mudah ditemukan oleh JavaScript --}}
        <div id="qr-code-container" class="inline-block p-4 border border-gray-200 rounded-lg">
            {!! $qrCode !!}
        </div>

        <p class="mt-4 text-gray-600">
            Scan QR code ini untuk membuka formulir laporan.
        </p>

        {{-- [MODIFIKASI] Tombol Download dan Kembali dibuat bersebelahan --}}
        <div class="mt-6 flex justify-center gap-4">
            <a href="{{ route('aset.index') }}" class="px-6 py-2 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Kembali
            </a>
            <button id="download-qr-btn" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Download
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- [MODIFIKASI] Skrip untuk menangani unduhan QR Code --}}
<script>
document.getElementById('download-qr-btn').addEventListener('click', function() {
    // 1. Ambil elemen SVG dari dalam container
    const svgElement = document.querySelector('#qr-code-container svg');
    
    if (svgElement) {
        // 2. Ubah SVG menjadi string XML
        const serializer = new XMLSerializer();
        const svgString = serializer.serializeToString(svgElement);

        // 3. Buat file virtual (Blob) dari string SVG
        const blob = new Blob([svgString], { type: "image/svg+xml" });

        // 4. Buat URL sementara untuk file virtual tersebut
        const url = URL.createObjectURL(blob);

        // 5. Buat link <a> sementara untuk memicu unduhan
        const a = document.createElement('a');
        a.href = url;
        // Atur nama file unduhan secara dinamis
        a.download = 'qrcode-{{ Str::slug($aset->nama_aset) }}.svg';
        document.body.appendChild(a);
        a.click(); // Klik link tersebut secara otomatis

        // 6. Hapus elemen dan URL sementara setelah selesai
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    } else {
        console.error("Elemen SVG QR Code tidak ditemukan!");
    }
});
</script>
@endpush