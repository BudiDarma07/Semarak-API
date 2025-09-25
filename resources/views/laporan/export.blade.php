@extends('layouts.app')

@section('content')

<div class="bg-white rounded-lg shadow-md p-5">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Download Laporan</h1>

    {{-- (BARU) Blok untuk menampilkan pesan error --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 text-sm" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('laporan.download') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="month" class="block text-sm font-medium text-gray-600">Bulan</label>
                <select id="month" name="month" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option hidden value="">Pilih Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="year" class="block text-sm font-medium text-gray-600">Tahun</label>
                <select id="year" name="year" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option hidden value="">Pilih Tahun</option>
                    @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="tipe_laporan" class="block text-sm font-medium text-gray-600">Tipe Laporan</label>
                <select id="tipe_laporan" name="tipe_laporan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Semua Laporan</option>
                    <option value="pemeliharaan">Pemeliharaan</option>
                    <option value="tindak_lanjut">Tindak Lanjut</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            <button type="submit" class="px-6 py-2 bg-yellow-400 text-gray-800 font-semibold rounded-lg hover:bg-yellow-500">
                Download
            </button>
            <a href="{{ url()->previous() }}" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                Kembali
            </a>
        </div>
    </form>
</div>

@endsection