<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 transition-all duration-500">
        <!-- Header Section -->
        <div class="bg-gradient-to-br from-blue-700 via-indigo-800 to-indigo-900 px-8 py-12 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-4xl font-black tracking-tight mb-2">Cek Tarif Pengiriman</h2>
                <p class="text-blue-100 text-lg opacity-90">Estimasi biaya pengiriman cepat dan transparan ke seluruh Indonesia.</p>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 opacity-10">
                <svg width="300" height="300" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/>
                </svg>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Origin Section -->
                <div>
                    <h4 class="text-sm font-black text-indigo-600 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-indigo-600 rounded-full"></span>
                        Lokasi Asal
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Provinsi</label>
                            <select wire:model.live="originProvince" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-gray-700">
                                <option value="">Pilih Provinsi...</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Kota/Kabupaten</label>
                            <select wire:model.live="originRegency" @disabled(!$originProvince) class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-gray-700 disabled:opacity-50">
                                <option value="">Pilih Kota...</option>
                                @foreach($originRegencies as $regency)
                                    <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Kecamatan</label>
                            <select wire:model.live="originDistrict" @disabled(!$originRegency) class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-gray-700 disabled:opacity-50">
                                <option value="">Pilih Kecamatan...</option>
                                @foreach($originDistricts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Destination Section -->
                <div>
                    <h4 class="text-sm font-black text-blue-600 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                        Lokasi Tujuan
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Provinsi</label>
                            <select wire:model.live="destinationProvince" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium text-gray-700">
                                <option value="">Pilih Provinsi...</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Kota/Kabupaten</label>
                            <select wire:model.live="destinationRegency" @disabled(!$destinationProvince) class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium text-gray-700 disabled:opacity-50">
                                <option value="">Pilih Kota...</option>
                                @foreach($destinationRegencies as $regency)
                                    <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Kecamatan</label>
                            <select wire:model.live="destinationDistrict" @disabled(!$destinationRegency) class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium text-gray-700 disabled:opacity-50">
                                <option value="">Pilih Kecamatan...</option>
                                @foreach($destinationDistricts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weight & Action -->
            <div class="mt-12 pt-8 border-t border-gray-50 flex flex-col md:flex-row items-end gap-6">
                <div class="w-full md:w-1/3">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Berat Paket (Kg)</label>
                    <div class="relative">
                        <input type="number" wire:model="weight" step="0.1" class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-xl text-gray-700">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-gray-300">KG</span>
                    </div>
                </div>
                <button wire:click="calculate" wire:loading.attr="disabled" class="w-full md:flex-grow py-5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-indigo-100 flex items-center justify-center gap-3">
                    <span wire:loading.remove wire:target="calculate">Hitung Estimasi Biaya</span>
                    <span wire:loading wire:target="calculate" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menghitung...
                    </span>
                </button>
            </div>
            @if ($errors->any())
                <div class="mt-4 text-red-500 text-sm font-medium text-center">Silakan lengkapi semua data lokasi.</div>
            @endif
        </div>

        <!-- Result Section -->
        @if($tariffResult)
            <div class="p-8 bg-gray-50 border-t border-gray-100 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Rute Pengiriman</p>
                        <p class="text-lg font-black text-gray-800">
                            {{ $tariffResult['origin'] }} 
                            <span class="mx-2 text-indigo-400">→</span> 
                            {{ $tariffResult['destination'] }}
                        </p>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                        <span class="text-sm font-bold text-gray-500">Berat: </span>
                        <span class="text-lg font-black text-indigo-600">{{ $tariffResult['weight'] }} KG</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($tariffResult['services'] as $service)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 transition-all group">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">{{ $service['name'] }}</h5>
                                    <p class="text-3xl font-black text-gray-900">Rp {{ number_format($service['price'], 0, ',', '.') }}</p>
                                    <div class="mt-4 flex items-center gap-2 text-green-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span class="text-xs font-bold uppercase">Estimasi: {{ $service['etd'] }}</span>
                                    </div>
                                </div>
                                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <p class="mt-8 text-center text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">
                    * Harga di atas adalah estimasi. Harga final dapat berubah saat penimbangan ulang oleh kurir.
                </p>
            </div>
        @endif
    </div>
</div>
