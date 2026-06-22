<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resi</title>
    <style>
        @page { size: 100mm 150mm; margin: 0; }
        body { font-family: sans-serif; margin: 0; padding: 0; background: #fff; }
        .label-container { width: 100mm; height: 150mm; padding: 5mm; box-sizing: border-box; position: relative; page-break-after: always; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 3mm; margin-bottom: 3mm; }
        .barcode-section { text-align: center; margin-bottom: 4mm; }
        .address-box { border: 1px solid #000; margin-bottom: 3mm; padding: 2mm; }
        .label { font-weight: bold; font-size: 8pt; display: block; }
        .footer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2mm; text-align: center; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 0; right: 0; padding: 10px; background: #fff; z-index: 100;">
        <button onclick="window.print()" style="padding: 12px 24px; cursor: pointer; background: #003366; color: white; border: none; font-weight: bold; border-radius: 5px;">CETAK ({{ count($items) }})</button>
    </div>
    @foreach($items as $item)
    <div class="label-container">
        <div class="header">
            <div><b>CBN LOGISTICS</b></div>
            <div><b>{{ strtoupper($item->package_type ?? 'REGULAR') }}</b></div>
        </div>
        <div class="barcode-section">
            {!! DNS1D::getBarcodeHTML($item->tracking_number, 'C128', 2.5, 60) !!}
            <div style="font-weight:bold;">{{ $item->tracking_number }}</div>
        </div>
        <div class="address-box">
            <span class="label">Penerima:</span>
            <b>{{ $item->recipient_name }} ({{ $item->recipient_phone }})</b><br>
            <span style="font-size:9pt">{{ $item->receiver_address ?? '' }}</span>
        </div>
        <div class="address-box">
            <span class="label">Pengirim:</span>
            <b>{{ $item->sender_name }} ({{ $item->sender_phone }})</b><br>
            <span style="font-size:9pt">{{ $item->sender_address ?? '' }}</span>
        </div>
        <div class="footer-grid">
            <div style="border:1px solid #000; padding:2mm;"><span class="label">Berat</span><b>{{ $item->weight_kg }} KG</b></div>
            <div style="border:1px solid #000; padding:2mm;"><span class="label">Koli</span><b>{{ $item->pieces }} PCS</b></div>
        </div>
        @if($item->is_fragile)
        <div style="text-align:center; font-weight:bold; margin-top:3mm; font-size:14pt; border:2px dashed #000;">FRAGILE</div>
        @endif
        <div style="margin-top: 5mm; border-top: 1px dashed #000; padding-top: 2mm; font-size: 8pt; text-align: center;">
            No: {{ $loop->iteration }} / {{ $loop->count }} | {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
    @endforeach
</body>
</html>
