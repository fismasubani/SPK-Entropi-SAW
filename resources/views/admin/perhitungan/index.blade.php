@extends('layouts.app')
@section('title', 'SPK Entropi-SAW | Perhitungan SAW')
@section('content')

<!-- Menampilkan Pesan Belum Ada Data -->
@if (isset($message))
    <div class="alert alert-warning">
        {{ $message }}
    </div>
@endif

<!-- Menampilkan Pesan Berhasil Simpan Data -->
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Tombol Simpan Hasil -->
@if (!isset($isPdf))
<div class="d-flex justify-content-end mb-4">
    <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#modalSimpanHasil">
        <i class="fas fa-save"></i> Simpan Hasil
    </button>
</div>
@endif

<!-- PENILAIAN ALTERNATIF -->
<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#tambahkriteria" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Penilaian Alternatif</h6>
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
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Normalisasi (SAW)</h6>
    </a>
    <div class="collapse show" id="normalisasi">
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
                                <td colspan="{{ count($kriteria) + 1 }}" class="text-center">Tidak ada data normalisasi</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
</div>

<!-- TAHAP PERHITUNGAN PREFERENSI -->
<div class="card shadow mb-4">
    <a href="#ranking" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardExample">
        <h6 class="m-0 font-weight-bold text-primary">Tahap Perhitungan Preferensi (V)</h6>
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
                                        <td>{{ number_format($hasil, 5) }}</td>
                                    @endforeach
                                    <td>{{ number_format($total, 5) }}</td>
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
                            <td>{{ number_format($rataRata, 4) }}</td>
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

<!-- Modal Simpan Hasil -->
<div class="modal fade" id="modalSimpanHasil" tabindex="-1" role="dialog" aria-labelledby="modalSimpanHasilLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('perhitungan.simpan') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSimpanHasilLabel">Simpan Hasil Perhitungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_perhitungan">Nama Perhitungan</label>
                        <input type="text" class="form-control" id="nama_perhitungan" name="nama_perhitungan" required placeholder="Masukkan nama perhitungan">
                    </div>

                    <div class="form-group">
                        <label for="metode">Metode Perhitungan</label>
                        <select class="form-control" id="metode" name="metode" required>
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
