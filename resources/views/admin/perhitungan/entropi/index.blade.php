@extends('layouts.app')
@section('title', 'SPK SAW | Perhitungan Entropi')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Perhitungan Entropi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Alternatif</th>
                        @foreach($kriteria as $k)
                            <th>{{ $k->nama_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @foreach($alternatif as $alt)
                <tr>
                    <td>{{ $alt->nama_alternatif }}</td>
                    @if(count($alt->penilaian) > 0)
                        @foreach($alt->penilaian as $value)
                            <td>{{ $value->crips->bobot }}</td>
                        @endforeach
                    @else
                        <td colspan="{{ count($kriteria) }}">Tidak ada nilai</td>  <!-- Menampilkan pesan jika tidak ada penilaian -->
                    @endif
                </tr>
                @endforeach

                <!-- @foreach($alternatif as $alt)
                <tr>
                    <td>{{ $alt->nama_alternatif }}</td>
                    @if(count($alt->penilaian) > 0)
                        @foreach($alt->penilaian as $value)
                            <td>{{ $value->crips->bobot }}</td>
                        @endforeach
                    @endif
                </tr>
                @endforeach -->

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- NORMALISASI -->
<div class="card shadow mb-4">
    <a href="#normalisasi" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Normalisasi (Entropy)</h6>
    </a>
    <div class="collapse show" id="normalisasi">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Alternatif</th>
                            @foreach($kriteria as $k)
                                <th>{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($normalisasi as $namaAlternatif => $nilaiAlternatif)
                            <tr>
                                <td>{{ $namaAlternatif }}</td>
                                @foreach($kriteria as $k)
                                    <td>
                                        {{ number_format($nilaiAlternatif[$k->id] ?? 0, 2) }}
                                    </td>
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

<!-- TABEL PROPORSI -->
<div class="card shadow mb-4">
    <a href="#proporsi" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Proporsi</h6>
    </a>
    <div class="collapse show" id="proporsi">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Alternatif</th>
                            @foreach($kriteria as $k)
                                <th>{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proporsi as $namaAlternatif => $nilaiAlternatif)
                            <tr>
                                <td>{{ $namaAlternatif }}</td>
                                @foreach($kriteria as $k)
                                    <td>{{ number_format($nilaiAlternatif[$k->id] ?? 0, 3) }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($kriteria) + 1 }}" class="text-center">Tidak ada data proporsi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> 
    </div> 
</div>

<!-- TABEL ENTROPI -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Perhitungan Entropi (Pij x ln(Pij))</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        @foreach ($kriteria as $kri)
                            <th>{{ $kri->nama_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pij_ln_pij as $namaAlternatif => $nilaiAlternatif)
                        <tr>
                            <td>{{ $namaAlternatif }}</td>
                            @foreach ($kriteria as $kri)
                                <td>
                                    {{ number_format($nilaiAlternatif[$kri->id] ?? 0, 3) }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    <tr>
                        <td><strong>Total</strong></td>
                        @foreach ($kriteria as $kri)
                            <td><strong>{{ number_format($total_pij_ln_pij[$kri->id] ?? 0, 3) }}</strong></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Entropi</strong></td>
                        @foreach ($kriteria as $kri)
                            <td><strong>{{ number_format($entropi[$kri->id] ?? 0, 3) }}</strong></td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- TABEL DISPERSI -->
<div class="card shadow mb-4">
    <a href="#dispersi" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Perhitungan Dispersi</h6>
    </a>
    <div class="collapse show" id="dispersi">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            @foreach($kriteria as $k)
                                <th>{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($kriteria as $k)
                                <td>
                                    {{ number_format($dispersi[$k->id], 3) }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- TABEL BOBOT -->
<div class="card shadow mb-4">
    <a href="#bobot" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Perhitungan Bobot</h6>
    </a>
    <div class="collapse show" id="bobot">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            @foreach($kriteria as $k)
                                <th>{{ $k->nama_kriteria }}</th>
                            @endforeach
                            <th>Total</th> <!-- Tambahkan kolom header Total -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($kriteria as $k)
                                <td>
                                    {{ number_format($bobot[$k->id], 3) }}
                                </td>
                            @endforeach
                            <td>
                                {{ number_format(array_sum($bobot), 3) }} <!-- Total semua bobot -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- BUTTON -->
<div class="mt-3">
    <form action="{{ route('entropi.simpanBobot') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            Gunakan Bobot
        </button>
    </form>
</div>


@endsection