<div class="max-w-4xl mx-auto py-12 px-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Lacak Kiriman Anda</h1>
        
        <!-- Search Form -->
        <form wire:submit.prevent="track" class="flex flex-col md:flex-row gap-4 mb-10">
            <input 
                type="text" 
                wire:model.defer="trackingNumber"
                placeholder="Masukkan Nomor Resi / AWB..."
                class="flex-grow px-6 py-4 rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 transition-all text-lg shadow-sm"
            >
            <button 
                type="submit"
                class="px-8 py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-orange-200"
            >
                Cari Sekarang
            </button>
        </form>

        @if($searched)
            @if($shipment)
                <!-- Shipment Info -->
                <div class="grid md:grid-cols-2 gap-8 mb-12">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                            <span class="text-gray-500">Nomor Resi</span>
                            <span class="font-bold text-gray-900">{{ $shipment->tracking_number }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                            <span class="text-gray-500">Status Terakhir</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold uppercase">
                                {{ str_replace('_', ' ', $shipment->current_status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                            <span class="text-gray-500">Pengirim</span>
                            <span class="font-bold text-gray-900">{{ $shipment->customer_name }}</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                            <span class="text-gray-500">Penerima</span>
                            <span class="font-bold text-gray-900">{{ $shipment->recipient_name }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                            <span class="text-gray-500">Asal</span>
                            <span class="font-bold text-gray-900">{{ $shipment->origin_city }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                            <span class="text-gray-500">Tujuan</span>
                            <span class="font-bold text-gray-900">{{ $shipment->destination_city }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="relative">
                    <h3 class="text-xl font-bold text-gray-900 mb-8 border-l-4 border-orange-500 pl-4">Riwayat Perjalanan</h3>
                    <div class="space-y-8">
                        @foreach($shipment->trackingHistories as $history)
                            <div class="relative pl-8">
                                <!-- Dot -->
                                <div class="absolute left-0 top-1.5 w-4 h-4 bg-orange-500 rounded-full border-4 border-white shadow-sm z-10"></div>
                                <!-- Line -->
                                @if(!$loop->last)
                                    <div class="absolute left-1.5 top-5 w-0.5 h-full bg-gray-200"></div>
                                @endif
                                
                                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 hover:border-orange-200 transition-colors shadow-sm">
                                    <div class="flex flex-col md:flex-row justify-between mb-2">
                                        <span class="text-sm font-bold text-orange-600">{{ $history->created_at->format('d M Y, H:i') }} WIB</span>
                                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ $history->status }}</span>
                                    </div>
                                    <p class="text-gray-900 font-bold mb-1">{{ $history->location }}</p>
                                    @if($history->description)
                                        <p class="text-gray-600 text-sm italic">"{{ $history->description }}"</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Not Found -->
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6 text-red-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Resi Tidak Ditemukan</h3>
                    <p class="text-gray-500">Nomor resi <strong>{{ $trackingNumber }}</strong> tidak ditemukan atau belum terdaftar.</p>
                </div>
            @endif
        @endif
    </div>
</div>
