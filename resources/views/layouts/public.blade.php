<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CBN Logistics - Pengiriman Cepat, Aman & Terpercaya')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cbn-blue': '#003366',
                        'cbn-orange': '#FF750F',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <style>
        @layer base {
            body { font-family: 'Inter', sans-serif; }
        }
        .hero-gradient {
            background: linear-gradient(135deg, #003366 0%, #001a33 100%);
        }
    </style>
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 overflow-x-hidden">

    <!-- Header / Navigation -->
    <header class="bg-white border-b sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Brand -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/logo.png') }}" alt="CBN Logistics Logo" class="h-12 w-auto transition-transform group-hover:scale-110">
                        <div class="flex flex-col leading-tight">
                            <span class="text-cbn-blue font-black text-xl tracking-tighter">CITRA BUANA</span>
                            <span class="text-cbn-orange font-extrabold text-lg tracking-widest -mt-1">NUSANTARA</span>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8">
                    <a href="/" class="text-gray-700 hover:text-cbn-blue font-semibold transition-colors">Beranda</a>
                    <a href="#layanan" class="text-gray-700 hover:text-cbn-blue font-semibold transition-colors">Layanan</a>
                    <a href="#cek-tarif" class="text-gray-700 hover:text-cbn-blue font-semibold transition-colors">Cek Tarif</a>
                    <a href="#tentang-kami" class="text-gray-700 hover:text-cbn-blue font-semibold transition-colors">Tentang Kami</a>
                </nav>
                
                <!-- Action Button -->
                <div class="hidden md:flex items-center">
                    <a href="/admin" class="bg-cbn-blue text-white px-6 py-2.5 rounded-lg font-bold hover:bg-opacity-90 transition-all">
                        Admin Portal
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-cbn-blue">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t py-4 px-4 space-y-4 shadow-lg absolute w-full">
            <a href="/" class="block text-gray-700 font-semibold">Beranda</a>
            <a href="#layanan" class="block text-gray-700 font-semibold">Layanan</a>
            <a href="#cek-tarif" class="block text-gray-700 font-semibold">Cek Tarif</a>
            <a href="#tentang-kami" class="block text-gray-700 font-semibold">Tentang Kami</a>
            <a href="/admin" class="block bg-cbn-blue text-white text-center py-3 rounded-lg font-bold">Admin Portal</a>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <img src="{{ asset('images/logo.png') }}" alt="CBN Logo" class="h-10 w-auto mb-6 brightness-0 invert">
                    <p class="text-slate-400 max-w-md leading-relaxed mb-6">
                        PT. Citra Buana Nusantara adalah penyedia layanan pengiriman barang dan logistik terpercaya di Indonesia yang melayani kebutuhan korporasi maupun individu dengan jaringan luas di seluruh pelosok Nusantara.
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social Icons (Placeholders) -->
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-cbn-orange transition-colors">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-1.002-2.178-1.627-3.595-1.627-2.72 0-4.925 2.205-4.925 4.925 0 .386.044.762.127 1.124-4.093-.205-7.721-2.166-10.151-5.145-.424.728-.666 1.574-.666 2.479 0 1.708 1.104 3.216 2.781 4.336-.931-.029-1.808-.285-2.574-.711v.062c0 2.386 1.697 4.376 3.948 4.828-.413.112-.849.172-1.298.172-.317 0-.626-.031-.927-.089.627 1.956 2.444 3.379 4.601 3.419-1.686 1.322-3.81 2.109-6.118 2.109-.398 0-.79-.023-1.175-.068 2.179 1.396 4.768 2.21 7.557 2.21 9.068 0 14.028-7.512 14.028-14.028 0-.214-.005-.428-.014-.641 1.025-.739 1.914-1.662 2.618-2.716z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-cbn-orange transition-colors">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.054 1.805.249 2.227.491.56.217.96.477 1.382.896.421.422.682.822.896 1.382.242.422.437 1.056.491 2.227.059 1.266.071 1.646.071 4.85s-.012 3.584-.071 4.85c-.054 1.17-.249 1.805-.491 2.227-.217.56-.477.96-.896 1.382-.422.421-.822.682-1.382.896-.422.242-1.056.437-2.227.491-1.266.059-1.646.071-4.85.071s-3.584-.012-4.85-.071c-1.17-.054-1.805-.249-2.227-.491-.56-.217-.96-.477-1.382-.896-.421-.422-.682-.822-.896-1.382-.242-.422-.437-1.056-.491-2.227-.059-1.266-.071-1.646-.071-4.85s.012-3.584.071-4.85c.054-1.17.249-1.805.491-2.227.217-.56.477-.96.896-1.382.422-.421.822-.682 1.382-.896.422-.242 1.056-.437 2.227-.491 1.266-.059 1.646-.071 4.85-.071zm0-2.163c-3.259 0-3.667.014-4.947.072-1.277.057-2.148.26-2.911.557-.788.306-1.457.715-2.124 1.383-.667.668-1.077 1.337-1.383 2.124-.297.763-.5 1.634-.557 2.911-.058 1.28-.072 1.688-.072 4.947s.014 3.667.072 4.947c.057 1.277.26 2.148.557 2.911.306.788.715 1.457 1.383 2.124.668.667 1.337 1.077 2.124 1.383.763.297 1.634.5 2.911.557 1.28.058 1.688.072 4.947.072s3.667-.014 4.947-.072c1.277-.057 2.148-.26 2.911-.557.788-.306 1.457-.715 2.124-1.383.667-.668 1.077-1.337 1.383-2.124.297-.763.5-1.634.557-2.911.058-1.28.072-1.688.072-4.947s-.014-3.667-.072-4.947c-.057-1.277-.26-2.148-.557-2.911-.306-.788-.715-1.457-1.383-2.124-.668-.667-1.337-1.077-2.124-1.383-.763-.297-1.634-.5-2.911-.557-1.28-.058-1.688-.072-4.947-.072z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-bold mb-6 border-l-4 border-cbn-orange pl-4">Hubungi Kami</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-cbn-orange shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>Head Office: Jl. I Gusti Ngurah Rai Ruko 1 I, RT.001 RW.015, Kel. Klender Kec. Duren Sawit, Jakarta Timur, 13470</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-cbn-orange shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <span>(021) 2919 4854</span>
                        </li>
                    </ul>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold mb-6 border-l-4 border-cbn-orange pl-4">Layanan</h4>
                    <ul class="space-y-3 text-slate-400">
                        <li><a href="#" class="hover:text-white transition-colors">Same Day Service (SDS)</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">One Night Service (ONS)</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Regular Cargo</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">International Delivery</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-slate-500 text-sm">
                <p>&copy; {{ date('Y') }} PT. Citra Buana Nusantara. All rights reserved.</p>
                <div class="mt-4 md:mt-0 space-x-6">
                    <a href="#" class="hover:text-white">Privacy Policy</a>
                    <a href="#" class="hover:text-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
    @livewireScripts
</body>
</html>
