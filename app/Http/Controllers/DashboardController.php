<?php

namespace App\Http\Controllers;

use App\RiwayatPerhitungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class DashboardController extends Controller
{
    // Menampilkan halaman dashboard utama + riwayat
    public function index()
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8');

        $riwayat = RiwayatPerhitungan::with('user')->latest()->get();
        return view('admin.dashboard.index', compact('riwayat')); // Pastikan file resources/views/dashboard.blade.php ada
    }

    // Menampilkan detail riwayat
    public function show($id)
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8');

        $data = RiwayatPerhitungan::with(['user', 'detail'])->findOrFail($id);
        $kriteria = collect(json_decode($data->kriteria_json));
        $normalisasi = [];
        foreach ($data->detail as $d) {
            $normalisasi[$d->nama_alternatif] = json_decode($d->nilai_kriteria_json, true);
        }

        return view('admin.dashboard.show', compact('data', 'kriteria', 'normalisasi'));

    }

    public function updateInfo(Request $request, $id)
    {
        $request->validate([
            'nama_perhitungan' => 'required|string|max:255',
            'metode' => 'required|in:entropi-saw,saw',
        ]);

        $riwayat = RiwayatPerhitungan::findOrFail($id);
        $riwayat->nama_perhitungan = $request->nama_perhitungan;
        $riwayat->metode = $request->metode;
        // Tambahkan pengguna yang memperbarui data ini
        $riwayat->user_id = auth()->id();
        $riwayat->save();

        return redirect()->back()->with('success', 'Nama perhitungan dan metode berhasil diperbarui.');
    }


    // Menghapus riwayat dan relasi detail
    public function destroy($id)
    {
        $riwayat = RiwayatPerhitungan::with('detail')->findOrFail($id);
        $riwayat->detail()->delete(); // Hapus semua detail
        $riwayat->delete(); // Hapus riwayat utama
        return redirect()->route('riwayat-perhitungan.index')->with('success', 'Riwayat berhasil dihapus.');
    }

    // Mencetak PDF
    public function cetakPDF($id)
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8');

        $data = RiwayatPerhitungan::with(['user', 'detail'])->findOrFail($id);
        $kriteria = collect(json_decode($data->kriteria_json));
        $normalisasi = [];
        foreach ($data->detail as $d) {
            $normalisasi[$d->nama_alternatif] = json_decode($d->nilai_kriteria_json, true);
        }

        $pdf = PDF::loadView('admin.dashboard.pdf', compact('data','kriteria', 'normalisasi'))->setPaper('a4', 'portrait');
        return $pdf->stream('LAPORAN HASIL PERANGKINGAN '.$data->nama_perhitungan.'.pdf');
    }
}
