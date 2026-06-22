<?php

namespace App\Http\Controllers;

use App\Models\ShipItem;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    /**
     * Tampilkan halaman cetak resi termal (100mm x 150mm).
     */
    public function resiThermal(ShipItem $record)
    {
        return view('print.resi-thermal', [
            'items' => [$record],
        ]);
    }

    /**
     * Tampilkan cetak resi termal massal berdasarkan koleksi ID.
     */
    public function resiThermalBulk(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $items = ShipItem::whereIn('id', $ids)->get();

        return view('print.resi-thermal', [
            'items' => $items,
        ]);
    }

    /**
     * Tampilkan halaman cetak dokumen Manifest.
     */
    public function manifest(\App\Models\Manifest $record)
    {
        return view('print.manifest', [
            'manifest' => $record->load(['shipItems', 'originAgency', 'destinationAgency']),
        ]);
    }

    /**
     * Tampilkan halaman cetak dokumen Invoice.
     */
    public function invoice(\App\Models\Invoice $record)
    {
        return view('print.invoice', [
            'invoice' => $record->load(['shipItems']),
        ]);
    }

    /**
     * Tampilkan halaman cetak resi termal untuk Shipment (baru).
     */
    public function resiThermalNew(\App\Models\Shipment $record)
    {
        abort_unless(auth()->check(), 403);

        return view('print.resi-new-thermal', [
            'items' => [$record],
        ]);
    }

    /**
     * Tampilkan cetak resi termal massal untuk Shipment (baru).
     */
    public function resiThermalBulkNew(Request $request)
    {
        abort_unless(auth()->check(), 403);

        $ids = explode(',', $request->query('ids', ''));
        $items = \App\Models\Shipment::whereIn('id', $ids)->get();

        return view('print.resi-new-thermal', [
            'items' => $items,
        ]);
    }
}
