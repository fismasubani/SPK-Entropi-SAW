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

        // Jika tidak ada data penilaian
        if ($penilaian->isEmpty()) {
            return view('admin.perhitungan.entropi.index', [
                'kriteria' => [],
                'alternatif' => [],
                'normalisasi' => [],
                'totalNormalisasiPerKriteria' => [],
                'proporsi' => [],
                'pijLnPij' => [],
                'totalPijLnPij' => [],
                'entropi' => [],
                'dispersi' => [],
                'bobot' => [],
                'message' => 'Belum ada data penilaian yang tersedia.'
            ]);
        }

        // Tahap Normalisasi (Entropi)
        // Step 1: Hitung min/max per kriteria untuk normalisasi
        $minMax = [];
        foreach ($penilaian as $pen) {
            if ($pen->crips) {
                $minMax[$pen->crips->kriteria_id][] = $pen->crips->bobot;
            }
        }

        // Step 2: Normalisasi matriks (gunakan nilai maksimum)
        $normalisasi = [];
        foreach ($penilaian as $pen) {
            if ($pen->crips) {
                $kriteriaId = $pen->crips->kriteria_id;
                $max = max($minMax[$kriteriaId] ?? [1]);
                $normalisasi[$pen->alternatif->nama_alternatif][$kriteriaId] = $pen->crips->bobot / $max;
            }
        }

        // Tahap Proporsi/Proyeksi
        // Step 3: Hitung total normalisasi per kriteria
        $totalNormalisasiPerKriteria = [];
        foreach ($kriteria as $krit) {
            $total = 0;
            foreach ($normalisasi as $altValues) {
                $total += $altValues[$krit->id] ?? 0;
            }
            $totalNormalisasiPerKriteria[$krit->id] = $total;
        }

        // Step 4: Hitung proporsi (p_ij)
        $proporsi = [];
        foreach ($normalisasi as $altName => $altValues) {
            foreach ($altValues as $kritId => $value) {
                $total = $totalNormalisasiPerKriteria[$kritId] ?? 1;
                $proporsi[$altName][$kritId] = $value / $total;
            }
        }

        // Tahap Entropi
        // Step 5: Hitung p_ij * ln(p_ij) (gunakan log() natural)
        $pijLnPij = [];
        foreach ($proporsi as $altName => $altValues) {
            foreach ($altValues as $kritId => $pij) {
                $pijLnPij[$altName][$kritId] = ($pij > 0) ? $pij * log($pij) : 0;
            }
        }

        // Step 6: Hitung total p_ij * ln(p_ij) per kriteria
        $totalPijLnPij = [];
        foreach ($kriteria as $krit) {
            $total = 0;
            foreach ($pijLnPij as $altValues) {
                $total += $altValues[$krit->id] ?? 0;
            }
            $totalPijLnPij[$krit->id] = $total;
        }

        // Step 7: Hitung entropi (e_j)
        $n = max(count($alternatif), 1); // Pastikan n >= 1
        $entropi = [];
        foreach ($totalPijLnPij as $kritId => $total) {
            $entropi[$kritId] = ($n > 1) ? (-1 / log($n)) * $total : 0;
        }

        // Tahap Dispersi
        // Step 8: Hitung dispersi (d_j = 1 - e_j)
        $dispersi = [];
        foreach ($entropi as $kritId => $ej) {
            $dispersi[$kritId] = 1 - $ej;
        }

        // Tahap Bobot
        // Step 9: Hitung bobot (w_j = d_j / total dispersi)
        $totalDispersi = array_sum($dispersi);
        $bobot = [];
        foreach ($dispersi as $kritId => $dj) {
            $bobot[$kritId] = ($totalDispersi != 0) ? $dj / $totalDispersi : 0;
        }

        // Simpan bobot ke session
        session(['bobot' => $bobot]);

        return view('admin.perhitungan.entropi.index', compact(
            'kriteria',
            'alternatif',
            'penilaian',
            'normalisasi',
            'totalNormalisasiPerKriteria',
            'proporsi',
            'pijLnPij',
            'totalPijLnPij',
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