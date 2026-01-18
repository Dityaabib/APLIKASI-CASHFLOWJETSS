<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Admin - Cashflow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #e63946;
            --light: #f8f9fa;
            --dark: #0f172a;
            --sidebar-width: 260px;
            --card-shadow: 0 10px 20px rgba(0,0,0,0.08);
            --card-hover: 0 18px 32px rgba(15,23,42,0.18);
            --transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: radial-gradient(circle at top, rgba(67, 97, 238, 0.12), transparent 60%), #f4f7fe;
            color: #4a5568;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: white;
            color: #2d3748;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            box-shadow: 2px 0 20px rgba(0,0,0,0.05);
        }

        .sidebar-header {
            padding: 2.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-header i {
            font-size: 2rem;
            color: var(--primary);
            filter: drop-shadow(0 4px 6px rgba(67, 97, 238, 0.3));
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.5px;
        }

        .sidebar-menu {
            padding: 1rem;
            flex-grow: 1;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: #718096;
            text-decoration: none;
            border-radius: 16px;
            margin-bottom: 0.8rem;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .menu-item:hover,
        .menu-item.active {
            background: linear-gradient(135deg, #4361ee, #3f37c9);
            color: white;
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.28);
            transform: translateY(-2px);
        }

        .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.2rem;
        }

        .sidebar-footer {
            padding: 2rem;
        }

        .logout-btn {
            width: 100%;
            padding: 1rem;
            background: #fff5f5;
            color: var(--danger);
            border: 1px solid rgba(230, 57, 70, 0.1);
            border-radius: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
            transition: var(--transition);
        }

        .logout-btn:hover {
            background: var(--danger);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(230, 57, 70, 0.2);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 3rem;
            width: calc(100% - var(--sidebar-width));
        }

        .header {
            margin-bottom: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .header-title h1 {
            font-size: 2.1rem;
            font-weight: 700;
            color: #111827;
        }

        .header-title p {
            font-size: 0.95rem;
            color: #6b7280;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(67, 97, 238, 0.08);
            color: #1d4ed8;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .settings-layout {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1.4fr);
            gap: 2rem;
            align-items: flex-start;
        }

        .settings-card {
            background: white;
            border-radius: 24px;
            padding: 1.75rem 1.75rem 1.9rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(15,23,42,0.03);
            position: relative;
            overflow: hidden;
        }

        .settings-card:hover {
            box-shadow: var(--card-hover);
            transform: translateY(-4px);
        }

        .settings-card::before {
            content: "";
            position: absolute;
            top: -60px;
            right: -60px;
            width: 160px;
            height: 160px;
            border-radius: 999px;
            background: radial-gradient(circle at center, rgba(67,97,238,0.18), transparent 70%);
            opacity: 0.9;
        }

        .settings-card.compact::before {
            display: none;
        }

        .settings-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.4rem;
        }

        .settings-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .settings-icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .settings-icon,
        .preview-card,
        .preview-icon {
            transition: var(--transition);
        }

        .settings-text h2 {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
        }

        .settings-text p {
            font-size: 0.82rem;
            color: #9ca3af;
        }

        .settings-group {
            margin-top: 0.8rem;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .settings-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .settings-label {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .settings-label span {
            font-size: 0.88rem;
            font-weight: 600;
            color: #111827;
        }

        .settings-label small {
            font-size: 0.78rem;
            color: #9ca3af;
        }

        .settings-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .switch {
            position: relative;
            display: inline-flex;
            align-items: center;
            width: 50px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            border-radius: 999px;
            transition: var(--transition);
        }

        .slider::before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            top: 3px;
            background-color: white;
            border-radius: 50%;
            transition: var(--transition);
            box-shadow: 0 4px 8px rgba(15,23,42,0.15);
        }

        .switch input:checked + .slider {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .switch input:checked + .slider::before {
            transform: translateX(24px);
            box-shadow: 0 6px 14px rgba(16,185,129,0.45);
        }

        select,
        input[type="number"],
        input[type="text"] {
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            padding: 6px 14px;
            font-size: 0.82rem;
            color: #111827;
            outline: none;
            background: #f9fafb;
            transition: var(--transition);
        }

        select:focus,
        input[type="number"]:focus,
        input[type="text"]:focus {
            border-color: #4361ee;
            background: #ffffff;
            box-shadow: 0 0 0 1px rgba(67, 97, 238, 0.18);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #374151;
            background: #f3f4f6;
        }

        .pill i {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .preview-card {
            margin-top: 0.6rem;
            border-radius: 18px;
            padding: 1.1rem 1.25rem;
            background: linear-gradient(135deg, #eef2ff, #e0f2fe);
            border: 1px solid rgba(129, 140, 248, 0.4);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .preview-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(15,23,42,0.18);
        }

        .preview-icon {
            width: 32px;
            height: 32px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: #4338ca;
            box-shadow: 0 3px 6px rgba(15,23,42,0.08);
        }

        .preview-card:hover .preview-icon {
            transform: scale(1.06) rotate(-3deg);
        }

        .preview-text h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827;
        }

        .preview-text p {
            font-size: 0.78rem;
            color: #4b5563;
        }

        .toast {
            position: fixed;
            right: 24px;
            bottom: 24px;
            min-width: 260px;
            max-width: 320px;
            background: #0f172a;
            color: white;
            padding: 12px 16px;
            border-radius: 14px;
            box-shadow: 0 15px 30px rgba(15,23,42,0.45);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 200;
        }

        .toast.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .toast i {
            color: #4ade80;
        }

        @media (max-width: 960px) {
            .main-content {
                padding: 1.75rem 1.5rem;
            }

            .settings-layout {
                grid-template-columns: minmax(0, 1fr);
            }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-bolt"></i>
            <h2>Cashflow</h2>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="menu-item">
                <i class="fas fa-user-friends"></i> Pengguna
            </a>
            <a href="{{ route('admin.users.transactions') }}" class="menu-item">
                <i class="fas fa-users-gear"></i> Transaksi
            </a>
            <a href="{{ route('admin.analysis') }}" class="menu-item">
                <i class="fas fa-chart-line"></i> Analisis
            </a>
            <a href="{{ route('admin.settings') }}" class="menu-item active">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="header-title">
                <h1>Pengaturan Admin</h1>
                <p>Atur preferensi tampilan dan perilaku dashboard admin agar sesuai gaya kerja.</p>
            </div>
            <div>
                <span class="header-badge">
                    <i class="fas fa-sparkles"></i>
                    Mode profesional dan halus
                </span>
            </div>
        </header>

        <div class="settings-layout">
            <section class="settings-card">
                <div class="settings-header">
                    <div class="settings-title">
                        <div class="settings-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div class="settings-text">
                            <h2>Tampilan & Tema</h2>
                            <p>Sempurnakan nuansa visual dashboard admin.</p>
                        </div>
                    </div>
                </div>

                <div class="settings-group">
                    <div class="settings-row">
                        <div class="settings-label">
                            <span>Mode Gelap Admin</span>
                            <small>Kurangi silau dan buat tampilan lebih elegan saat malam hari.</small>
                        </div>
                        <div class="settings-control">
                            <label class="switch">
                                <input id="setting-theme-dark" type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="settings-row">
                        <div class="settings-label">
                            <span>Animasi Kartu Statistik</span>
                            <small>Aktifkan animasi halus untuk kartu total pengguna dan server.</small>
                        </div>
                        <div class="settings-control">
                            <label class="switch">
                                <input id="setting-stats-animated" type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="settings-row">
                        <div class="settings-label">
                            <span>Kepadatan Daftar Pengguna</span>
                            <small>Atur berapa banyak baris yang terlihat di setiap panel daftar.</small>
                        </div>
                        <div class="settings-control">
                            <select id="setting-max-items">
                                <option value="5">Ringkas (5 baris)</option>
                                <option value="10">Seimbang (10 baris)</option>
                                <option value="15">Detail (15 baris)</option>
                                <option value="0">Semua (tanpa batas)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="preview-theme" class="preview-card">
                    <div id="preview-theme-icon" class="preview-icon">
                        <i class="fas fa-moon"></i>
                    </div>
                    <div class="preview-text">
                        <h3 id="preview-theme-title">Pratinjau Langsung</h3>
                        <p id="preview-theme-desc">Perubahan tema dan animasi akan terasa langsung saat membuka dashboard admin.</p>
                    </div>
                </div>
            </section>

            <section class="settings-card compact">
                <div class="settings-header">
                    <div class="settings-title">
                        <div class="settings-icon" style="background: rgba(16,185,129,0.12); color:#059669;">
                            <i class="fas fa-sliders"></i>
                        </div>
                        <div class="settings-text">
                            <h2>Perilaku Dashboard</h2>
                            <p>Kontrol refresh dan responsivitas tampilan admin.</p>
                        </div>
                    </div>
                </div>

                <div class="settings-group">
                    <div class="settings-row">
                        <div class="settings-label">
                            <span>Refresh Otomatis Status Server</span>
                            <small>Perbarui jumlah pengguna online secara berkala tanpa reload halaman.</small>
                        </div>
                        <div class="settings-control">
                            <label class="switch">
                                <input id="setting-server-refresh" type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="settings-row">
                        <div class="settings-label">
                            <span>Interval Refresh (detik)</span>
                            <small>Semakin kecil, semakin sering data diambil dari server.</small>
                        </div>
                        <div class="settings-control">
                            <input id="setting-server-interval" type="number" min="5" max="120" step="5" value="10" style="width:90px;">
                            <span class="pill">
                                <i class="fas fa-gauge-high"></i>
                                Rekomendasi: 10–20
                            </span>
                        </div>
                    </div>

                    <div class="settings-row">
                        <div class="settings-label">
                            <span>Highlight Online</span>
                            <small>Pertegas efek visual untuk pengguna yang sedang online.</small>
                        </div>
                        <div class="settings-control">
                            <label class="switch">
                                <input id="setting-highlight-online" type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div id="preview-behavior" class="preview-card" style="margin-top:1.4rem;background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-color:rgba(16,185,129,0.35);">
                    <div id="preview-behavior-icon" class="preview-icon" style="color:#059669;">
                        <i class="fas fa-signal"></i>
                    </div>
                    <div class="preview-text">
                        <h3 id="preview-behavior-title">Dashboard Lebih Hidup</h3>
                        <p id="preview-behavior-desc">Dengan pengaturan ini, panel admin terasa ringan, konsisten, dan responsif.</p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <div id="settingsToast" class="toast">
        <i class="fas fa-circle-check"></i>
        <span>Pengaturan tersimpan. Buka kembali dashboard admin untuk melihat efeknya.</span>
    </div>

    <script>
        (function () {
            var STORAGE_KEY = 'adminSettings';

            function loadSettings() {
                try {
                    var raw = localStorage.getItem(STORAGE_KEY);
                    if (!raw) return {};
                    var parsed = JSON.parse(raw);
                    if (!parsed || typeof parsed !== 'object') return {};
                    return parsed;
                } catch (e) {
                    return {};
                }
            }

            function saveSettings(s) {
                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(s));
                    showToast();
                } catch (e) {}
            }

            function showToast() {
                var toast = document.getElementById('settingsToast');
                if (!toast) return;
                toast.classList.add('show');
                setTimeout(function () {
                    toast.classList.remove('show');
                }, 2200);
            }

            function updatePreviews(s) {
                var themeCard = document.getElementById('preview-theme');
                var themeIconWrap = document.getElementById('preview-theme-icon');
                var themeDesc = document.getElementById('preview-theme-desc');
                var behaviorCard = document.getElementById('preview-behavior');
                var behaviorIconWrap = document.getElementById('preview-behavior-icon');
                var behaviorDesc = document.getElementById('preview-behavior-desc');

                if (themeCard && themeIconWrap) {
                    var dark = s.themeDark === true;
                    var animated = s.statsAnimated !== false;
                    themeCard.style.background = dark
                        ? 'linear-gradient(135deg,#020617,#111827)'
                        : 'linear-gradient(135deg,#eef2ff,#e0f2fe)';
                    themeCard.style.borderColor = dark
                        ? 'rgba(15,23,42,0.7)'
                        : 'rgba(129,140,248,0.4)';
                    themeIconWrap.style.background = dark ? '#020617' : '#ffffff';
                    themeIconWrap.style.color = dark ? '#e5e7eb' : '#4338ca';
                    var icon = themeIconWrap.querySelector('i');
                    if (icon) {
                        icon.className = dark ? 'fas fa-moon' : 'fas fa-sun';
                    }
                    if (themeDesc) {
                        if (animated) {
                            themeDesc.textContent = 'Tema dan animasi halus aktif untuk kartu dan daftar dashboard.';
                        } else {
                            themeDesc.textContent = 'Tema disimpan, animasi dimatikan agar tampilan super ringan.';
                        }
                    }
                }

                if (behaviorCard && behaviorIconWrap) {
                    var enabled = s.serverRefreshEnabled !== false;
                    var intervalMs = parseInt(s.serverRefreshInterval || 10000, 10);
                    if (isNaN(intervalMs) || intervalMs < 5000) intervalMs = 10000;
                    var highlight = s.highlightOnline === true;
                    behaviorCard.style.background = enabled
                        ? 'linear-gradient(135deg,#ecfdf5,#d1fae5)'
                        : 'linear-gradient(135deg,#f9fafb,#e5e7eb)';
                    behaviorCard.style.borderColor = enabled
                        ? 'rgba(16,185,129,0.45)'
                        : 'rgba(148,163,184,0.45)';
                    var bIcon = behaviorIconWrap.querySelector('i');
                    if (bIcon) {
                        bIcon.className = enabled ? 'fas fa-signal' : 'fas fa-pause-circle';
                    }
                    if (behaviorDesc) {
                        var seconds = Math.round(intervalMs / 1000);
                        var text = enabled
                            ? 'Refresh otomatis aktif setiap ' + seconds + ' detik'
                            : 'Refresh otomatis dinonaktifkan';
                        if (highlight) {
                            text += ', highlight online diperkuat.';
                        } else {
                            text += ', highlight online standar.';
                        }
                        behaviorDesc.textContent = text;
                    }
                }
            }

            function init() {
                var s = loadSettings();

                var themeDark = document.getElementById('setting-theme-dark');
                var statsAnimated = document.getElementById('setting-stats-animated');
                var maxItems = document.getElementById('setting-max-items');
                var serverRefresh = document.getElementById('setting-server-refresh');
                var serverInterval = document.getElementById('setting-server-interval');
                var highlightOnline = document.getElementById('setting-highlight-online');

                if (themeDark) {
                    themeDark.checked = s.themeDark === true;
                    themeDark.addEventListener('change', function () {
                        s.themeDark = themeDark.checked;
                        saveSettings(s);
                        updatePreviews(s);
                    });
                }

                if (statsAnimated) {
                    statsAnimated.checked = s.statsAnimated !== false;
                    statsAnimated.addEventListener('change', function () {
                        s.statsAnimated = statsAnimated.checked;
                        saveSettings(s);
                        updatePreviews(s);
                    });
                }

                if (maxItems) {
                    var maxVal = typeof s.maxItemsPerList === 'number' ? String(s.maxItemsPerList) : (s.maxItemsPerList || '10');
                    if (['0', '5', '10', '15'].indexOf(maxVal) === -1) {
                        maxVal = '10';
                    }
                    maxItems.value = maxVal;
                    maxItems.addEventListener('change', function () {
                        var v = parseInt(maxItems.value, 10);
                        if (isNaN(v)) v = 10;
                        s.maxItemsPerList = v;
                        saveSettings(s);
                        updatePreviews(s);
                    });
                }

                if (serverRefresh) {
                    serverRefresh.checked = s.serverRefreshEnabled !== false;
                    serverRefresh.addEventListener('change', function () {
                        s.serverRefreshEnabled = serverRefresh.checked;
                        saveSettings(s);
                        updatePreviews(s);
                    });
                }

                if (serverInterval) {
                    var interval = parseInt(s.serverRefreshInterval || 10000, 10);
                    if (isNaN(interval) || interval < 5000) interval = 10000;
                    serverInterval.value = Math.round(interval / 1000);
                    serverInterval.addEventListener('change', function () {
                        var v = parseInt(serverInterval.value, 10);
                        if (isNaN(v) || v < 5) v = 10;
                        if (v > 120) v = 120;
                        serverInterval.value = v;
                        s.serverRefreshInterval = v * 1000;
                        saveSettings(s);
                        updatePreviews(s);
                    });
                }

                if (highlightOnline) {
                    highlightOnline.checked = s.highlightOnline === true;
                    highlightOnline.addEventListener('change', function () {
                        s.highlightOnline = highlightOnline.checked;
                        saveSettings(s);
                        updatePreviews(s);
                    });
                }

                updatePreviews(s);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
</body>
</html>
