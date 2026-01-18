<style>
    :root { --primary-color: #1a9cb0; --bar-bg: rgba(255,255,255,0.94); --bar-border: #e5e7eb; --text-muted: #64748b; --active-grad-start: #60a5fa; --active-grad-end: #2563eb; }
    .gradient-nav {
        background: var(--bar-bg);
        backdrop-filter: saturate(180%) blur(8px);
        -webkit-backdrop-filter: saturate(180%) blur(8px);
        box-shadow: 0 -2px 16px rgba(0, 0, 0, 0.08);
        border-top: 1px solid var(--bar-border);
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }
    .gradient-nav nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 48rem;
        margin-left: auto;
        margin-right: auto;
    }

    .nav-item {
        position: relative;
        transition: all 0.3s ease;
        flex: 1 1 0;
        text-align: center;
    }

    .nav-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        width: 0;
        height: 3px;
        background: #ffffff;
        transform: translateX(-50%);
        transition: width 0.3s ease;
    }

    .nav-item.active::before { width: 40%; background: linear-gradient(90deg, var(--active-grad-start), var(--active-grad-end)); height: 2px; }

    .nav-item i, .nav-item span {
        transition: all 0.2s ease;
    }

    .nav-item:hover i, .nav-item:hover span {
        transform: translateY(-2px);
    }

    .nav-item.active i, .nav-item.active span {
        background: linear-gradient(135deg, var(--active-grad-start), var(--active-grad-end));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: none;
    }

    .nav-item:not(.active) i, .nav-item:not(.active) span { color: var(--text-muted); }

    .nav-item:hover i, .nav-item:hover span { color: var(--primary-color); }

    .theme-dark .gradient-nav { background: rgba(17,24,39,0.88); border-top-color: #374151; box-shadow: 0 -2px 16px rgba(0,0,0,0.35); }
    .theme-dark .nav-item:not(.active) i, .theme-dark .nav-item:not(.active) span { color: #9ca3af; }
    .theme-dark .nav-item.active i, .theme-dark .nav-item.active span {
        background: linear-gradient(135deg, #93c5fd, #3b82f6);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
</style>

<div class="fixed inset-x-0 bottom-0 z-50 gradient-nav">
    <nav class="max-w-3xl mx-auto">
        <a href="{{ route('dashboard') }}"
           class="nav-item flex flex-col items-center justify-center py-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house text-xl"></i>
            <span class="text-xs mt-1">Home</span>
        </a>
        <a href="{{ url('/catat') }}"
           class="nav-item flex flex-col items-center justify-center py-3 {{ request()->is('catat') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-plus text-xl"></i>
            <span class="text-xs mt-1">Catat</span>
        </a>
        <a href="{{ url('/laporan') }}"
           class="nav-item flex flex-col items-center justify-center py-3 {{ request()->is('laporan') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie text-xl"></i>
            <span class="text-xs mt-1">Laporan</span>
        </a>
        <a href="{{ url('/budget') }}"
           class="nav-item flex flex-col items-center justify-center py-3 {{ request()->is('budget') ? 'active' : '' }}">
            <i class="fa-solid fa-wallet text-xl"></i>
            <span class="text-xs mt-1">Budget</span>
        </a>
        <a href="{{ url('/profile') }}"
           class="nav-item flex flex-col items-center justify-center py-3 {{ request()->is('profile') ? 'active' : '' }}">
            <i class="fa-solid fa-user text-xl"></i>
            <span class="text-xs mt-1">Profile</span>
        </a>
    </nav>
</div>
