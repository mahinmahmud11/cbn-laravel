<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 transition-all duration-500">
        <!-- Header Section -->
        <div class="bg-gradient-to-br from-indigo-700 via-indigo-800 to-blue-900 px-8 py-12 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-4xl font-black tracking-tight mb-2">Lacak Pengiriman</h2>
                <p class="text-indigo-100 text-lg opacity-90">Pantau status paket Anda secara real-time dengan presisi.</p>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 opacity-10">
                <svg width="300" height="300" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 16.5c0 .38-.21.71-.53.88l-7.97 4.44c-.31.17-.69.17-1 0l-7.97-4.44c-.31-.17-.53-.51-.53-.88v-9c0-.38.21-.71.53-.88l7.97-4.44c.31-.17.69-.17 1 0l7.97 4.44c.31.17.53.51.53.88v9z"/>
                </svg>
            </div>
        </div>

        <!-- Search Section -->
        <div class="p-8 -mt-8 relative z-20">
            <div class="bg-white p-2 rounded-2xl shadow-xl border border-gray-50 flex flex-col md:flex-row gap-3">
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model="awb" 
                           placeholder="Masukkan Nomor Resi / AWB..." 
                           class="w-full pl-12 pr-4 py-4 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all outline-none text-lg font-medium text-gray-700">
                </div>
                <button wire:click="search" 
                        wire:loading.attr="disabled"
                        class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-indigo-200 flex items-center justify-center gap-3">
                    <span wire:loading.remove wire:target="search">Lacak Sekarang</span>
                    <span wire:loading wire:target="search" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
            @error('awb') <span class="text-red-500 text-sm mt-3 block font-medium ml-2">{{ $message }}</span> @enderror
        </div>

        <!-- Result Display -->
        <div wire:loading.flex wire:target="search" class="p-20 justify-center items-center flex-col gap-4">
            <div class="relative flex h-20 w-20">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-20 w-20 bg-indigo-500 items-center justify-center">
                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </span>
            </div>
            <p class="text-gray-500 font-bold animate-pulse">Mencari data pengiriman...</p>
        </div>

        @if($result)
            <div wire:loading.remove wire:target="search" class="p-8 border-t border-gray-50">
                <!-- Summary Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 pb-8 border-b border-gray-100">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 mb-2 uppercase tracking-wider border border-indigo-100">
                            Nomor Resi Resmi
                        </span>
                        <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tight">{{ $result->tracking_number }}</h3>
                    </div>
                    <div class="flex items-center gap-4 bg-gray-50 px-6 py-4 rounded-2xl border border-gray-100">
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Status Terakhir</p>
                            <p class="text-lg font-black {{ \App\Enums\ShipmentStatusGroup::classify($result->histories->first()?->status) === \App\Enums\ShipmentStatusGroup::Delivered ? 'text-green-600' : 'text-orange-500' }} uppercase">
                                {{ $result->histories->first()?->status ?? 'Menunggu' }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl {{ \App\Enums\ShipmentStatusGroup::classify($result->histories->first()?->status) === \App\Enums\ShipmentStatusGroup::Delivered ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-500' }} flex items-center justify-center shadow-inner">
                            @if(\App\Enums\ShipmentStatusGroup::classify($result->histories->first()?->status) === \App\Enums\ShipmentStatusGroup::Delivered)
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Technical Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 transition-colors">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-widest">Pengirim</p>
                        <p class="text-lg font-extrabold text-gray-800">{{ $this->maskName($result->customer_name) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Verified Sender</p>
                    </div>
                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 transition-colors">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-widest">Penerima</p>
                        <p class="text-lg font-extrabold text-gray-800">{{ $this->maskName($result->recipient_name) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Verified Recipient</p>
                    </div>
                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 transition-colors">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-widest">Detail Paket</p>
                        <div class="flex items-end gap-2">
                            <span class="text-2xl font-black text-indigo-700">{{ $result->kilogram }}</span>
                            <span class="text-sm font-bold text-gray-500 mb-1">KG</span>
                            <span class="mx-2 text-gray-300">|</span>
                            <span class="text-2xl font-black text-indigo-700">{{ $result->pieces }}</span>
                            <span class="text-sm font-bold text-gray-500 mb-1">Koli</span>
                        </div>
                    </div>
                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 transition-colors">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-widest">Layanan</p>
                        <p class="text-lg font-extrabold text-gray-800 uppercase">{{ $result->package_type ?? 'Standard' }}</p>
                        <p class="text-xs text-gray-500 mt-1">CBN Express Delivery</p>
                    </div>
                </div>

                <!-- Timeline Section -->
                <div class="mt-12 bg-gray-50/30 rounded-3xl p-8 border border-gray-50">
                    <h4 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                        <span class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white text-sm shadow-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        Riwayat Perjalanan
                    </h4>

                    <div class="space-y-0">
                        @forelse($result->histories as $status)
                            <div class="relative pl-12 pb-10 group">
                                <!-- Vertical Line -->
                                @if(!$loop->last)
                                    <div class="absolute left-[19px] top-10 bottom-0 w-0.5 bg-gradient-to-b from-indigo-200 to-gray-100 group-hover:from-indigo-400 transition-colors"></div>
                                @endif
                                
                                <!-- Icon/Dot -->
                                <div class="absolute left-0 top-0 w-10 h-10 rounded-2xl border-4 border-white shadow-md flex items-center justify-center transition-all duration-300 group-hover:scale-110 z-10 
                                    {{ $loop->first ? 'bg-indigo-600 text-white shadow-indigo-200' : 'bg-white text-gray-400' }}">
                                    @if(\App\Enums\ShipmentStatusGroup::classify($status->status) === \App\Enums\ShipmentStatusGroup::Delivered)
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    @elseif(\App\Enums\ShipmentStatusGroup::classify($status->status) === \App\Enums\ShipmentStatusGroup::InTransit)
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    @elseif($status->status == 'return')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-6a4 4 0 00-4-4H4.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 1.414L4.414 5H12a6 6 0 016 6v6h-2z" /></svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    @endif
                                </div>
                                
                                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 group-hover:shadow-md group-hover:border-indigo-100 transition-all">
                                    <div class="flex flex-col sm:flex-row justify-between mb-2 gap-2">
                                        <span class="text-sm font-black text-indigo-600 uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $status->status) }}
                                        </span>
                                        <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-md">
                                            {{ $status->date->format('d M Y | H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-gray-800 font-bold text-lg mb-1">{{ $status->location }}</p>
                                    @if($status->description)
                                        <p class="text-gray-500 text-sm leading-relaxed">{{ $status->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <p class="text-gray-400 font-medium">Belum ada riwayat status untuk resi ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Additional Info/FAQ (Optional UX improvement) -->
    @if(!$result)
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h5 class="font-bold text-gray-800 mb-2">Real-time Update</h5>
                <p class="text-sm text-gray-500">Status pengiriman diperbarui setiap saat kurir melakukan scan.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04 Pelindung" /></svg>
                </div>
                <h5 class="font-bold text-gray-800 mb-2">Keamanan Data</h5>
                <p class="text-sm text-gray-500">Data pengirim dan penerima disensor demi privasi Anda.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                </div>
                <h5 class="font-bold text-gray-800 mb-2">Bantuan 24/7</h5>
                <p class="text-sm text-gray-500">Hubungi customer service kami jika ada kendala pengiriman.</p>
            </div>
        </div>
    @endif
</div>
