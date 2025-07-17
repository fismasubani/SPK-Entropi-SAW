<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Alternatif;
use App\Kriteria;
use App\Penilaian;
use App\RiwayatPerhitungan;
use App\RiwayatDetail;
use Carbon\Carbon;
use PDF;

class AlgoritmaController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $alternatif = Alternatif::with('penilaian.crips')->get();
        $kriteria = Kriteria::with('crips')->orderBy('id', 'ASC')->get();
        $penilaian = Penilaian::with('crips', 'alternatif')->get();

        if ($penilaian->isEmpty()) {
            return view('admin.perhitungan.index', [
                'alternatif' => [],
                'kriteria' => [],
                'normalisasi' => [],
                'ranking' => [],
                'peringkat' => [],
                'rataRata' => 0,
                'tingkatKesesuaian' => 0,
                'message' => 'Belum ada data penilaian yang tersedia.'
            ]);
        }

        // Tahap Normalisasi
        // 1. Cari min/max dengan presisi tinggi
        $minMax = [];
        foreach ($kriteria as $krit) {
            $values = [];
            foreach ($penilaian as $pen) {
                if ($pen->crips && $pen->crips->kriteria_id == $krit->id) {
                    $values[] = (float)$pen->crips->bobot;
                }
            }
            $minMax[$krit->id] = [
                'min' => !empty($values) ? min($values) : 0,
                'max' => !empty($values) ? max($values) : 0
            ];
        }

        // 2. Normalisasi dengan handling pembagian nol
        $normalisasi = [];
        foreach ($penilaian as $pen) {
            if (!$pen->crips) continue;

            $kritId = $pen->crips->kriteria_id;
            $bobot = (float)$pen->crips->bobot;
            $kriteriaData = $kriteria->firstWhere('id', $kritId);

            if ($kriteriaData->attribut == 'Benefit') {
                $max = $minMax[$kritId]['max'] ?? 1;
                $normalisasi[$pen->alternatif->nama_alternatif][$kritId] = 
                    ($max != 0) ? round($bobot / $max, 10) : 0;
            } else { // Cost
                $min = $minMax[$kritId]['min'] ?? 1;
                $normalisasi[$pen->alternatif->nama_alternatif][$kritId] = 
                    ($bobot != 0) ? round($min / $bobot, 10) : 0;
            }
        }

        // Tahap Preferensi
        // 3. Perhitungan ranking dengan presisi tinggi
        $ranking = [];
        foreach ($normalisasi as $altName => $kritValues) {
            $total = 0;
            foreach ($kriteria as $krit) {
                if (isset($kritValues[$krit->id])) {
                    $total += round($kritValues[$krit->id] * (float)$krit->bobot, 10);
                }
            }
            $ranking[$altName] = $total;
        }

        // 4. Pengurutan ranking
        arsort($ranking);
        $peringkat = array_flip(array_keys($ranking)); // Dimulai dari 0
        $peringkat = array_map(fn($x) => $x + 1, $peringkat); // Ubah ke mulai dari 1

        // 5. Hitung uji kesesuaian
        $jumlahAlternatif = count($ranking);
        // Hitung rata-rata skor dengan presisi tinggi
        $totalSkor = array_sum($ranking);
        $jumlahAlternatif = count($ranking);
        $rataRata = ($jumlahAlternatif > 0) ? round($totalSkor / $jumlahAlternatif, 6) : 0;

        // Jika ingin persentase deviasi dari skor ideal (1.0)
        $tingkatKesesuaian = 100 - ($rataRata / 100) * 100;

        return view('admin.perhitungan.index', compact(
            'alternatif', 'kriteria', 'normalisasi', 'ranking', 'peringkat', 
            'rataRata', 'tingkatKesesuaian'
        ));
    }

    public function simpanRiwayat(Request $request)
    {
        $request->validate([
            'nama_perhitungan' => 'required|string|max:255',
            'metode' => 'required|in:Entropi-SAW,SAW',
        ]);

        // Ambil data alternatif, kriteria, dan penilaian
        $alternatif = Alternatif::with('penilaian.crips')->get();
        $kriteria = Kriteria::with('crips')->orderBy('id', 'ASC')->get();
        $penilaian = Penilaian::with('crips', 'alternatif')->get();

        // Jika tidak ada data penilaian, kembalikan error
        if ($penilaian->isEmpty()) {
            return redirect()->route('perhitungan.index')->with('error', 'Belum ada data penilaian.');
        }

        // 1. Hitung min/max per kriteria (presisi tinggi seperti di index terbaru)
        $minMax = [];
        foreach ($kriteria as $krit) {
            $values = [];
            foreach ($penilaian as $pen) {
                if ($pen->crips && $pen->crips->kriteria_id == $krit->id) {
                    $values[] = (float)$pen->crips->bobot;
                }
            }
            $minMax[$krit->id] = [
                'min' => !empty($values) ? min($values) : 0,
                'max' => !empty($values) ? max($values) : 0
            ];
        }

        // 2. Normalisasi dengan rounding (seperti di index terbaru)
        $normalisasi = [];
        foreach ($penilaian as $pen) {
            if (!$pen->crips) continue; // Skip jika crips tidak ada

            $kritId = $pen->crips->kriteria_id;
            $bobot = (float)$pen->crips->bobot;
            $kriteriaData = $kriteria->firstWhere('id', $kritId);

            if ($kriteriaData->attribut == 'Benefit') {
                $max = $minMax[$kritId]['max'] ?? 1;
                $normalisasi[$pen->alternatif->nama_alternatif][$kritId] = 
                    ($max != 0) ? round($bobot / $max, 10) : 0;
            } else { // Cost
                $min = $minMax[$kritId]['min'] ?? 1;
                $normalisasi[$pen->alternatif->nama_alternatif][$kritId] = 
                    ($bobot != 0) ? round($min / $bobot, 10) : 0;
            }
        }

        // 3. Hitung ranking (dengan rounding seperti di index terbaru)
        $ranking = [];
        foreach ($normalisasi as $altName => $kritValues) {
            $total = 0;
            foreach ($kriteria as $krit) {
                if (isset($kritValues[$krit->id])) {
                    $total += round($kritValues[$krit->id] * (float)$krit->bobot, 10);
                }
            }
            $ranking[$altName] = $total;
        }

        // Urutkan dari skor tertinggi ke terendah
        arsort($ranking);

        // Simpan ke tabel riwayat_perhitungan (TANPA rata_rata_skor dan tingkat_kesesuaian)
        $riwayat = RiwayatPerhitungan::create([
            'nama_perhitungan' => $request->nama_perhitungan,
            'tanggal_perhitungan' => Carbon::now(),
            'jumlah_alternatif' => count($ranking),
            'kriteria_json' => json_encode($kriteria),
            'metode' => $request->metode,
            'user_id' => Auth::id(),
        ]);

        // Simpan detail peringkat
        $peringkat = 1;
        foreach ($ranking as $nama => $skor) {
            RiwayatDetail::create([
                'riwayat_id' => $riwayat->id,
                'nama_alternatif' => $nama,
                'nilai_kriteria_json' => json_encode($normalisasi[$nama]),
                'skor_akhir' => $skor,
                'peringkat' => $peringkat++,
            ]);
        }

        return redirect()->route('perhitungan.index')->with('success', 'Hasil perhitungan berhasil disimpan.');
    }

}