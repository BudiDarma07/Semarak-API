<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulir</title>
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

    <link rel="icon" href="{{ asset('images/logo-prov-bali.png') }}" type="image/png">
    
    <style>
        /* Sembunyikan radio button asli */
        .radio-group input[type="radio"] {
            opacity: 0;
            position: fixed;
            width: 0;
        }
        /* Style untuk label sebagai tombol radio */
        .radio-group label {
            display: inline-block;
            background-color: #ffffff;
            padding: 8px 15px;
            font-family: sans-serif, Arial;
            font-size: 14px;
            border: 1px solid #d4d4d8;
            border-radius: 8px;
            cursor: pointer;
        }

        .radio-group label:hover {
            background-color: #2563eb;
            border-color: #1d4ed8;
            color: white;
        }

        /* Style saat radio button dipilih */
        .radio-group input[type="radio"]:checked + label {
            background-color: #2563eb;
            border-color: #1d4ed8;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">
    {{-- Header --}}
    <div class="w-full max-w-md bg-white p-2 rounded-xl shadow-md mx-auto">
        {{-- Logo --}}
        <div class="flex mb-4">
            <img src="{{asset('images/logo-turyapada.webp')}}" alt="Logo Turyapada" class="h-12 mt-4">
        </div>
        <h2 class="text-3xl font-semibold text-gray-800 border-b-4 border-blue-600 pb-1 mb-5">Formulir Pengecekan</h2>

        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="aset_id" value="{{ $aset->id_aset }}">
            <input type="hidden" name="nama_teknisi" value="{{ $nama_teknisi }}">
            <input type="hidden" name="tipe_laporan" value="{{ $tipe_laporan }}">

            <div class="space-y-4 mb-6">
                 <div>
                    <label class="block text-base font-semibold mb-1 text-gray-700">Nama Aset</label>
                    <input type="text" value="{{ $aset->nama_aset }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500" readonly>
                </div>
                <div>
                    <label class="block text-base font-semibold mb-1 text-gray-700">Nama Teknisi</label>
                    <input type="text" value="{{ $nama_teknisi }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500" readonly>
                </div>
                <div>
                    <label class="block text-base font-semibold mb-1 text-gray-700">Tipe Laporan</label>
                    <input type="text" value="{{ $tipe_laporan == 'pemeliharaan' ? 'Laporan Pemeliharaan' : 'Laporan Tindak Lanjut' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500" readonly>
                </div>
            </div>

            <hr class="my-4">
            
            <h2 class="text-xl font-semibold text-gray-800 mb-3">{{ $tipe_laporan == 'pemeliharaan' ? 'Pertanyaan Pemeliharaan' : 'Pertanyaan Tindak Lanjut' }}</h2>
            
            <div class="space-y-5">
                @php
                    $tugasSectionRendered = false;
                @endphp

                @foreach($pertanyaans as $p)
                    
                    @if($p->jenis_pertanyaan == 'tugas' && !$tugasSectionRendered)
                        <div class="pt-2">
                            <h4 class="text-xl font-semibold text-gray-800 border-t pt-4 mt-4">Tugas</h4>
                        </div>
                        @php
                            $tugasSectionRendered = true;
                        @endphp
                    @endif

                    <div>
                        <label class="block text-base font-semibold text-gray-700 mb-1">
                            {{ $p->pertanyaan }}
                            
                            @if(!Str::contains(strtolower($p->pertanyaan), 'catatan'))
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        
                        @if(Str::contains(strtolower($p->pertanyaan), 'catatan'))
                            <textarea name="jawaban[{{ $p->id_pertanyaan }}]" placeholder="Tambahkan catatan di sini..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                        @elseif(Str::contains($p->tipe_jawaban, '/'))
                            <div class="radio-group flex space-x-4">
                                @foreach(explode('/', $p->tipe_jawaban) as $key => $option)
                                <div>
                                    <input type="radio" id="option_{{ $p->id_pertanyaan }}_{{ $key }}" name="jawaban[{{ $p->id_pertanyaan }}]" value="{{ $option }}" required>
                                    <label for="option_{{ $p->id_pertanyaan }}_{{ $key }}">{{ $option }}</label>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <input type="text" name="jawaban[{{ $p->id_pertanyaan }}]" placeholder="Masukkan jawaban" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                <label class="block text-xl font-semibold text-gray-800">Dokumentasi <span class="text-red-500">*</span></label>
                <p class="text-sm text-gray-600 mb-2">Upload file yang didukung: .png, .jpg. Maksimal 10 MB</p>
                <label for="file-upload" class="cursor-pointer bg-white border border-gray-400 text-gray-700 font-semibold py-2 px-4 rounded-md hover:bg-gray-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                    Tambahkan File
                </label>
                <input id="file-upload" type="file" name="dokumentasi[]" class="hidden" multiple>
                <div id="file-list-container" class="mt-4 space-y-2">

                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('laporan.create', ['aset' => $aset->kode_aset]) }}" class="text-red-600 font-semibold hover:underline mr-5">Kembali</a>
                <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-6 rounded-md hover:bg-green-700 transition duration-300 mr-3">Kirim</button>
            </div>
        </form>
    </div>

    <script>
        // Ambil elemen input file dan wadah daftar file
        const fileInput = document.getElementById('file-upload');
        const fileListContainer = document.getElementById('file-list-container');

        // Tambahkan event listener saat ada file yang dipilih
        fileInput.addEventListener('change', function() {
            // Kosongkan daftar file yang lama setiap kali ada perubahan
            fileListContainer.innerHTML = '';

            // Jika tidak ada file yang dipilih, jangan lakukan apa-apa
            if (this.files.length === 0) {
                return;
            }

            // Buat tombol untuk menghapus semua pilihan
            const clearButton = document.createElement('button');
            clearButton.type = 'button'; // Tipe 'button' agar tidak mengirim form
            clearButton.innerText = 'Hapus Semua Pilihan';
            clearButton.className = 'text-sm text-red-600 hover:underline mb-2';
            clearButton.onclick = function() {
                fileInput.value = ''; // Mengosongkan input file
                fileListContainer.innerHTML = ''; // Menghapus tampilan daftar file
            };
            fileListContainer.appendChild(clearButton);
            
            // Loop sebanyak file yang dipilih dan tampilkan di wadah
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                
                // Buat elemen div untuk setiap item file
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between bg-gray-100 p-2 rounded-md text-sm';
                
                // Format ukuran file agar mudah dibaca
                const fileSize = file.size > 1024 * 1024 
                    ? `${(file.size / (1024 * 1024)).toFixed(2)} MB` 
                    : `${(file.size / 1024).toFixed(2)} KB`;

                // Isi HTML untuk item file
                fileItem.innerHTML = `
                    <div class="flex items-center truncate">
                        <svg class="w-5 h-5 mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="truncate pr-2">${file.name}</span>
                    </div>
                    <span class="text-gray-600 flex-shrink-0">(${fileSize})</span>
                `;
                
                // Masukkan item file ke dalam wadah
                fileListContainer.appendChild(fileItem);
            }
        });
    </script>

</body>
</html>