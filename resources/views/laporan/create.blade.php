<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulir</title>
    {{-- Setting Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="icon" href="{{ asset('images/logo-prov-bali.png') }}" type="image/png">

</head>
<body class="bg-gray-100 flex justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-2 rounded-xl shadow-md">
        {{-- Logo --}}
        <div class="flex mb-4">
            <img src="{{asset('images/logo-turyapada.webp')}}" alt="Logo Turyapada" class="h-12 mt-4">
        </div>
        <h2 class="text-3xl font-semibold text-gray-800 border-b-4 border-blue-600 pb-1 mb-5">Formulir Pengecekan</h2>

        <form action="{{ route('laporan.next') }}" method="POST">
            @csrf
            <input type="hidden" name="aset_id" value="{{ $aset->id_aset }}">

            <div class="mb-4">
                <label for="nama_aset" class="block text-base font-semibold text-gray-700 mb-1">Nama Aset</label>
                <input type="text" id="nama_aset" value="{{ $aset->nama_aset }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500" readonly>
            </div>
            
            <div class="mb-4">
                <label for="lokasi" class="block text-base font-semibold text-gray-700 mb-1">Lokasi</label>
                <input type="text" id="lokasi" value="{{ $aset->lokasi }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500" readonly>
            </div>
            
            <div class="mb-4">
                <label for="nama_teknisi" class="block text-base font-semibold text-gray-700 mb-1">Nama Teknisi</label>
                <input type="text" id="nama_teknisi" name="nama_teknisi" placeholder="Masukkan nama teknisi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
            </div>
            
            <div class="mb-4">
                <label for="tipe_laporan" class="block text-base font-semibold text-gray-700 mb-1">Tipe Laporan</label>
                <select id="tipe_laporan" name="tipe_laporan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    <option hidden value="">Pilih laporan</option>
                    <option value="pemeliharaan">Laporan Pemeliharaan</option>
                    <option value="tindak_lanjut">Laporan Tindak Lanjut</option>
                </select>
            </div>
            
            <div dir="rtl">
                <button type="submit" class="w-32 bg-blue-600 text-white font-base py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">Selanjutnya</button>
            </div>
            
        </form>
    </div>

    @if (session('success'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

</body>
</html>