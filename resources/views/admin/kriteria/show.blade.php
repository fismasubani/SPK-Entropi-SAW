@extends('layouts.app')
@section('title', 'SPK Entropi-SAW | '.$kriteria->nama_kriteria)
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
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#tambahcrips" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h5 class="m-0 font-weight-bold text-primary">Tambah Data Sub Krtiteria {{ $kriteria->nama_kriteria }} (Crips)</h5>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="tambahkcrips">
                    <div class="card-body">
                        @if(Session::has('msg'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Info!</strong> {{ Session::get('msg') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <form action="{{ route('crips.store') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $kriteria->id }}" name="kriteria_id">
                            <div class="form-group">
                                <label for="nama">Nama Sub Kriteria (Crips)</label>
                                <input type="text" class="form-control @error('nama_crips') is invalid @enderror" name="nama_crips" value="{{ old('nama_crips') }}">

                                @error('nama_crips')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bobot">Skala</label>
                                <input type="text" class="form-control @error('bobot') is invalid @enderror" name="bobot" value="{{ old('bobot') }}">

                                @error('bobot')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button class="btn btn-sm btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary">
                        Daftar Data Sub Kriteria {{ $kriteria->nama_kriteria }} (Crips)
                    </h5>
                </div>

                <!-- Card Content -->
                <div class="collapse show" id="listkriteria">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle" id="DataTable">
                                <thead class="thead-dark text-center">
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Nama Sub Kriteria (Crips)</th>
                                        <th>Skala</th>
                                        <th style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @forelse($crips as $row)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $row->nama_crips }}</td>
                                            <td class="text-center">{{ $row->bobot }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('crips.edit', $row->id) }}" 
                                                class="btn btn-sm btn-warning mx-1" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ route('crips.destroy', $row->id) }}" 
                                                class="btn btn-sm btn-danger hapus mx-1" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada data sub kriteria</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
@section('js')
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function(){
            $('#DataTable').DataTable();

            $(document).on('click', '.hapus', function(e) {
                e.preventDefault(); // cegah aksi default

                let url = $(this).attr('href');

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
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                '_token': "{{ csrf_token() }}"
                            },
                            success: function() {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Data berhasil dihapus.",
                                    icon: "success",
                                    confirmButtonText: "Oke"
                                }).then(() => {
                                    window.location = "{{ route('kriteria.show', $kriteria->id) }}";
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: "Terjadi kesalahan saat menghapus data.",
                                    icon: "error",
                                    confirmButtonText: "Oke"
                                });
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: "Dibatalkan",
                            text: "Data aman 😊",
                            icon: "info",
                            confirmButtonText: "Oke"
                        });
                    }
                });

                return false;
            });
        });
    </script>
@stop