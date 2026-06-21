<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        body{
            font-family: Arial, times new roman;
            font-size: 12px;
        }

        .header{
            text-align: center;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .header h2{
            margin:0;
        }

        .info{
            margin-bottom:20px;
        }

        .info table{
            border:none;
        }

        .info td{
            border:none;
            padding:2px 0;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        th, td{
            border:1px solid black;
            padding:5px;
        }

        th{
            text-align:center;
            background:#f2f2f2;
        }

        td{
            text-align:center;
        }

        .nama{
            text-align:left;
        }

        .info-section{
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info-left{
            flex: 1;
        }

        .info-left td {
            padding: 1px 0 !important;
        }

        .info-right{
            text-align: center;
            width: 150px;
        }

        .info-right p{
            margin: 5px 0;
        }

        .footer {
            margin-left: auto;
            margin-right: 0;
            width: max-content;
            text-align: center;
            margin-top: 40px;
        }

        .ttd{
            width: 150px;
            float: right;
        }

        .ttd p{
            margin: 5px 0;
        }

        .nama-guru{
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>REKAPITULASI ABSENSI</h2>
    <h3>SMPN X BATAM</h3>
    <div>Jl. Fontaine</div>
</div>

<div class="info-section">
    <div class="info-left">
        <table>
            <tr>
                <td width="120" style="text-align: left; border: none;">Kelas</td>
                <td width="10" style="text-align: left; border: none;">:</td>
                <td style="text-align: left; border: none;">{{ $kelas->nama_kelas }}</td>
            </tr>

            <tr>
                <td style="text-align: left; border: none;">Wali Kelas</td>
                <td style="text-align: left; border: none;">:</td>
                <td style="text-align: left; border: none;">{{ $kelas->guru->nama }}</td>
            </tr>

            <tr>
                <td style="text-align: left; border: none;">Tahun Pelajaran</td>
                <td style="text-align: left; border: none;">:</td>
                <td style="text-align: left; border: none;">{{ $kelas->tahunPelajaran->tahun_pelajaran }}</td>
            </tr>

            <tr>
                <td style="text-align: left; border: none;">Periode</td>
                <td style="text-align: left; border: none;">:</td>
                <td style="text-align: left; border: none;">
                    {{ \Carbon\Carbon::create()->month($bulanAwal)->translatedFormat('F') }}
                    –
                    {{ \Carbon\Carbon::create()->month($bulanAkhir)->translatedFormat('F') }}
                    {{ $tahun }}
                </td>
            </tr>
        </table>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th width="40">No</th>
            <th width="80">NIS</th>
            <th>Nama Siswa</th>
            <th width="40">L/P</th>
            <th width="40">H</th>
            <th width="40">S</th>
            <th width="40">I</th>
            <th width="40">A</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item['nis'] }}</td>
            <td class="nama">{{ $item['nama'] }}</td>
            <td>{{ $item['jk'] }}</td>
            <td>{{ $item['hadir'] }}</td>
            <td>{{ $item['sakit'] }}</td>
            <td>{{ $item['izin'] }}</td>
            <td>{{ $item['alpha'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <div class="ttd">
        <p>Batam, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Wali Kelas</p>
        <div class="nama-guru">
            {{ $kelas->guru->nama ?? '-' }}
        </div>
    </div>
</div>

</body>
</html>