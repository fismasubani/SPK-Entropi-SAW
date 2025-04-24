<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Perhitungan SAW</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 30px;
        }

        .kop {
            text-align: center;
            position: relative;
            margin-bottom: 20px;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
        }

        .kop img {
            position: absolute;
            top: 0;
            height: 80px;
        }

        .kop .logo-kiri {
            left: 0;
        }

        .kop .logo-kanan {
            right: 0;
        }

        .kop .text {
            font-size: 14px;
            line-height: 1.4;
        }

        h3 {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        .page-break {
            page-break-before: always;
        }


        .ttd {
            margin-top: 50px;
            width: 100%;
        }

        .ttd .kanan {
            float: right;
            text-align: center;
        }
    </style>
</head>
<body>
    @if (isset($message))
        <p style="color: red;">{{ $message }}</p>
    @endif

    <div class="kop">
        <img src="{{ public_path('img/logo kota pasuruan.png') }}" style="width: 100px; height: auto;" class="logo-kiri">
        <img src="{{ public_path('img/logo smp 8.png') }}" style="width: 100px; height: auto;" class="logo-kanan">
        <div class="text">
            <strong style="font-size: 22px;">PEMERINTAH KOTA PASURUAN</strong><br>
            <strong style="font-size: 22px;">UPT SMP NEGERI 8 PASURUAN</strong><br>
            Jl. KH. Mansyur Nomor 162 Kota Pasuruan, Jawa Timur Indonesia 67127<br>
            Telepon (0343) 422108 <br>
            Pos-el: <a href="mailto:smpn8pasuruan@yahoo.co.id" style="color: blue; text-decoration: underline; font-style: italic;">smpn8pasuruan@yahoo.co.id</a> |
            Laman: <a href="http://www.smpn8pasuruan.sch.id" target="_blank" style="color: blue; text-decoration: underline; font-style: italic;">www.smpn8pasuruan.sch.id</a>
        </div>
    </div>

    <h4 style="text-align: center;">
    LAPORAN HASIL PENERAPAN METODE ENTROPI-SAW<br>
    SISTEM PENDUKUNG KEPUTUSAN <br>
    PEMILIHAN KEPALA LABORATORIUM SMPN 8 KOTA PASURUAN
    </h4>


    <table>
        <thead>
            <tr>
                <th>Nama Alternatif</th>
                @foreach($kriteria as $k)
                    <th>{{ $k->nama_kriteria }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($ranking as $nama => $skor)
                <tr>
                    <td>{{ $nama }}</td>
                    @foreach($kriteria as $k)
                        <td>{{ number_format($normalisasi[$nama][$k->id] * $k->bobot, 4) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>
    <h4 style="text-align: center;">
        HASIL PERINGKAT ALTERNATIF <br>
        PEMILIHAN KEPALA LABORATORIUM SMPN 8 KOTA PASURUAN
    </h4>

    <table>
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Nama Alternatif</th>
                <th>Total Skor</th>
            </tr>
        </thead>
        <tbody>
            @php $peringkat = 1; @endphp
            @foreach($ranking as $nama => $skor)
                <tr>
                    <td>{{ $peringkat++ }}</td>
                    <td>{{ $nama }}</td>
                    <td>{{ number_format($skor, 4) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <div class="ttd">
        <div class="kanan">
            Pasuruan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Kepala UPT SMPN 8 Pasuruan<br><br><br><br><br><br>
            <strong><u>Arif Syaifurrohman, S.Pd</u></strong><br>
            NIP. 198106202009041003
        </div>
    </div>

</body>
</html>