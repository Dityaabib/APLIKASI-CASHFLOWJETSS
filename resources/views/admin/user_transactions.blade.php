<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Pengguna Biasa - Cashflow</title>
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
            --dark: #212529;
            --sidebar-width: 260px;
            --card-shadow: 0 10px 20px rgba(0,0,0,0.08);
            --card-hover: 0 15px 30px rgba(0,0,0,0.12);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f4f7fe;
            color: #4a5568;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            position: fixed;
            height: 100vh;
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
        }
        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
        }
        .sidebar-menu {
            padding: 1rem;
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
        }
        .menu-item:hover,
        .menu-item.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 8px 16px rgba(67, 97, 238, 0.25);
            transform: translateY(-2px);
        }
        .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.2rem;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 3rem;
        }
        .header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }
        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
        }
        .analysis-header-text { display: flex; flex-direction: column; gap: 4px; }
        .analysis-subtitle { font-size: 0.85rem; color: #a0aec0; }
        .analysis-search { position: relative; width: 320px; max-width: 100%; }
        .analysis-search input { width: 100%; border-radius: 999px; border: 1px solid rgba(209,213,219,0.9); padding: 0.7rem 2.6rem 0.7rem 2.6rem; font-size: 0.88rem; background: #f9fafc; outline: none; color: #111827; box-shadow: 0 14px 35px rgba(15,23,42,0.06); transition: box-shadow 0.18s ease, border-color 0.18s ease, background 0.18s ease, transform 0.18s ease; }
        .analysis-search input::placeholder { color: #9ca3af; }
        .analysis-search input:focus { border-color: #6366f1; box-shadow: 0 0px 15px rgba(79,70,229,0.26); background: #ffffff; transform: translateY(-1px); }
        .analysis-search i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); font-size: 0.95rem; color: #6366f1; }
        .analysis-search-badge { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 26px; height: 26px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 600; background: rgba(129,140,248,0.12); color: #4f46e5; border: 1px solid rgba(129,140,248,0.45); }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.02);
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--card-hover);
        }
        .stat-text h3 {
            font-size: 0.9rem;
            color: #a0aec0;
            font-weight: 500;
            margin-bottom: 0.4rem;
        }
        .stat-text .value {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
        }
        .stat-icon-bg {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        .card-users .stat-icon-bg {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }
        .card-transactions .stat-icon-bg {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }
        .card-amount .stat-icon-bg {
            background: rgba(247, 37, 133, 0.1);
            color: var(--warning);
        }

        .users-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .user-block {
            background: white;
            border-radius: 18px;
            padding: 1.2rem 1.4rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.02);
        }
        .user-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .user-main {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 6px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            flex: 0 0 52px;
            background: linear-gradient(135deg, #48bb78, #38a169);
        }
        .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
            border-radius: inherit;
        }
        .avatar.online {
            background: linear-gradient(135deg, #22d3ee, #2563eb);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.16), 0 0 0 2px rgba(34, 211, 238, 0.65);
        }
        .avatar.offline {
            background: linear-gradient(135deg, #cbd5e0, #a0aec0);
            color: #2d3748;
            box-shadow: 0 6px 12px rgba(160, 174, 192, 0.25);
        }
        .user-info {
            display: flex;
            flex-direction: column;
        }
        .user-name {
            font-size: 1rem;
            font-weight: 600;
            color: #2d3748;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-sub {
            font-size: 0.8rem;
            color: #a0aec0;
            margin-top: 2px;
        }
        .badge {
            display: inline-block;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            margin-left: 6px;
        }
        .badge.online {
            background: rgba(76, 201, 240, 0.15);
            color: #0ea5e9;
            border: 1px solid rgba(67, 97, 238, 0.4);
        }
        .badge.offline {
            background: rgba(226, 232, 240, 0.6);
            color: #718096;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }
        .badge.level {
            background: rgba(72, 187, 120, 0.08);
            color: #48bb78;
            border: 1px solid rgba(72,187,120,0.15);
        }
        .user-meta {
            font-size: 0.75rem;
            color: #718096;
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 10px;
            border: 1px solid #edf2f7;
            white-space: nowrap;
        }
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5rem;
        }
        .transactions-table thead {
            background: #f8fafc;
        }
        .transactions-table th,
        .transactions-table td {
            padding: 0.6rem 0.5rem;
            font-size: 0.82rem;
            text-align: left;
        }
        .transactions-table th {
            color: #718096;
            font-weight: 600;
        }
        .transactions-table tbody tr:nth-child(odd) {
            background: #f9fafb;
        }
        .transactions-table tbody tr:nth-child(even) {
            background: #ffffff;
        }
        .transactions-empty {
            padding: 0.7rem 0.5rem;
            font-size: 0.85rem;
            color: #a0aec0;
        }
        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .type-badge.income {
            background: rgba(72, 187, 120, 0.1);
            color: #16a34a;
        }
        .type-badge.expense {
            background: rgba(248, 113, 113, 0.1);
            color: #dc2626;
        }
        .toggle-transactions {
            margin-top: 0.8rem;
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.55rem 1.1rem;
            border-radius: 999px;
            border: 1px solid rgba(67, 97, 238, 0.35);
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(76, 201, 240, 0.12));
            color: #2b3b8f;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        .toggle-transactions:hover {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.12), rgba(76, 201, 240, 0.2));
            box-shadow: 0 6px 14px rgba(67, 97, 238, 0.18);
            transform: translateY(-1px);
        }
        .toggle-transactions .toggle-icon {
            font-size: 0.75rem;
        }
        .toggle-transactions .toggle-label {
            white-space: nowrap;
        }
        .pagination {
            display: flex;
            gap: 8px;
            margin-top: 1.5rem;
            align-items: center;
            justify-content: center;
        }
        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #2d3748;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .pagination .active span {
            background: var(--primary);
            color: white;
            border-color: transparent;
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
            <a href="{{ route('admin.users.transactions') }}" class="menu-item active">
                <i class="fas fa-users-gear"></i> Transaksi
            </a>
            <a href="{{ route('admin.analysis') }}" class="menu-item">
                <i class="fas fa-chart-line"></i> Analisis
            </a>
            <a href="{{ route('admin.settings') }}" class="menu-item">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
        </nav>
    </aside>
    <main class="main-content">
        <div class="header">
            <div class="analysis-header-text">
                <h1>Transaksi Pengguna Biasa</h1>
                <p class="analysis-subtitle" id="pageSummary">Daftar transaksi terbaru dari setiap pengguna biasa.</p>
            </div>
            <div class="analysis-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" id="transactionSearchInput" placeholder="Cari pengguna..." autocomplete="off" inputmode="search" value="{{ $q }}">
                <span class="analysis-search-badge" id="searchCountBadge">{{ number_format($users->total()) }}</span>
            </div>
        </div>
        <div class="stats-grid">
            <div class="stat-card card-users">
                <div class="stat-text">
                    <h3>Total Pengguna Biasa</h3>
                    <div class="value">{{ $totalRegulars }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-user"></i></div>
            </div>
            <div class="stat-card card-transactions">
                <div class="stat-text">
                    <h3>Total Transaksi</h3>
                    <div class="value">{{ $totalTransactions }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-list-alt"></i></div>
            </div>
            <div class="stat-card card-amount">
                <div class="stat-text">
                    <h3>Total Nominal</h3>
                    <div class="value">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-wallet"></i></div>
            </div>
        </div>

        <div class="users-list" id="userListContainer">
            @forelse($users as $u)
            @php
                $isOnline = $u->is_online;
                $avatarClass = $isOnline ? 'online' : 'offline';
                $displayNameRaw = trim(preg_replace('/\s+/u',' ', $u->name));
                $displayName = mb_strlen($displayNameRaw) > 24 ? (mb_substr($displayNameRaw, 0, 12) . '…' . mb_substr($displayNameRaw, -10)) : $displayNameRaw;
            @endphp
            <div class="user-block">
                <div class="user-header">
                    <div class="user-main">
                        <div class="avatar {{ $avatarClass }}">
                            @if($u->avatar)
                                <img class="avatar-image" src="{{ asset('avatars/' . $u->avatar) }}" alt="{{ $displayName }}">
                            @else
                                <i class="fas {{ $isOnline ? 'fa-user-check' : 'fa-user-slash' }}"></i>
                            @endif
                        </div>
                        <div class="user-info">
                            <div class="user-name">
                                {{ $displayName }}
                                <span class="badge level">Pengguna Biasa</span>
                                @if($isOnline)
                                    <span class="badge online">Online</span>
                                @else
                                    <span class="badge offline">Offline</span>
                                @endif
                            </div>
                            <div class="user-sub">{{ $u->username ?? $u->email }}</div>
                        </div>
                    </div>
                    <div class="user-meta">
                        Bergabung {{ $u->created_at ? $u->created_at->diffForHumans(null, true) : '-' }}
                    </div>
                </div>
                @if($u->transactions->count() > 0)
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($u->transactions as $t)
                        <tr @if($loop->iteration > 5) class="extra-transaction" style="display:none;" @endif>
                            <td>{{ $t->date ? $t->date->format('d M Y') : '-' }}</td>
                            <td>
                                <span class="type-badge {{ $t->type === 'income' ? 'income' : 'expense' }}">
                                    @if($t->type === 'income')
                                        <i class="fas fa-arrow-down"></i> Masuk
                                    @else
                                        <i class="fas fa-arrow-up"></i> Keluar
                                    @endif
                                </span>
                            </td>
                            <td>{{ $t->category ?? '-' }}</td>
                            <td>Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                            <td>{{ $t->description ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($u->transactions->count() > 5)
                <button
                    type="button"
                    class="toggle-transactions"
                    data-state="collapsed"
                    data-label-collapsed="Lihat semua transaksi ({{ $u->transactions->count() }})"
                    data-label-expanded="Sembunyikan transaksi"
                >
                    <span class="toggle-label">Lihat semua transaksi ({{ $u->transactions->count() }})</span>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </button>
                @endif
                @else
                <div class="transactions-empty">
                    <i class="fas fa-info-circle"></i> Belum ada transaksi untuk pengguna ini.
                </div>
                @endif
            </div>
            @empty
            <div class="user-block" style="justify-content: center; text-align: center; color: #a0aec0; padding: 3rem;">
                <div>
                    <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p>Tidak ada pengguna ditemukan.</p>
                </div>
            </div>
            @endforelse
        </div>
        <div class="pagination" id="paginationContainer">
            {{ $users->links() }}
        </div>
    </main>
    <script>
        (function () {
            var input = document.getElementById('transactionSearchInput');
            var container = document.getElementById('userListContainer');
            var pagination = document.getElementById('paginationContainer');
            var badge = document.getElementById('searchCountBadge');
            var summary = document.getElementById('pageSummary');
            var timer;
            var currentRequest = null;

            function updateToggleButtonVisual(btn, state) {
                var labelCollapsed = btn.getAttribute('data-label-collapsed') || '';
                var labelExpanded = btn.getAttribute('data-label-expanded') || '';
                var label = state === 'expanded' ? labelExpanded : labelCollapsed;
                var iconClass = state === 'expanded' ? 'fa-chevron-up' : 'fa-chevron-down';
                btn.innerHTML = '<span class="toggle-label">' + label + '</span>'
                    + '<i class="fas ' + iconClass + ' toggle-icon"></i>';
            }

            function bindToggleButtons(scope) {
                var root = scope || document;
                var buttons = root.querySelectorAll('.toggle-transactions');
                buttons.forEach(function (btn) {
                    // Prevent double binding
                    if (btn.dataset.bound) return;
                    btn.dataset.bound = true;

                    var initialState = btn.getAttribute('data-state') || 'collapsed';
                    updateToggleButtonVisual(btn, initialState);
                    btn.addEventListener('click', function () {
                        var block = btn.closest('.user-block');
                        if (!block) return;
                        var extras = block.querySelectorAll('tr.extra-transaction');
                        var state = btn.getAttribute('data-state') || 'collapsed';
                        if (state === 'collapsed') {
                            extras.forEach(function (row) { row.style.display = ''; });
                            state = 'expanded';
                        } else {
                            extras.forEach(function (row) { row.style.display = 'none'; });
                            state = 'collapsed';
                        }
                        btn.setAttribute('data-state', state);
                        updateToggleButtonVisual(btn, state);
                    });
                });
            }

            // Bind initial buttons
            bindToggleButtons();

            function renderUserBlock(u) {
                var isOnline = u.is_online;
                var avatarHtml = '';
                if (u.avatar_url) {
                    avatarHtml = '<img class="avatar-image" src="' + u.avatar_url + '" alt="' + u.display_name + '">';
                } else {
                    var icon = isOnline ? 'fa-user-check' : 'fa-user-slash';
                    avatarHtml = '<i class="fas ' + icon + '"></i>';
                }

                var statusBadge = isOnline
                    ? '<span class="badge online">Online</span>'
                    : '<span class="badge offline">Offline</span>';

                var transactionsHtml = '';
                if (u.transactions && u.transactions.length > 0) {
                    var rows = '';
                    u.transactions.forEach(function(t, idx) {
                        var extraClass = idx >= 5 ? 'class="extra-transaction" style="display:none;"' : '';
                        var typeBadge = t.type === 'income'
                            ? '<span class="type-badge income"><i class="fas fa-arrow-down"></i> Masuk</span>'
                            : '<span class="type-badge expense"><i class="fas fa-arrow-up"></i> Keluar</span>';

                        rows += '<tr ' + extraClass + '>' +
                                '<td>' + t.date + '</td>' +
                                '<td>' + typeBadge + '</td>' +
                                '<td>' + t.category + '</td>' +
                                '<td>Rp ' + t.amount + '</td>' +
                                '<td>' + t.description + '</td>' +
                            '</tr>';
                    });

                    transactionsHtml = '<table class="transactions-table">' +
                            '<thead><tr><th>Tanggal</th><th>Jenis</th><th>Kategori</th><th>Jumlah</th><th>Keterangan</th></tr></thead>' +
                            '<tbody>' + rows + '</tbody>' +
                        '</table>';

                    if (u.transactions.length > 5) {
                        transactionsHtml += '<button type="button" class="toggle-transactions" data-state="collapsed" ' +
                            'data-label-collapsed="Lihat semua transaksi (' + u.transactions.length + ')" ' +
                            'data-label-expanded="Sembunyikan transaksi">' +
                            '<span class="toggle-label">Lihat semua transaksi (' + u.transactions.length + ')</span>' +
                            '<i class="fas fa-chevron-down toggle-icon"></i></button>';
                    }
                } else {
                    transactionsHtml = '<div class="transactions-empty"><i class="fas fa-info-circle"></i> Belum ada transaksi untuk pengguna ini.</div>';
                }

                return '<div class="user-block">' +
                        '<div class="user-header">' +
                            '<div class="user-main">' +
                                '<div class="avatar ' + (isOnline ? 'online' : 'offline') + '">' + avatarHtml + '</div>' +
                                '<div class="user-info">' +
                                    '<div class="user-name">' +
                                        u.display_name +
                                        '<span class="badge level">Pengguna Biasa</span>' +
                                        statusBadge +
                                    '</div>' +
                                    '<div class="user-sub">' + u.sub + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="user-meta">Bergabung ' + u.created_human + '</div>' +
                        '</div>' +
                        transactionsHtml +
                    '</div>';
            }

            function fetchResults() {
                var q = input.value.trim();
                var url = '{{ route('admin.users.transactions') }}?q=' + encodeURIComponent(q);

                if (summary) {
                    summary.textContent = q ? 'Sedang mencari...' : 'Daftar transaksi terbaru dari setiap pengguna biasa.';
                }

                // Cancel previous request if exists (not strictly necessary for XHR but good practice)
                if (currentRequest) {
                    currentRequest.abort();
                }

                var xhr = new XMLHttpRequest();
                currentRequest = xhr;
                xhr.open('GET', url, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 400) {
                        var data = JSON.parse(xhr.responseText);
                        var items = data.items || [];

                        var html = '';
                        if (items.length > 0) {
                            items.forEach(function(u) {
                                html += renderUserBlock(u);
                            });
                        } else {
                            html = '<div class="user-block" style="justify-content: center; text-align: center; color: #a0aec0; padding: 3rem;">' +
                                    '<div><i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.5;"></i>' +
                                    '<p>Tidak ada pengguna ditemukan untuk "' + q + '".</p></div></div>';
                        }

                        container.innerHTML = html;
                        bindToggleButtons(container);

                        if (badge) badge.textContent = data.total !== undefined ? data.total : items.length;
                        if (summary) {
                            summary.textContent = q
                                ? 'Menampilkan ' + items.length + ' dari ' + (data.total || items.length) + ' hasil pencarian untuk "' + q + '".'
                                : 'Daftar transaksi terbaru dari setiap pengguna biasa.';
                        }

                        // Hide pagination when searching because we might not have handled AJAX pagination links yet
                        // Or we can leave it if the controller returns pagination links in JSON (it currently doesn't)
                        // For this "fast" experience, hiding pagination during search is acceptable
                        if (pagination) pagination.style.display = q ? 'none' : 'flex';
                    }
                };

                xhr.send();
            }

            input.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(fetchResults, 300);
            });
        })();
    </script>
</body>
</html>
