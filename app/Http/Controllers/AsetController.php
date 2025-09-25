<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Ditambahkan

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Aset::with('kategori')->latest();

        if ($search) {
            $query->where('nama_aset', 'like', "%{$search}%")
            ->orWhere('kode_aset', 'like', "%{$search}%")
            ->orWhere('lokasi', 'like', "%{$search}%");
        }

        $asets = $query->paginate(10);
        return view('aset.index', compact('asets', 'search'));
    }
    public function qrcode(Aset $aset)
    {
        // URL ini akan menjadi isi dari QR code
        $url = route('laporan.create', ['aset' => $aset->kode_aset]);

        // Hasilkan QR code
        $qrCode = QrCode::size(300)->generate($url);

        // Tampilkan view dengan QR code
        return view('aset.qrcode', compact('aset', 'qrCode'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('aset.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_aset' => 'required|string|max:255|unique:asets,kode_aset',
            'nama_aset' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id_kategori',
            'lokasi' => 'required|string|max:255',
        ]);

        Aset::create($validated);

        return redirect()->route('aset.index')->with('success', 'Aset baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aset $aset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aset $aset)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('aset.edit', compact('aset', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'kode_aset' => [
                'required', 'string', 'max:255',
                Rule::unique('asets')->ignore($aset->id_aset, 'id_aset'),
            ],
            'nama_aset' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id_kategori',
            'lokasi' => 'required|string|max:255',
        ]);

        $aset->update($validated);

        return redirect()->route('aset.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $aset)
    {
        try {
            $aset->delete();
            return redirect()->route('aset.index')->with('success', 'Aset berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('aset.index')->with('error', 'Aset tidak dapat dihapus karena masih digunakan di laporan lain.');
        }
    }
}