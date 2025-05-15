@extends('layouts.app')
@section('css')
<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
                            <li>Menggunakan tombol <strong>Cetak PDF</strong> untuk mencetak laporan hasil metode Entropi-SAW.</li>
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
                <a href="#riwayatCollapse" class="d-block card-header py-3 text-decoration-none" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="riwayatCollapse">
                    <h5 class="m-0 font-weight-bold text-success">📊 Riwayat Hasil Perhitungan</h5>
                </a>
                <div class="collapse show" id="riwayatCollapse">
                    <div class="card-body">
                        @if($riwayat->isEmpty())
                            <div class="alert alert-warning">
                                Belum ada riwayat hasil perhitungan yang disimpan.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Perhitungan</th>
                                            <th>Metode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($riwayat as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                                <td>{{ $item->nama_perhitungan ? strtoupper($item->nama_perhitungan) : '-' }}</td>
                                                <td>{{ $item->metode ? strtoupper($item->metode) : '-' }}</td>
                                                <td class="justify-content-start" style="white-space: nowrap;">
                                                    <a href="{{ route('dashboard.show', $item->id) }}" class="btn btn-info btn-sm mr-2">Lihat</a>
                                                    <a href="{{ route('dashboard.cetak', $item->id) }}" class="btn btn-secondary btn-sm mr-2" target="_blank">Cetak</a>
                                                    <form action="{{ route('dashboard.destroy', $item->id) }}" method="POST" class="d-inline form-hapus" >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
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
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.table').DataTable();

        // Intersep form hapus
        $('.form-hapus').on('submit', function(e){
            e.preventDefault(); // cegah submit langsung

            const form = this;

            swal({
                title: "Apa kamu yakin?",
                text: "Sekali kamu hapus, data tidak bisa dipulihkan!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit(); // lanjutkan submit form
                } else {
                    swal("Data aman!");
                }
            });
        });
    });
</script>
@stop