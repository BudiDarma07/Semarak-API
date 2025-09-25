<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Fungsi Menampilkan Dashboard
     */
    public function index(Request $request) {
        // Ambil input pencarian dan filter tipe laporan
        $search = $request->input('search');
        $tipe_laporan = $request->input('tipe_laporan');

        // Mulai query dasar
        $query = Laporan::with('aset.kategori')->latest();

        // (KONDISI 1) Terapkan filter PENCARIAN TEKS jika ada isinya
        if ($search) {
            $query->where(function ($q) use ($search) {
                
                // Pencarian teks biasa
                $q->where('nama_teknisi', 'like', "%{$search}%")
                  ->orWhereHas('aset', function ($asetQuery) use ($search) {
                      $asetQuery->where('nama_aset', 'like', "%{$search}%")
                                ->orWhere('lokasi', 'like', "%{$search}%");
                  });

                // Pencarian cerdas untuk TAHUN
                if (is_numeric($search) && strlen($search) == 4) {
                    $q->orWhereYear('created_at', $search);
                }

                // Pencarian cerdas untuk BULAN
                $months = [
                    'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
                    'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
                    'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
                ];
                $searchLower = strtolower($search);
                if (isset($months[$searchLower])) {
                    $q->orWhereMonth('created_at', $months[$searchLower]);
                }
            });
        }
        
        // (KONDISI 2) Terapkan filter TIPE LAPORAN jika dipilih
        if ($tipe_laporan) {
            $query->where('tipe_laporan', $tipe_laporan);
        }
        
        // Eksekusi query setelah semua filter diterapkan
        $laporans = $query->get();
        
        // Kirim semua variabel filter ke view agar form bisa "mengingat" pilihan terakhir
        return view('dashboard', compact('laporans', 'search', 'tipe_laporan'));    
    }
}
