@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Panduan Penggunaan Sistem</div>

                <div class="card-body">
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
    </div>
</div>
@endsection
