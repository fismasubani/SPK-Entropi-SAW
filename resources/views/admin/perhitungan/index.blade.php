@extends('layouts.app')
@section('title', 'SPK Entropi-SAW | Perhitungan SAW')
@section('content')

<!-- Menampilkan Pesan Belum Ada Data -->
@if (isset($message))
    <div class="alert alert-warning">{{ $message }}</div>
@endif

<!-- Menampilkan Pesan Berhasil -->
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Tombol Simpan Hasil -->
@if (!isset($isPdf))
<div class="d-flex justify-content-end mb-4">
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalSimpanHasil">
        <i class="fas fa-save"></i> Simpan Hasil
    </button>
</div>
@endif

<!-- PENILAIAN ALTERNATIF -->
<div class="card shadow mb-4">
    <a href="#penilaianAlternatif" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="penilaianAlternatif">
        <h5 class="m-0 font-weight-bold text-primary">Tahap Penilaian Alternatif</h5>
    </a>
    <div class="collapse show" id="penilaianAlternatif">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-left align-middle">Nama Alternatif</th>
                            @foreach($kriteria as $value)
                                <th class="text-center align-middle">{{ $value->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alternatif as $valt)
                            <tr>
                                <td class="text-left align-middle">{{ $valt->nama_alternatif }}</td>
                                @if(count($valt->penilaian) > 0)
                                    @foreach($valt->penilaian as $value)
                                        <td class="text-center align-middle">{{ $value->crips->bobot }}</td>
                                    @endforeach
                                @else
                                    @for($i = 0; $i < count($kriteria); $i++)
                                        <td class="text-center align-middle">-</td>
                                    @endfor
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
        <h5 class="m-0 font-weight-bold text-primary">Tahap Normalisasi (SAW)</h5>
    </a>
    <div class="collapse show" id="normalisasi">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-left align-middle">Nama Alternatif</th>
                            @foreach($kriteria as $value)
                                <th class="text-center align-middle">{{ $value->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($normalisasi))
                            @foreach($normalisasi as $alt => $values)
                                <tr>
                                    <td class="text-left align-middle">{{ $alt }}</td>
                                    @foreach($values as $val)
                                        <td class="text-center align-middle">{{ number_format($val, 2) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ count($kriteria) + 1 }}" class="text-center">Tidak ada data normalisasi</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
</div>

<!-- PERHITUNGAN PREFERENSI -->
<div class="card shadow mb-4">
    <a href="#ranking" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="ranking">
        <h5 class="m-0 font-weight-bold text-primary">Tahap Perhitungan Preferensi (V)</h5>
    </a>
    <div class="collapse show" id="ranking">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" class="text-left align-middle">Nama Alternatif</th>
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
                        @if(!empty($normalisasi))
                            @php
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
                                arsort($ranking);
                                $rank = 1;
                                $ranked = [];
                                foreach($ranking as $alt => $total){
                                    $ranked[$alt] = $rank++;
                                }
                            @endphp

                            @foreach($normalisasi as $alt => $values)
                                <tr>
                                    <td class="text-left align-middle">{{ $alt }}</td>
                                    @php $total = 0; @endphp
                                    @foreach($kriteria as $k)
                                        @php
                                            $bobot = $k->bobot ?? 0;
                                            $nilai_normalisasi = $values[$k->id] ?? 0;
                                            $hasil = $nilai_normalisasi * $bobot;
                                            $total += $hasil;
                                        @endphp
                                        <td class="text-center align-middle">{{ number_format($hasil, 5) }}</td>
                                    @endforeach
                                    <td class="text-center align-middle">{{ number_format($total, 5) }}</td>
                                    <td class="text-center align-middle">{{ $ranked[$alt] }}</td>
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
        role="button" aria-expanded="true" aria-controls="kesesuaian">
        <h5 class="m-0 font-weight-bold text-primary">Uji Kesesuaian Metode</h5>
    </a>
    <div class="collapse show" id="kesesuaian">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped w-50">
                    <tbody>
                        <tr>
                            <th class="text-left align-middle">Nilai Rata-rata Metode SAW</th>
                            <td class="text-center align-middle">{{ number_format($rataRata, 4) }}</td>
                        </tr>
                        <tr>
                            <th class="text-left align-middle">Tingkat Kesesuaian Metode SAW</th>
                            <td class="text-center align-middle">{{ number_format($tingkatKesesuaian, 2) }}%</td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-muted mt-3">Hasil uji kesesuaian dihitung berdasarkan rata-rata skor SAW dari semua alternatif.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalSimpanHasil" tabindex="-1" role="dialog" aria-labelledby="modalSimpanHasilLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('perhitungan.simpan') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Simpan Hasil Perhitungan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Perhitungan</label>
                        <input type="text" class="form-control" name="nama_perhitungan" required placeholder="Masukkan nama perhitungan">
                    </div>
                    <div class="form-group">
                        <label>Metode Perhitungan</label>
                        <select class="form-control" name="metode" required>
                            <option value="Entropi-SAW">Entropi-SAW</option>
                            <option value="SAW">SAW</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
