@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Selamat Datang di Sistem Pendukung Keputusan</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Panduan Penggunaan Sistem</h5>
            <ol>
                <li>Masukkan data <strong>Kriteria</strong> dan bobotnya.</li>
                <li>Masukkan data <strong>Alternatif</strong> (misalnya kandidat kepala lab).</li>
                <li>Masukkan data <strong>Crips</strong> untuk setiap kriteria.</li>
                <li>Lakukan <strong>Penilaian</strong> untuk setiap alternatif berdasarkan kriteria.</li>
                <li>Buka menu <strong>Perhitungan</strong> untuk melihat hasil perangkingan metode SAW.</li>
                <li>Gunakan tombol <strong>Cetak</strong> untuk mencetak hasil perangkingan ke dalam PDF.</li>
            </ol>
            <p class="mt-3">Pastikan semua data telah lengkap sebelum melakukan perhitungan.</p>
        </div>
    </div>
</div>
@endsection
