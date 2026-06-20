<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        {{-- Sisi Kiri: Form Input Manual --}}
        <x-filament::section>
            <x-slot name="heading">Input Manual / Scanner Laser</x-slot>
            <form wire:submit.prevent="processScan">
                <div class="grid grid-cols-1 gap-4 mb-4">
                    {{-- Dropdown Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Pemrosesan</label>
                        <select wire:model.live="scanStatus" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600">
                            <option value="RECEIVED">RECEIVED (Diterima di Agen)</option>
                            <option value="TRANSIT">TRANSIT (Sedang Dikirim)</option>
                            <option value="OUT_FOR_DELIVERY">OUT FOR DELIVERY (Kurir Menuju Lokasi)</option>
                            <option value="DELIVERED">DELIVERED (Sampai di Tujuan)</option>
                        </select>
                    </div>

                    {{-- Upload Foto (Hanya jika DELIVERED) --}}
                    @if($scanStatus === 'DELIVERED')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto Bukti (POD)</label>
                        <input type="file" wire:model="photo" accept="image/*" capture="environment" 
                            class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        <div wire:loading wire:target="photo" class="mt-1 text-xs text-info-600">Mengunggah foto...</div>
                    </div>
                    @endif
                </div>

                {{ $this->form }}
                
                <div class="mt-4">
                    <x-filament::button type="submit" class="w-full">
                        Proses Scan
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        {{-- Sisi Kanan: Scanner Kamera (WebRTC) --}}
        <x-filament::section>
            <x-slot name="heading">Scanner Kamera HP</x-slot>
            <div id="reader" style="width: 100%;" class="overflow-hidden bg-gray-100 rounded-lg dark:bg-gray-800 min-h-[300px] flex items-center justify-center">
                <p class="text-sm text-gray-500">Klik tombol di bawah untuk mengaktifkan kamera</p>
            </div>
            
            <div class="mt-4 text-center">
                <x-filament::button id="start-camera" color="info" icon="heroicon-o-camera">
                    Buka Kamera Scanner
                </x-filament::button>
                <p class="mt-2 text-xs text-gray-400">Pastikan Anda menggunakan koneksi HTTPS</p>
            </div>
        </x-filament::section>
    </div>

    {{-- Aset Pendukung --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <audio id="beep-sound" src="https://assets.mixkit.co/active_storage/sfx/1000/1000-preview.mp3" preload="auto"></audio>

    @script
    <script>
        const beep = document.getElementById('beep-sound');
        const startButton = document.getElementById('start-camera');
        const readerElement = document.getElementById('reader');
        let html5QrCode;

        startButton.addEventListener('click', async () => {
            // Hapus teks placeholder
            readerElement.innerHTML = '';
            
            html5QrCode = new Html5Qrcode("reader");
            
            try {
                await html5QrCode.start(
                    { facingMode: "environment" }, 
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText) => {
                        // Mainkan suara beep
                        beep.play().catch(() => {});
                        
                        // Isi data ke Livewire dan jalankan proses
                        $wire.set('tracking_number', decodedText);
                        $wire.processScan();
                        
                        // Opsional: Jika ingin berhenti setelah 1x scan, uncomment baris bawah
                        // html5QrCode.stop();
                        // startButton.style.display = 'inline-flex';
                    }
                );
                
                startButton.style.display = 'none';
                
            } catch (err) {
                console.error("Gagal membuka kamera:", err);
                alert("Kamera gagal diakses. Pastikan izin kamera diberikan dan menggunakan HTTPS.");
            }
        });
    </script>
    @endscript
</x-filament-panels::page>
