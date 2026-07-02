@extends('layouts.app')
@section('title', 'SPK Entropi-SAW | Lihat Detail')
@section('content')

{{-- Informasi Umum Riwayat Perhitungan --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">
            📊 Detail Riwayat Perhitungan
        </h5>
        <span class="badge badge-pill badge-info text-uppercase">
            Metode: {{ strtoupper($data->metode) }}
        </span>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('riwayat-perhitungan.updateInfo', $data->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group row">
                <label for="nama_perhitungan" class="col-sm-3 col-form-label">📝 Nama Perhitungan</label>
                <div class="col-sm-9">
                    <input type="text" name="nama_perhitungan" class="form-control font-weight-bold" value="{{ $data->nama_perhitungan }}" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="metode" class="col-sm-3 col-form-label">🧠 Metode</label>
                <div class="col-sm-9">
                    <select name="metode" class="form-control text-uppercase" required>
                        <option value="entropi-saw" {{ strtolower($data->metode) == 'entropi-saw' ? 'selected' : '' }}>ENTROPI-SAW</option>
                        <option value="saw" {{ strtolower($data->metode) == 'saw' ? 'selected' : '' }}>SAW</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">📌 Jumlah Alternatif</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control-plaintext" readonly value="{{ $data->detail->count() }} Alternatif">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">📅 Tanggal Perhitungan</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control-plaintext" readonly value="{{ \Carbon\Carbon::parse($data->tanggal_perhitungan)->translatedFormat('d F Y') }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">👤 Dibuat oleh</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control-plaintext" readonly value="{{ $data->user->name ?? 'Tidak diketahui' }}">
                </div>
            </div>

            <div class="form-group row mt-3">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Detail Perhitungan --}}
<!-- DETAIL HASIL PERHITUNGAN -->
<div class="card shadow mb-4">
    <a href="#ranking" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="ranking">
        <h5 class="m-0 font-weight-bold text-primary">Detail Hasil Perhitungan</h5>
    </a>
    <div class="collapse show" id="ranking">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" class="text-left align-middle">Alternatif</th>
                            @foreach($kriteria as $k)
                                <th class="text-center align-middle">{{ $k->nama_kriteria }}</th>
                            @endforeach
                            <th rowspan="2" class="text-center align-middle">Total</th>
                            <th rowspan="2" class="text-center align-middle">Rank</th>
                        </tr>
                        <tr>
                            @foreach($kriteria as $k)
                                <th class="text-center align-middle">{{ number_format($k->bobot, 4) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->detail as $d)
                            <tr>
                                <td class="text-left align-middle">{{ $d->nama_alternatif }}</td>
                                @php $total = 0; @endphp
                                @foreach($kriteria as $k)
                                    @php
                                        $nilai = $normalisasi[$d->nama_alternatif][$k->id] ?? 0;
                                        $preferensi = $nilai * $k->bobot;
                                        $total += $preferensi;
                                    @endphp
                                    <td class="text-center align-middle">{{ number_format($preferensi, 5) }}</td>
                                @endforeach
                                <td class="text-center align-middle">{{ number_format($total, 5) }}</td>
                                <td class="text-center align-middle">{{ $d->peringkat }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>   
@endsection
