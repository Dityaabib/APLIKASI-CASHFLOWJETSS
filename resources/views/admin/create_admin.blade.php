<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator - Cashflow</title>
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
        .menu-item.add-admin { background: linear-gradient(135deg, #f0f7ff 0%, #e0eaff 100%); box-shadow: 0 4px 12px rgba(30, 58, 138, 0.06); color: #475569; opacity: 0; transform: translateX(-18px) scale(0.96); animation: slideInAdminMenu 0.55s cubic-bezier(0.22, 0.61, 0.36, 1) 0.05s forwards; }
        .menu-item.add-admin i { font-size: 1.2rem; color: #4f46e5; }
        .menu-item.add-admin:hover { background: linear-gradient(135deg, #e0eaff 0%, #dbeafe 100%); box-shadow: 0 8px 16px rgba(30, 58, 138, 0.1); color: #1e293b; }
        .menu-item.add-admin.active {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(30, 58, 138, 0.4);
            transform: translateY(-2px);
            border: none;
        }
        .menu-item.add-admin.active i {
            color: white;
        }
        .menu-item.add-admin:focus-visible {
            outline: none;
            box-shadow:
                0 0 0 3px rgba(99,102,241,0.45),
                0 8px 18px rgba(59,130,246,0.25);
        }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 3rem; }
        .header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .header-title { font-size: 2rem; font-weight: 700; color: #1a202c; }
        .header-sub { font-size: 0.95rem; color: #a0aec0; margin-top: 6px; }
        .grid-wrapper { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr); gap: 2rem; align-items: stretch; }
        .form-wrapper { width: 100%; display: flex; flex-direction: column; }
        .form-card { background: white; border-radius: 24px; padding: 2rem 2.2rem; box-shadow: var(--card-shadow); position: relative; overflow: hidden; border: 1px solid rgba(148,163,184,0.18); height: 100%; transition: var(--transition); }
        .form-card::before { content: ""; position: absolute; right: -80px; top: -80px; width: 220px; height: 220px; background: radial-gradient(circle at center, rgba(67,97,238,0.16), transparent 60%); opacity: 0.7; }
        .form-header { display: flex; align-items: center; gap: 14px; margin-bottom: 1.5rem; }
        .form-icon { width: 52px; height: 52px; border-radius: 999px; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at 0% 0%, #4cc9f0 0, #4361ee 60%, #3f37c9 100%); color: white; font-size: 1.3rem; box-shadow: 0 10px 20px rgba(67,97,238,0.35); }
        .form-title { font-size: 1.3rem; font-weight: 700; color: #1a202c; transition: var(--transition); }
        .form-sub { font-size: 0.9rem; color: #a0aec0; margin-top: 2px; transition: var(--transition); }
        .form-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; margin-top: 0.5rem; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label { font-size: 0.85rem; font-weight: 600; color: #4a5568; }
        .form-input { width: 100%; padding: 0.85rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: #2d3748; font-size: 0.9rem; transition: var(--transition); }
        .form-input:focus { outline: none; border-color: var(--primary); background: #ffffff; box-shadow: 0 0 0 1px rgba(67,97,238,0.25), 0 8px 20px rgba(15,23,42,0.12); }
        .form-input::placeholder { color: #cbd5e0; }
        .form-footer { margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .submit-btn { padding: 0.9rem 1.8rem; border-radius: 16px; border: none; background: radial-gradient(circle at 0% 0%, #4cc9f0 0, #4361ee 42%, #3f37c9 100%); color: white; font-weight: 600; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 14px 30px rgba(67,97,238,0.55); transition: var(--transition); }
        .submit-btn:hover { transform: translateY(-2px) translateX(1px); box-shadow: 0 18px 38px rgba(67,97,238,0.6); }
        .submit-btn i { font-size: 0.9rem; }
        .submit-btn .btn-icon { display: inline-flex; align-items: center; justify-content: center; }
        .submit-btn .btn-spinner { width: 16px; height: 16px; border-radius: 999px; border: 2px solid rgba(255,255,255,0.45); border-top-color: #ffffff; animation: spin 0.75s linear infinite; display: none; }
        .submit-btn.is-loading { cursor: not-allowed; opacity: 0.9; box-shadow: 0 10px 22px rgba(67,97,238,0.45); transform: none; }
        .submit-btn.is-loading .btn-spinner { display: inline-block; }
        .submit-btn.is-loading .btn-icon { display: none; }
        .back-link { font-size: 0.85rem; color: #718096; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 0.8rem; border-radius: 999px; background: rgba(226,232,240,0.6); }
        .back-link i { font-size: 0.8rem; }
        .error-text { font-size: 0.8rem; color: #e63946; margin-top: 2px; }
        .input-error { border-color: rgba(230,57,70,0.9); background: #fff5f5; box-shadow: 0 0 0 1px rgba(230,57,70,0.28); }
        .badge-level { display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; padding: 4px 10px; border-radius: 999px; background: rgba(247,37,133,0.06); color: #f72585; border: 1px solid rgba(247,37,133,0.18); }
        .badge-level i { font-size: 0.9rem; }
        .header-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; }
        .header-chip { font-size: 0.78rem; padding: 4px 10px; border-radius: 999px; background: #e0f2fe; color: #1d4ed8; display: inline-flex; align-items: center; gap: 6px; }
        .header-chip i { font-size: 0.85rem; }
        .flash-success { position: fixed; top: 1.5rem; right: 1.8rem; min-width: 260px; max-width: 360px; padding: 0.85rem 1rem; border-radius: 14px; background: #ecfdf3; color: #166534; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 8px; font-size: 0.85rem; box-shadow: 0 18px 40px rgba(22,163,74,0.25); z-index: 9999; transform: translateX(120%); opacity: 0; animation: toast-in 0.45s cubic-bezier(0.22,0.61,0.36,1) forwards, toast-out 0.4s ease-in 4.2s forwards; }
        .flash-success i { color: #16a34a; }
        .admin-list-card { background: white; border-radius: 24px; padding: 1.6rem 1.8rem; box-shadow: var(--card-shadow); border: 1px solid rgba(148,163,184,0.18); display: flex; flex-direction: column; height: 100%; }
        .admin-list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .admin-list-title { display: flex; align-items: center; gap: 10px; font-size: 1.05rem; font-weight: 700; color: #1f2933; }
        .admin-list-title-icon { width: 36px; height: 36px; border-radius: 999px; display: flex; align-items: center; justify-content: center; background: rgba(67,97,238,0.09); color: #4361ee; font-size: 1rem; }
        .admin-list-badge { font-size: 0.8rem; padding: 4px 10px; border-radius: 999px; background: #edf2ff; color: #3730a3; border: 1px solid rgba(129,140,248,0.4); }
        .admin-scroll { flex: 1; overflow-y: auto; margin-top: 0.5rem; padding-right: 4px; }
        .admin-item { display: flex; align-items: center; padding: 0.7rem 0.55rem; border-radius: 16px; margin-bottom: 0.4rem; transition: var(--transition); border: 1px solid transparent; background: #f8fafc; cursor: pointer; }
        .admin-item:last-child { margin-bottom: 0; }
        .admin-item:not(.online):hover { border-color: rgba(148,163,184,0.6); box-shadow: 0 8px 16px rgba(15,23,42,0.08); transform: translateY(-1px); }
        .admin-avatar { width: 40px; height: 40px; border-radius: 999px; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 1.05rem; color: white; box-shadow: 0 4px 10px rgba(15,23,42,0.25); background: linear-gradient(135deg, #667eea, #764ba2); overflow: hidden; flex: 0 0 40px; }
        .admin-avatar.online { background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%); box-shadow: 0 8px 18px rgba(67,97,238,0.30), 0 0 0 2px rgba(76,201,240,0.55); }
        .admin-avatar.offline { background: linear-gradient(135deg, #cbd5e0, #a0aec0); color: #2d3748; box-shadow: 0 6px 12px rgba(160, 174, 192, 0.25); }
        .admin-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: inherit; }
        .admin-info { flex: 1; min-width: 0; }
        .admin-name { font-size: 0.93rem; font-weight: 600; color: #1f2933; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: flex; align-items: center; gap: 6px; }
        .admin-sub { font-size: 0.78rem; color: #9ca3af; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .admin-status { font-size: 0.7rem; padding: 3px 8px; border-radius: 999px; font-weight: 600; }
        .admin-item.online { background: linear-gradient(180deg, #e6f3ff 0%, #edf2ff 100%); border-color: rgba(37,99,235,0.45); box-shadow: none; cursor: not-allowed; opacity: 0.85; }
        .admin-item.offline { background: #f8fafc; border-color: rgba(226,232,240,0.8); }
        .admin-status.online { background: rgba(76,201,240,0.2); color: #0284c7; border: 1px solid rgba(59,130,246,0.5); }
        .admin-status.offline { background: rgba(226,232,240,0.9); color: #6b7280; border: 1px solid rgba(209,213,219,0.9); }
        .admin-meta { font-size: 0.76rem; color: #9ca3af; white-space: nowrap; margin-left: 10px; }
        .admin-empty { font-size: 0.85rem; color: #9ca3af; display: flex; align-items: center; justify-content: center; padding: 1.2rem 0.6rem; gap: 8px; }
        .field-with-toggle { position: relative; }
        .field-with-toggle .form-input { padding-right: 2.4rem; }
        .password-toggle { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: transparent; cursor: pointer; color: #a0aec0; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; }
        .password-toggle:hover { color: #4a5568; }
        .form-card.editing { box-shadow: 0 18px 40px rgba(67,97,238,0.4); transform: translateY(-2px); border-color: rgba(67,97,238,0.4); }
        .form-card.editing .form-icon { background: radial-gradient(circle at 0% 0%, #f72585 0, #f97316 60%, #facc15 100%); }
        .admin-item.selected {
            border-color: rgba(244, 63, 94, 0.45);
            box-shadow: none;
            transform: none;
            background: linear-gradient(120deg, #fff1f2 0%, #ffe4e6 33%, #fef3c7 66%, #fce7f3 100%);
            background-size: 200% 200%;
            animation: selectedGlow 8s ease infinite;
        }
        @keyframes selectedGlow {
            0%   { background-position: 0% 50%; border-color: rgba(244, 63, 94, 0.45); }
            50%  { background-position: 100% 50%; border-color: rgba(245, 158, 11, 0.45); }
            100% { background-position: 0% 50%; border-color: rgba(244, 63, 94, 0.45); }
        }
        .admin-item.online.selected { box-shadow: none; }
        .admin-item.shake { animation: shakeItem 0.4s ease; }
        @keyframes shakeItem {
            0% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            50% { transform: translateX(4px); }
            75% { transform: translateX(-2px); }
            100% { transform: translateX(0); }
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes toast-in {
            0% { opacity: 0; transform: translateX(120%) translateY(0); }
            100% { opacity: 1; transform: translateX(0) translateY(0); }
        }
        @keyframes toast-out {
            0% { opacity: 1; transform: translateX(0) translateY(0); }
            100% { opacity: 0; transform: translateX(60%) translateY(0); }
        }
        @keyframes slideInAdminMenu {
            0% { opacity: 0; transform: translateX(-24px) scale(0.94); }
            50% { opacity: 1; transform: translateX(4px) scale(1.02); }
            100% { opacity: 1; transform: translateY(-2px); }
        }
        @media (max-width: 1024px) {
            .grid-wrapper { grid-template-columns: minmax(0, 1fr); }
        }
        @media (max-width: 768px) {
            .main-content { padding: 2rem 1.5rem; }
            .form-card { padding: 1.6rem 1.4rem; }
            .header { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .header-meta { align-items: flex-start; }
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
            <a href="{{ route('admin.admins.create') }}" class="menu-item add-admin active">
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
            <div>
                <div class="header-title">Administrator</div>
                <p class="header-sub">Kelola dan tambahkan administrator sistem dengan tampilan yang rapi dan aman.</p>
            </div>
            <div class="header-meta">
                <span class="badge-level">
                    <i class="fas fa-shield-halved"></i>
                    Level: Administrator
                </span>
                <span class="header-chip">
                    <i class="fas fa-lock"></i>
                    Hanya administrator yang dapat mengakses halaman ini
                </span>
            </div>
        </div>
        @if(session('success'))
            <div class="flash-success">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        <div class="grid-wrapper">
            <div class="form-wrapper">
                <div class="form-card">
                    <div class="form-header">
                        <div class="form-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <div class="form-title">Data Administrator Baru</div>
                            <div class="form-sub">Isi data dengan lengkap untuk menambahkan administrator sistem.</div>
                        </div>
                    </div>
                    <form
                        action="{{ route('admin.admins.store') }}"
                        method="POST"
                        autocomplete="off"
                        data-mode="create"
                        data-create-action="{{ route('admin.admins.store') }}"
                        data-update-base="{{ url('/admin/admins') }}"
                    >
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="name">Nama Lengkap</label>
                                <input id="name" name="name" type="text" class="form-input @error('name') input-error @enderror" value="{{ old('name') }}" placeholder="Misalnya: Budi Santoso">
                                @error('name')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="username">Username</label>
                                <input id="username" name="username" type="text" class="form-input @error('username') input-error @enderror" value="{{ old('username') }}" placeholder="Username unik untuk login">
                                @error('username')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Email</label>
                                <input id="email" name="email" type="email" class="form-input @error('email') input-error @enderror" value="{{ old('email') }}" placeholder="Alamat email administrator">
                                @error('email')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password">Password</label>
                                <div class="field-with-toggle">
                                    <input id="password" name="password" type="password" class="form-input @error('password') input-error @enderror" placeholder="Minimal 8 karakter">
                                    <button type="button" class="password-toggle" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                                <div class="field-with-toggle">
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" placeholder="Ulangi password yang sama">
                                    <button type="button" class="password-toggle" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-footer">
                            <a href="{{ route('admin.users') }}" class="back-link">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke daftar pengguna
                            </a>
                            <button type="submit" class="submit-btn">
                                <span class="btn-icon">
                                    <i class="fas fa-floppy-disk"></i>
                                </span>
                                <span class="btn-spinner" aria-hidden="true"></span>
                                <span class="submit-text">Simpan Administrator</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="admin-list-card">
                <div class="admin-list-header">
                    <div class="admin-list-title">
                        <div class="admin-list-title-icon">
                            <i class="fas fa-users-gear"></i>
                        </div>
                        <span>Daftar Administrator</span>
                    </div>
                    <div class="admin-list-badge">
                        {{ $admins->count() }} Admin
                    </div>
                </div>
                <div class="admin-scroll">
                    @php
                        $visibleAdmins = $admins->take(20);
                    @endphp
                    @forelse($visibleAdmins as $admin)
                    @php($adminNameRaw = trim(preg_replace('/\s+/u',' ', $admin->name)))
                    @php($adminName = mb_strlen($adminNameRaw) > 24 ? (mb_substr($adminNameRaw, 0, 12) . '…' . mb_substr($adminNameRaw, -10)) : $adminNameRaw)
                    <div
                        class="admin-item {{ $admin->is_online ? 'online' : 'offline' }}"
                        data-admin-id="{{ $admin->id }}"
                        data-admin-name="{{ $adminNameRaw }}"
                        data-admin-username="{{ $admin->username }}"
                        data-admin-email="{{ $admin->email }}"
                        data-admin-online="{{ $admin->is_online ? '1' : '0' }}"
                    >
                        <div class="admin-avatar {{ $admin->is_online ? 'online' : 'offline' }}">
                            @if($admin->avatar)
                                <img src="{{ asset('avatars/' . $admin->avatar) }}" alt="{{ $adminName }}">
                            @else
                                <span>{{ mb_strtoupper(mb_substr($adminNameRaw, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="admin-info">
                            <div class="admin-name">
                                {{ $adminName }}
                                <span class="admin-status {{ $admin->is_online ? 'online' : 'offline' }}">
                                    {{ $admin->is_online ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                            <div class="admin-sub">{{ $admin->email }}</div>
                        </div>
                        <div class="admin-meta">
                            {{ $admin->created_at ? $admin->created_at->diffForHumans(null, true) : '-' }}
                        </div>
                    </div>
                    @empty
                    <div class="admin-empty">
                        <i class="fas fa-info-circle"></i>
                        <span>Belum ada administrator yang terdaftar.</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</body>
<script>
    (function() {
        var toggles = document.querySelectorAll('.password-toggle');
        toggles.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var targetId = btn.getAttribute('data-target');
                var input = document.getElementById(targetId);
                if (!input) return;
                var isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                var icon = btn.querySelector('i');
                if (icon) {
                    icon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
                }
            });
        });

        var form = document.querySelector('.form-card form');
        if (form) {
            var submitBtn = form.querySelector('.submit-btn');
            if (submitBtn) {
                form.addEventListener('submit', function() {
                    if (submitBtn.classList.contains('is-loading')) {
                        return;
                    }
                    submitBtn.classList.add('is-loading');
                    submitBtn.disabled = true;
                    var textEl = submitBtn.querySelector('.submit-text');
                    if (textEl) {
                        textEl.textContent = form.getAttribute('data-mode') === 'edit' ? 'Memperbarui...' : 'Menyimpan...';
                    }
                });
            }

            var formCard = document.querySelector('.form-card');
            var formTitle = formCard ? formCard.querySelector('.form-title') : null;
            var formSub = formCard ? formCard.querySelector('.form-sub') : null;
            var nameInput = document.getElementById('name');
            var usernameInput = document.getElementById('username');
            var emailInput = document.getElementById('email');
            var passwordInput = document.getElementById('password');
            var passwordConfirmInput = document.getElementById('password_confirmation');
            var adminItems = document.querySelectorAll('.admin-item');
            var original = {
                title: formTitle ? formTitle.textContent : '',
                sub: formSub ? formSub.textContent : '',
                action: form.getAttribute('data-create-action'),
                buttonText: submitBtn ? submitBtn.querySelector('.submit-text').textContent : '',
            };
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);

            function setModeCreate() {
                form.setAttribute('data-mode', 'create');
                form.setAttribute('action', original.action);
                methodInput.value = 'POST';
                if (formTitle) {
                    formTitle.textContent = original.title;
                }
                if (formSub) {
                    formSub.textContent = original.sub;
                }
                if (submitBtn) {
                    var textEl = submitBtn.querySelector('.submit-text');
                    if (textEl) {
                        textEl.textContent = original.buttonText;
                    }
                }
                if (formCard) {
                    formCard.classList.remove('editing');
                }
                if (nameInput) nameInput.value = '';
                if (usernameInput) usernameInput.value = '';
                if (emailInput) emailInput.value = '';
                if (passwordInput) {
                    passwordInput.value = '';
                }
                if (passwordConfirmInput) {
                    passwordConfirmInput.value = '';
                }
                adminItems.forEach(function(item) {
                    item.classList.remove('selected');
                });
            }

            function setModeEdit(item) {
                var isOnline = item.getAttribute('data-admin-online') === '1';
                if (isOnline) {
                    item.classList.add('shake');
                    setTimeout(function() {
                        item.classList.remove('shake');
                    }, 400);
                    return;
                }
                var id = item.getAttribute('data-admin-id');
                var base = form.getAttribute('data-update-base');
                form.setAttribute('data-mode', 'edit');
                form.setAttribute('action', base.replace(/\/+$/, '') + '/' + id);
                methodInput.value = 'PATCH';
                if (formTitle) {
                    formTitle.textContent = 'Edit Data Administrator';
                }
                if (formSub) {
                    formSub.textContent = 'Perbarui data administrator dengan informasi yang tepat dan aman.';
                }
                if (submitBtn) {
                    var textEl = submitBtn.querySelector('.submit-text');
                    if (textEl) {
                        textEl.textContent = 'Perbarui Administrator';
                    }
                }
                if (formCard) {
                    formCard.classList.add('editing');
                }
                if (nameInput) {
                    nameInput.value = item.getAttribute('data-admin-name') || '';
                }
                if (usernameInput) {
                    usernameInput.value = item.getAttribute('data-admin-username') || '';
                }
                if (emailInput) {
                    emailInput.value = item.getAttribute('data-admin-email') || '';
                }
                if (passwordInput) {
                    passwordInput.value = '';
                }
                if (passwordConfirmInput) {
                    passwordConfirmInput.value = '';
                }
                adminItems.forEach(function(it) {
                    it.classList.remove('selected');
                });
                item.classList.add('selected');
            }

            adminItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    if (item.classList.contains('selected')) {
                        setModeCreate();
                    } else {
                        setModeEdit(item);
                    }
                });
            });

            var backLink = document.querySelector('.back-link');
            if (backLink) {
                backLink.addEventListener('click', function(e) {
                    if (form.getAttribute('data-mode') === 'edit') {
                        e.preventDefault();
                        setModeCreate();
                    }
                });
            }
        }
    })();
</script>
</html>
