<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Dokumentasi;
use App\Models\Jawaban;
use App\Models\Laporan;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporaExport;
use App\Models\Notifiaksi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator; // Import Validator

class LaporanController extends Controller
{
    use AuthorizesRequests;

    /**
     * [API] Menampilkan daftar semua laporan.
     */
    public function index(Request $request)
    {
        // Fungsi ini ditambahkan khusus untuk endpoint API
        if (!$request->wantsJson()) {
            // Jika bukan request API, mungkin arahkan ke dashboard atau halaman lain
            return redirect()->route('dashboard');
        }

        $laporans = Laporan::with(['aset', 'aset.kategori'])->latest()->paginate(15);

        return response()->json($laporans);
    }

    /**
     * [WEB] Fungsi Menampilkan Formulir Laporan (halaman 1)
     */
    public function create(Aset $aset)
    {
        return view('laporan.create', compact('aset'));
    }

    /**
     * [WEB] Fungsi Memproses Data di Page 1 Formulir Laporan
     */
    public function next(Request $request)
    {
        // Validasi input page 1
        $request->validate([
            'aset_id' => 'required|exists:asets,id_aset',
            'nama_teknisi' => 'required|string|max:255',
            'tipe_laporan' => 'required|in:pemeliharaan,tindak_lanjut',
        ]);

        $aset = Aset::find($request->aset_id);
        $pertanyaans = null;

        if ($request->tipe_laporan == 'pemeliharaan') {
            $pertanyaans = Pertanyaan::where('kategori_id', $aset->kategori_id)
                ->whereIn('jenis_pertanyaan', ['pemeliharaan', 'tugas'])
                ->orderBy('urutan', 'asc')
                ->get();
        } elseif ($request->tipe_laporan == 'tindak_lanjut') {
            $pertanyaans = Pertanyaan::where('kategori_id', $aset->kategori_id)
                ->where('jenis_pertanyaan', 'tindak_lanjut')
                ->orderBy('urutan', 'asc')
                ->get();
        }

        // Menampilkan page 2
        return view('laporan.next', [
            'aset' => $aset,
            'nama_teknisi' => $request->nama_teknisi,
            'tipe_laporan' => $request->tipe_laporan,
            'pertanyaans' => $pertanyaans,
        ]);
    }

    /**
     * [WEB] Fungsi untuk halaman scan QR
     */
    public function scanQr()
    {
        return view('laporan.scan');
    }

    /**
     * [WEB & API] Menyimpan laporan baru.
     * Untuk API, semua data dikirim dalam satu request.
     * Untuk Web, ini adalah proses dari halaman ke-2 form.
     */
    public function store(Request $request)
    {
        // Validasi data umum
        $validator = Validator::make($request->all(), [
            'aset_id' => 'required|exists:asets,id_aset',
            'nama_teknisi' => 'required|string|max:255',
            'tipe_laporan' => 'required|in:pemeliharaan,tindak_lanjut',
            'jawaban' => 'sometimes|array', // 'jawaban' bisa ada atau tidak
            'jawaban.*' => 'nullable|string', // setiap jawaban boleh kosong
            'dokumentasi' => 'sometimes|array',
            'dokumentasi.*' => 'image|mimes:jpeg,png,jpg|max:2048' // setiap file adalah gambar
        ]);

        if ($validator->fails() && $request->wantsJson()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } elseif ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $laporan = Laporan::create([
            'aset_id' => $request->aset_id,
            'nama_teknisi' => $request->nama_teknisi,
            'tipe_laporan' => $request->tipe_laporan,
            'status' => 'Belum Proses',
        ]);

        // Proses jawaban
        if ($request->has('jawaban')) {
            foreach ($request->jawaban as $pertanyaan_id => $jawaban_text) {
                if (!empty($jawaban_text)) {
                    Jawaban::create([
                        'laporan_id' => $laporan->id_laporan,
                        'pertanyaan_id' => $pertanyaan_id,
                        'jawaban' => $jawaban_text,
                    ]);
                }
            }
        }

        // Proses upload dokumentasi
        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {
                $path = $file->store('laporan_dokumentasi', 'public');
                Dokumentasi::create([
                    'laporan_id' => $laporan->id_laporan,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Buat notifikasi jika tindak lanjut
        if ($laporan->tipe_laporan == 'tindak_lanjut') {
            Notifiaksi::create([
                'laporan_id' => $laporan->id_laporan,
                'pesan' => "Butuh Tindak Lajut - " . $laporan->aset->nama_aset,
            ]);
        }
        
        // [MODIFIKASI API] Respon untuk API
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Laporan berhasil disimpan!',
                'data' => $laporan->load('jawabans', 'dokumentasis')
            ], 201);
        }
        
        // Respon untuk Web
        $aset = Aset::find($request->aset_id);
        return redirect()->route('laporan.create', ['aset' => $aset->kode_aset])->with('success', 'Laporan berhasil disimpan!');
    }


    /**
     * [WEB & API] Menampilkan detail satu laporan.
     */
    public function show(Request $request, Laporan $laporan)
    {
        $laporan->load('aset', 'jawabans.pertanyaan', 'dokumentasis');

        // [MODIFIKASI API] Respon untuk API
        if ($request->wantsJson()) {
            return response()->json($laporan);
        }

        return view('laporan.show', compact('laporan'));
    }

    /**
     * [WEB & API] Menghapus Laporan Dari Database Beserta Dokumentasinya
     */
    public function destroy(Request $request, Laporan $laporan)
    {
        // Hapus file dokumentasi dari storage
        foreach ($laporan->dokumentasis as $dokumentasi) {
            Storage::disk('public')->delete($dokumentasi->file_path);
        }

        // Hapus data laporan (otomatis menghapus jawaban dan dokumentasi dari tabelnya karena relasi)
        $laporan->delete();

        // [MODIFIKASI API] Respon untuk API
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Laporan berhasil dihapus.']);
        }

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus');
    }

    /**
     * [WEB & API] Mengubah Status Laporan 
     */
    public function updateStatus(Request $request, Laporan $laporan)
    {
        $this->authorize('update-status-laporan');

        $request->validate([
            'status' => 'required|string|in:Belum Proses,Selesai',
        ]);

        $laporan->status = $request->status;
        $laporan->save();

        if (strtolower($laporan->status) == 'selesai') {
            Notifiaksi::where('laporan_id', $laporan->id_laporan)->delete();
        }

        // [MODIFIKASI API] Respon untuk API
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Status laporan berhasil diperbarui.',
                'data' => $laporan
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Status laporan berhasil diperbarui');
    }

    // Metode di bawah ini spesifik untuk web dan tidak perlu diubah untuk API
    
    public function export()
    {
        return view('laporan.export');
    }

    public function download(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $tipe_laporan = $request->input('tipe_laporan');

        $query = Laporan::query();

        if ($month) $query->whereMonth('created_at', $month);
        if ($year) $query->whereYear('created_at', $year);
        if ($tipe_laporan) $query->where('tipe_laporan', $tipe_laporan);

        if (!$query->exists()) {
            return back()->with('error', 'Tidak ada data laporan yang ditemukan untuk filter yang dipilih.');
        }
        
        $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $tipePart = $tipe_laporan ? ucfirst(str_replace('_', ' ', $tipe_laporan)) : 'SemuaLaporan';
        $bulanPart = $month ? $namaBulan[(int)$month] : 'SemuaBulan';
        $tahunPart = $year ? $year : 'SemuaTahun';
        $fileName = 'Laporan_' . $tipePart . '_' . $bulanPart . '_' . $tahunPart . '.xlsx';

        return Excel::download(new LaporaExport($month, $year, $tipe_laporan), $fileName);
    }
}