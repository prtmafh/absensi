<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>

    <style>
        @page {
            margin: 28px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #111;
        }

        .muted {
            color: #6b7280;
        }

        .right {
            text-align: right;
        }

        /* ================= HEADER ================= */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .header-table td {
            vertical-align: top;
            padding: 6px;
        }

        .company {
            font-size: 18px;
            font-weight: 700;
        }

        .header-note {
            font-size: 12px;
            margin-top: 4px;
            line-height: 1.4;
        }

        .slip-title {
            font-size: 16px;
            font-weight: 700;
            text-align: right;
        }

        .meta {
            font-size: 12px;
            margin-top: 6px;
            line-height: 1.5;
            text-align: right;
        }

        /* ================= INFO ================= */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #f9fafb;
        }

        .info-table td {
            padding: 10px 12px;
        }

        .info-label {
            width: 30%;
            font-weight: 600;
            color: #374151;
        }

        /* ================= GAJI TABLE ================= */
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .salary-table col.col-komponen {
            width: 65%;
        }

        .salary-table col.col-nilai {
            width: 35%;
        }

        .salary-table th {
            text-align: left;
            font-weight: 700;
            font-size: 14px;
            padding: 12px;
            background: #f3f4f6;
        }

        .salary-table th.right {
            text-align: right;
            font-weight: 700;
            font-size: 14px;
            padding: 12px;
            background: #f3f4f6;
        }

        .salary-table td {
            padding: 12px;
        }

        /* ================= FOOTER ================= */
        .footer {
            margin-top: 14px;
            font-size: 12px;
            padding: 10px;
            border: 1px dashed #111;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td style="width:60%;">
                <div class="company">PT Tidarjaya Solidindo</div>
                <div class="header-note muted">
                    Slip gaji ini dihasilkan otomatis oleh sistem absensi & penggajian.<br>
                    Simpan dokumen ini sebagai bukti penerimaan upah.
                </div>
            </td>
            <td style="width:40%;">
                <div class="slip-title">SLIP GAJI KARYAWAN</div>
                <div class="meta">
                    <div>No Slip : <strong>{{ $id_gaji }}</strong></div>
                    <div>Periode : <strong>{{ $periode }}</strong></div>
                    <div>Dicetak : <strong>{{ $tanggal_cetak }}</strong></div>
                </div>
            </td>
        </tr>
    </table>

    <!-- INFO KARYAWAN -->
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Karyawan</td>
            <td style="text-transform: capitalize;">{{ $karyawan }}</td>
        </tr>
        <tr>
            <td class="info-label">Jenis Gaji</td>
            <td>{{ $jenis_gaji }}</td>
        </tr>
    </table>

    <!-- RINCIAN GAJI -->
    <table class="salary-table">
        <colgroup>
            <col class="col-komponen">
            <col class="col-nilai">
        </colgroup>
        <thead>
            <tr>
                <th>Komponen</th>
                <th class="right">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Upah Dasar
                    <div class="row-note">Sesuai ketentuan perusahaan</div>
                </td>
                <td class="right">Rp {{ $upah_dasar }}</td>
            </tr>
            <tr>
                <td>Total Hadir</td>
                <td class="right">{{ $total_hadir }}</td>
            </tr>
            <tr>
                <td>Terlambat (hari)</td>
                <td class="right">{{ $total_terlambat }}</td>
            </tr>
            <tr>
                <td>Menit Terlambat</td>
                <td class="right">{{ $late_minutes }} menit</td>
            </tr>
            <tr>
                <td>Denda Terlambat</td>
                <td class="right">Rp {{ $late_penalty }}</td>
            </tr>
            <tr>
                <td>Izin</td>
                <td class="right">{{ $izin }}</td>
            </tr>
            <tr>
                <td>Tidak Hadir</td>
                <td class="right">{{ $tidak_hadir }}</td>
            </tr>
            <tr>
                <td>Lembur</td>
                <td class="right">Rp {{ $lembur }}</td>
            </tr>
            <tr>
                <td>Potongan</td>
                <td class="right">Rp {{ $potongan }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Gaji Diterima</td>
                <td class="right">Rp {{ $total_gaji }}</td>
            </tr>
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer muted">
        <strong>Catatan:</strong> Apabila terdapat ketidaksesuaian data, silakan menghubungi HRD maksimal 3×24 jam
        setelah slip diterbitkan. Dokumen ini sah tanpa tanda tangan basah.
    </div>

</body>

</html>