<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manifest - {{ $manifest->manifest_number }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 10pt; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 16pt; font-weight: bold; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; text-align: center; margin-top: 50px; }
        .sign-box { height: 80px; border-bottom: 1px solid #000; margin-bottom: 5px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Manifest</button>
    </div>

    <div class="header">
        <div class="title">SURAT JALAN MANIFEST PENGIRIMAN</div>
        <div>PT. CITRA BUANA NUSANTARA LOGISTICS</div>
    </div>

    <div class="info-grid">
        <div>
            <strong>NOMOR MANIFEST:</strong> {{ $manifest->manifest_number }}<br>
            <strong>TANGGAL:</strong> {{ $manifest->created_at->format('d/m/Y H:i') }}<br>
            <strong>STATUS:</strong> {{ strtoupper($manifest->status) }}
        </div>
        <div>
            <strong>ASAL:</strong> {{ $manifest->originAgency->agency_name ?? '-' }}<br>
            <strong>TUJUAN:</strong> {{ $manifest->destinationAgency->agency_name ?? '-' }}<br>
            <strong>DRIVER/NOPOL:</strong> {{ $manifest->driver_name ?? '-' }} / {{ $manifest->vehicle_plate ?? '-' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">No. AWB / Resi</th>
                <th width="25%">Pengirim</th>
                <th width="25%">Penerima</th>
                <th width="10%">Berat</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($manifest->shipItems as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->awb }}</td>
                <td>{{ $item->sender_name }}</td>
                <td>{{ $item->recipient_name }}</td>
                <td>{{ $item->kilogram }} KG</td>
                <td>{{ $item->package_type }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right;">TOTAL:</th>
                <th>{{ $manifest->shipItems->sum('kilogram') }} KG</th>
                <th>{{ $manifest->shipItems->count() }} Resi</th>
            </tr>
        </tfoot>
    </table>

    <p><strong>Catatan:</strong> {{ $manifest->notes ?? '-' }}</p>

    <div class="footer-grid">
        <div>
            Petugas Asal
            <div class="sign-box"></div>
            ( ________________ )
        </div>
        <div>
            Driver / Kurir
            <div class="sign-box"></div>
            ( {{ $manifest->driver_name ?? '________________' }} )
        </div>
        <div>
            Petugas Tujuan
            <div class="sign-box"></div>
            ( ________________ )
        </div>
    </div>

    <div style="margin-top: 30px; font-size: 8pt; text-align: center; color: #666;">
        Dokumen ini dihasilkan secara otomatis oleh CBN ERP System - {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
