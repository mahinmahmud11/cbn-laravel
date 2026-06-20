@extends('layouts.public')

@section('title', 'CBN Logistics - Pengiriman Cepat, Aman & Terpercaya')

@section('content')
<!-- Hero Section (Corporate Logistics Style) -->
<section class="relative bg-cbn-blue pt-20 pb-48 lg:pt-32 lg:pb-56 overflow-hidden">
    <!-- Background Image Slider with Overlay -->
    <div class="absolute inset-0 z-0 bg-black">
        <!-- Slides -->
        <div id="hero-slider" class="w-full h-full relative">
            <img src="https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?q=80&w=2000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover object-center transition-opacity duration-1000 opacity-100 slide-img" alt="Cargo Truck">
            <img src="https://images.unsplash.com/photo-1474487548417-781cb71495f3?q=80&w=2000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover object-center transition-opacity duration-1000 opacity-0 slide-img" alt="Freight Train">
            <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover object-center transition-opacity duration-1000 opacity-0 slide-img" alt="Cargo Plane">
        </div>
        <!-- Deep Blue Corporate Overlay -->
        <div class="absolute inset-0 bg-[#002244]/80"></div>
    </div>

    <!-- Slider Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide-img');
            let currentSlide = 0;
            
            setInterval(() => {
                slides[currentSlide].classList.remove('opacity-100');
                slides[currentSlide].classList.add('opacity-0');
                
                currentSlide = (currentSlide + 1) % slides.length;
                
                slides[currentSlide].classList.remove('opacity-0');
                slides[currentSlide].classList.add('opacity-100');
            }, 5000); // Change image every 5 seconds
        });
    </script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <div class="inline-block bg-cbn-orange text-white font-bold tracking-wider text-sm px-4 py-1.5 mb-6 uppercase shadow-md">
                Mitra Logistik Terpercaya
            </div>
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 leading-[1.1] tracking-tight">
                PENGIRIMAN <span class="text-transparent bg-clip-text bg-gradient-to-r from-cbn-orange to-yellow-400">TANPA BATAS</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-50 mb-10 max-w-2xl leading-relaxed font-medium">
                Solusi logistik end-to-end untuk mendongkrak efisiensi bisnis Anda ke seluruh pelosok Nusantara.
            </p>
        </div>
    </div>
</section>

<!-- Floating Tracking Box -->
<section class="relative z-20 -mt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.15)] rounded-none border-t-8 border-cbn-orange p-6 md:p-10 flex flex-col lg:flex-row gap-6 items-center">
            
            <div class="w-full lg:w-1/4">
                <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight flex items-center gap-3">
                    <svg class="w-8 h-8 text-cbn-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Lacak Paket
                </h2>
                <p class="text-gray-500 text-sm mt-1 font-medium">Cek status pengiriman real-time</p>
            </div>

            <form action="{{ route('public.track') }}" method="GET" class="w-full lg:w-3/4 flex flex-col md:flex-row gap-3">
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <input type="text" name="resi" placeholder="Masukkan Nomor Resi (AWB)..." class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 font-bold text-lg rounded-none focus:ring-0 focus:border-cbn-blue block pl-12 pr-4 py-4 transition-colors" required>
                </div>
                <button type="submit" class="bg-cbn-orange hover:bg-[#e66a0e] text-white px-10 py-4 font-black text-lg uppercase tracking-wider transition-colors shrink-0 whitespace-nowrap shadow-md">
                    Lacak Sekarang
                </button>
            </form>

        </div>
    </div>
</section>

<!-- Services Grid Section -->
<section id="layanan" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div class="max-w-2xl">
                <h2 class="text-4xl font-black text-gray-900 mb-4 uppercase tracking-tight">Layanan Pengiriman</h2>
                <div class="w-24 h-2 bg-cbn-orange mb-6"></div>
                <p class="text-gray-600 text-lg">Infrastruktur dan armada kami siap menangani segala jenis kebutuhan pengiriman barang Anda, dari dokumen hingga kargo berat.</p>
            </div>
            <a href="#" class="text-cbn-blue font-bold hover:text-cbn-orange flex items-center gap-2 transition-colors">
                Lihat Semua Layanan
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <!-- SDS -->
            <div class="bg-white group hover:-translate-y-1 transition-all duration-300 shadow-sm hover:shadow-xl border border-gray-100 relative">
                <div class="absolute inset-x-0 bottom-0 h-1 bg-cbn-orange scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                <div class="h-40 overflow-hidden relative">
                    <div class="absolute inset-0 bg-cbn-blue/20 group-hover:bg-transparent transition-colors z-10"></div>
                    <img src="https://images.unsplash.com/photo-1580674285054-bed31e145f59?q=80&w=800&auto=format&fit=crop" alt="SDS" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="p-8">
                    <div class="w-12 h-12 bg-gray-100 text-cbn-blue flex items-center justify-center mb-6 shadow-sm border border-gray-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3 uppercase tracking-tight">Same Day Service</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">Pengiriman super cepat. Paket dikirim dan diterima pada hari yang sama untuk area khusus.</p>
                    <a href="#" class="text-cbn-orange font-bold text-sm uppercase tracking-wider flex items-center gap-2 group-hover:gap-3 transition-all">Pelajari <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg></a>
                </div>
            </div>

            <!-- ONS -->
            <div class="bg-white group hover:-translate-y-1 transition-all duration-300 shadow-sm hover:shadow-xl border border-gray-100 relative">
                <div class="absolute inset-x-0 bottom-0 h-1 bg-cbn-orange scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                <div class="h-40 overflow-hidden relative">
                    <div class="absolute inset-0 bg-cbn-blue/20 group-hover:bg-transparent transition-colors z-10"></div>
                    <img src="https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?q=80&w=800&auto=format&fit=crop" alt="ONS" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="p-8">
                    <div class="w-12 h-12 bg-gray-100 text-cbn-orange flex items-center justify-center mb-6 shadow-sm border border-gray-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3 uppercase tracking-tight">One Night Service</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">Layanan prioritas satu malam. Jaminan paket tiba keesokan harinya di kota besar.</p>
                    <a href="#" class="text-cbn-orange font-bold text-sm uppercase tracking-wider flex items-center gap-2 group-hover:gap-3 transition-all">Pelajari <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg></a>
                </div>
            </div>

            <!-- Regular -->
            <div class="bg-white group hover:-translate-y-1 transition-all duration-300 shadow-sm hover:shadow-xl border border-gray-100 relative">
                <div class="absolute inset-x-0 bottom-0 h-1 bg-cbn-orange scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                <div class="h-40 overflow-hidden relative">
                    <div class="absolute inset-0 bg-cbn-blue/20 group-hover:bg-transparent transition-colors z-10"></div>
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=800&auto=format&fit=crop" alt="Regular" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="p-8">
                    <div class="w-12 h-12 bg-gray-100 text-cbn-blue flex items-center justify-center mb-6 shadow-sm border border-gray-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3 uppercase tracking-tight">Regular Cargo</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">Distribusi kargo darat & laut dengan biaya paling efisien untuk volume besar.</p>
                    <a href="#" class="text-cbn-orange font-bold text-sm uppercase tracking-wider flex items-center gap-2 group-hover:gap-3 transition-all">Pelajari <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg></a>
                </div>
            </div>

            <!-- International -->
            <div class="bg-white group hover:-translate-y-1 transition-all duration-300 shadow-sm hover:shadow-xl border border-gray-100 relative">
                <div class="absolute inset-x-0 bottom-0 h-1 bg-cbn-orange scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                <div class="h-40 overflow-hidden relative">
                    <div class="absolute inset-0 bg-cbn-blue/20 group-hover:bg-transparent transition-colors z-10"></div>
                    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=800&auto=format&fit=crop" alt="International" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="p-8">
                    <div class="w-12 h-12 bg-gray-100 text-cbn-blue flex items-center justify-center mb-6 shadow-sm border border-gray-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3 uppercase tracking-tight">Global Freight</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">Layanan ekspor impor via udara dan laut dengan jaringan mitra internasional terpercaya.</p>
                    <a href="#" class="text-cbn-orange font-bold text-sm uppercase tracking-wider flex items-center gap-2 group-hover:gap-3 transition-all">Pelajari <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tariff Check Section (Corporate Style) -->
<section id="cek-tarif" class="relative py-24 bg-cbn-blue overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=2000&auto=format&fit=crop" alt="Containers" class="w-full h-full object-cover opacity-20">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 text-white">
                <h2 class="text-4xl font-black mb-4 uppercase tracking-tight">Estimasi Biaya</h2>
                <div class="w-16 h-2 bg-cbn-orange mb-6"></div>
                <p class="text-blue-100 text-lg mb-8 opacity-90">Kalkulasi biaya pengiriman Anda secara instan dan transparan. Dapatkan rincian tarif terbaik dari kami.</p>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3 font-bold">
                        <svg class="w-6 h-6 text-cbn-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Harga Kompetitif
                    </li>
                    <li class="flex items-center gap-3 font-bold">
                        <svg class="w-6 h-6 text-cbn-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Tidak Ada Biaya Tersembunyi
                    </li>
                    <li class="flex items-center gap-3 font-bold">
                        <svg class="w-6 h-6 text-cbn-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Perhitungan Akurat
                    </li>
                </ul>
            </div>

            <div class="lg:col-span-7">
                <div class="bg-white p-8 md:p-10 shadow-2xl border-t-8 border-cbn-orange">
                    <form action="{{ route('public.tarif') }}" method="GET" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-black text-gray-500 uppercase tracking-widest block">Kota Asal</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <input type="text" name="origin" placeholder="Masukkan Kota Asal" class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-none focus:ring-0 focus:border-cbn-blue block pl-12 pr-4 py-3" required>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-black text-gray-500 uppercase tracking-widest block">Kota Tujuan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <input type="text" name="destination" placeholder="Masukkan Kota Tujuan" class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-none focus:ring-0 focus:border-cbn-blue block pl-12 pr-4 py-3" required>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest block">Berat Barang (KG)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                                </div>
                                <input type="number" name="weight" min="1" value="1" class="w-full bg-gray-50 border border-gray-200 text-gray-900 font-bold rounded-none focus:ring-0 focus:border-cbn-blue block pl-12 pr-4 py-3" required>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white py-4 font-black text-lg uppercase tracking-wider transition-colors shadow-md mt-4">
                            Tampilkan Tarif
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- About Section -->
<section id="tentang-kami" class="py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <!-- Images Grid -->
            <div class="w-full lg:w-1/2 relative">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-cbn-orange z-0"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 border-8 border-cbn-blue z-0"></div>
                
                <div class="relative z-10 grid grid-cols-2 gap-4">
                    <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=800&auto=format&fit=crop" class="w-full h-80 object-cover shadow-xl" alt="Warehouse Operations">
                    <img src="https://images.unsplash.com/photo-1621252179027-94459d278660?q=80&w=800&auto=format&fit=crop" class="w-full h-80 object-cover shadow-xl mt-12" alt="Logistics Team">
                </div>
            </div>

            <!-- Text Content -->
            <div class="w-full lg:w-1/2">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-1 bg-cbn-orange"></div>
                    <span class="text-cbn-blue font-black uppercase tracking-widest text-sm">Tentang CBN Logistics</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-8 leading-tight tracking-tight">KONEKTIVITAS BISNIS SKALA GLOBAL</h2>
                <p class="text-gray-600 leading-relaxed mb-8 text-lg border-l-4 border-gray-200 pl-6">
                    Sejak berdiri, PT. Citra Buana Nusantara berdedikasi membangun infrastruktur logistik yang andal. Kami mengelola pergerakan barang dengan presisi, keamanan, dan kecepatan yang memadai untuk mendukung pertumbuhan bisnis Anda.
                </p>
                
                <div class="grid grid-cols-2 gap-8 mb-10 pb-10 border-b border-gray-100">
                    <div>
                        <p class="text-5xl font-black text-gray-900 mb-1">99<span class="text-cbn-orange">%</span></p>
                        <p class="text-gray-500 font-bold uppercase tracking-wider text-xs">On-Time Delivery Rate</p>
                    </div>
                    <div>
                        <p class="text-5xl font-black text-gray-900 mb-1">20<span class="text-cbn-blue">+</span></p>
                        <p class="text-gray-500 font-bold uppercase tracking-wider text-xs">Tahun Pengalaman</p>
                    </div>
                </div>

                <a href="#" class="inline-flex items-center gap-3 bg-cbn-blue hover:bg-[#002244] text-white px-8 py-3.5 font-bold uppercase tracking-wider transition-colors shadow-lg">
                    Profil Perusahaan
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
