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
}
