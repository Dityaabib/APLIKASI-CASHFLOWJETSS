<style>
    .top-nav-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 50;
        background-color: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }

    /* Tambahkan class untuk efek blur saat scroll */
    .top-nav-container.scrolled {
        background-color: rgba(255, 255, 255, 0.30);
        backdrop-filter: saturate(200%) blur(18px);
        -webkit-backdrop-filter: saturate(200%) blur(18px);
        box-shadow: 0 8px 22px rgba(0,0,0,0.10);
    }

    .top-nav-container.no-shadow { box-shadow: none; }
    .top-nav-container.no-shadow.scrolled { box-shadow: none; }
    .top-nav-container.rounded-bottom { border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; overflow: hidden; }
    .top-nav-container.rounded-bottom.scrolled { border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; }

    /* Tambahkan gradien transparan di bagian bawah */
    .top-nav-container.scrolled::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 15px;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.3));
        pointer-events: none;
    }

    .logo-img {
        height: 60px;
        width: auto;
    }

    .profile-pic-header {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    .profile-pic-header:hover {
        border-color: #9ca3af;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .notification-icon {
        color: #4b5563;
        font-size: 1.4rem;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .notification-icon:hover {
        background-color: #f3f4f6;
        color: #1a9cb0;
    }
    :root { --primary-color: #1a9cb0; --accent-color: #0f7b8a; }
    .theme-dark body { background: #0f172a; color: #f8fafc; }
    .theme-dark .top-nav-container { background-color: rgba(15, 23, 42, 0.7); }
    .theme-dark .history-card, .theme-dark .summary-card, .theme-dark .comparison-card, .theme-dark .detail-chart-container { background-color: #111827; color: #e5e7eb; }
    .theme-dark .notification-icon:hover { background-color: #1f2937; color: var(--primary-color); }
</style>

<div class="top-nav-container rounded-bottom" id="navbar">
    <div class="w-full py-3 flex items-center justify-between" style="padding-left:15px;padding-right:15px;gap:12px;">
        <div class="flex items-center" style="gap:8px;">
            <img src="{{ asset('img/logocashflowjets.png') }}" alt="Logo" class="logo-img">
            <span class="text-xl font-semibold hidden sm:block">CashFlow</span>
        </div>

        <div class="flex items-center" style="gap:10px;">
            <a href="{{ url('/profile') }}" class="flex items-center">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('avatars/' . auth()->user()->avatar) }}"
                         alt="Profile"
                         class="profile-pic-header">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=1a9cb0&background=e6f7f9"
                         alt="Profile"
                         class="profile-pic-header">
                @endif
            </a>
            <a href="{{ url('/notifications') }}" class="notification-icon">
                <i class="fa-solid fa-bell"></i>
            </a>
        </div>
    </div>
 </div>

<script>
    // Tambahkan event listener untuk scroll
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (!navbar) return;
        const y = window.scrollY || document.documentElement.scrollTop || 0;
        if (y > 8) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }, { passive: true });

    (function(){
        const USER_ID = {{ Auth::id() }};
        function loadTheme(){ try { return JSON.parse(localStorage.getItem('themePrefs:'+USER_ID) || '{}'); } catch { return {}; } }
        function saveTheme(p){ localStorage.setItem('themePrefs:'+USER_ID, JSON.stringify(p)); }
        function applyTheme(p){ const root = document.documentElement; if ((p.mode||'light') === 'dark') { root.classList.add('theme-dark'); } else { root.classList.remove('theme-dark'); } }
        let prefs = loadTheme(); if (!prefs.mode) { prefs.mode = 'light'; }
        applyTheme(prefs);
    })();
</script>
