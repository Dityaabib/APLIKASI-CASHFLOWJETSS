<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Baru - Analisis - Cashflow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --primary: #4361ee; --secondary: #3f37c9; --success: #4cc9f0; --info: #4895ef; --warning: #f72585; --danger: #e63946; --light: #f8f9fa; --dark: #212529; --sidebar-width: 260px; --card-shadow: 0 10px 20px rgba(0,0,0,0.08); --card-hover: 0 15px 30px rgba(0,0,0,0.12); --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7fe; color: #4a5568; display: flex; min-height: 100vh; }
        .sidebar { width: var(--sidebar-width); background: white; position: fixed; height: 100vh; box-shadow: 2px 0 20px rgba(0,0,0,0.05); }
        .sidebar-header { padding: 2.5rem 2rem; display: flex; align-items: center; gap: 12px; }
        .sidebar-header i { font-size: 2rem; color: var(--primary); }
        .sidebar-header h2 { font-size: 1.5rem; font-weight: 700; color: var(--dark); }
        .sidebar-menu { padding: 1rem; }
        .menu-item { display: flex; align-items: center; padding: 1rem 1.5rem; color: #718096; text-decoration: none; border-radius: 16px; margin-bottom: 0.8rem; transition: var(--transition); font-weight: 500; position: relative; overflow: hidden; }
        .menu-item:hover, .menu-item.active { background: var(--primary); color: white; box-shadow: 0 8px 16px rgba(67, 97, 238, 0.25); transform: translateY(-2px); }
        .menu-item i { width: 24px; margin-right: 12px; font-size: 1.2rem; }
        .menu-item-new { opacity: 0; transform: translateX(18px); background: linear-gradient(135deg, #f9fafb, #eef2ff); box-shadow: 0 12px 26px rgba(15,23,42,0.12); border: none; color: #111827; animation: submenuFadeIn 0.5s cubic-bezier(0.22, 0.61, 0.36, 1) 0.18s forwards; }
        .menu-item-new i { color: #4f46e5; }
        .menu-item-new.active { background: linear-gradient(135deg, #06b6d4, #6366f1); color: #f9fafb; box-shadow: 0 18px 40px rgba(59,130,246,0.55); border-color: transparent; }
        .menu-item-new.active i { color: #f9fafb; }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 3rem; display: flex; flex-direction: column; }
        .header { margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 2rem; font-weight: 700; color: #1a202c; }

        .user-analysis-section { margin-top: 1rem; }
        .user-analysis-header { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 10px; }
        .user-analysis-title { font-size: 1.05rem; font-weight: 600; color: #1a202c; display: flex; align-items: center; gap: 8px; }
        .user-analysis-sub { font-size: 0.85rem; color: #a0aec0; }
        .user-list-grid { column-count: 2; column-gap: 10px; }
        .user-card { background: white; border-radius: 18px; padding: 1.2rem; box-shadow: var(--card-shadow); border: 1px solid rgba(148,163,184,0.16); display: inline-block; width: 100%; margin-bottom: 10px; break-inside: avoid; }
        .user-card-header { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 44px; height: 44px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-weight: 600; color: white; background: linear-gradient(135deg, #6366f1, #ec4899); overflow: hidden; }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: inherit; }
        .user-info-main { flex: 1; min-width: 0; }
        .user-name { font-size: 0.95rem; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-meta { font-size: 0.8rem; color: #9ca3af; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-tag { font-size: 0.75rem; padding: 3px 8px; border-radius: 999px; background: #ecfdf5; color: #047857; border: 1px solid rgba(16,185,129,0.3); }
        .user-metrics { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; grid-auto-rows: 1fr; }
        .user-metric-card { background: #f9fafb; border-radius: 12px; padding: 0.7rem 0.8rem; border: 1px solid #e5e7eb; display: flex; flex-direction: column; gap: 6px; }
        .user-metric-card.savings { grid-column: 1 / -1; }
        .user-metric-label { font-size: 0.8rem; color: #9ca3af; display: flex; align-items: center; gap: 6px; }
        .user-metric-value { font-size: 1rem; font-weight: 600; color: #111827; }
        .user-metric-icon { width: 28px; height: 28px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem; }
        .user-metric-icon.income { background: linear-gradient(135deg, rgba(16,185,129,0.16), rgba(34,197,94,0.16)); color: #0f766e; border: 1px solid rgba(16,185,129,0.25); }
        .user-metric-icon.expense { background: linear-gradient(135deg, rgba(248,113,113,0.16), rgba(239,68,68,0.16)); color: #b91c1c; border: 1px solid rgba(239,68,68,0.25); }
        .user-metric-icon.savings { background: linear-gradient(135deg, rgba(168,85,247,0.16), rgba(245,158,11,0.16)); color: #7e22ce; border: 1px solid rgba(168,85,247,0.25); }
        .user-metric-card.savings { grid-column: 1 / -1; flex-direction: row; justify-content: space-between; align-items: center; padding-right: 1.2rem; }
        .user-metric-content { display: flex; flex-direction: column; gap: 6px; }
        .user-metric-percent { display: flex; align-items: center; gap: 10px; }
        .percent-badge { display: flex; flex-direction: column; align-items: flex-end; }
        .percent-val { font-size: 1rem; font-weight: 700; background: linear-gradient(135deg, #a855f7, #d946ef); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .percent-label { font-size: 0.7rem; color: #a0aec0; }
        .percent-icon-circle { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #a855f7, #f59e0b); color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 4px 12px rgba(168, 85, 247, 0.35); }
        .user-savings-pie-wrap { margin-top: 4px; margin-bottom: 4px; display: flex; justify-content: center; align-items: center; }
        .user-savings-pie-inner { position: relative; width: 72px; height: 72px; }
        .user-savings-pie-canvas { width: 72px; height: 72px; }
        .user-savings-pie-center { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; color: #111827; pointer-events: none; }
        .user-budget-section { margin-top: 10px; display: flex; flex-direction: column; gap: 8px; }
        .user-budget-title { font-weight: 600; font-size: 0.85rem; color: #111827; }
        .user-budget-sub { font-size: 0.78rem; color: #9ca3af; }
        .user-budget-list { margin-top: 4px; display: flex; flex-direction: column; gap: 10px; max-height: 260px; overflow-y: auto; padding-right: 4px; scroll-behavior: smooth; }
        .user-budget-item { display: grid; grid-template-columns: 40px minmax(0, 1fr); column-gap: 10px; row-gap: 4px; align-items: center; padding: 8px 10px; border-radius: 12px; border: 1px solid rgba(15,23,42,0.05); background: rgba(249,250,251,0.9); }
        .user-budget-icon { width: 40px; height: 40px; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; background: linear-gradient(135deg, rgba(59,130,246,0.96), rgba(139,92,246,0.96)); }
        .user-budget-main { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
        .user-budget-name-row { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
        .user-budget-name { font-size: 0.85rem; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-budget-amount { font-size: 0.8rem; font-weight: 600; color: #4b5563; }
        .user-budget-amount span { white-space: nowrap; }
        .user-budget-bar-wrap { margin-top: 2px; }
        .user-budget-bar-track { position: relative; height: 8px; border-radius: 999px; background: rgba(148,163,184,0.2); overflow: hidden; }
        .user-budget-bar-fill { position: absolute; inset: 0; width: 0%; border-radius: inherit; background: linear-gradient(90deg, rgba(236,72,153,0.95), rgba(249,115,22,0.98)); transition: width 0.35s ease-out; }
        .user-budget-bar-center { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 2px 8px; border-radius: 999px; background: #ffffff; font-size: 0.7rem; font-weight: 600; color: #1f2937; box-shadow: 0 6px 14px rgba(15,23,42,0.16); white-space: nowrap; }
        .user-budget-percent-row { margin-top: 4px; display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #6b7280; }
        .user-budget-percent-row .main { font-weight: 600; color: #111827; }
        .user-budget-list::-webkit-scrollbar { width: 6px; }
        .user-budget-list::-webkit-scrollbar-track { background: transparent; }
        .user-budget-list::-webkit-scrollbar-thumb { background: linear-gradient(180deg, rgba(99,102,241,0.45), rgba(236,72,153,0.55)); border-radius: 999px; }
        .user-budget-list::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, rgba(79,70,229,0.7), rgba(219,39,119,0.8)); }
        @media (max-width: 980px) {
            .user-list-grid { column-count: 1; }
        }

        .new-page-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; }
        .new-page-card { min-width: 320px; max-width: 640px; padding: 2.5rem 2.8rem; border-radius: 24px; background: radial-gradient(circle at top, rgba(129,140,248,0.18), rgba(15,23,42,0.05)), #ffffff; box-shadow: 0 24px 60px rgba(15,23,42,0.18); border: 1px solid rgba(148,163,184,0.35); animation: submenuFadeIn 0.4s cubic-bezier(0.22, 0.61, 0.36, 1) 0.1s both; }
        .new-page-icon { width: 54px; height: 54px; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.4rem; font-size: 1.7rem; background: linear-gradient(135deg, #4f46e5, #06b6d4); color: #f9fafb; box-shadow: 0 18px 40px rgba(59,130,246,0.55); }
        .new-page-title { font-size: 1.8rem; font-weight: 700; letter-spacing: -0.03em; color: #0f172a; margin-bottom: 0.8rem; }
        .new-page-sub { font-size: 0.98rem; color: #6b7280; line-height: 1.6; }
        .new-page-chip-row { display: flex; flex-wrap: wrap; gap: 0.55rem; margin-top: 1.5rem; }
        .new-page-chip { padding: 0.4rem 0.8rem; border-radius: 999px; font-size: 0.78rem; font-weight: 500; background: rgba(59,130,246,0.06); color: #1d4ed8; border: 1px solid rgba(129,140,248,0.6); }
        .analysis-search { position: relative; width: 320px; max-width: 100%; }
        .analysis-search input { width: 100%; border-radius: 999px; border: 1px solid rgba(209,213,219,0.9); padding: 0.7rem 2.6rem 0.7rem 2.6rem; font-size: 0.88rem; background: #f9fafc; outline: none; color: #111827; box-shadow: 0 14px 35px rgba(15,23,42,0.06); transition: box-shadow 0.18s ease, border-color 0.18s ease, background 0.18s ease, transform 0.18s ease; }
        .analysis-search input::placeholder { color: #9ca3af; }
        .analysis-search input:focus { border-color: #6366f1; box-shadow: 0 0px 15px rgba(79,70,229,0.26); background: #ffffff; transform: translateY(-1px); }
        .analysis-search i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); font-size: 0.95rem; color: #6366f1; }
        .analysis-search-badge { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 26px; height: 26px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 600; background: rgba(129,140,248,0.12); color: #4f46e5; border: 1px solid rgba(129,140,248,0.45); }
        @keyframes submenuFadeIn { from { opacity: 0; transform: translateX(18px); } to { opacity: 1; transform: translateX(0); } }
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
                <i class="fas fa-chart-line"></i> Analisis Sistem
            </a>
            <a href="{{ route('admin.analysis.new') }}" class="menu-item menu-item-new active">
                <i class="fas fa-users"></i> Analisis
            </a>
            <a href="{{ route('admin.settings') }}" class="menu-item">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
        </nav>
    </aside>
    <main class="main-content">
        @php
            $colorForLabel = function (string $label): string {
                $h = 0;
                $len = strlen($label);
                for ($i = 0; $i < $len; $i++) {
                    $h = ($h * 31 + ord($label[$i])) % 360;
                }

                return "hsl($h, 70%, 55%)";
            };

            $iconForLabel = function (string $label): string {
                $needle = \Illuminate\Support\Str::lower($label);
                if (\Illuminate\Support\Str::contains($needle, ['makan', 'minum', 'kuliner'])) {
                    return 'fa-utensils';
                } elseif (\Illuminate\Support\Str::contains($needle, ['transport', 'bensin', 'ojek', 'bus', 'kereta'])) {
                    return 'fa-car-side';
                } elseif (\Illuminate\Support\Str::contains($needle, ['hiburan', 'entertain', 'game', 'film'])) {
                    return 'fa-ticket';
                } elseif (\Illuminate\Support\Str::contains($needle, ['tagihan', 'listrik', 'air', 'internet', 'cicilan'])) {
                    return 'fa-file-invoice-dollar';
                } elseif (\Illuminate\Support\Str::contains($needle, ['belanja', 'shopping'])) {
                    return 'fa-bag-shopping';
                } elseif (\Illuminate\Support\Str::contains($needle, ['spp', 'sekolah', 'pendidikan', 'kampus'])) {
                    return 'fa-graduation-cap';
                } elseif (\Illuminate\Support\Str::contains($needle, ['kesehatan', 'dokter', 'obat', 'rumah sakit'])) {
                    return 'fa-briefcase-medical';
                } elseif (\Illuminate\Support\Str::contains($needle, ['donasi', 'amal', 'zakat'])) {
                    return 'fa-hand-holding-heart';
                } elseif (\Illuminate\Support\Str::contains($needle, ['utang', 'pinjam', 'kredit'])) {
                    return 'fa-money-bill-transfer';
                }

                return 'fa-wallet';
            };
        @endphp
    <div class="header">
            <div>
                <h1>Analisis Pengguna</h1>
                <p class="user-analysis-sub" id="analysisSummary">Ringkasan pemasukan, pengeluaran, penghematan, dan budget per kategori untuk setiap pengguna biasa.</p>
            </div>
            <div class="analysis-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" id="analysisSearchInput" placeholder="Cari pengguna" autocomplete="off" inputmode="search">
                <span class="analysis-search-badge" id="analysisSearchCountBadge">{{ number_format($userAnalyses->count()) }}</span>
            </div>
        </div>
        <section class="user-analysis-section" id="userAnalysisSection">
            <div class="user-analysis-header">
                <div>
                    <div class="user-analysis-title">
                        <i class="fas fa-users"></i>
                        <span>Ringkasan per Pengguna Biasa</span>
                    </div>
                    <p class="user-analysis-sub">Detail pemasukan, pengeluaran, dan penghematan dari setiap user biasa.</p>
                </div>
                <div class="user-analysis-sub" id="userAnalysisTotal">
                    Total Pengguna Biasa: {{ number_format($userAnalyses->count()) }}
                </div>
            </div>
            <div class="user-list-grid">
                @forelse($userAnalyses as $u)
                <div class="user-card">
                    <div class="user-card-header">
                        <div class="user-avatar">
                            @if($u->avatar)
                                <img src="{{ asset('avatars/' . $u->avatar) }}" alt="{{ $u->name }}">
                            @else
                                @php($rawName = trim(preg_replace('/\s+/u',' ', (string) $u->name)))
                                <span>{{ mb_strtoupper(mb_substr($rawName, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="user-info-main">
                            <div class="user-name">{{ $u->name }}</div>
                            <div class="user-meta">
                                {{ $u->username ? '@' . $u->username : '@-' }} · {{ $u->email }}
                            </div>
                        </div>
                        <span class="user-tag">Pengguna Biasa</span>
                    </div>
                    <div class="user-metrics">
                        <div class="user-metric-card income">
                            <div class="user-metric-label">
                                <span class="user-metric-icon income"><i class="fas fa-arrow-trend-up"></i></span>
                                <span>Pemasukan</span>
                            </div>
                            <div class="user-metric-value">
                                Rp {{ number_format($u->analysis_income ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="user-metric-sub">
                                {{ number_format($u->analysis_income_percentage ?? 0, 1) }}% dari arus kas
                            </div>
                        </div>
                        <div class="user-metric-card expense">
                            <div class="user-metric-label">
                                <span class="user-metric-icon expense"><i class="fas fa-arrow-trend-down"></i></span>
                                <span>Pengeluaran</span>
                            </div>
                            <div class="user-metric-value">
                                Rp {{ number_format($u->analysis_expense ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="user-metric-sub">
                                {{ number_format($u->analysis_expense_percentage ?? 0, 1) }}% dari arus kas
                            </div>
                        </div>
                        <div class="user-metric-card savings">
                            <div class="user-metric-content">
                                <div class="user-metric-label">
                                    <span class="user-metric-icon savings"><i class="fas fa-piggy-bank"></i></span>
                                    <span>Penghematan</span>
                                </div>
                                <div class="user-metric-value">
                                    Rp {{ number_format($u->analysis_savings ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="user-savings-pie-wrap">
                                <div class="user-savings-pie-inner">
                                    <canvas
                                        class="user-savings-pie-canvas"
                                        data-pie='@json($u->analysis_budget_items ?? [])'
                                        width="72"
                                        height="72"
                                    ></canvas>
                                    <div class="user-savings-pie-center"></div>
                                </div>
                            </div>
                            <div class="user-metric-percent">
                                <div class="percent-badge">
                                    <div class="percent-val">{{ number_format($u->analysis_savings_percentage ?? 0, 1) }}%</div>
                                    <div class="percent-label">dari maksimal</div>
                                </div>
                                <div class="percent-icon-circle">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!empty($u->analysis_budget_items))
                    <div class="user-budget-section">
                        <div class="user-budget-title">Pengeluaran per Kategori</div>
                        <div class="user-budget-sub">Terisi otomatis dari transaksi, dibandingkan dengan budget per kategori</div>
                        <div class="user-budget-list">
                            @foreach($u->analysis_budget_items as $item)
                                @php($label = (string) ($item['label'] ?? ''))
                                @php($color = $colorForLabel($label))
                                @php($icon = $iconForLabel($label))
                                <div class="user-budget-item">
                                    <div class="user-budget-icon" style="background: {{ $color }}; box-shadow: 0 4px 10px {{ $color }}40;">
                                        <i class="fas {{ $icon }}"></i>
                                    </div>
                                    <div class="user-budget-main">
                                        <div class="user-budget-name-row">
                                            <div class="user-budget-name">{{ $item['label'] }}</div>
                                            <div class="user-budget-amount">
                                                <span>Rp {{ number_format($item['spent'], 0, ',', '.') }}</span>
                                                <span>/</span>
                                                <span>Rp {{ number_format($item['max'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="user-budget-bar-wrap">
                                            <div class="user-budget-bar-track">
                                                <div class="user-budget-bar-fill" style="width: {{ (int) round($item['fillPercent'] ?? 0) }}%; background: {{ $color }};"></div>
                                                <div class="user-budget-bar-center">
                                                    {{ (int) round($item['usagePercent'] ?? 0) }}% · Maks 1 bulan
                                                </div>
                                            </div>
                                        </div>
                                        <div class="user-budget-percent-row">
                                            <span class="main">{{ (int) round($item['usagePercent'] ?? 0) }}%</span>
                                            <span>Sisa Rp {{ number_format($item['remaining'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="user-card">
                    <div class="user-name">Belum ada pengguna biasa.</div>
                </div>
                @endforelse
            </div>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Chart === 'undefined') return;

            var CATEGORY_PALETTE = {
                'Belanja': '#ec4899',
                'Transportasi': '#f59e0b',
                'Utang': '#3b82f6',
                'Makanan': '#ef4444',
                'Hiburan': '#8b5cf6',
                'Tagihan': '#10b981',
                'Kesehatan': '#06b6d4',
                'Pendidikan': '#f97316',
                'Lainnya': '#64748b'
            };

            function colorForLabel(label) {
                label = label || '';
                if (CATEGORY_PALETTE[label]) return CATEGORY_PALETTE[label];
                var h = 0;
                for (var i = 0; i < label.length; i++) {
                    h = (h * 31 + label.charCodeAt(i)) % 360;
                }
                return 'hsl(' + h + ', 70%, 55%)';
            }

            var canvases = document.querySelectorAll('.user-savings-pie-canvas');
            canvases.forEach(function (canvas) {
                var raw = canvas.dataset.pie;
                if (!raw) return;

                var items;
                try {
                    items = JSON.parse(raw);
                } catch (e) {
                    return;
                }

                if (!Array.isArray(items)) return;

                var labels = [];
                var data = [];

                items.forEach(function (item) {
                    if (!item) return;
                    var spent = Number(item.spent || 0);
                    if (!spent) return;
                    labels.push(item.label || 'Kategori');
                    data.push(spent);
                });

                if (!data.length) return;

                var bg = labels.map(function (label) {
                    return colorForLabel(label);
                });

                var ctx = canvas.getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: bg,
                            borderColor: '#ffffff',
                            borderWidth: 1.5,
                            hoverOffset: 2
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false }
                        }
                    }
                });

                var inner = canvas.parentElement;
                var centerEl = inner ? inner.querySelector('.user-savings-pie-center') : null;

                if (centerEl) {
                    canvas.addEventListener('mousemove', function (evt) {
                        var points = chart.getElementsAtEventForMode('nearest', { intersect: true }, { x: evt.offsetX, y: evt.offsetY });
                        if (!points || !points.length) {
                            centerEl.textContent = '';
                            return;
                        }
                        var first = points[0];
                        var ds = chart.data.datasets[first.datasetIndex];
                        var vals = ds.data || [];
                        var value = Number(vals[first.index] || 0);
                        var total = vals.reduce(function (acc, v) {
                            return acc + Number(v || 0);
                        }, 0);
                        var pct = total ? Math.round((value / total) * 100) : 0;
                        centerEl.textContent = pct + '%';
                    });

                    canvas.addEventListener('mouseleave', function () {
                        centerEl.textContent = '';
                    });
                }
            });
        });
    </script>
    <script>
        (function() {
            var input = document.getElementById('analysisSearchInput');
            if (!input) return;
            var section = document.getElementById('userAnalysisSection');
            if (!section) return;
            var cards = Array.prototype.slice.call(section.querySelectorAll('.user-card'));
            var totalEl = document.getElementById('userAnalysisTotal');
            var badgeEl = document.getElementById('analysisSearchCountBadge');
            var summaryEl = document.getElementById('analysisSummary');
            var baseTotal = cards.length;
            var baseTotalText = totalEl ? totalEl.textContent : '';
            var timer;

            function formatNumber(n) {
                var num = Number(n || 0);
                if (!isFinite(num)) num = 0;
                return num.toLocaleString('id-ID');
            }

            function update() {
                var q = input.value || '';
                var keyword = q.toLowerCase().trim();
                var visible = 0;
                cards.forEach(function(card) {
                    var nameEl = card.querySelector('.user-name');
                    var metaEl = card.querySelector('.user-meta');
                    var source = '';
                    if (nameEl && nameEl.textContent) source += nameEl.textContent;
                    if (metaEl && metaEl.textContent) source += ' ' + metaEl.textContent;
                    var text = source.toLowerCase();
                    var match = keyword === '' || text.indexOf(keyword) !== -1;
                    card.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                if (totalEl) {
                    if (keyword === '') {
                        totalEl.textContent = baseTotalText;
                    } else {
                        totalEl.textContent = 'Total Pengguna Biasa: ' + formatNumber(visible);
                    }
                }
                if (badgeEl) {
                    var badgeVal = keyword === '' ? baseTotal : visible;
                    badgeEl.textContent = formatNumber(badgeVal);
                }
                if (summaryEl) {
                    if (keyword === '') {
                        summaryEl.textContent = 'Ringkasan pemasukan, pengeluaran, penghematan, dan budget per kategori untuk setiap pengguna biasa.';
                    } else {
                        summaryEl.textContent = 'Difokuskan pada ' + formatNumber(visible) + ' pengguna yang cocok dengan pencarian.';
                    }
                }
            }

            function debouncedUpdate() {
                clearTimeout(timer);
                timer = setTimeout(update, 120);
            }

            input.addEventListener('input', debouncedUpdate);
        })();
    </script>
</body>
</html>
