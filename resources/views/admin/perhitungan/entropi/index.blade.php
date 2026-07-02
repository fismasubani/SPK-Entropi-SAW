@extends('layouts.app')
@section('title', 'SPK Entropi-SAW | Perhitungan Entropi')

@section('content')

@if (isset($message))
    <div class="alert alert-warning">
        {{ $message }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- PENILAIAN ALTERNATIF -->
<div class="card shadow mb-4">
    <a href="#penilaian" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="penilaian">
        <h5 class="m-0 font-weight-bold text-primary">Tahap Penilaian Alternatif</h5>
    </a>
    <div class="collapse show" id="penilaian">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-left align-middle">Nama Alternatif</th>
                            @foreach($kriteria as $k)
                                <th class="text-center align-middle">{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($alternatif as $alt)
                        <tr>
                            <td class="align-middle">{{ $alt->nama_alternatif }}</td>
                            @if(count($alt->penilaian) > 0)
                                @foreach($alt->penilaian as $value)
                                    <td class="text-center align-middle">{{ $value->crips->bobot }}</td>
                                @endforeach
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($kriteria) + 1 }}" class="text-center">Tidak ada data penilaian</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- NORMALISASI -->
<div class="card shadow mb-4">
    <a href="#normalisasi" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="normalisasi">
        <h5 class="m-0 font-weight-bold text-primary">Tahap Normalisasi (ENTROPI)</h5>
    </a>
    <div class="collapse show" id="normalisasi">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-left align-middle">Nama Alternatif</th>
                            @foreach($kriteria as $k)
                                <th class="text-center align-middle">{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($normalisasi as $namaAlternatif => $nilaiAlternatif)
                            <tr>
                                <td class="align-middle">{{ $namaAlternatif }}</td>
                                @foreach($kriteria as $k)
                                    <td class="text-center align-middle">{{ number_format($nilaiAlternatif[$k->id] ?? 0, 2) }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($kriteria) + 1 }}" class="text-center">Tidak ada data normalisasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> 
    </div> 
</div>

<!-- PROPORSI -->
<div class="card shadow mb-4">
    <a href="#proporsi" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="proporsi">
        <h5 class="m-0 font-weight-bold text-primary">Tahap Proyeksi</h5>
    </a>
    <div class="collapse show" id="proporsi">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-left align-middle">Nama Alternatif</th>
                            @foreach($kriteria as $k)
                                <th class="text-center align-middle">{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proporsi as $namaAlternatif => $nilaiAlternatif)
                            <tr>
                                <td class="align-middle">{{ $namaAlternatif }}</td>
                                @foreach($kriteria as $k)
                                    <td class="text-center align-middle">{{ number_format($nilaiAlternatif[$k->id] ?? 0, 6) }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($kriteria) + 1 }}" class="text-center">Tidak ada data proyeksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> 
    </div> 
</div>

<!-- ENTROPI -->
<div class="card shadow mb-4">
    <a href="#entropi" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="entropi">
        <h5 class="m-0 font-weight-bold text-primary">Tahap Perhitungan Entropi</h5>
    </a>
    <div class="collapse show" id="entropi">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-left align-middle">Alternatif</th>
                            @foreach ($kriteria as $kri)
                                <th class="text-center align-middle">{{ $kri->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pijLnPij as $namaAlternatif => $nilaiAlternatif)
                            <tr>
                                <td class="align-middle">{{ $namaAlternatif }}</td>
                                @foreach ($kriteria as $kri)
                                    <td class="text-center align-middle">{{ number_format($nilaiAlternatif[$kri->id] ?? 0, 6) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-left align-middle">Total</td>
                            @foreach ($kriteria as $kri)
                                <td class="text-center align-middle">{{ number_format($totalPijLnPij[$kri->id] ?? 0, 6) }}</td>
                            @endforeach
                        </tr>
                        <tr class="font-weight-bold">
                            <td class="text-left align-middle">Entropi</td>
                            @foreach ($kriteria as $kri)
                                <td class="text-center align-middle">{{ number_format($entropi[$kri->id] ?? 0, 6) }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DISPERSI -->
<div class="card shadow mb-4">
    <a href="#dispersi" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="dispersi">
        <h5 class="m-0 font-weight-bold text-primary">Tahap Perhitungan Dispersi</h5>
    </a>
    <div class="collapse show" id="dispersi">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            @foreach($kriteria as $k)
                                <th class="text-center align-middle">{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($kriteria as $k)
                                <td class="text-center align-middle">{{ number_format($dispersi[$k->id], 6) }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- BOBOT -->
<div class="card shadow mb-4">
    <a href="#bobot" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="bobot">
        <h5 class="m-0 font-weight-bold text-primary">Tahap Perhitungan Bobot</h5>
    </a>
    <div class="collapse show" id="bobot">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            @foreach($kriteria as $k)
                                <th class="text-center align-middle">{{ $k->nama_kriteria }}</th>
                            @endforeach
                            <th class="text-center align-middle">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($kriteria as $k)
                                <td class="text-center align-middle">{{ number_format($bobot[$k->id], 6) }}</td>
                            @endforeach
                            <td class="text-center align-middle">{{ number_format(array_sum($bobot), 6) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tombol Gunakan Bobot -->
<div class="mt-3">
    <form action="{{ route('entropi.simpanBobot') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            Gunakan Bobot
        </button>
    </form>
</div>

@endsection