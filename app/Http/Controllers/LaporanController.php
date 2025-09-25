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

class LaporanController extends Controller
{
    use AuthorizesRequests;

    /**
     * Fungsi Menampilkan Formulir Laporan
     */
    public function create(Aset $aset) {
        return view('laporan.create', compact('aset'));
    }

    /**
     * Fungsi Memproses Data di Page 1 Formulir Laporan
     */
    public function next(Request $request) {
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


    public function scanQr()
    {
        return view('laporan.scan');
    }

    /**
     * Fungsi Memproses Data di Page 2 Formulir Laporan
     */
    public function store(Request $request) {
        // Validasi data umum
        $request->validate([
            'aset_id' => 'required|exists:asets,id_aset',
            'nama_teknisi' => 'required|string|max:255',
            'tipe_laporan' => 'required|in:pemeliharaan,tindak_lanjut',
        ]);

        $laporan = Laporan::create([
            'aset_id' => $request->aset_id,
            'nama_teknisi' => $request->nama_teknisi,
            'tipe_laporan' => $request->tipe_laporan,
            'status' => 'Belum Proses',
        ]);

        if($request->has('jawaban')) {
            foreach($request->jawaban as $pertanyaan_id => $jawaban_text) {
                if (!empty($jawaban_text)) {
                    Jawaban::create([
                        'laporan_id' => $laporan->id_laporan,
                        'pertanyaan_id' => $pertanyaan_id,
                        'jawaban' => $jawaban_text,
                    ]);
                }
            }
        }

        if($request->hasFile('dokumentasi')) {
            foreach($request->file('dokumentasi') as $file) {
                $path = $file->store('laporan_dokumentasi', 'public');
                Dokumentasi::create([
                    'laporan_id' => $laporan->id_laporan,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        if ($laporan->tipe_laporan == 'tindak_lanjut') {
            Notifiaksi::create([
                'laporan_id' => $laporan->id_laporan,
                'pesan' => "Butuh Tindak Lajut - " . $laporan->aset->nama_aset,
            ]);
        }

        $aset = Aset::find($request->aset_id);
        return redirect()->route('laporan.create', ['aset' => $aset->kode_aset])->with('success', 'Laporan berhasil disimpan!');
    }

    /**
     * Fungsi Menampilkan Laporan di Dashboard
     */
    public function show(Laporan $laporan) {
        $laporan->load('aset', 'jawabans.pertanyaan', 'dokumentasis');

        return view('laporan.show', compact('laporan'));
    }

    /**
     * Fungsi Menghapus Laporan Dari Database Beserta Dokumentasinya
     */
    public function destroy(Laporan $laporan) {
        // Hapus file dokumentasi dari laporan
        foreach ($laporan->dokumentasis as $dokumentasi) {
            Storage::disk('public')->delete($dokumentasi->file_path);
        }

        // Hapus data laporan
        $laporan->delete();

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus');
    }

    /**
     * Fungsi Mengubah Status Laporan 
     */
    public function updateStatus(Request $request, Laporan $laporan) {
        $this->authorize('update-status-laporan');

        $request->validate([
            'status' => 'required|string|in:Belum Proses,Selesai',
        ]);

        $laporan->status = $request->status;
        $laporan->save();

        if (strtolower($laporan->status) == 'selesai') {
            Notifiaksi::where('laporan_id', $laporan->id_laporan)->delete();
        }

        return redirect()->route('dashboard')->with('success', 'Status laporan berhasil diperbarui');
    }

    /**
     * Fungsi Menampilkan Halaman Download Laporan Excel
     */
    public function export() {
        return view('laporan.export');
    }

    /** 
     * Fungsi Mendowload Laporan Excel Tanpa Dokumentasi
    */
    public function download(Request $request) {
        // Ambil data filter
        $month = $request->input('month');
        $year = $request->input('year');
        $tipe_laporan = $request->input('tipe_laporan');

        $query = Laporan::query();

        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        if ($tipe_laporan) {
            $query->where('tipe_laporan', $tipe_laporan);
        }

        // Pengecekan data yang kosong
        if (!$query->exists()) {
            // Pesan error jika tidak ada
            return back()->with('error', 'Tidak ada data laporan yang ditemukan untuk filter yang dipilih.');
        }
        
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $tipePart = $tipe_laporan ? ucfirst(str_replace('_', ' ', $tipe_laporan)) : 'SemuaLaporan';
        $bulanPart = $month ? $namaBulan[(int)$month] : 'SemuaBulan';
        $tahunPart = $year ? $year : 'SemuaTahun';

        // Penamaan laporan
        $fileName = 'Laporan_' . $tipePart . '_' . $bulanPart . '_' . $tahunPart . '.xlsx';

        // Panggil class LaporanExport untuk men-download file
        return Excel::download(new LaporaExport($month, $year, $tipe_laporan), $fileName);
    }
}
