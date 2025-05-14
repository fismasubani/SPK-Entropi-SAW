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
        // Ambil data yang sama seperti di index
        $alternatif = Alternatif::with('penilaian.crips')->get();
        $kriteria = Kriteria::with('crips')->orderBy('id', 'ASC')->get();
        $penilaian = Penilaian::with('crips', 'alternatif')->get();

        // Jika tidak ada data penilaian, langsung tampilkan view dengan pesan
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

        // Inisialisasi array kosong untuk minMax
        $minMax = [];

        // Mencari nilai min/max per kriteria
        foreach ($kriteria as $value) {
            foreach ($penilaian as $value_1) {
                if ($value->id == $value_1->crips->kriteria_id) {
                    $minMax[$value->id][] = $value_1->crips->bobot;
                }
            }
        }

        // Inisialisasi array kosong untuk normalisasi
        $normalisasi = [];

        // Normalisasi
        foreach ($penilaian as $value_1) {
            foreach ($kriteria as $value) {
                if ($value->id == $value_1->crips->kriteria_id) {
                    $bobot = $value_1->crips->bobot;
                    $max = max($minMax[$value->id] ?? [1]); // Default 1 untuk aman
                    $min = min($minMax[$value->id] ?? [1]); // Default 1 untuk aman

                    if ($value->attribut == 'Benefit') {
                        $normalisasi[$value_1->alternatif->nama_alternatif][$value->id] = $max != 0 ? ($bobot / $max) : 0;
                    } elseif ($value->attribut == 'Cost') {
                        $normalisasi[$value_1->alternatif->nama_alternatif][$value->id] = $bobot != 0 ? ($min / $bobot) : 0;
                    }
                }
            }
        }

        // Inisialisasi array kosong untuk rank
        $rank = [];

        // Perangkingan
        foreach ($normalisasi as $key => $value) {
            foreach ($kriteria as $k) {
                if (isset($value[$k->id])) {
                    $rank[$key][] = $value[$k->id] * $k->bobot;
                }
            }
        }

        // Hitung total skor tiap alternatif
        $ranking = [];
        foreach ($rank as $key => $value) {
            $ranking[$key] = array_sum($value);
        }

        // Urutkan skor dari besar ke kecil
        arsort($ranking);

        // Tambahkan peringkat
        $peringkat = [];
        $i = 1;
        foreach ($ranking as $key => $score) {
            $peringkat[$key] = $i++;
        }

        // Uji Kesesuaian
        $totalSkor = array_sum($ranking);
        $jumlahAlternatif = count($ranking);
        $rataRata = $jumlahAlternatif > 0 ? ($totalSkor / $jumlahAlternatif) : 0;
        $tingkatKesesuaian = 100 - ($rataRata / 100);

        return view('admin.perhitungan.index', compact(
            'alternatif', 'kriteria', 'normalisasi', 'ranking', 'peringkat', 'rataRata', 'tingkatKesesuaian'
        ));
    }

    public function simpanRiwayat(Request $request)
    {
        $request->validate([
            'nama_perhitungan' => 'required|string|max:255',
            'metode' => 'required|in:SAW,Entropi-SAW',
        ]);

        // Ambil data yang sama seperti di index
        $alternatif = Alternatif::with('penilaian.crips')->get();
        $kriteria = Kriteria::with('crips')->orderBy('id', 'ASC')->get();
        $penilaian = Penilaian::with('crips', 'alternatif')->get();

        $minMax = [];
        foreach ($kriteria as $value) {
            foreach ($penilaian as $value_1) {
                if ($value->id == $value_1->crips->kriteria_id) {
                    $minMax[$value->id][] = $value_1->crips->bobot;
                }
            }
        }

        $normalisasi = [];
        foreach ($penilaian as $value_1) {
            foreach ($kriteria as $value) {
                if ($value->id == $value_1->crips->kriteria_id) {
                    $bobot = $value_1->crips->bobot;
                    $max = max($minMax[$value->id] ?? [1]);
                    $min = min($minMax[$value->id] ?? [1]);

                    if ($value->attribut == 'Benefit') {
                        $normalisasi[$value_1->alternatif->nama_alternatif][$value->id] = $max != 0 ? ($bobot / $max) : 0;
                    } elseif ($value->attribut == 'Cost') {
                        $normalisasi[$value_1->alternatif->nama_alternatif][$value->id] = $bobot != 0 ? ($min / $bobot) : 0;
                    }
                }
            }
        }

        $rank = [];
        foreach ($normalisasi as $key => $value) {
            foreach ($kriteria as $k) {
                if (isset($value[$k->id])) {
                    $rank[$key][] = $value[$k->id] * $k->bobot;
                }
            }
        }

        $ranking = [];
        foreach ($rank as $key => $value) {
            $ranking[$key] = array_sum($value);
        }

        arsort($ranking);

        // Buat riwayat_perhitungan
        $riwayat = RiwayatPerhitungan::create([
            'nama_perhitungan' => $request->nama_perhitungan,
            'tanggal_perhitungan' => Carbon::now(),
            'jumlah_alternatif' => count($ranking),
            'kriteria_json' => json_encode($kriteria),
            'metode' => $request->metode,
            'user_id' => Auth::id(),
        ]);

        // Simpan detail
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

        return redirect()->route('perhitungan.index')->with('success', 'Hasil perhitungan berhasil disimpan ke riwayat.');
    }

    public function cetak()
    {
        $alternatif = Alternatif::with('penilaian.crips')->get();
        $kriteria = Kriteria::with('crips')->orderBy('id', 'ASC')->get();
        $penilaian = Penilaian::with('crips', 'alternatif')->get();

        // Jika tidak ada data penilaian, buat PDF kosong dengan pesan
        if ($penilaian->isEmpty()) {
            $pdf = Pdf::loadView('admin.perhitungan.pdf', [
                'alternatif' => [],
                'kriteria' => [],
                'normalisasi' => [],
                'ranking' => [],
                'peringkat' => [],
                'rataRata' => 0,
                'tingkatKesesuaian' => 0,
                'message' => 'Belum ada data penilaian yang tersedia.'
            ]);
            return $pdf->stream('hasil-perhitungan.pdf');
        }

        // Proses sama seperti index()
        $minMax = [];
        foreach ($kriteria as $value) {
            foreach ($penilaian as $value_1) {
                if ($value->id == $value_1->crips->kriteria_id) {
                    $minMax[$value->id][] = $value_1->crips->bobot;
                }
            }
        }

        $normalisasi = [];
        foreach ($penilaian as $value_1) {
            foreach ($kriteria as $value) {
                if ($value->id == $value_1->crips->kriteria_id) {
                    $bobot = $value_1->crips->bobot;
                    $max = max($minMax[$value->id] ?? [1]);
                    $min = min($minMax[$value->id] ?? [1]);

                    if ($value->attribut == 'Benefit') {
                        $normalisasi[$value_1->alternatif->nama_alternatif][$value->id] = $max != 0 ? ($bobot / $max) : 0;
                    } elseif ($value->attribut == 'Cost') {
                        $normalisasi[$value_1->alternatif->nama_alternatif][$value->id] = $bobot != 0 ? ($min / $bobot) : 0;
                    }
                }
            }
        }

        $rank = [];
        foreach ($normalisasi as $key => $value) {
            foreach ($kriteria as $k) {
                if (isset($value[$k->id])) {
                    $rank[$key][] = $value[$k->id] * $k->bobot;
                }
            }
        }

        $ranking = [];
        foreach ($rank as $key => $value) {
            $ranking[$key] = array_sum($value);
        }

        arsort($ranking);

        $peringkat = [];
        $i = 1;
        foreach ($ranking as $key => $score) {
            $peringkat[$key] = $i++;
        }

        $totalSkor = array_sum($ranking);
        $jumlahAlternatif = count($ranking);
        $rataRata = $jumlahAlternatif > 0 ? ($totalSkor / $jumlahAlternatif) : 0;
        $tingkatKesesuaian = 100 - ($rataRata / 100);

        // Load PDF
        $pdf = Pdf::loadView('admin.perhitungan.pdf', compact(
            'alternatif', 'kriteria', 'normalisasi', 'ranking', 'peringkat', 'rataRata', 'tingkatKesesuaian'
        ));

        return $pdf->stream('hasil-perhitungan.pdf');
    }


}