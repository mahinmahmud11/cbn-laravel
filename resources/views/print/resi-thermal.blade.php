<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Resi Massal</title>
    <style>
        @page {
            size: 100mm 150mm;
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: #f0f0f0;
        }
        .label-container {
            width: 100mm;
            height: 150mm;
            padding: 5mm;
            box-sizing: border-box;
            background: #fff;
            margin: 10px auto;
            position: relative;
            page-break-after: always;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        @media print {
            body { background: none; }
            .label-container { 
                margin: 0; 
                box-shadow: none; 
                page-break-after: always;
            }
            .no-print { display: none; }
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 3mm;
            margin-bottom: 3mm;
        }
        .logo { font-weight: bold; font-size: 14pt; }
        .service-type {
            background: #000;
            color: #fff;
            padding: 1mm 3mm;
            font-weight: bold;
            font-size: 12pt;
        }
        .barcode-section { text-align: center; margin-bottom: 4mm; }
        .awb-text { font-size: 12pt; font-weight: bold; margin-top: 1mm; }
        .address-box { border: 1px solid #000; margin-bottom: 3mm; padding: 2mm; }
        .label { font-weight: bold; font-size: 8pt; text-transform: uppercase; display: block; margin-bottom: 1mm; }
        .content { font-size: 10pt; font-weight: bold; }
        .footer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2mm; margin-top: 3mm; }
        .footer-item { border: 1px solid #000; padding: 2mm; text-align: center; }
        .footer-val { font-size: 12pt; font-weight: bold; }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 0; right: 0; padding: 10px; background: #fff; border-bottom-left-radius: 10px; box-shadow: -2px 2px 10px rgba(0,0,0,0.1); z-index: 100;">
        <button onclick="window.print()" style="padding: 12px 24px; cursor: pointer; background: #003366; color: white; border: none; font-weight: bold; border-radius: 5px;">CETAK SEMUA ({{ count($items) }})</button>
    </div>

    @foreach($items as $item)
    <div class="label-container">
        <div class="header">
            <div class="logo">CBN LOGISTICS</div>
            <div class="service-type">{{ $item->package_type ?? 'REGULAR' }}</div>
        </div>

        <div class="barcode-section">
            {!! DNS1D::getBarcodeHTML($item->awb, 'C128', 2.5, 60) !!}
            <div class="awb-text">{{ $item->awb }}</div>
        </div>

        <div class="address-box">
            <span class="label">Penerima:</span>
            <div class="content">{{ $item->recipient_name }}</div>
            <div style="font-size: 9pt; margin-top: 1mm;">
                {{ $item->notes ?? 'No address notes provided.' }}
            </div>
        </div>

        <div class="address-box">
            <span class="label">Pengirim:</span>
            <div class="content">{{ $item->sender_name }}</div>
            <div style="font-size: 9pt; margin-top: 1mm;">
                Cabang: {{ $item->user->agency->agency_name ?? 'CBN HQ' }}
            </div>
        </div>

        <div class="footer-grid">
            <div class="footer-item">
                <span class="label">Berat</span>
                <div class="footer-val">{{ $item->kilogram }} KG</div>
            </div>
            <div class="footer-item">
                <span class="label">Koli</span>
                <div class="footer-val">{{ $item->pieces }} PCS</div>
            </div>
        </div>

        <div style="margin-top: 5mm; border-top: 1px dashed #000; padding-top: 2mm; font-size: 8pt; text-align: center;">
            No: {{ $loop->iteration }} / {{ $loop->count }} | {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
    @endforeach
</body>
</html>
