<div class="login-page-wrapper">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@500;700&display=swap" rel="stylesheet">
    <style>
        .login-page-wrapper {
            --orange: #F97316;
            --orange-light: #FB923C;
            --orange-glow: rgba(249, 115, 22, 0.35);
            --navy: #0B1628;
            --navy-mid: #112040;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.12);
            --glass-shine: rgba(255, 255, 255, 0.06);
            --text-primary: #F0F4FF;
            --text-muted: rgba(200, 215, 240, 0.6);
            --input-bg: rgba(255, 255, 255, 0.05);
            --input-border: rgba(255, 255, 255, 0.1);
            --input-focus: rgba(249, 115, 22, 0.4);

            position: fixed;
            inset: 0;
            height: 100vh;
            width: 100vw;
            font-family: 'Barlow', sans-serif;
            background: var(--navy);
            overflow: hidden;
            margin: 0;
            z-index: 9999;
        }

        .bg-scene {
            position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 80%, rgba(249,115,22,0.15) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 85% 10%, rgba(30,80,160,0.2) 0%, transparent 55%),
                linear-gradient(145deg, #060e1e 0%, #0d1f3c 40%, #0a1628 70%, #050c1a 100%);
        }

        .noise {
            position: absolute; inset: 0; z-index: 2;
            opacity: 0.04;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        .grid-overlay {
            position: absolute; inset: 0; z-index: 2;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .page-container {
            position: relative; z-index: 10;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 8% 0 0;
        }

        @media (max-width: 1024px) {
            .page-container {
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
            backdrop-filter: blur(24px) saturate(160%);
            -webkit-backdrop-filter: blur(24px) saturate(160%);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 44px 40px 40px;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.05) inset,
                0 40px 80px rgba(0,0,0,0.5),
                0 0 60px rgba(249,115,22,0.05);
            animation: cardIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
            position: relative;
            overflow: hidden;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, #F97316, #EA580C);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 20px rgba(249,115,22,0.3);
        }

        .brand-icon svg { width: 28px; height: 28px; fill: #fff; }

        .brand-name {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 18px; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase;
            color: var(--text-primary);
            line-height: 1.1;
        }
        .brand-sub {
            font-size: 11px; font-weight: 400;
            color: var(--orange-light);
            letter-spacing: 0.1em; text-transform: uppercase;
            opacity: 0.8;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
            margin-bottom: 28px;
        }

        .heading {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 32px; font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.01em;
            margin-bottom: 6px;
        }
        .sub-heading {
            font-size: 14px; color: var(--text-muted);
            margin-bottom: 32px;
        }

        .field { margin-bottom: 20px; }

        .field label {
            display: block;
            font-size: 12px; font-weight: 600;
            letter-spacing: 0.05em; text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .input-wrap { position: relative; }

        .input-wrap svg.icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 18px; height: 18px; stroke: rgba(200,215,240,0.3);
            pointer-events: none; transition: stroke 0.2s;
        }

        .field input {
            width: 100%; height: 50px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: 'Barlow', sans-serif;
            font-size: 15px;
            padding: 0 44px;
            outline: none;
            transition: all 0.2s;
        }

        .field input:focus {
            border-color: var(--orange);
            background: rgba(255,255,255,0.08);
            box-shadow: 0 0 0 4px rgba(249,115,22,0.1);
        }

        .toggle-pw {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; color: var(--text-muted);
            display: flex; padding: 4px; opacity: 0.6; transition: opacity 0.2s;
        }
        .toggle-pw:hover { opacity: 1; }

        .remember-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 30px;
        }

        .checkbox-label {
            display: flex; align-items: center; gap: 10px;
            cursor: pointer; font-size: 14px; color: var(--text-muted);
        }
        .checkbox-label input { display: none; }
        .checkbox-box {
            width: 18px; height: 18px; border: 1px solid var(--input-border);
            border-radius: 5px; background: var(--input-bg);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .checkbox-label input:checked ~ .checkbox-box {
            background: var(--orange); border-color: var(--orange);
        }
        .checkbox-box svg { width: 12px; height: 12px; stroke: #fff; stroke-width: 3; fill: none; opacity: 0; }
        .checkbox-label input:checked ~ .checkbox-box svg { opacity: 1; }

        .forgot-link { font-size: 13px; color: var(--orange-light); text-decoration: none; opacity: 0.8; }
        .forgot-link:hover { opacity: 1; }

        .btn-signin {
            width: 100%; height: 54px;
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
            border: none; border-radius: 14px;
            color: #fff; font-family: 'Barlow Condensed', sans-serif;
            font-size: 18px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;
            cursor: pointer; box-shadow: 0 6px 24px rgba(249,115,22,0.3);
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center;
        }
        .btn-signin:hover { transform: translateY(-1px); box-shadow: 0 8px 30px rgba(249,115,22,0.4); }
        .btn-signin:active { transform: translateY(0); }

        .card-footer {
            margin-top: 24px; text-align: center;
            font-size: 12px; color: var(--text-muted); opacity: 0.6;
        }

        .left-panel {
            position: absolute; left: 8%; top: 50%; transform: translateY(-50%);
            z-index: 10; width: 440px;
        }

        .lp-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.2);
            border-radius: 99px; padding: 6px 16px;
            font-size: 12px; font-weight: 600; color: var(--orange-light);
            text-transform: uppercase; margin-bottom: 24px;
        }
        .lp-badge-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--orange); animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(249,115,22,0.7); } 70% { box-shadow: 0 0 0 8px rgba(249,115,22,0); } 100% { box-shadow: 0 0 0 0 rgba(249,115,22,0); } }

        .lp-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 64px; font-weight: 700; line-height: 0.95;
            color: var(--text-primary); text-transform: uppercase; margin-bottom: 20px;
        }
        .lp-title span { color: var(--orange); }

        .lp-desc { font-size: 16px; color: var(--text-muted); line-height: 1.6; margin-bottom: 40px; }

        .stat-row { display: flex; gap: 32px; }
        .stat { display: flex; flex-direction: column; }
        .stat-num { font-family: 'Barlow Condensed', sans-serif; font-size: 32px; font-weight: 700; color: var(--text-primary); }
        .stat-lbl { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; }
        .stat-sep { width: 1px; background: var(--glass-border); height: 40px; align-self: center; }

        .error-msg {
            background: rgba(239, 68, 68, 0.1); border-left: 3px solid #ef4444;
            padding: 10px 14px; border-radius: 6px; margin-bottom: 20px;
            font-size: 13px; color: #f87171; display: flex; align-items: center; gap: 8px;
        }

        .bg-illustration {
            position: absolute; bottom: 0; left: 0; width: 100%; height: auto;
            max-height: 40vh; z-index: 1; pointer-events: none; opacity: 0.4;
        }
    </style>

    <div class="bg-scene"></div>
    <div class="noise"></div>
    <div class="grid-overlay"></div>

    <svg class="bg-illustration" viewBox="0 0 1440 300" preserveAspectRatio="xMidYMax slice" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 250H1440" stroke="white" stroke-opacity="0.05" stroke-width="2"/>
        <path d="M0 270H1440" stroke="white" stroke-opacity="0.03" stroke-width="1"/>
        <g opacity="0.2">
            <rect x="1000" y="150" width="200" height="100" rx="4" fill="#1e3a5f"/>
            <rect x="1200" y="180" width="80" height="70" rx="4" fill="#243d63"/>
            <circle cx="1040" cy="250" r="12" fill="#060c18"/>
            <circle cx="1160" cy="250" r="12" fill="#060c18"/>
        </g>
    </svg>

    <div class="left-panel">
        <div class="lp-badge">
            <div class="lp-badge-dot"></div>
            CBN ERP - Operational System
        </div>
        <div class="lp-title">Logistic<br><span>Intelligence</span></div>
        <div class="lp-desc">Manajemen logistik end-to-end dengan efisiensi tinggi.<br>Pantau pengiriman dan armada secara real-time.</div>
        <div class="stat-row">
            <div class="stat">
                <div class="stat-num">2.4K+</div>
                <div class="stat-lbl">Shipment/Mo</div>
            </div>
            <div class="stat-sep"></div>
            <div class="stat">
                <div class="stat-num">98.2%</div>
                <div class="stat-lbl">On-Time</div>
            </div>
            <div class="stat-sep"></div>
            <div class="stat">
                <div class="stat-num">150+</div>
                <div class="stat-lbl">Active Fleet</div>
            </div>
        </div>
    </div>

    <div class="page-container">
        <div class="glass-card">
            <div class="brand">
                <div class="brand-icon" style="background: white; padding: 4px; border-radius: 8px; display: flex; align-items: center; justify-content: center; width: 44px; height: 44px;">
                    <img src="{{ asset('images/logo.png') }}" alt="CBN Logo" style="width: 32px; height: auto;">
                </div>
                <div class="brand-text" style="display: flex; flex-direction: column; line-height: 1.1;">
                    <div class="brand-name" style="font-size: 21px; font-weight: 900; color: #F0F4FF; letter-spacing: -0.02em;">CITRA BUANA</div>
                    <div class="brand-sub" style="font-size: 16px; font-weight: 800; color: var(--orange); letter-spacing: 0.12em; margin-top: -2px;">NUSANTARA</div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="heading">Selamat Datang</div>
            <div class="sub-heading">Masuk ke akun Anda untuk melanjutkan</div>

            @if (session('status'))
                <div class="error-msg" style="border-left-color: #10b981; color: #34d399; background: rgba(16, 185, 129, 0.1);">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit.prevent="authenticate">
                <div class="field">
                    <label>Alamat Email</label>
                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" wire:model="data.email" placeholder="admin@cbn-logistics.co.id" required autofocus>
                    </div>
                    @error('data.email') <span class="error-msg" style="background:none; border:none; padding:4px 0; margin:0;">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label>Kata Sandi</label>
                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="password" wire:model="data.password" placeholder="••••••••" required>
                        <button type="button" class="toggle-pw" onclick="togglePassword()">
                            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('data.password') <span class="error-msg" style="background:none; border:none; padding:4px 0; margin:0;">{{ $message }}</span> @enderror
                </div>

                <div class="remember-row">
                    <label class="checkbox-label">
                        <input type="checkbox" wire:model="data.remember">
                        <div class="checkbox-box">
                            <svg viewBox="0 0 12 12"><polyline points="2,6 5,9 10,3"/></svg>
                        </div>
                        Ingat Saya
                    </label>
                    @if (filament()->hasPasswordReset())
                        <a href="{{ filament()->getForgotPasswordUrl() }}" class="forgot-link">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-signin" wire:loading.attr="disabled" wire:target="authenticate">
                    <span wire:loading.remove wire:target="authenticate">Masuk Portal &rarr;</span>
                    <span wire:loading wire:target="authenticate">Memverifikasi...</span>
                </button>
            </form>

            <div class="card-footer">
                &copy; {{ date('Y') }} PT. Citra Buana Nusantara
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                pw.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }
    </script>
</div>
