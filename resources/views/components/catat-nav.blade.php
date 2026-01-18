<style>
    .top-nav-container { position: fixed; top: 0; left: 0; right: 0; z-index: 50; background-color: #ffffff; transition: background-color 200ms ease, backdrop-filter 200ms ease; }
    .top-nav-container.scrolled { background-color: rgba(255,255,255,0.7); backdrop-filter: saturate(180%) blur(10px); }
    .logo-img { height: 40px; width: auto; }
    .nav-icon { color: #4b5563; font-size: 1.4rem; padding: 8px; border-radius: 8px; transition: all 0.2s ease; }
    .nav-icon:hover { background-color: #f3f4f6; color: #1a9cb0; }
    :root { --primary-color: #1a9cb0; --accent-color: #0f7b8a; }
    .theme-dark body { background-color: #0f172a; color: #f8fafc; }
    .theme-dark .top-nav-container { background-color: rgba(15, 23, 42, 0.85); }
    .theme-dark .top-nav-container.scrolled { background-color: rgba(15, 23, 42, 0.6); backdrop-filter: saturate(180%) blur(10px); }
    .top-nav-container.rounded-bottom { border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; overflow: hidden; }
    .top-nav-container.rounded-bottom.scrolled { border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; }
</style>

<div class="top-nav-container rounded-bottom">
    <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            @php
                $prev = url()->previous();
                $current = url()->current();
                if (!$prev || $prev === $current) { $prev = route('dashboard'); }
            @endphp
            <a href="{{ $prev }}" class="nav-icon"><i class="fa-solid fa-arrow-left"></i></a>
            <img src="{{ asset('img/logocashflowjets.png') }}" alt="Logo" class="logo-img">
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pending.index') }}" class="nav-icon" title="Transaksi Pending"><i class="fa-solid fa-clock"></i></a>
            <a href="{{ url('/notifications') }}" class="nav-icon"><i class="fa-solid fa-bell"></i></a>
        </div>
    </div>
</div>

<script>
    (function(){
        const USER_ID = {{ Auth::id() }};
        function loadTheme(){ try { return JSON.parse(localStorage.getItem('themePrefs:'+USER_ID) || '{}'); } catch { return {}; } }
        function applyTheme(p){ const root = document.documentElement; if ((p.mode||'light') === 'dark') { root.classList.add('theme-dark'); } else { root.classList.remove('theme-dark'); } }
        const prefs = loadTheme(); applyTheme(prefs);
        const nav = document.querySelector('.top-nav-container');
        let lastY = 0;
        function onScroll(){ const y = window.scrollY || document.documentElement.scrollTop || 0; if (y > 8) { nav.classList.add('scrolled'); } else { nav.classList.remove('scrolled'); } lastY = y; }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    })();
</script>
