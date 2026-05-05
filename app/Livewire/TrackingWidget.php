<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\ShipItem;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TrackingWidget extends Component
{
    #[Validate('required|string|min:3|max:50')]
    public string $awb = '';

    public ?ShipItem $result = null;

    /**
     * Menjalankan pencarian resi.
     * 
     * @return void
     */
    public function search(): void
    {
        $this->validate();

        // Menggunakan eager loading pada shipStatuses untuk optimasi query
        // Status diurutkan secara descending (terbaru di atas)
        $this->result = ShipItem::with(['shipStatuses' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->where('awb', $this->awb)
            ->first();

        if (!$this->result) {
            $this->addError('awb', 'Nomor resi tidak ditemukan. Silakan cek kembali.');
        }
    }

    public function render()
    {
        return view('livewire.tracking-widget');
    }

    /**
     * Menyensor nama untuk privasi (misal: "Budi Santoso" -> "B*** *******o").
     * 
     * @param string|null $name
     * @return string
     */
    public function maskName(?string $name): string
    {
        if (!$name) {
            return '';
        }

        $words = explode(' ', trim($name));
        $count = count($words);

        if ($count === 1) {
            $word = $words[0];
            $len = mb_strlen($word);
            if ($len <= 2) {
                return $word;
            }
            return mb_substr($word, 0, 1) . str_repeat('*', $len - 2) . mb_substr($word, -1);
        }

        $masked = [];
        foreach ($words as $index => $word) {
            $len = mb_strlen($word);
            if ($index === 0) {
                // Kata pertama: Huruf pertama + sisanya asterisk
                $masked[] = mb_substr($word, 0, 1) . str_repeat('*', $len - 1);
            } elseif ($index === $count - 1) {
                // Kata terakhir: Asterisk + huruf terakhir
                $masked[] = str_repeat('*', $len - 1) . mb_substr($word, -1);
            } else {
                // Kata tengah: Semua asterisk
                $masked[] = str_repeat('*', $len);
            }
        }

        return implode(' ', $masked);
    }
}
