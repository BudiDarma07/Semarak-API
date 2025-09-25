@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Edit Data Aset</h1>
    <form action="{{ route('aset.update', $aset) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="kode_aset" class="block text-sm font-semibold text-gray-700">Kode Aset</label>
                <input type="text" name="kode_aset" id="kode_aset" value="{{ old('kode_aset', $aset->kode_aset) }}" class="py-2 px-3 mt-1 block w-full border-gray-300 rounded-md border focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="nama_aset" class="block text-sm font-semibold text-gray-700">Nama Aset</label>
                <input type="text" name="nama_aset" id="nama_aset" value="{{ old('nama_aset', $aset->nama_aset) }}" class="py-2 px-3 mt-1 block w-full border-gray-300 rounded-md border focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="kategori_id" class="block text-sm font-semibold text-gray-700">Kategori</label>
                <select name="kategori_id" id="kategori_id" class="py-2 px-3 mt-1 block w-full border-gray-300 rounded-md border focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option hidden value="">Pilih Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}" @if(old('kategori_id', $aset->kategori_id) == $kategori->id_kategori) selected @endif>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="lokasi" class="block text-sm font-semibold text-gray-700">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $aset->lokasi) }}" class="py-2 px-3 mt-1 block w-full border-gray-300 rounded-md border focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>
        <div class="flex justify-end space-x-2 mt-6">
            <a href="{{ route('aset.index') }}" class="px-4 py-2 bg-red-600 text-white rounded-lg">Batal</a>
            <button type="submit" class="px-4 py-2 bg-yellow-400 text-black rounded-lg">Edit</button>
        </div>
    </form>
</div>
@endsection