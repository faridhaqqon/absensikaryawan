<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Cetak Laporan Presensi</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>
  @page {
    size: A4
    }

    #title {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 18px;
        font-weight: bold;
    }

    .tabeldatakaryawan {
        margin-top: 40px;
    }

    .tabeldatakaryawan tr td {
        padding: 5px;
    }

    .tabelpresensi{
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .tabelpresensi tr th{
        border: 1px solid #020000;
        padding: 6px;
        background-color: #aceda6
    }

    .tabelpresensi tr td{
        border: 1px solid #020000;
        padding: 6px;
        font-size: 12px;
        text-align: center;
    }

    .foto{
        width: 40px;
        height: 30;
    }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">
    @php
         function selisih($jam_masuk, $jam_keluar)
       {
            list($h, $m, $s) = explode(":", $jam_masuk);
            $dtAwal = mktime($h, $m, $s, "1", "1", "1");
            list($h, $m, $s) = explode(":", $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode(".", $totalmenit / 60);
            $sisamenit = ($totalmenit / 60) - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ":" . round($sisamenit2);
        }
    @endphp

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table style="width: 100%">
        <tr>
            <td style="width: 90px">
                <img src="{{ asset('assets/img/honda.png') }}" width="80" height="80" alt="">
            </td>
            <td>
                <span id="title">
                    LAPORAN PRESENSI KARYAWAN<br>
                   PT. Sinar Mulia Sejahtera<br>
                    PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
                </span>
                <span><i>Jl. Harapan Indah Boulevard, RT.005/RW.010, Medan Satria, Kecamatan Medan Satria, <br>Kota Bks, Jawa Barat 17132</i></span>
            </td>
        </tr>
    </table>
    <table class="tabeldatakaryawan">
        <tr>
            <td rowspan= "6">
                @php
                    $path = Storage::url('uploads/karyawan/'.$karyawan->foto);
                @endphp
                <img src="{{ url($path) }}" alt="" width="120px" height="160">
            </td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $karyawan->nip }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan }}</td>
        </tr>
        <tr>
            <td>Divisi</td>
            <td>:</td>
            <td>{{ $karyawan->nama_dept }}</td>
        </tr>
        <tr>
            <td>No Hanphone</td>
            <td>:</td>
            <td>{{ $karyawan->no_hp }}</td>
        </tr>
    </table>
    <table class="tabelpresensi">
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
            <th>Keterangan</th>
        </tr>
        @foreach ($presensi as $d )
        @php
        $jamterlambat = selisih('08:30:00',$d->jam_in);
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date("d-m-y", strtotime($d->tgl_presensi)) }}</td>
            <td>{{ $d->jam_in }}</td>
            <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
            <td>
                @if ($d->jam_in > '08:30')
                Terlambat {{ $jamterlambat }}
                @else
                Tepat Waktu
                @endif
            </td>
        </tr>
        @endforeach
    </table>

    <table width="100%" style="margin-top:20px">
        <tr>
            <td colspan="2">Bekasi, {{ date('d M Y') }}</td>
        </tr>
        <tr>
            <td style="height:30px" >
                <i>Kepala Cabang Dealer</i><br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <u><b> Yusuf Firdaust</b></u><br>
                NIP. 215767<br>
            </td>
        </tr>
  </section>

</body>

</html>
