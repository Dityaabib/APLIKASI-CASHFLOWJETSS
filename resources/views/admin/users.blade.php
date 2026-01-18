<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengguna - Cashflow</title>
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
        .menu-item.add-admin { background: linear-gradient(120deg, rgba(67,97,238,0.05), rgba(72,187,120,0.07)); box-shadow: 0 10px 20px rgba(15,23,42,0.12); opacity: 0; transform: translateX(-18px) scale(0.96); animation: slideInAdminMenu 0.55s cubic-bezier(0.22, 0.61, 0.36, 1) 0.05s forwards; }
        .menu-item.add-admin i { font-size: 1.2rem; }
        .menu-item.add-admin:hover { background: linear-gradient(120deg, rgba(67,97,238,0.12), rgba(72,187,120,0.16)); box-shadow: 0 14px 28px rgba(15,23,42,0.16); }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 3rem; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 2rem; font-weight: 700; color: #1a202c; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; border-radius: 20px; padding: 1.5rem; box-shadow: var(--card-shadow); display: flex; align-items: center; justify-content: space-between; transition: var(--transition); }
        .stat-card:hover { transform: translateY(-6px); box-shadow: var(--card-hover); }
        .stat-text h3 { font-size: 0.9rem; color: #a0aec0; font-weight: 500; margin-bottom: 0.4rem; }
        .stat-text .value { font-size: 2rem; font-weight: 700; color: #2d3748; }
        .stat-icon-bg { width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
        .card-users .stat-icon-bg { background: rgba(67, 97, 238, 0.1); color: var(--primary); }
        .card-admins .stat-icon-bg { background: rgba(247, 37, 133, 0.1); color: var(--warning); }
        .card-regulars .stat-icon-bg { background: rgba(76, 201, 240, 0.1); color: var(--success); }
        .card-online .stat-icon-bg { background: rgba(72, 187, 120, 0.1); color: #48bb78; }
        .filters { background: white; border-radius: 20px; padding: 1rem; box-shadow: var(--card-shadow); display: grid; grid-template-columns: 1.6fr 1fr 1fr auto; gap: 1rem; align-items: center; margin-bottom: 2rem; position: sticky; top: 0; z-index: 10; }
        .filters input, .filters select, .filters button { width: 100%; padding: 0.8rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: #2d3748; }
        .filters button { background: var(--primary); color: white; border-color: transparent; font-weight: 600; }
        .users-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.2rem; }
        .user-card { background: white; border-radius: 18px; padding: 1rem; box-shadow: var(--card-shadow); display: flex; align-items: center; gap: 14px; transition: var(--transition); border: 1px solid rgba(0,0,0,0.02); will-change: transform, box-shadow; }
        .user-card:hover { transform: translateY(-4px); box-shadow: var(--card-hover); }
        .avatar { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.1rem; box-shadow: 0 6px 12px rgba(0,0,0,0.08); overflow: hidden; flex: 0 0 60px; }
        .avatar-image { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; border-radius: inherit; }
        .avatar.admin { background: linear-gradient(135deg, #667eea, #764ba2); }
        .avatar.regular { background: linear-gradient(135deg, #48bb78, #38a169); }
        .avatar.online {
            background: linear-gradient(135deg, #22d3ee, #2563eb);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.16), 0 0 0 2px rgba(34, 211, 238, 0.65);
        }
        .avatar.offline {
            background: linear-gradient(135deg, #cbd5e0, #a0aec0);
            color: #2d3748;
            box-shadow: 0 6px 12px rgba(160, 174, 192, 0.25);
        }
        .user-info { flex: 1; }
        .user-name { font-size: 1rem; font-weight: 600; color: #2d3748; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-sub { font-size: 0.8rem; color: #a0aec0; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .badges { margin-top: 6px; display: flex; gap: 6px; flex-wrap: nowrap; align-items: center; }
        .badge { display: inline-block; font-size: 0.7rem; padding: 4px 10px; border-radius: 12px; font-weight: 600; }
        .badge.level-admin { background: rgba(247, 37, 133, 0.08); color: #f72585; border: 1px solid rgba(247,37,133,0.15); }
        .badge.level-regular { background: rgba(72, 187, 120, 0.08); color: #48bb78; border: 1px solid rgba(72,187,120,0.15); }
        .badge.online { background: linear-gradient(135deg, rgba(76, 201, 240, 0.25), rgba(67, 97, 238, 0.25)); color: #0ea5e9; border: 1px solid rgba(67,97,238,0.45); background-size: 200% 200%; animation: onlineShimmer 3s ease infinite; position: relative; padding-left: 16px; }
        .badge.online::before { content: ""; position: absolute; left: 6px; top: 50%; transform: translateY(-50%); width: 8px; height: 8px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); animation: badgePulse 1.8s ease-out infinite; }
        .badge.offline { background: rgba(226, 232, 240, 0.6); color: #718096; border: 1px solid rgba(226, 232, 240, 0.9); }
        .user-card.online { background: linear-gradient(180deg, #e6f3ff 0%, #edf2ff 100%); border-color: rgba(37,99,235,0.55); border-width: 2px; box-shadow: 0 4px 12px rgba(37,99,235,0.12), 0 0 0 2px rgba(37,99,235,0.10); }
        .user-card.offline { background: #f8fafc; border-color: rgba(226,232,240,0.8); }
        @keyframes onlineShimmer { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes badgePulse { 0% { box-shadow: 0 0 0 0 rgba(34,197,94,0.4); } 70% { box-shadow: 0 0 0 6px rgba(34,197,94,0); } 100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); } }
        .meta { font-size: 0.75rem; color: #718096; background: #f8fafc; padding: 6px 10px; border-radius: 10px; border: 1px solid #edf2f7; white-space: nowrap; }
        .pagination { display: flex; gap: 8px; margin-top: 1.5rem; align-items: center; justify-content: center; }
        .pagination a, .pagination span { padding: 8px 12px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; color: #2d3748; text-decoration: none; }
        .pagination .active span { background: var(--primary); color: white; border-color: transparent; }
        @keyframes slideInAdminMenu {
            0% { opacity: 0; transform: translateX(-24px) scale(0.94); }
            50% { opacity: 1; transform: translateX(4px) scale(1.02); }
            100% { opacity: 1; transform: translateX(0) scale(1); }
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
            <a href="{{ route('admin.users') }}" class="menu-item active">
                <i class="fas fa-user-friends"></i> Pengguna
            </a>
            <a href="{{ route('admin.admins.create') }}" class="menu-item add-admin">
                <i class="fas fa-user-shield"></i> Administrator
            </a>
            <a href="{{ route('admin.users.transactions') }}" class="menu-item">
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
            <h1>Pengguna</h1>
        </div>
        <div class="stats-grid">
            <div class="stat-card card-users">
                <div class="stat-text">
                    <h3>Total Pengguna</h3>
                    <div class="value">{{ $totalUsers }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-card card-admins">
                <div class="stat-text">
                    <h3>Administrator</h3>
                    <div class="value">{{ $totalAdmins }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-user-shield"></i></div>
            </div>
            <div class="stat-card card-regulars">
                <div class="stat-text">
                    <h3>Pengguna Biasa</h3>
                    <div class="value">{{ $totalRegulars }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-user"></i></div>
            </div>
            <div class="stat-card card-online">
                <div class="stat-text">
                    <h3>User Online</h3>
                    <div class="value">{{ $onlineRegulars }}</div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-wifi"></i></div>
            </div>
        </div>
        <form class="filters" method="GET" action="{{ route('admin.users') }}">
            <input type="text" name="q" placeholder="Cari nama, username, atau email" value="{{ $q }}" autocomplete="off" inputmode="search">
            <select name="level">
                <option value="" {{ $level === '' ? 'selected' : '' }}>Semua Level</option>
                <option value="administrator" {{ $level === 'administrator' ? 'selected' : '' }}>Administrator</option>
                <option value="regular" {{ $level === 'regular' ? 'selected' : '' }}>Pengguna Biasa</option>
            </select>
            <select name="status">
                <option value="" {{ $status === '' ? 'selected' : '' }}>Semua Status</option>
                <option value="online" {{ $status === 'online' ? 'selected' : '' }}>Online</option>
            </select>
            <button type="submit">Terapkan</button>
        </form>
        <div class="users-grid">
            @forelse($users as $u)
            <div
                class="user-card {{ $u->is_online ? 'online' : 'offline' }}"
                data-user-id="{{ $u->id }}"
                data-is-admin="{{ $u->level === 'administrator' ? '1' : '0' }}"
            >
                @php
                    $isAdmin = $u->level === 'administrator';
                    $isOnline = $u->is_online;
                    $avatarClass = $isOnline ? 'online' : 'offline';
                @endphp
                <div class="avatar {{ $avatarClass }}">
                    @php($displayNameRaw = trim(preg_replace('/\s+/u',' ', $u->name)))
                    @php($displayName = mb_strlen($displayNameRaw) > 23 ? (mb_substr($displayNameRaw, 0, 10) . '…' . mb_substr($displayNameRaw, -8)) : $displayNameRaw)
                    @if($u->avatar)
                        <img class="avatar-image" src="{{ asset('avatars/' . $u->avatar) }}" alt="{{ $displayName }}">
                    @else
                        <i class="fas {{ $isOnline ? 'fa-user-check' : 'fa-user-slash' }}"></i>
                    @endif
                </div>
                <div class="user-info">
                    <div class="user-name">{{ $displayName }}</div>
                    <div class="user-sub">{{ $u->username ?? $u->email }}</div>
                    <div class="badges">
                        @if($isAdmin)
                            <span class="badge level-admin">Administrator</span>
                        @else
                            <span class="badge level-regular">Pengguna Biasa</span>
                        @endif
                        @if($isOnline)
                            <span class="badge online">Online</span>
                        @else
                            <span class="badge offline">Offline</span>
                        @endif
                    </div>
                </div>
                <div class="meta">
                    {{ $u->created_at ? $u->created_at->diffForHumans(null, true) : '-' }}
                </div>
            </div>
            @empty
            <div class="user-card" style="justify-content: center;">
                <i class="fas fa-info-circle" style="margin-right: 8px;"></i> Tidak ada data pengguna.
            </div>
            @endforelse
        </div>
        <div class="pagination">
            {{ $users->links() }}
        </div>
    </main>
        <script>
        (function() {
            var form = document.querySelector('.filters');
            var input = form.querySelector('input[name="q"]');
            var levelSel = form.querySelector('select[name="level"]');
            var statusSel = form.querySelector('select[name="status"]');
            var grid = document.querySelector('.users-grid');
            var pagination = document.querySelector('.pagination');
            var timer;
            var transactionsUrlBase = @json(route('admin.users.transactions'));

            function escapeHtml(str) {
                return String(str).replace(/[&<>"']/g, function(s) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[s];
                });
            }

            function bindUserCardClicks(scope) {
                var container = scope || document;
                var cards = container.querySelectorAll('.user-card');
                cards.forEach(function(card) {
                    var isAdmin = card.getAttribute('data-is-admin');
                    var userId = card.getAttribute('data-user-id');
                    if (isAdmin === '1' || !userId) {
                        return;
                    }
                    card.style.cursor = 'pointer';
                    card.addEventListener('click', function() {
                        var url = transactionsUrlBase + '?user=' + encodeURIComponent(userId);
                        window.location.href = url;
                    });
                });
            }

            function render(items) {
                var html = '';
                items.forEach(function(u) {
                    var isAdmin = u.level === 'administrator';
                    var avatarClass = u.is_online ? 'online' : 'offline';
                    var avatarHtml = u.avatar ? '<img class="avatar-image" src="' + (u.avatar_url || ('/avatars/' + u.avatar)) + '" alt="' + escapeHtml(u.display_name) + '">' : ('<i class="fas ' + (u.is_online ? 'fa-user-check' : 'fa-user-slash') + '"></i>');
                    html += '<div class="user-card ' + (u.is_online ? 'online' : 'offline') + '"'
                          + ' data-user-id="' + (u.id || '') + '"'
                          + ' data-is-admin="' + (isAdmin ? '1' : '0') + '">'
                          +   '<div class="avatar ' + avatarClass + '">' + avatarHtml + '</div>'
                          +   '<div class="user-info">'
                          +     '<div class="user-name">' + escapeHtml(u.display_name || '') + '</div>'
                          +     '<div class="user-sub">' + escapeHtml(u.sub || '') + '</div>'
                          +     '<div class="badges">'
                          +       (isAdmin ? '<span class="badge level-admin">Administrator</span>' : '<span class="badge level-regular">Pengguna Biasa</span>')
                          +       (u.is_online ? '<span class="badge online">Online</span>' : '<span class="badge offline">Offline</span>')
                          +     '</div>'
                          +   '</div>'
                          +   '<div class="meta">' + escapeHtml(u.created_human || '-') + '</div>'
                          + '</div>';
                });
                if (items.length === 0) {
                    html = '<div class="user-card" style="justify-content: center;"><i class="fas fa-info-circle" style="margin-right: 8px;"></i> Tidak ada data pengguna.</div>';
                }
                grid.innerHTML = html;
                bindUserCardClicks(grid);
                if (pagination) pagination.style.display = 'none';
            }

            function update() {
                var url = form.action;
                var params = new URLSearchParams();
                params.set('q', input.value || '');
                params.set('level', levelSel.value || '');
                params.set('status', statusSel.value || '');
                fetch(url + '?' + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).then(function(r){ return r.json(); }).then(function(data){ render(data.items || []); }).catch(function(){});
            }

            function debouncedUpdate() {
                clearTimeout(timer);
                timer = setTimeout(update, 250);
            }

            form.addEventListener('submit', function(e){ e.preventDefault(); update(); });
            input.addEventListener('input', debouncedUpdate);
            levelSel.addEventListener('change', debouncedUpdate);
            statusSel.addEventListener('change', debouncedUpdate);

            bindUserCardClicks(document);
        })();
        </script>
</body>
</html>
