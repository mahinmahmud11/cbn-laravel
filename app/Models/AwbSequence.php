<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel counter AWB.
 * Digunakan secara eksklusif oleh AwbNumberGeneratorService.
 * JANGAN query model ini secara langsung dari luar Service tersebut.
 *
 * @property int    $id
 * @property string $period         Format YYMM, contoh: "2606"
 * @property int    $last_sequence  Nomor urut terakhir yang sudah digunakan
 */
class AwbSequence extends Model
{
    protected $table = 'awb_sequences';

    protected $fillable = [
        'period',
        'last_sequence',
    ];

    protected function casts(): array
    {
        return [
            'last_sequence' => 'integer',
        ];
    }
}
