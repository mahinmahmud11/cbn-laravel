<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #333; margin: 0; padding: 40px; line-height: 1.6; }
        .header-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .brand { font-size: 24pt; font-weight: 900; color: #003366; letter-spacing: -1px; }
        .brand span { color: #FF750F; }
        .invoice-title { font-size: 20pt; font-weight: bold; text-align: right; margin-bottom: 5px; }
        .meta-text { text-align: right; color: #666; }
        .section-title { font-weight: bold; text-transform: uppercase; border-bottom: 2px solid #003366; padding-bottom: 5px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; padding: 12px 8px; text-align: left; }
        td { border-bottom: 1px solid #dee2e6; padding: 10px 8px; }
        .total-row { font-weight: bold; font-size: 12pt; background-color: #f8f9fa; }
        .footer { margin-top: 50px; display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }
        .payment-info { font-size: 9pt; color: #555; background: #fdfdfd; padding: 15px; border: 1px solid #eee; }
        .signature { text-align: center; }
        .sign-space { height: 80px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 12px 24px; background: #003366; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">CETAK INVOICE</button>
    </div>

    <div class="header-grid">
        <div>
            <div class="brand">CITRA BUANA <span>NUSANTARA</span></div>
            <p style="margin-top: 10px;">
                Jl. I Gusti Ngurah Rai Ruko 1 I, RT.001 RW.015,<br>
                Kel. Klender Kec. Duren Sawit, Jakarta Timur, 13470<br>
                Telp: (021) 2919 4854
            </p>
        </div>
        <div>
            <div class="invoice-title">INVOICE</div>
            <div class="meta-text">
                <strong>No:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Tanggal:</strong> {{ $invoice->created_at->format('d M Y') }}<br>
                <strong>Jatuh Tempo:</strong> {{ \Illuminate\Support\Carbon::parse($invoice->due_date)->format('d M Y') }}
            </div>
        </div>
    </div>

    <div class="header-grid">
        <div>
            <div class="section-title">Ditagihkan Kepada:</div>
            <strong>{{ $invoice->customer_name }}</strong><br>
            {!! nl2br(e($invoice->customer_address)) !!}
        </div>
        <div style="text-align: right;">
            <div class="section-title">Status:</div>
            <span style="font-size: 14pt; font-weight: bold; color: {{ $invoice->status === 'paid' ? '#28a745' : '#ffc107' }}">
                {{ strtoupper($invoice->status === 'paid' ? 'LUNAS' : 'MENUNGGU PEMBAYARAN') }}
            </span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">No. AWB</th>
                <th width="40%">Keterangan Layanan</th>
                <th width="20%" style="text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->shipItems as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Illuminate\Support\Carbon::createFromTimestamp($item->created_at)->format('d/m/y') }}</td>
                <td>{{ $item->awb }}</td>
                <td>{{ $item->package_type }} ({{ $item->sender_name }} &raquo; {{ $item->recipient_name }})</td>
                <td style="text-align: right;">{{ number_format($item->price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">TOTAL TAGIHAN:</td>
                <td style="text-align: right;">IDR {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="payment-info">
            <strong>Informasi Pembayaran:</strong><br>
            Pembayaran dapat dilakukan melalui transfer bank ke:<br>
            Bank Mandiri: 123-00-9876543-2<br>
            A/N PT. CITRA BUANA NUSANTARA<br>
            *Mohon cantumkan nomor invoice pada berita transfer.
        </div>
        <div class="signature">
            Jakarta, {{ now()->format('d M Y') }}<br>
            Finance Department
            <div class="sign-space"></div>
            <strong>( ________________ )</strong>
        </div>
    </div>

    <div style="margin-top: 40px; text-align: center; font-size: 8pt; color: #999; border-top: 1px solid #eee; padding-top: 10px;">
        Terima kasih atas kepercayaan Anda menggunakan layanan CBN Logistics.
    </div>
</body>
</html>
