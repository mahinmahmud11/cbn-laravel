@extends('layouts.public')

@section('title', 'CBN Logistics - Pengiriman Cepat, Aman & Terpercaya')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient relative py-24 lg:py-32 overflow-hidden">
    <!-- Abstract Background Decor -->
    <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-[400px] h-[400px] bg-orange-500/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
            Solusi Logistik <span class="text-cbn-orange">Terbaik</span> <br>Untuk Bisnis Anda
        </h1>
        <p class="text-xl text-blue-100 mb-12 max-w-2xl mx-auto opacity-90 leading-relaxed">
            Menghubungkan Nusantara dengan layanan pengiriman yang cepat, aman, dan dapat dilacak secara real-time.
        </p>

        <!-- Tracking Box -->
        <div class="max-w-2xl mx-auto bg-white p-2 rounded-2xl shadow-2xl flex flex-col md:flex-row gap-2">
            <div class="flex-grow flex items-center px-4 py-2 border-b md:border-b-0 md:border-r border-gray-100">
                <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Masukkan Nomor Resi (AWB)..." class="w-full bg-transparent outline-none font-medium text-gray-800 placeholder:text-gray-400">
            </div>
            <button class="bg-cbn-orange text-white px-10 py-4 rounded-xl font-black text-lg hover:bg-opacity-90 transition-all shadow-lg shadow-orange-500/30">
                LACAK PAKET
            </button>
        </div>
        
        <p class="mt-6 text-blue-200 text-sm font-medium">
            *Lacak kiriman Anda secara instan 24/7
        </p>
    </div>
</section>

<!-- Services Section -->
<section id="layanan" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 uppercase tracking-tight">Layanan Unggulan Kami</h2>
            <div class="w-20 h-1.5 bg-cbn-blue mx-auto rounded-full mb-6"></div>
            <p class="text-gray-500 max-w-xl mx-auto">Berbagai pilihan layanan pengiriman yang disesuaikan dengan kebutuhan dan urgensi paket Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- SDS -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-blue-50 text-cbn-blue rounded-2xl flex items-center justify-center mb-6 group-hover:bg-cbn-blue group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Same Day Service</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Kirim pagi, sampai di hari yang sama. Solusi tercepat untuk dokumen dan paket mendesak.</p>
            </div>

            <!-- ONS -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-orange-50 text-cbn-orange rounded-2xl flex items-center justify-center mb-6 group-hover:bg-cbn-orange group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">One Night Service</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Layanan pengiriman satu malam. Paket sampai keesokan harinya di seluruh kota besar Indonesia.</p>
            </div>

            <!-- Regular -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-slate-50 text-slate-700 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-slate-700 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Regular Cargo</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Pengiriman handal dengan estimasi waktu standar ke seluruh wilayah Nusantara dengan harga ekonomis.</p>
            </div>

            <!-- International -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">International Freight</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Jangkauan global untuk kebutuhan impor dan ekspor barang Anda ke berbagai negara di dunia.</p>
            </div>
        </div>
    </div>
</section>

<!-- Tariff Check Section -->
<section id="cek-tarif" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-cbn-blue rounded-[3rem] p-8 md:p-16 shadow-2xl relative overflow-hidden">
            <!-- Decor -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/3">
                    <h2 class="text-3xl md:text-4xl font-black text-white mb-6 uppercase">Cek Tarif Pengiriman</h2>
                    <p class="text-blue-100 opacity-80 leading-relaxed">
                        Dapatkan informasi estimasi biaya pengiriman secara akurat dengan mengisi data asal, tujuan, dan berat paket Anda.
                    </p>
                </div>
                
                <div class="lg:w-2/3 w-full">
                    <div class="bg-white p-6 rounded-3xl shadow-lg">
                        <form class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 ml-1">KOTA ASAL</label>
                                <input type="text" placeholder="Contoh: Jakarta" class="w-full bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 outline-none focus:border-cbn-orange transition-colors">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 ml-1">KOTA TUJUAN</label>
                                <input type="text" placeholder="Contoh: Surabaya" class="w-full bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 outline-none focus:border-cbn-orange transition-colors">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 ml-1">BERAT (KG)</label>
                                <input type="number" value="1" class="w-full bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 outline-none focus:border-cbn-orange transition-colors">
                            </div>
                            <div class="md:col-span-3 pt-2">
                                <button type="button" class="w-full bg-cbn-orange text-white py-4 rounded-xl font-black text-lg hover:bg-opacity-90 transition-all shadow-lg shadow-orange-500/20">
                                    HITUNG ONGKOS KIRIM
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About / CTA Section -->
<section id="tentang-kami" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&q=80&w=1000" class="rounded-[3rem] shadow-2xl relative z-10" alt="CBN Warehouse">
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-cbn-orange rounded-full z-0"></div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <span class="text-cbn-orange font-black uppercase tracking-widest text-sm mb-4 block">Tentang Kami</span>
                <h2 class="text-4xl font-black text-gray-900 mb-8 leading-tight">Pengiriman Aman Sampai Ke Ujung Dunia</h2>
                <p class="text-gray-500 leading-relaxed mb-8 text-lg">
                    Sejak berdiri, PT. Citra Buana Nusantara telah melayani ribuan pelanggan dengan integritas dan dedikasi tinggi. Kami percaya bahwa setiap paket memiliki cerita dan harapan, itulah sebabnya kami menjaganya seolah milik kami sendiri.
                </p>
                <div class="grid grid-cols-2 gap-8 mb-10">
                    <div>
                        <p class="text-4xl font-black text-cbn-blue mb-2">99%</p>
                        <p class="text-gray-500 text-sm font-bold uppercase">On-Time Delivery</p>
                    </div>
                    <div>
                        <p class="text-4xl font-black text-cbn-blue mb-2">10k+</p>
                        <p class="text-gray-500 text-sm font-bold uppercase">Happy Clients</p>
                    </div>
                </div>
                <a href="#" class="inline-flex items-center gap-3 text-cbn-blue font-black text-lg group">
                    Pelajari Lebih Lanjut 
                    <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
