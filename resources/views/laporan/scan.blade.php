@extends('layouts.app')

@section('content')

<div class="p-6 bg-white rounded-lg shadow-md max-w-xl mx-auto">
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">
            Pindai QR Code Aset
        </h2>
        
        {{-- Area ini akan digunakan untuk menampilkan video kamera atau pratinjau gambar --}}
        <div id="qr-reader" class="w-full max-w-sm mx-auto border-4 border-dashed rounded-lg overflow-hidden mb-4"></div>
        
        {{-- Keterangan status untuk memandu pengguna --}}
        <p id="status-message" class="text-gray-600 mb-4">Pilih metode pemindaian di bawah ini.</p>

        {{-- 1. TAMPILAN AWAL (INITIAL STATE) --}}
        <div id="initial-state" class="space-y-3">
            <button id="start-camera-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                <i class="fas fa-camera mr-2"></i> Pindai dengan Kamera
            </button>
            <button id="start-file-btn" class="w-full bg-gray-700 hover:bg-gray-800 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                <i class="fas fa-file-image mr-2"></i> Pilih dari File Gambar
            </button>
        </div>

        {{-- 2. TAMPILAN KAMERA (CAMERA STATE) - Awalnya tersembunyi --}}
        <div id="camera-state" class="hidden">
            <button id="stop-camera-btn" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                <i class="fas fa-stop-circle mr-2"></i> Batalkan & Kembali
            </button>
        </div>

        {{-- 3. TAMPILAN FILE (FILE STATE) - Awalnya tersembunyi --}}
        <div id="file-state" class="hidden space-y-3">
            {{-- Input file yang tersembunyi, akan dipicu oleh tombol --}}
            <input type="file" id="qr-input-file" accept="image/*" class="hidden">
            
            <button id="trigger-file-input" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                <i class="fas fa-upload mr-2"></i> Unggah Gambar QR Code
            </button>
            <button id="cancel-file-btn" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                <i class="fas fa-times-circle mr-2"></i> Batalkan & Kembali
            </button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
{{-- Tambahkan Font Awesome untuk ikon, jika belum ada --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Referensi ke elemen-elemen UI
    const initialStateDiv = document.getElementById('initial-state');
    const cameraStateDiv = document.getElementById('camera-state');
    const fileStateDiv = document.getElementById('file-state');
    const statusMessage = document.getElementById('status-message');
    const qrReaderContainer = document.getElementById('qr-reader');

    // Tombol-tombol
    const startCameraBtn = document.getElementById('start-camera-btn');
    const stopCameraBtn = document.getElementById('stop-camera-btn');
    const startFileBtn = document.getElementById('start-file-btn');
    const cancelFileBtn = document.getElementById('cancel-file-btn');
    const triggerFileInputBtn = document.getElementById('trigger-file-input');
    const fileInput = document.getElementById('qr-input-file');

    // Inisialisasi scanner
    const html5QrCode = new Html5Qrcode("qr-reader");

    // === FUNGSI UTAMA ===

    const onScanSuccess = (decodedText, decodedResult) => {
        statusMessage.innerText = `Scan Berhasil! Mengarahkan...`;
        statusMessage.className = 'text-green-600 font-bold mb-4';
        
        // Hentikan pemindaian kamera jika sedang aktif
        if (html5QrCode.isScanning) {
            html5QrCode.stop().catch(err => console.error("Gagal menghentikan kamera.", err));
        }
        
        window.location.href = decodedText;
    };

    const onScanFailure = (error) => {
        // Abaikan error "QR code not found" karena itu normal saat kamera aktif
    };
    
    // === PENGELOLA TAMPILAN (STATE MANAGER) ===

    function showInitialState() {
        initialStateDiv.classList.remove('hidden');
        cameraStateDiv.classList.add('hidden');
        fileStateDiv.classList.add('hidden');
        statusMessage.innerText = 'Pilih metode pemindaian di bawah ini.';
        statusMessage.className = 'text-gray-600 mb-4';
        qrReaderContainer.innerHTML = ""; // Bersihkan area pratinjau
    }

    function showCameraState() {
        initialStateDiv.classList.add('hidden');
        cameraStateDiv.classList.remove('hidden');
        fileStateDiv.classList.add('hidden');
        statusMessage.innerText = 'Arahkan kamera ke QR Code...';
        
        html5QrCode.start(
            { facingMode: "environment" }, // Prioritaskan kamera belakang
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            statusMessage.innerText = 'Error: Tidak dapat mengakses kamera. Pastikan Anda memberikan izin.';
            statusMessage.className = 'text-red-600 font-bold mb-4';
            console.error("Gagal memulai kamera", err);
        });
    }

    function showFileState() {
        initialStateDiv.classList.add('hidden');
        cameraStateDiv.classList.add('hidden');
        fileStateDiv.classList.remove('hidden');
        statusMessage.innerText = 'Pilih file gambar yang berisi QR Code.';
    }

    // === EVENT LISTENERS ===

    startCameraBtn.addEventListener('click', showCameraState);
    
    startFileBtn.addEventListener('click', showFileState);
    
    stopCameraBtn.addEventListener('click', () => {
        if (html5QrCode.isScanning) {
            html5QrCode.stop()
                .then(() => showInitialState())
                .catch(err => {
                    console.error("Gagal menghentikan scanner:", err);
                    showInitialState(); // Paksa kembali ke state awal
                });
        }
    });

    cancelFileBtn.addEventListener('click', showInitialState);
    
    // Memicu input file saat tombol "Unggah" diklik
    triggerFileInputBtn.addEventListener('click', () => fileInput.click());

    // Memproses file yang dipilih
    fileInput.addEventListener('change', e => {
        if (e.target.files.length == 0) {
            return;
        }
        statusMessage.innerText = 'Memproses gambar...';
        html5QrCode.scanFile(e.target.files[0], true)
            .then(onScanSuccess)
            .catch(err => {
                statusMessage.innerText = 'Gagal menemukan QR Code di dalam gambar.';
                statusMessage.className = 'text-red-600 font-bold mb-4';
                console.error(`Error scanning file. Reason: ${err}`);
            });
    });

    // Panggil state awal saat halaman dimuat
    showInitialState();
});
</script>
@endpush