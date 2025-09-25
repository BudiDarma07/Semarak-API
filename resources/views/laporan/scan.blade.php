@extends('layouts.app')

@section('content')

{{-- Kode yang sudah disesuaikan dengan Tailwind CSS --}}
<div class="p-6 bg-white rounded-lg shadow-md max-w-xl mx-auto">
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">
            Pindai QR Code Aset
        </h2>
        
        <p class="text-gray-600 mb-4">
            Arahkan kamera ke QR code untuk membuka formulir laporan secara otomatis.
        </p>

        {{-- Area untuk menampilkan kamera pemindai --}}
        <div id="qr-reader" class="w-full max-w-sm mx-auto border-4 border-dashed rounded-lg overflow-hidden"></div>
        
    </div>
</div>

@endsection

@push('scripts')
{{-- Library untuk scan QR --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan pemindaian setelah berhasil
        html5QrcodeScanner.clear().catch(error => {
            console.error("Gagal menghentikan scanner.", error);
        });
        
        // Arahkan ke URL yang ada di dalam QR code
        window.location.href = decodedText;
    }

    function onScanFailure(error) {
        // Fungsi ini bisa dibiarkan kosong jika tidak ingin ada notifikasi error saat scan gagal
        // console.warn(`Code scan error = ${error}`);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
      "qr-reader",
      { 
        fps: 10, 
        qrbox: (viewfinderWidth, viewfinderHeight) => {
            // Membuat area scan menjadi persegi
            const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
            return {
                width: minEdge * 0.7,
                height: minEdge * 0.7
            };
        }
      },
      /* verbose= */ false);
      
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush