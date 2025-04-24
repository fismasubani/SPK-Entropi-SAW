@extends('layouts.app')
@section('title', 'SPK SAW | Perhitungan SAW')
@section('content')

@section('content')

@if (isset($message))
    <div class="alert alert-warning">
        {{ $message }}
    </div>
@endif

@if (!isset($isPdf))
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('perhitungan.cetak') }}" target="_blank" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Cetak PDF
    </a>
</div>
@endif

<!-- ANALISA -->

<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#tambahkriteria" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Analisa</h6>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show" id="tambahkriteria">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Alternatif</th>
                            @foreach($kriteria as $value)
                                <th>{{ $value->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alternatif as $valt)
                            <tr>
                                <td>{{ $valt->nama_alternatif }}</td>
                                @if(count($valt->penilaian) > 0)
                                    @foreach($valt->penilaian as $value)
                                        <td>{{ $value->crips->bobot }}</td>
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
</div>

<!-- NORMALISASI -->
<div class="card shadow mb-4">
    <a href="#normalisasi" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Normalisasi</h6>
    </a>
    <div class="collapse show" id="normalisasi">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Alternatif / Kriteria</th>
                            @foreach($kriteria as $value)
                                <th>{{ $value->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($normalisasi))
                            @foreach($normalisasi as $alt => $values)
                                <tr>
                                    <td>{{ $alt }}</td>
                                    @foreach($values as $val)
                                        <td>{{ number_format($val, 2) }}</td> <!-- 2 angka koma -->
                                    @endforeach
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ count($kriteria) + 1 }}" class="text-center">Tidak ada data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
</div>

<!-- PERANGKINGAN -->
<div class="card shadow mb-4">
    <a href="#ranking" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Perangkingan</h6>
    </a>
    <div class="collapse show" id="ranking">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2"></th> <!-- Kosong pojok kiri -->
                            @foreach($kriteria as $k)
                                <th>{{ $k->nama_kriteria }}</th>
                            @endforeach
                            <th rowspan="2" style="text-align:center; padding-bottom:45px;">Total</th>
                            <th rowspan="2" style="text-align:center; padding-bottom:45px;">Rank</th>
                        </tr>
                        <tr>
                            @foreach($kriteria as $k)
                                <th>{{ number_format($k->bobot, 4) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($normalisasi))
                            @php
                                // Hitung total skor dulu
                                $ranking = [];
                                foreach($normalisasi as $alt => $values){
                                    $total = 0;
                                    foreach($kriteria as $k){
                                        $bobot = $k->bobot ?? 0;
                                        $nilai_normalisasi = $values[$k->id] ?? 0;
                                        $total += ($nilai_normalisasi * $bobot);
                                    }
                                    $ranking[$alt] = $total;
                                }

                                // Urutkan ranking
                                arsort($ranking);
                                $rank = 1;
                                $ranked = [];
                                foreach($ranking as $alt => $total){
                                    $ranked[$alt] = $rank++;
                                }
                            @endphp

                            @foreach($normalisasi as $alt => $values)
                                <tr>
                                    <td>{{ $alt }}</td>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach($kriteria as $k)
                                        @php
                                            $bobot = $k->bobot ?? 0;
                                            $nilai_normalisasi = $values[$k->id] ?? 0;
                                            $hasil = $nilai_normalisasi * $bobot;
                                            $total += $hasil;
                                        @endphp
                                        <td>{{ number_format($hasil, 4) }}</td>
                                    @endforeach
                                    <td>{{ number_format($total, 4) }}</td>
                                    <td>{{ $ranked[$alt] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- UJI KESESUAIAN -->
<div class="card shadow mb-4">
    <a href="#kesesuaian" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Uji Kesesuaian Metode</h6>
    </a>
    <div class="collapse show" id="kesesuaian">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered w-50">
                    <tbody>
                        <tr>
                            <th>Nilai Rata-rata Metode SAW</th>
                            <td>{{ number_format($rataRata, 3) }}</td>
                        </tr>
                        <tr>
                            <th>Tingkat Kesesuaian Metode SAW</th>
                            <td>{{ number_format($tingkatKesesuaian, 2) }}%</td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-muted mt-3">Hasil uji kesesuaian dihitung berdasarkan rata-rata skor SAW dari semua alternatif.</p>
            </div>
        </div>
    </div>
</div>

@endsection
