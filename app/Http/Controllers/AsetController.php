<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

        // [MODIFIKASI API] Cek jika request meminta JSON
        if ($request->wantsJson()) {
            return response()->json($asets);
        }

        return view('aset.index', compact('asets', 'search'));
    }

    /**
     * Display QR Code page for a specific asset.
     */
    public function qrcode(Aset $aset)
    {
        $url = route('laporan.create', ['aset' => $aset->kode_aset]);
        $qrCode = QrCode::size(300)->generate($url);
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
            // Menambahkan validasi lain jika ada di form, sesuaikan jika perlu
            // 'tanggal_pembelian' => 'nullable|date',
            // 'harga' => 'nullable|numeric',
            // 'kondisi' => 'nullable|string',
        ]);

        $aset = Aset::create($validated);

        // [MODIFIKASI API] Cek jika request meminta JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Aset baru berhasil ditambahkan.',
                'data' => $aset
            ], 201); // 201 = Created
        }

        return redirect()->route('aset.index')->with('success', 'Aset baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Aset $aset)
    {
        // [MODIFIKASI API] Mengisi fungsi show untuk API
        if ($request->wantsJson()) {
            // Muat relasi kategori agar ikut tampil di JSON
            return response()->json($aset->load('kategori'));
        }

        // Untuk web, kita arahkan ke halaman edit
        return redirect()->route('aset.edit', $aset);
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

        // [MODIFIKASI API] Cek jika request meminta JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Data aset berhasil diperbarui.',
                'data' => $aset
            ]);
        }

        return redirect()->route('aset.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Aset $aset)
    {
        try {
            $aset->delete();

            // [MODIFIKASI API] Cek jika request meminta JSON
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Aset berhasil dihapus.']);
            }

            return redirect()->route('aset.index')->with('success', 'Aset berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // [MODIFIKASI API] Cek jika request meminta JSON
            if ($request->wantsJson()) {
                // Mengirimkan status error yang sesuai (409 Conflict)
                return response()->json(['message' => 'Aset tidak dapat dihapus karena masih digunakan di laporan lain.'], 409);
            }

            return redirect()->route('aset.index')->with('error', 'Aset tidak dapat dihapus karena masih digunakan di laporan lain.');
        }
    }
}