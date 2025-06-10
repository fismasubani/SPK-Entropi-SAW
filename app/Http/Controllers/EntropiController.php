<?php

namespace App\Http\Controllers;

use App\Kriteria;    
use App\Alternatif;
use App\Penilaian;
use Illuminate\Http\Request;

class EntropiController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::orderBy('id', 'asc')->get();
        $alternatif = Alternatif::with('penilaian.crips')->get();
        $penilaian = Penilaian::all();

        //Jika tidak ada data penilaian, langsung tampilkan view dengan pesan
        if ($penilaian->isEmpty()) {
            return view('admin.perhitungan.entropi.index', [
                'kriteria' => [],
                'alternatif'=> [],
                'penilaian'=> [],
                'normalisasi'=> [],
                'total_normalisasi_per_kriteria'=> [],
                'proporsi'=> [],
                'pij_ln_pij'=> [],
                'total_pij_ln_pij'=> [],
                'entropi'=> [],
                'dispersi'=> [],
                'bobot'=> [],
                'message' => 'Belum ada data penilaian yang tersedia.'
            ]);
        }

        // Ambil semua nilai bobot dulu
        $minMax = [];
        foreach ($penilaian as $pen) {
            if ($pen->crips) {
                $minMax[$pen->crips->kriteria_id][] = $pen->crips->bobot;
            }
        }

        // Normalisasi
        $normalisasi = [];
        foreach ($penilaian as $value_1) {
            foreach ($kriteria as $value) {
                if ($value_1->crips && $value->id == $value_1->crips->kriteria_id) {
                    $max = max($minMax[$value->id] ?? [1]); // fallback ke 1 untuk mencegah pembagian dengan 0
                    $normalisasi[$value_1->alternatif->nama_alternatif][$value->id] = 
                        $value_1->crips->bobot / $max;
                }
            }
        }

        // Hitung total normalisasi per kriteria
        $total_normalisasi_per_kriteria = [];
        foreach ($kriteria as $krit) {
            $id_krit = $krit->id;
            $total = 0;
            foreach ($normalisasi as $alt => $nilai) {
                $total += $nilai[$id_krit] ?? 0;
            }
            $total_normalisasi_per_kriteria[$id_krit] = $total;
        }

        // Hitung proporsi p_ij
        $proporsi = [];
        foreach ($normalisasi as $alt => $nilai) {
            foreach ($nilai as $id_krit => $v) {
                $total = $total_normalisasi_per_kriteria[$id_krit] ?? 1; // fallback ke 1
                $proporsi[$alt][$id_krit] = $v / $total;
            }
        }

        // Hitung p_ij * ln(p_ij)
        $pij_ln_pij = [];
        foreach ($proporsi as $alt => $nilai) {
            foreach ($nilai as $id_krit => $v) {
                $pij_ln_pij[$alt][$id_krit] = ($v > 0) ? $v * log($v) : 0;
            }
        }

        // Hitung total p_ij * ln(p_ij) per kriteria
        $total_pij_ln_pij = [];
        foreach ($kriteria as $krit) {
            $id_krit = $krit->id;
            $total = 0;
            foreach ($pij_ln_pij as $alt => $nilai) {
                $total += $nilai[$id_krit] ?? 0;
            }
            $total_pij_ln_pij[$id_krit] = $total;
        }

        // Hitung entropi per kriteria
        $n = count($alternatif); // jumlah alternatif
        $entropi = [];
        foreach ($total_pij_ln_pij as $id_krit => $total) {
            $entropi[$id_krit] = ($n > 1) ? (-1 / log($n)) * $total : 0;
        }

        // Hitung dispersi
        $dispersi = [];
        foreach ($kriteria as $krit) {
            $id_krit = $krit->id;
            $dispersi[$id_krit] = 1 - ($entropi[$id_krit] ?? 0);
        }

        // Hitung Bobot
        $total_dispersi = array_sum($dispersi);
        $bobot = [];
        foreach ($dispersi as $id_kriteria => $nilai) {
            $bobot[$id_kriteria] = ($total_dispersi != 0) ? $nilai / $total_dispersi : 0;
        }

        session(['bobot' => $bobot]);

        return view('admin.perhitungan.entropi.index', compact(
            'kriteria',
            'alternatif',
            'penilaian',
            'normalisasi',
            'total_normalisasi_per_kriteria',
            'proporsi',
            'pij_ln_pij',
            'total_pij_ln_pij',
            'entropi',
            'dispersi',
            'bobot'
        ));
    }

    public function simpanBobot(Request $request)
    {
        $kriteria = Kriteria::all();
        foreach ($kriteria as $krit) {
            $bobot = session('bobot')[$krit->id] ?? 0;
            $krit->bobot = $bobot;
            $krit->save();
        }

        return redirect()->route('entropi.index')->with('success', 'Bobot berhasil digunakan!');
    }
}