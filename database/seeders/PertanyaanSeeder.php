<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Pertanyaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PertanyaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Pertanyaan::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Pertanyaan Appar
        $kategoriAppar = Kategori::where('nama_kategori', 'Appar')->first();

        if ($kategoriAppar) {
            $listPertanyaanAppar = [
                // Pemeliharaan
                ['pertanyaan' => 'APAR mudah diakses dan tidak terhalang', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 1],
                ['pertanyaan' => 'Tanda/label instruksi masih terbaca', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 2],
                ['pertanyaan' => 'Tidak ada karat, penyok, atau kerusakan fisik', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 3],
                ['pertanyaan' => 'Pin pengaman dan segel masih utuh', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 4],
                ['pertanyaan' => 'Manometer menunjukkan tekanan normal (zona hijau)', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 5],
                ['pertanyaan' => 'Selang dan nozzle tidak retak atau tersumbat', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 6],
                ['pertanyaan' => 'Berat sesuai spesifikasi (tidak berkurang)', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 7],
                ['pertanyaan' => 'Tanggal kadaluarsa/servis belum terlewati', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 8],
                ['pertanyaan' => 'Braket/penggantung aman dan kuat', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 9],
                ['pertanyaan' => 'APAR telah dibalik (dry chemical) untuk mencegah beku', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 10],

                // Tindak_Lanjut
                ['pertanyaan' => 'Apakah perlu servis profesional?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 101],
                ['pertanyaan' => 'Apakah perlu pengisian ulang?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 102],
                ['pertanyaan' => 'Apakah perlu penggantian unit?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 103],

                // Catatan Kerusakan
                ['pertanyaan' => 'Catatan', 'tipe_jawaban' => 'Teks', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 201],
            ];
            foreach ($listPertanyaanAppar as $p) {
                $p['kategori_id'] = $kategoriAppar->id_kategori;
                Pertanyaan::create($p);
            }
        }

        // Pertanyaan AC
        $kategoriAC = Kategori::where('nama_kategori', 'AC')->first();

        if ($kategoriAC) {
            $listPertanyaanAC = [
                // Pemeliharaan
                ['pertanyaan' => 'Unit AC menyala dan berfungsi dengan normal', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 1],
                ['pertanyaan' => 'Suhu udara sesuai pengaturan', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 2],
                ['pertanyaan' => 'Filter udara dibersihkan atau diganti', 'tipe_jawaban' => 'Sudah/Belum', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 3],
                ['pertanyaan' => 'Evaporator coil bersih dan tidak berdebu', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 4],
                ['pertanyaan' => 'Kondensor coil bersih (unit outdoor)', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 5],
                ['pertanyaan' => 'Drainase air lancar, tidak ada kebocoran atau air menetes di dalam', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 6],
                ['pertanyaan' => 'Kipas indoor dan outdoor berfungsi normal', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 7],
                ['pertanyaan' => 'Tidak ada suara bising/tidak normal', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 8],
                ['pertanyaan' => 'Cek tekanan freon (tekanan normal sesuai standar teknis)', 'tipe_jawaban' => 'Normal/Kurang', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 9],
                ['pertanyaan' => 'Pipa instalasi aman dan tidak bocor', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 10],
                ['pertanyaan' => 'Remote control dan panel kontrol berfungsi dengan baik', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 11],
                ['pertanyaan' => 'Tidak ada getaran berlebihan pada unit indoor/outdoor', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 12],
                ['pertanyaan' => 'Konsumsi daya sesuai standar', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 13],
                ['pertanyaan' => 'Timer dan mode otomatis bekerja normal', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 14],

                // Tindak_Lanjut
                ['pertanyaan' => 'Apakah perlu pembersihan menyeluruh?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 101],
                ['pertanyaan' => 'Apakah perlu isi ulang freon?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 102],
                ['pertanyaan' => 'Apakah perlu servis teknisi profesional?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 103],
                ['pertanyaan' => 'Apakah perlu penggantian spare part?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 104],

                // Catatan Kerusakan
                ['pertanyaan' => 'Catatan', 'tipe_jawaban' => 'Teks', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 201],
            ];
            foreach ($listPertanyaanAC as $p) {
                $p['kategori_id'] = $kategoriAC->id_kategori;
                Pertanyaan::create($p);
            }
        }

        // Pertanyaan Lift
        $kategoriLift = Kategori::where('nama_kategori', 'Lift')->first();

        if ($kategoriLift) {
            $listPertanyaanLift = [
                // Pemeliharaan
                ['pertanyaan' => 'Lift dapat naik dan turun dengan lancar', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 1],
                ['pertanyaan' => 'Semua tombol panel berfungsi dengan baik', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 2],
                ['pertanyaan' => 'Indikator lantai dan arah menyala normal', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 3],
                ['pertanyaan' => 'Lampu kabin menyala normal', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 4],
                ['pertanyaan' => 'Pintu lift membuka dan menutup sempurna tanpa hambatan', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 5],
                ['pertanyaan' => 'Sensor pintu bekerja (pintu terbuka kembali jika terhalang)', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 6],
                ['pertanyaan' => 'Alarm darurat berfungsi (dengan uji coba)', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 7],
                ['pertanyaan' => 'Komunikasi interkom ke ruang kontrol berfungsi', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 8],
                ['pertanyaan' => 'Tidak ada suara bising atau getaran tidak wajar saat beroperasi', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 9],
                ['pertanyaan' => 'Lantai kabin bersih dan tidak licin', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 10],
                ['pertanyaan' => 'Pengunci pintu lantai berfungsi dan tidak longgar', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 11],
                ['pertanyaan' => 'Emergency light (lampu darurat) berfungsi saat simulasi mati listrik', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 12],
                ['pertanyaan' => 'Panel kontrol utama bebas dari debu dan lembap', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 13],
                ['pertanyaan' => 'pemeriksaan kondisi kabel sling dan guide rail', 'tipe_jawaban' => 'Baik/Perlu Servis', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 14],
                ['pertanyaan' => 'Pelumasan mekanik lift dilakukan sesuai jadwal', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 15],
                ['pertanyaan' => 'Pengujian sistem rem dan perlambatan darurat', 'tipe_jawaban' => 'Sudah/Belum', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 16],
                ['pertanyaan' => 'Cek baterai backup (jika ada)', 'tipe_jawaban' => 'Normal/Lemah', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 17],
                ['pertanyaan' => 'Lift berhenti tepat di level lantai', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 18],
                ['pertanyaan' => 'Sistem proteksi beban lebih (overload) bekerja dengan benar', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 19],
                ['pertanyaan' => 'Sertifikat laik operasi masih berlaku', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 20],

                // Tindak_Lanjut
                ['pertanyaan' => 'Apakah perlu perbaikan mekanik?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 101],
                ['pertanyaan' => 'Apakah perlu servis kelistrikan?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 102],
                ['pertanyaan' => 'Apakah perlu penggantian suku cadang?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 103],
                ['pertanyaan' => 'Apakah perlu inspeksi pihak ketiga?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 104],

                // Catatan Kerusakan
                ['pertanyaan' => 'Catatan', 'tipe_jawaban' => 'Teks', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 201],
            ];
            foreach ($listPertanyaanLift as $p) {
                $p['kategori_id'] = $kategoriLift->id_kategori;
                Pertanyaan::create($p);
            }
        }

        // Pertanyaan Genset
        $kategoriGenset = Kategori::where('nama_kategori', 'Genset')->first();

        if ($kategoriGenset) {
            $listPertanyaanGenset = [
                // Pemeliharaan
                ['pertanyaan' => 'Genset dapat dinyalakan dan dimatikan dengan baik', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 1],
                ['pertanyaan' => 'Pemeriksaan level oli mesin', 'tipe_jawaban' => 'Normal/Kurang', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 2],
                ['pertanyaan' => 'Pemeriksaan level air radiator', 'tipe_jawaban' => 'Normal/Kurang', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 3],
                ['pertanyaan' => 'Pemeriksaan bahan bakar (solar/bensin/gas)', 'tipe_jawaban' => 'Cukup/Kurang', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 4],
                ['pertanyaan' => 'Tidak ada kebocoran oli, bahan bakar, atau air', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 5],
                ['pertanyaan' => 'Aki dalam kondisi baik dan terisi penuh', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 6],
                ['pertanyaan' => 'Charger aki berfungsi dengan baik', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 7],
                ['pertanyaan' => 'Panel kontrol bekerja normal (indikator, volt, Hz, ampere)', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 8],
                ['pertanyaan' => 'Tegangan output sesuai spesifikasi', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 9],
                ['pertanyaan' => 'Frekuensi (Hz) sesuai standar (50/60 Hz)', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 10],
                ['pertanyaan' => 'Pemeriksaan kondisi kabel dan koneksi', 'tipe_jawaban' => 'Baik/Perlu Perbaikan', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 11],
                ['pertanyaan' => 'Suara mesin normal, tanpa suara aneh atau getaran berlebih', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 12],
                ['pertanyaan' => 'Sistem pendingin (kipas & radiator) berfungsi baik', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 13],
                ['pertanyaan' => 'Pemeriksaan dan pengencangan baut/mur', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 14],
                ['pertanyaan' => 'Filter udara bersih atau diganti jika perlu', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 15],
                ['pertanyaan' => 'Pemeriksaan knalpot, tidak bocor dan tidak tersumbat', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 16],
                ['pertanyaan' => 'Jam operasi tercatat (untuk jadwal servis berkala)', 'tipe_jawaban' => 'Teks', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 17],
                ['pertanyaan' => 'Sistem ATS (jika ada) berfungsi dan melakukan switching otomatis', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'pemeliharaan', 'urutan' => 18],

                // Tugas
                ['pertanyaan' => 'Ganti oli mesin', 'tipe_jawaban' => 'Sudah/Belum', 'jenis_pertanyaan' => 'tugas', 'urutan' => 51],
                ['pertanyaan' => 'Ganti filter oli', 'tipe_jawaban' => 'Sudah/Belum', 'jenis_pertanyaan' => 'tugas', 'urutan' => 52],
                ['pertanyaan' => 'Ganti filter bahan bakar', 'tipe_jawaban' => 'Sudah/Belum', 'jenis_pertanyaan' => 'tugas', 'urutan' => 53],
                ['pertanyaan' => 'Ganti filter udara', 'tipe_jawaban' => 'Sudah/Belum', 'jenis_pertanyaan' => 'tugas', 'urutan' => 54],
                ['pertanyaan' => 'Flushing radiator (jika diperlukan)', 'tipe_jawaban' => 'Sudah/Belum', 'jenis_pertanyaan' => 'tugas', 'urutan' => 55],

                // Tindak_Lanjut
                ['pertanyaan' => 'Apakah perlu pengisian bahan bakar?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 101],
                ['pertanyaan' => 'Apakah perlu penggantian oli/filter?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 102],
                ['pertanyaan' => 'Apakkah perlu servis teknisi profesional?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 103],
                ['pertanyaan' => 'Apakah perlu pengecekan ATS/manual switch?', 'tipe_jawaban' => 'Ya/Tidak', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 104],

                // Catatan Kerusakan
                ['pertanyaan' => 'Catatan', 'tipe_jawaban' => 'Teks', 'jenis_pertanyaan' => 'tindak_lanjut', 'urutan' => 201],
            ];
            foreach ($listPertanyaanGenset as $p) {
                $p['kategori_id'] = $kategoriGenset->id_kategori;
                Pertanyaan::create($p);
            }
        }
    }
}
