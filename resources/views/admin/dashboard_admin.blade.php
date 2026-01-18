<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Cashflow</title>
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

        /* Sidebar Styling */
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

        .menu-item:hover, .menu-item.active {
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

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 3rem;
            width: calc(100% - var(--sidebar-width));
        }

        .header {
            margin-bottom: 3.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            background: white;
            padding: 0.8rem 1.2rem;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .user-profile:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-hover);
        }

        .avatar-circle {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }

        /* Stats Grid - Modern Clean Style */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .stat-card {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-hover);
        }

        .stat-content {
            z-index: 2;
        }

        .stat-text h3 {
            font-size: 0.95rem;
            color: #a0aec0;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-text .value {
            font-size: 2.8rem;
            font-weight: 700;
            color: #2d3748;
            line-height: 1.1;
        }

        .stat-icon-bg {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            transition: var(--transition);
            opacity: 0.9;
        }

        .stat-card:hover .stat-icon-bg {
            transform: scale(1.1) rotate(5deg);
        }

        /* Card Colors */
        .card-users .stat-icon-bg {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .card-server .stat-icon-bg {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }

        .card-date .stat-icon-bg {
            background: rgba(247, 37, 133, 0.1);
            color: var(--warning);
        }

        /* Split Layout & List Items */
        .split-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2.5rem;
            align-items: start;
        }

        .panel-column {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            height: 100%;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .panel-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .panel-title i {
            padding: 8px;
            border-radius: 10px;
            font-size: 1.1rem;
        }

        .title-admin i {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .title-user i {
            background: rgba(72, 187, 120, 0.1);
            color: #48bb78;
        }

        .badge-counter {
            background: #edf2f7;
            color: #718096;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Modern List Item */
        .list-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 16px;
            margin-bottom: 1rem;
            transition: var(--transition);
            border: 1px solid transparent;
            background: #f8fafc;
        }

        .list-item:last-child {
            margin-bottom: 0;
        }

        .list-item:hover {
            background: white;
            border-color: rgba(67, 97, 238, 0.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transform: translateX(5px);
        }

        .item-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 15px;
            flex: 0 0 52px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        .item-avatar .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: inherit;
        }

        .avatar-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }
        .avatar-admin.online {
            background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(67, 97, 238, 0.30), 0 0 0 3px rgba(76, 201, 240, 0.55), inset 0 0 0 1px rgba(255, 255, 255, 0.6);
        }

        .avatar-user {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
        }

        .avatar-user.online {
            background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
            color: #ffffff;
            box-shadow: 0 6px 14px rgba(67, 97, 238, 0.35), 0 0 0 3px rgba(76, 201, 240, 0.45);
        }
        .avatar-user.offline {
            background: linear-gradient(135deg, #cbd5e0 0%, #a0aec0 100%);
            color: #2d3748;
            box-shadow: 0 4px 8px rgba(160, 174, 192, 0.3);
        }

        .status-badge {
            display: inline-block;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 12px;
            margin-left: 6px;
            font-weight: 600;
            vertical-align: middle;
        }

        .status-badge.online {
            background: linear-gradient(135deg, rgba(76, 201, 240, 0.25), rgba(67, 97, 238, 0.25));
            color: #0ea5e9;
            border: 1px solid rgba(67, 97, 238, 0.45);
            background-size: 200% 200%;
            animation: onlineShimmer 3s ease infinite;
            position: relative;
            padding-left: 16px;
        }
        .status-badge.online::before {
            content: "";
            position: absolute;
            left: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            animation: badgePulse 1.8s ease-out infinite;
        }
        .status-badge.offline {
            background: rgba(226, 232, 240, 0.6);
            color: #718096;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .item-info {
            flex-grow: 1;
        }

        .item-name {
            font-size: 1rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-sub {
            font-size: 0.8rem;
            color: #a0aec0;
        }

        .item-time {
            font-size: 0.75rem;
            color: #718096;
            font-weight: 500;
            background: white;
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid #edf2f7;
            white-space: nowrap;
            margin-left: auto;
        }

        /* Animations */
        @keyframes onlineShimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes badgePulse {
            0% { box-shadow: 0 0 0 0 rgba(34,197,94,0.4); }
            70% { box-shadow: 0 0 0 6px rgba(34,197,94,0); }
            100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card, .panel-column {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .list-item {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
            position: relative;
            z-index: 1;
        }
        .list-item:hover { z-index: 2; }
        .list-item.online {
            background: linear-gradient(135deg, #dbeafe 0%, #eef2ff 100%);
            border-color: rgba(67, 97, 238, 0.45);
            border-width: 2px;
            box-shadow: none;
        }
        .list-item.online:hover {
            box-shadow: none;
        }
        .list-item.admin.online {
            background: linear-gradient(135deg, #e0e7ff 0%, #f5f7ff 100%);
            border-color: rgba(67, 97, 238, 0.55);
            border-width: 2px;
            box-shadow: none;
        }
        .list-item.admin.online:hover {
            transform: translateY(-2px);
            box-shadow: none;
        }
        .list-item.offline {
            background: #f1f5f9;
            border-color: rgba(226, 232, 240, 0.8);
        }

        .list-item:nth-child(1) { animation-delay: 0.1s; }
        .list-item:nth-child(2) { animation-delay: 0.2s; }
        .list-item:nth-child(3) { animation-delay: 0.3s; }
        .list-item:nth-child(4) { animation-delay: 0.4s; }
        .list-item:nth-child(5) { animation-delay: 0.5s; }

        /* Scrollable List Styling */
        .list-container {
            position: relative;
            max-height: 480px; /* Height for approximately 5 items */
            overflow-y: auto;
            padding-right: 10px;
            scroll-behavior: smooth;
            overscroll-behavior: contain;
            scroll-snap-type: y proximity;
            -webkit-overflow-scrolling: touch;
        }
        .list-item { scroll-snap-align: start; }
        /* Fade edges for professional look */
        .list-container::before,
        .list-container::after {
            content: "";
            position: sticky;
            left: 0;
            right: 0;
            height: 18px;
            pointer-events: none;
            z-index: 0;
        }
        .list-container::before {
            top: 0;
            background: linear-gradient(to bottom, rgba(255,255,255,1), rgba(255,255,255,0));
        }
        .list-container::after {
            bottom: 0;
            background: linear-gradient(to top, rgba(255,255,255,1), rgba(255,255,255,0));
        }

        /* Custom Scrollbar for Webkit */
        .list-container::-webkit-scrollbar {
            width: 6px;
        }
        .list-container::-webkit-scrollbar-track {
            background: transparent;
        }
        .list-container::-webkit-scrollbar-thumb {
            background-color: rgba(203, 213, 224, 0.6);
            border-radius: 20px;
            border: 2px solid transparent;
            background-clip: content-box;
        }
        .list-container::-webkit-scrollbar-thumb:hover {
            background-color: rgba(160, 174, 192, 0.8);
        }

        .admin-theme-dark body {
            background: #020617;
            color: #e5e7eb;
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-bolt"></i>
            <h2>Cashflow</h2>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item active">
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
            <a href="{{ route('admin.settings') }}" class="menu-item">
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

    <!-- Main Content -->
    <main class="main-content">
        <header class="header">
            <div>
                <h1>Dashboard Overview</h1>
                <p style="color: #718096; margin-top: 8px; font-size: 1.05rem;">Halo, <span style="color: var(--primary); font-weight: 600;">{{ Auth::user()->name }}</span>! Siap memantau hari ini?</p>
            </div>
            <div class="user-profile">
                <div class="avatar-circle">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div style="margin-left: 10px;">
                    <div style="font-weight: 600; font-size: 0.9rem; color: #2d3748;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.75rem; color: #a0aec0;">Administrator</div>
                </div>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <!-- Total User -->
            <div class="stat-card card-users">
                <div class="stat-text">
                    <h3>Total Pengguna</h3>
                    <div class="value">{{ $totalUsers }}</div>
                </div>
                <div class="stat-icon-bg">
                    <i class="fas fa-users"></i>
                </div>
            </div>

            <!-- Server Status -->
            <div class="stat-card card-server">
                <div class="stat-text">
                    <h3>Status Server</h3>
                    <div class="value" style="font-size: 2rem;">
                        @if($onlineUsersCount > 0)
                            {{ $onlineUsersCount }} Online
                        @else
                            Online
                        @endif
                    </div>
                </div>
                <div class="stat-icon-bg"><i class="fas fa-wifi"></i></div>
            </div>

            <!-- Date -->
            <div class="stat-card card-date">
                <div class="stat-text">
                    <h3>Tanggal</h3>
                    <div class="value" style="font-size: 1.5rem;">{{ now()->format('d M') }}</div>
                </div>
                <div class="stat-icon-bg">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Split Container for Lists -->
        <div class="split-container">

            <!-- Left Panel: Administrator -->
            <div class="panel-column">
                <div class="panel-header">
                    <div class="panel-title title-admin">
                        <i class="fas fa-user-shield"></i>
                        Administrator
                    </div>
                    <span class="badge-counter">{{ $recentAdmins->count() }} Baru</span>
                </div>

                <div class="list-container">
                    @forelse($recentAdmins as $admin)
                    <div class="list-item admin {{ $admin->is_online ? 'online' : 'offline' }}">
                        <div class="item-avatar avatar-admin {{ $admin->is_online ? 'online' : 'offline' }}">
                            @php($adminNameRaw = trim(preg_replace('/\s+/u',' ', $admin->name)))
                            @php($adminName = mb_strlen($adminNameRaw) > 26 ? (mb_substr($adminNameRaw, 0, 13) . '…' . mb_substr($adminNameRaw, -12)) : $adminNameRaw)
                            @if($admin->avatar)
                                <img class="avatar-image" src="{{ asset('avatars/' . $admin->avatar) }}" alt="{{ $adminName }}">
                            @else
                                <i class="fas fa-user-shield"></i>
                            @endif
                        </div>
                        <div class="item-info">
                            <div class="item-name">
                                {{ $adminName }}
                                @if($admin->is_online)
                                    <span class="status-badge online">Online</span>
                                @else
                                    <span class="status-badge offline">Offline</span>
                                @endif
                            </div>
                            <div class="item-sub">{{ $admin->email }}</div>
                        </div>
                        <div class="item-time">
                            {{ $admin->created_at ? $admin->created_at->diffForHumans(null, true) : '-' }}
                        </div>
                    </div>
                    @empty
                    <div class="list-item" style="justify-content: center; color: #a0aec0;">
                        <i class="fas fa-info-circle" style="margin-right: 8px;"></i> Belum ada data.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Panel: Regular Users -->
            <div class="panel-column">
                <div class="panel-header">
                    <div class="panel-title title-user">
                        <i class="fas fa-users"></i>
                        Pengguna Biasa
                    </div>
                    <span class="badge-counter">{{ $recentUsers->count() }} Baru</span>
                </div>

                <div class="list-container">
                    @forelse($recentUsers as $user)
                    <div class="list-item {{ $user->is_online ? 'online' : 'offline' }}">
                        <div class="item-avatar avatar-user {{ $user->is_online ? 'online' : 'offline' }}">
                            @php($userNameRaw = trim(preg_replace('/\s+/u',' ', $user->name)))
                            @php($userName = mb_strlen($userNameRaw) > 26 ? (mb_substr($userNameRaw, 0, 13) . '…' . mb_substr($userNameRaw, -12)) : $userNameRaw)
                            @if($user->avatar)
                                <img class="avatar-image" src="{{ asset('avatars/' . $user->avatar) }}" alt="{{ $userName }}">
                            @else
                                <i class="fas {{ $user->is_online ? 'fa-user-check' : 'fa-user-slash' }}"></i>
                            @endif
                        </div>
                        <div class="item-info">
                            <div class="item-name">
                                {{ $userName }}
                                @if($user->is_online)
                                    <span class="status-badge online">Online</span>
                                @else
                                    <span class="status-badge offline">Offline</span>
                                @endif
                            </div>
                            <div class="item-sub">{{ $user->username ?? $user->email }}</div>
                        </div>
                        <div class="item-time">
                            {{ $user->created_at ? $user->created_at->diffForHumans(null, true) : '-' }}
                        </div>
                    </div>
                    @empty
                    <div class="list-item" style="justify-content: center; color: #a0aec0;">
                        <i class="fas fa-info-circle" style="margin-right: 8px;"></i> Belum ada data.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

    </main>

    <script>
        (function () {
            function loadAdminSettings() {
                try {
                    var raw = localStorage.getItem('adminSettings');
                    if (!raw) return {};
                    var parsed = JSON.parse(raw);
                    if (!parsed || typeof parsed !== 'object') return {};
                    return parsed;
                } catch (e) {
                    return {};
                }
            }

            var s = loadAdminSettings();

            if (s.themeDark === true) {
                document.documentElement.classList.add('admin-theme-dark');
            }

            if (s.statsAnimated === false) {
                var cards = document.querySelectorAll('.stat-card, .panel-column, .list-item');
                for (var i = 0; i < cards.length; i++) {
                    cards[i].style.animation = 'none';
                    cards[i].style.opacity = '1';
                }
            }

            if (typeof s.maxItemsPerList === 'number' && s.maxItemsPerList >= 0) {
                var maxItems = s.maxItemsPerList;
                if (maxItems > 0) {
                    var adminItems = document.querySelectorAll('.panel-column:nth-child(1) .list-item');
                    var userItems = document.querySelectorAll('.panel-column:nth-child(2) .list-item');
                    function limitList(list, limit) {
                        var visible = 0;
                        for (var i = 0; i < list.length; i++) {
                            if (list[i].querySelector('.fa-info-circle')) {
                                continue;
                            }
                            visible += 1;
                            if (visible > limit) {
                                list[i].style.display = 'none';
                            }
                        }
                    }
                    limitList(adminItems, maxItems);
                    limitList(userItems, maxItems);
                }
            }

            if (s.highlightOnline === true) {
                var onlineBadges = document.querySelectorAll('.status-badge.online');
                for (var j = 0; j < onlineBadges.length; j++) {
                    onlineBadges[j].style.boxShadow = '0 0 0 1px rgba(34,197,94,0.8)';
                }
            }
        })();

        (function(){
            var el = document.querySelector('.card-server .value');
            var card = document.querySelector('.card-server');
            var icon = document.querySelector('.card-server .stat-icon-bg i');
            function updateServerStatus() {
                fetch('{{ route('admin.online.count') }}', {
                    headers: { 'Accept': 'application/json' }
                }).then(function(r){ return r.json(); }).then(function(data){
                    var c = parseInt(data.count || 0, 10);
                    el.textContent = c > 0 ? (c + ' Online') : 'Online';
                    if (c > 0) {
                        card.classList.add('active');
                        card.classList.remove('idle');
                        icon.className = 'fas fa-wifi';
                    } else {
                        card.classList.add('idle');
                        card.classList.remove('active');
                        icon.className = 'fas fa-wifi';
                    }
                }).catch(function(){});
            }
            updateServerStatus();
            try {
                var raw = localStorage.getItem('adminSettings');
                var s = raw ? JSON.parse(raw) : {};
                var enabled = s && s.serverRefreshEnabled !== false;
                var interval = parseInt(s.serverRefreshInterval || 10000, 10);
                if (isNaN(interval) || interval < 5000) interval = 10000;
                if (enabled) {
                    setInterval(updateServerStatus, interval);
                }
            } catch (e) {
                setInterval(updateServerStatus, 10000);
            }
        })();
    </script>
</body>
</html>
