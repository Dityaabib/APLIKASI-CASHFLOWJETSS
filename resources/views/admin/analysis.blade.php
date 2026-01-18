<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis - Cashflow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 3rem; }
        .header { margin-bottom: 2.2rem; display: flex; justify-content: space-between; align-items: center; gap: 20px; }
        .header h1 { font-size: 2rem; font-weight: 700; color: #1a202c; }
        .analysis-header-text { display: flex; flex-direction: column; gap: 4px; }
        .analysis-subtitle { font-size: 0.85rem; color: #a0aec0; }
        .analysis-search { position: relative; width: 320px; max-width: 100%; }
        .analysis-search input { width: 100%; border-radius: 999px; border: 1px solid rgba(209,213,219,0.9); padding: 0.7rem 2.6rem 0.7rem 2.6rem; font-size: 0.88rem; background: #f9fafc; outline: none; color: #111827; box-shadow: 0 14px 35px rgba(15,23,42,0.06); transition: box-shadow 0.18s ease, border-color 0.18s ease, background 0.18s ease, transform 0.18s ease; }
        .analysis-search input::placeholder { color: #9ca3af; }
        .analysis-search input:focus { border-color: #6366f1; box-shadow: 0 0px 15px rgba(79,70,229,0.26); background: #ffffff; transform: translateY(-1px); }
        .analysis-search i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); font-size: 0.95rem; color: #6366f1; }
        .analysis-search-badge { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 26px; height: 26px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 600; background: rgba(129,140,248,0.12); color: #4f46e5; border: 1px solid rgba(129,140,248,0.45); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.8rem; margin-bottom: 2rem; }
        .stat-card { background: white; border-radius: 20px; padding: 1.6rem; box-shadow: var(--card-shadow); display: flex; align-items: center; justify-content: space-between; transition: var(--transition); border: 1px solid rgba(148,163,184,0.18); }
        .stat-card:hover { transform: translateY(-6px); box-shadow: var(--card-hover); }
        .stat-text h3 { font-size: 0.9rem; color: #a0aec0; font-weight: 500; margin-bottom: 0.4rem; }
        .stat-text .value { font-size: 2rem; font-weight: 700; color: #2d3748; }
        .stat-icon-bg { width: 68px; height: 68px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.9rem; }
        .card-income .stat-icon-bg { background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(34, 197, 94, 0.15)); color: #10b981; }
        .card-expense .stat-icon-bg { background: linear-gradient(135deg, rgba(247, 37, 133, 0.15), rgba(234, 88, 12, 0.15)); color: #ef4444; }
        .card-savings .stat-icon-bg { background: linear-gradient(135deg, rgba(217, 70, 239, 0.15), rgba(245, 158, 11, 0.15)); color: #d946ef; }
        .user-analysis-section { margin-top: 2.5rem; }
        .user-analysis-header { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 10px; }
        .user-analysis-title { font-size: 1.05rem; font-weight: 600; color: #1a202c; display: flex; align-items: center; gap: 8px; }
        .user-analysis-sub { font-size: 0.85rem; color: #a0aec0; }
        .user-list-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 10px; align-items: stretch; }
        .user-card { background: white; border-radius: 18px; padding: 1.2rem; box-shadow: var(--card-shadow); border: 1px solid rgba(148,163,184,0.16); display: flex; flex-direction: column; gap: 10px; overflow: hidden; cursor: pointer; }
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
        .user-metric-label { font-size: 0.75rem; color: #9ca3af; display: flex; align-items: center; gap: 6px; }
        .user-metric-value { font-size: 0.9rem; font-weight: 600; color: #111827; }
        .user-metric-icon { width: 28px; height: 28px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem; }
        .user-metric-icon.income { background: linear-gradient(135deg, rgba(16,185,129,0.16), rgba(34,197,94,0.16)); color: #0f766e; border: 1px solid rgba(16,185,129,0.25); }
        .user-metric-icon.expense { background: linear-gradient(135deg, rgba(248,113,113,0.16), rgba(239,68,68,0.16)); color: #b91c1c; border: 1px solid rgba(239,68,68,0.25); }
        .user-metric-icon.savings { background: linear-gradient(135deg, rgba(168,85,247,0.16), rgba(245,158,11,0.16)); color: #7e22ce; border: 1px solid rgba(168,85,247,0.25); }

        .user-metric-card.savings {
            grid-column: 1 / -1;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding-right: 1.2rem;
        }
        .user-metric-content {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .user-metric-percent {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .percent-badge {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        .percent-val {
            font-size: 1rem;
            font-weight: 700;
            background: linear-gradient(135deg, #a855f7, #d946ef);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .percent-label {
            font-size: 0.7rem;
            color: #a0aec0;
        }
        .percent-icon-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a855f7, #f59e0b);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.35);
        }

        @keyframes submenuFadeIn {
            from { opacity: 0; transform: translateX(18px); }
            to { opacity: 1; transform: translateX(0); }
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
            <a href="{{ route('admin.analysis') }}" class="menu-item active">
                <i class="fas fa-chart-line"></i> Analisis Sistem
            </a>
            <a href="{{ route('admin.analysis.new') }}" class="menu-item menu-item-new">
                <i class="fas fa-users"></i> Analisis
            </a>
            <a href="{{ route('admin.settings') }}" class="menu-item">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
        </nav>
    </aside>
    <main class="main-content">
        <div class="header">
            <div class="analysis-header-text">
                <h1>Analisis Sistem</h1>
                <p class="analysis-subtitle" id="analysisSummary">Ringkasan pemasukan, pengeluaran, dan penghematan per pengguna biasa.</p>
            </div>
            <div class="analysis-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" id="analysisSearchInput" placeholder="Cari pengguna" autocomplete="off" inputmode="search">
                <span class="analysis-search-badge" id="analysisSearchCountBadge">{{ number_format($userAnalyses->count()) }}</span>
            </div>
        </div>
        <div class="stats-grid">
            <div class="stat-card card-income">
                <div class="stat-text">
                    <h3>Total Pemasukan</h3>
                    <div class="value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-arrow-trend-up"></i></div>
            </div>
            <div class="stat-card card-expense">
                <div class="stat-text">
                    <h3>Total Pengeluaran</h3>
                    <div class="value">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-arrow-trend-down"></i></div>
            </div>
            <div class="stat-card card-savings">
                <div class="stat-text">
                    <h3>Penghematan</h3>
                    <div class="value">Rp {{ number_format($totalSavings, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-piggy-bank"></i></div>
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
                <div class="user-card" onclick="location.href='{{ route('admin.analysis.new') }}?user={{ $u->id }}'">
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
                        </div>
                        <div class="user-metric-card expense">
                            <div class="user-metric-label">
                                <span class="user-metric-icon expense"><i class="fas fa-arrow-trend-down"></i></span>
                                <span>Pengeluaran</span>
                            </div>
                            <div class="user-metric-value">
                                Rp {{ number_format($u->analysis_expense ?? 0, 0, ',', '.') }}
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
                        summaryEl.textContent = 'Ringkasan pemasukan, pengeluaran, dan penghematan per pengguna biasa.';
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
