<div class="login-wrapper">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@500;700&display=swap" rel="stylesheet">
    <style>
        .login-wrapper {
            --orange: #F97316;
            --orange-light: #FB923C;
            --orange-glow: rgba(249, 115, 22, 0.35);
            --navy: #0B1628;
            --navy-mid: #112040;
            --glass-bg: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.14);
            --glass-shine: rgba(255, 255, 255, 0.08);
            --text-primary: #F0F4FF;
            --text-muted: rgba(200, 215, 240, 0.65);
            --input-bg: rgba(255, 255, 255, 0.07);
            --input-border: rgba(255, 255, 255, 0.15);
            --input-focus: rgba(249, 115, 22, 0.5);

            position: fixed;
            inset: 0;
            height: 100vh;
            width: 100vw;
            font-family: 'Barlow', sans-serif;
            background: var(--navy);
            overflow: hidden;
            margin: 0;
            z-index: 1000;
        }

        .bg-scene {
            position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 80%, rgba(249,115,22,0.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 85% 10%, rgba(30,80,160,0.25) 0%, transparent 55%),
                linear-gradient(145deg, #060e1e 0%, #0d1f3c 40%, #0a1628 70%, #050c1a 100%);
        }

        .bg-illustration {
            position: absolute; bottom: 0; left: 0; right: 0; z-index: 1;
            height: 100vh;
            pointer-events: none;
        }

        .noise {
            position: absolute; inset: 0; z-index: 2;
            opacity: 0.03;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        .grid-overlay {
            position: absolute; inset: 0; z-index: 2;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .login-container {
            position: relative; z-index: 10;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 8% 0 0;
        }

        @media (max-width: 1024px) {
            .login-container {
                justify-content: center;
                padding: 0;
            }
            .left-panel {
                display: none;
            }
        }

        .glass-card {
            width: 420px;
            background: var(--glass-bg);
            backdrop-filter: blur(28px) saturate(160%);
            -webkit-backdrop-filter: blur(28px) saturate(160%);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 44px 40px 40px;
            box-shadow:
                0 0 0 0.5px rgba(255,255,255,0.06) inset,
                0 40px 80px rgba(0,0,0,0.55),
                0 0 60px rgba(249,115,22,0.06);
            animation: cardIn 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
            position: relative;
            overflow: hidden;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(32px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 46px; height: 46px;
            background: linear-gradient(135deg, #F97316, #EA580C);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 20px rgba(249,115,22,0.4);
            flex-shrink: 0;
        }

        .brand-icon svg { width: 26px; height: 26px; fill: #fff; }

        .brand-name {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 17px; font-weight: 700;
            letter-spacing: 0.04em; text-transform: uppercase;
            color: var(--text-primary);
            line-height: 1;
        }
        .brand-sub {
            font-size: 11px; font-weight: 400;
            color: var(--orange-light);
            letter-spacing: 0.12em; text-transform: uppercase;
            margin-top: 3px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
            margin-bottom: 28px;
        }

        .heading {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 30px; font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.01em;
            line-height: 1.1;
            margin-bottom: 6px;
        }
        .sub-heading {
            font-size: 13.5px; color: var(--text-muted);
            margin-bottom: 30px;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 12px; font-weight: 500;
            letter-spacing: 0.07em; text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap svg.icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 18px; height: 18px; stroke: rgba(200,215,240,0.4);
            pointer-events: none; transition: stroke 0.2s;
        }

        .field input {
            width: 100%; height: 48px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: 'Barlow', sans-serif;
            font-size: 15px;
            padding: 0 44px 0 44px;
            outline: none !important;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
            box-shadow: none !important;
        }

        .field input:focus {
            border-color: rgba(249,115,22,0.6) !important;
            background: rgba(255,255,255,0.09) !important;
            box-shadow: 0 0 0 3px rgba(249,115,22,0.14), 0 0 20px rgba(249,115,22,0.06) !important;
        }

        .toggle-pw {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 4px;
            color: rgba(200,215,240,0.4);
            transition: color 0.2s;
            display: flex;
        }

        .remember-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 26px;
        }

        .checkbox-label {
            display: flex; align-items: center; gap: 9px;
            cursor: pointer;
            font-size: 13.5px; color: var(--text-muted);
            user-select: none;
        }

        .checkbox-label input[type=checkbox] { display: none; }

        .checkbox-box {
            width: 18px; height: 18px;
            border: 1px solid var(--input-border);
            border-radius: 5px;
            background: var(--input-bg);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .checkbox-label input:checked ~ .checkbox-box {
            background: var(--orange);
            border-color: var(--orange);
        }

        .checkbox-box svg {
            width: 11px; height: 11px; stroke: #fff; stroke-width: 2.5;
            fill: none; opacity: 0; transition: opacity 0.15s;
        }
        .checkbox-label input:checked ~ .checkbox-box svg { opacity: 1; }

        .forgot-link {
            font-size: 13px; color: var(--orange-light);
            text-decoration: none;
            transition: color 0.2s;
        }

        .btn-signin {
            width: 100%; height: 52px;
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
            border: none; border-radius: 13px;
            color: #fff;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 17px; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            cursor: pointer;
            position: relative; overflow: hidden;
            box-shadow: 0 6px 24px rgba(249,115,22,0.4), 0 1px 0 rgba(255,255,255,0.2) inset;
            transition: transform 0.15s, box-shadow 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-footer {
            margin-top: 20px; text-align: center;
            font-size: 11.5px; color: var(--text-muted);
        }

        .left-panel {
            position: absolute; left: 8%; top: 50%; transform: translateY(-50%);
            z-index: 10; width: 450px;
        }

        .lp-badge {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(249,115,22,0.15);
            border: 1px solid rgba(249,115,22,0.3);
            border-radius: 99px;
            padding: 5px 14px 5px 8px;
            font-size: 12px; font-weight: 500;
            color: var(--orange-light);
            letter-spacing: 0.06em; text-transform: uppercase;
            margin-bottom: 22px;
        }

        .lp-badge-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--orange);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(249,115,22,0.6); }
            50% { box-shadow: 0 0 0 5px rgba(249,115,22,0); }
        }

        .lp-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 64px; font-weight: 700; line-height: 0.95;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: -0.02em;
            margin-bottom: 20px;
        }
        .lp-title span { color: var(--orange); }

        .lp-desc {
            font-size: 16px; color: var(--text-muted); line-height: 1.7;
            margin-bottom: 40px;
            max-width: 400px;
        }

        .stat-row {
            display: flex; gap: 32px;
        }

        .stat {
            display: flex; flex-direction: column; gap: 4px;
        }
        .stat-num {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 32px; font-weight: 700;
            color: var(--text-primary);
        }
        .stat-lbl {
            font-size: 11px; color: var(--text-muted);
            letter-spacing: 0.1em; text-transform: uppercase;
        }
        .stat-sep {
            width: 1px; background: var(--glass-border); align-self: stretch;
        }

        .error-text {
            color: #fb7185;
            font-size: 12.5px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
    </style>

    <div class="bg-scene"></div>
    <div class="noise"></div>
    <div class="grid-overlay"></div>

    <svg class="bg-illustration" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMax slice" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="road" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#0d1829"/>
                <stop offset="100%" stop-color="#060c18"/>
            </linearGradient>
            <linearGradient id="truckBody" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#1e3a5f"/>
                <stop offset="100%" stop-color="#0f2040"/>
            </linearGradient>
            <linearGradient id="truckCab" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0%" stop-color="#1a3050"/>
                <stop offset="100%" stop-color="#243d63"/>
            </linearGradient>
            <linearGradient id="vanBody" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#162035"/>
                <stop offset="100%" stop-color="#0c1828"/>
            </linearGradient>
            <linearGradient id="skyGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="transparent"/>
                <stop offset="70%" stop-color="transparent"/>
                <stop offset="100%" stop-color="rgba(6,12,24,0.9)"/>
            </linearGradient>
            <filter id="glow">
                <feGaussianBlur stdDeviation="3" result="blur"/>
                <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
            </filter>
            <clipPath id="sceneClip">
                <rect width="1440" height="900"/>
            </clipPath>
        </defs>

        <g clip-path="url(#sceneClip)">
            <g opacity="0.15">
                <rect x="0" y="480" width="1440" height="420" fill="#060c18"/>
                <rect x="40" y="560" width="30" height="140" fill="#0d1f38"/>
                <rect x="75" y="540" width="50" height="160" fill="#102540"/>
                <rect x="130" y="520" width="35" height="180" fill="#0e2240"/>
                <rect x="200" y="505" width="60" height="195" fill="#112545"/>
                <rect x="310" y="490" width="80" height="210" fill="#122848"/>
                <rect x="900" y="500" width="70" height="200" fill="#0f2040"/>
                <rect x="1025" y="490" width="90" height="210" fill="#122848"/>
                <rect x="1165" y="510" width="60" height="190" fill="#102040"/>
            </g>

            <rect x="0" y="700" width="1440" height="200" fill="url(#road)"/>
            <g stroke="rgba(255,255,255,0.08)" stroke-width="2" stroke-dasharray="60 40">
                <line x1="0" y1="770" x2="1440" y2="770"/>
            </g>

            <g transform="translate(870, 568)" filter="url(#glow)">
                <rect x="-230" y="0" width="230" height="130" rx="4" fill="url(#truckBody)" stroke="rgba(255,255,255,0.05)" stroke-width="1"/>
                <text x="-115" y="72" font-family="Barlow Condensed, sans-serif" font-size="20" font-weight="700" fill="rgba(255,255,255,0.12)" text-anchor="middle" letter-spacing="4">CBN LOGISTICS</text>
                <rect x="0" y="15" width="120" height="115" rx="6" fill="url(#truckCab)" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                <rect x="108" y="55" width="14" height="8" rx="2" fill="#FFF8D0" opacity="0.6"/>
            </g>

            <g transform="translate(120, 620)" opacity="0.6">
                <rect x="0" y="0" width="160" height="80" rx="5" fill="url(#vanBody)" stroke="rgba(255,255,255,0.05)" stroke-width="1"/>
            </g>

            <rect x="0" y="0" width="1440" height="900" fill="url(#skyGrad)"/>
        </g>
    </svg>

    <div class="left-panel">
        <div class="lp-badge">
            <div class="lp-badge-dot"></div>
            Sistem Aktif & Terpantau
        </div>
        <div class="lp-title">Citra Buana<br><span>Nusantara</span></div>
        <div class="lp-desc">Platform manajemen logistik terpadu untuk efisiensi operasional real-time dan pengiriman terukur.</div>
        <div class="stat-row">
            <div class="stat">
                <div class="stat-num">2.4K+</div>
                <div class="stat-lbl">Shipment/Mo</div>
            </div>
            <div class="stat-sep"></div>
            <div class="stat">
                <div class="stat-num">98.2%</div>
                <div class="stat-lbl">On-Time Rate</div>
            </div>
            <div class="stat-sep"></div>
            <div class="stat">
                <div class="stat-num">150+</div>
                <div class="stat-lbl">Active Fleet</div>
            </div>
        </div>
    </div>

    <div class="login-container">
        <div class="glass-card">
            <div class="brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 3h14v11H1zM15 7h4l3 3v4h-7V7zM5.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM18.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
                <div class="brand-text-group">
                    <div class="brand-name">CBN Logistics ERP</div>
                    <div class="brand-sub">PT. Citra Buana Nusantara</div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="heading">Selamat Datang</div>
            <div class="sub-heading">Silakan masuk untuk mengelola operasional logistik</div>

            <form wire:submit.prevent="authenticate">
                <div class="field">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="3"/><path d="m2 7 10 7 10-7"/>
                        </svg>
                        <input type="email" id="email" wire:model.defer="data.email" placeholder="admin@cbn-logistics.co.id" autocomplete="email" required autofocus/>
                    </div>
                    @error('data.email')
                        <p class="error-text">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <input type="password" id="password" wire:model.defer="data.password" placeholder="••••••••" autocomplete="current-password" required/>
                        <button class="toggle-pw" onclick="togglePw()" type="button" aria-label="Tampilkan password">
                            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('data.password')
                        <p class="error-text">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="remember-row">
                    <label class="checkbox-label" for="remember">
                        <input type="checkbox" id="remember" wire:model.defer="data.remember"/>
                        <div class="checkbox-box">
                            <svg viewBox="0 0 12 12"><polyline points="2,6 5,9 10,3"/></svg>
                        </div>
                        Ingat Saya
                    </label>
                    @if (filament()->hasPasswordReset())
                        <a href="{{ filament()->getForgotPasswordUrl() }}" class="forgot-link">Lupa Password?</a>
                    @endif
                </div>

                <button class="btn-signin" type="submit" wire:loading.attr="disabled" wire:target="authenticate">
                    <span wire:loading.remove wire:target="authenticate">Masuk Portal &rarr;</span>
                    <span wire:loading wire:target="authenticate">Memverifikasi Akses...</span>
                </button>
            </form>

            <div class="card-footer">
                &copy; {{ date('Y') }} PT. Citra Buana Nusantara &mdash; CBN Logistics ERP
            </div>
        </div>
    </div>

    <script>
        function togglePw() {
            const inp = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                inp.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }
    </script>
</div>
