@extends('layouts.app')
@section('title', 'SPK Entropi-SAW | Dashboard')
@section('css')
<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

<style>
    /* Tombol aksi bulat dan seragam */
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    /* Supaya kolom aksi tidak melebar */
    table td:last-child,
    table th:last-child {
        text-align: center;
        width: 150px !important;
        white-space: nowrap;
    }
</style>
@stop
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
                            <li>Menggunakan tombol <strong>Simpan Hasil</strong> untuk menyimpan hasil perhitungan dengan mengisi nama dan metode perhitungan ke tabel riwayat hasil perhitungan.</li>
                            <li><strong>Riwayat Hasil Perhitungan</strong> digunakan sebagai record data dari hasil perhitungan metode Entropi-SAW atau SAW yang telah dilakukan. Selain itu, terdapat 3 aksi utama: <strong>Lihat</strong>, <strong>Cetak</strong>, dan <strong>Hapus</strong>.</li>
                            <li>Aksi <strong>Lihat</strong> untuk melihat detail riwayat perhitungan yang telah disimpan.</li>
                            <li>Aksi <strong>Cetak</strong> untuk mencetak laporan hasil perhitungan yang telah disimpan.</li>
                            <li>Aksi <strong>Hapus</strong> untuk menghapus riwayat perhitungan yang telah disimpan.</li>
                        </ol>
                        <div class="alert alert-info mt-3" role="alert">
                            💡 <strong>Catatan:</strong> Pastikan semua data telah terisi dengan lengkap sebelum melakukan proses perhitungan.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Riwayat Hasil Perhitungan --}}
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <a href="#riwayatCollapse" class="d-block card-header py-3 text-decoration-none"
                data-toggle="collapse" role="button" aria-expanded="true" aria-controls="riwayatCollapse">
                    <h5 class="m-0 font-weight-bold text-success">
                        📊 Riwayat Hasil Perhitungan
                    </h5>
                </a>

                <!-- Card Content -->
                <div class="collapse show" id="riwayatCollapse">
                    <div class="card-body">
                        @if($riwayat->isEmpty())
                            <div class="alert alert-warning text-center">
                                Belum ada riwayat hasil perhitungan yang disimpan.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover align-middle">
                                    <thead class="thead-dark text-center">
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 15%">Tanggal</th>
                                            <th>Nama Perhitungan</th>
                                            <th style="width: 15%">Metode</th>
                                            <th style="width: 15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($riwayat as $index => $item)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ $item->nama_perhitungan ? strtoupper($item->nama_perhitungan) : '-' }}</td>
                                                <td class="text-center">{{ $item->metode ? strtoupper($item->metode) : '-' }}</td>
                                                <td class="text-center" style="white-space: nowrap;">
                                                    <!-- Tombol Lihat -->
                                                    <a href="{{ route('dashboard.show', $item->id) }}" 
                                                    class="btn btn-icon btn-sm btn-info mx-1" title="Lihat Detail">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <!-- Tombol Cetak -->
                                                    <a href="{{ route('dashboard.cetak', $item->id) }}" 
                                                    class="btn btn-icon btn-sm btn-secondary mx-1" title="Cetak" target="_blank">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('dashboard.destroy', $item->id) }}" 
                                                        method="POST" class="d-inline form-hapus">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon btn-sm btn-danger mx-1" title="Hapus">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
<!-- SweetAlert & DataTables -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(){
        $('.table').DataTable();

        // Intersep form hapus
        $('.form-hapus').on('submit', function(e){
            e.preventDefault(); // cegah submit langsung

            const form = this;

            Swal.fire({
                title: "Apa Anda yakin?",
                text: "Sekali Anda hapus, data tidak bisa dipulihkan kembali!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Oke",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit form kalau klik hapus
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Dibatalkan",
                        text: "Data aman 😊",
                        icon: "info",
                        confirmButtonText: "Oke"
                    });
                }
            });
        });
    });
</script>
@stop