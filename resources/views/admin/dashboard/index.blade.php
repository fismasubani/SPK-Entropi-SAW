@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h1 class="display-5 fw-bold">Sistem Pendukung Keputusan</h1>
        <p class="lead">Pemilihan Kepala Laboratorium dengan Metode Entropi-SAW (Simple Additive Weighting)</p>
    </div>

    <div class="row">
        {{-- Card Panduan Penggunaan --}}
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <a href="#panduanCollapse" class="d-block card-header py-3 text-decoration-none" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="panduanCollapse">
                    <h5 class="m-0 font-weight-bold text-primary">📘 Panduan Penggunaan Sistem</h5>
                </a>
                <div class="collapse show" id="panduanCollapse">
                    <div class="card-body">
                        <ol class="ps-3">
                            <li>Menambahkan <strong>Data Kriteria</strong>, menentukan jenis attribut, dan memberikan nilai bobot (opsional).</li>
                            <li>Menambahkan <strong>Data Sub Kriteria (Crips)</strong> dan memberikan nilai rentang skala pada setiap kriteria melalui aksi tampilkan.</li> 
                            <li>Menambahkan <strong>Data Alternatif</strong> calon kandidat Kepala Laboratorium</li>
                            <li>Memberikan <strong>Penilaian terhadap Alternatif</strong> pada setiap kriteria berdasarkan data sub kriteria yang telah ditentukan.</li>
                            <li>Membuka menu perhitungan <strong>Metode Entropi</strong> untuk menentukan nilai bobot ideal dan menggunakan tombol <strong>Gunakan Bobot</strong>.</li>
                            <li>Membuka menu perhitungan <strong>Metode SAW</strong> untuk mendapatkan hasil peringkat alternatif terbaik.</li>
                            <li>Menggunakan tombol <strong>Simpan Hasil</strong> untuk menyimpan hasil perhitungan ke tabel riwayat hasil perhitungan.</li>
                            <li>Menggunakan tombol <strong>Cetak PDF</strong> untuk mencetak laporan hasil metode Entropi-SAW.</li>
                            <li><strong>Riwayat Hasil Perhitungan</strong> digunakan sebagai record data dari hasil perhitungan metode Entropi-SAW.</li>
                        </ol>
                        <div class="alert alert-info mt-3" role="alert">
                            💡 <strong>Catatan:</strong> Pastikan semua data telah terisi dengan lengkap sebelum melakukan proses perhitungan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
