@extends('layouts.app')
@section('title', 'SPK Entropi-SAW | Penilaian Alternatif')
@section('content')
    <div class="card shadow mb-4">
        <!-- Form membungkus header + konten agar tombol di header tetap submit seluruh input -->
        <form action="{{ route('penilaian.store') }}" method="post" class="m-0">
            @csrf

            <!-- Card Header - Judul + Tombol Simpan -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <a href="#tambahkriteria" class="text-decoration-none" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="tambahkriteria">
                    <h5 class="m-0 font-weight-bold text-primary">Penilaian Alternatif</h5>
                </a>

                <!-- Tombol Simpan (masih bagian dari form) -->
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>

            <!-- Card Content - Collapse -->
            <div class="collapse show" id="tambahkriteria">
                <div class="card-body">
                    @if(Session::has('msg'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Info!</strong> {{ Session::get('msg') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-left align-middle">Nama Alternatif</th>
                                    @foreach($kriteria as $key => $value)
                                        <th class="text-center align-middle">{{ $value->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alternatif as $alt => $valt)
                                    <tr>
                                        <td class="align-middle">{{ $valt->nama_alternatif }}</td>
                                        @if(count($valt->penilaian) > 0)
                                            @foreach($kriteria as $key => $value)
                                                <td class="text-center align-middle">
                                                    <select name="crips_id[{{$valt->id}}][]" class="form-control form-control-sm">
                                                        @foreach($value->crips as $v_1)
                                                            <option value="{{ $v_1->id }}" 
                                                                {{ isset($valt->penilaian[$key]) && $v_1->id == $valt->penilaian[$key]->crips_id ? 'selected' : '' }}>
                                                                {{ $v_1->nama_crips }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @endforeach
                                        @else
                                            @foreach($kriteria as $key => $value)
                                                <td class="text-center align-middle">
                                                    <select name="crips_id[{{$valt->id}}][]" class="form-control form-control-sm">
                                                        @foreach($value->crips as $v_1)
                                                            <option value="{{ $v_1->id }}">{{ $v_1->nama_crips }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @endforeach
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($kriteria) + 1 }}" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </form>
    </div>
@stop
