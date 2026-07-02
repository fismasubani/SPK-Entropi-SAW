@extends('layouts.app')
@section('title', 'SPK SAW | Alternatif')
@section('css')
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
    <!-- Kolom Kiri: Form Manual & Form Import -->
    <div class="col-md-4">
        <!-- Card: Tambah Manual -->
        <div class="card shadow mb-4">
            <a href="#tambahalternatif" class="d-block card-header py-3" data-toggle="collapse">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Data Alternatif</h5>
            </a>
            <div class="collapse show" id="tambahalternatif">
                <div class="card-body">
                    @if(session('success_manual'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Info!</strong> {{ session('success_manual') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif
                    <form action="{{ route('alternatif.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama Alternatif</label>
                            <input type="text" class="form-control @error('nama_alternatif') is-invalid @enderror"
                                   name="nama_alternatif" value="{{ old('nama_alternatif') }}">
                            @error('nama_alternatif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-sm btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Card: Import Excel -->
        <div class="card shadow mb-4">
            <a href="#importalternatif" class="d-block card-header py-3" data-toggle="collapse">
                <h5 class="m-0 font-weight-bold text-primary">Impor Data Alternatif</h5>
            </a>
            <div class="collapse show" id="importalternatif">
                <div class="card-body">
                    @if(session('success_import'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Info!</strong> {{ session('success_import') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif
                    <form action="{{ route('alternatif.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Pilih File Data Alternatif</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('file') is-invalid @enderror"
                                       id="customFile" name="file" required>
                                <label class="custom-file-label" for="customFile">Format File (.xlsx/.xls/.csv)</label>
                                @error('file')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success">Impor</button>
                        <a href="{{ asset('template/alternatif_template.xlsx') }}" class="btn btn-sm btn-secondary">Download Template</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Tabel Alternatif -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">Daftar Data Alternatif</h5>
                <form action="{{ route('alternatif.deleteAll') }}" method="POST" id="form-delete-all">
                    @csrf
                    <button type="button" class="btn btn-sm btn-outline-danger" id="btn-delete-all">
                        <i class="fa fa-trash"></i> Hapus Semua
                    </button>
                </form>
            </div>

            <div class="collapse show" id="listalternatif">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle" id="DataTable">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Nama Alternatif</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse($alternatif as $row)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $row->nama_alternatif }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('alternatif.edit', $row->id) }}" 
                                            class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('alternatif.destroy', $row->id) }}" 
                                            class="btn btn-sm btn-danger hapus" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada data alternatif</td>
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
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(){
        bsCustomFileInput.init();
        $('#DataTable').DataTable();

        // Konfirmasi hapus per item
        $(document).on('click', '.hapus', function(e){
            e.preventDefault();
            var url = $(this).attr('href');

            Swal.fire({
                title: "Apa Anda yakin?",
                text: "Sekali Anda hapus, data tidak bisa dipulihkan kembali!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { '_token': "{{ csrf_token() }}" },
                        success:function() {
                            Swal.fire("Terhapus!", "Data berhasil dihapus.", "success")
                            .then(() => window.location = "{{ route('alternatif.index') }}");
                        },
                        error: function() {
                            Swal.fire("Error!", "Terjadi kesalahan saat menghapus!", "error");
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
        });

        // Konfirmasi hapus semua
        $(document).on('click', '#btn-delete-all', function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Yakin Hapus Semua Data?",
                text: "Semua data alternatif akan dihapus dan tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus semua!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-delete-all').submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Dibatalkan",
                        text: "Data alternatif tetap aman 😊",
                        icon: "info",
                        confirmButtonText: "Oke"
                    });
                }
            });
        });
    });
</script>
@stop
