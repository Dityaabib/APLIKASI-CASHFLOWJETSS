<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Transaksi - {{ config('app.name', 'CashFlow') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { padding-top: 60px; padding-bottom: 70px; margin: 0; background:#f9fafb; }
        .theme-dark body { background:#0f172a; color:#e5e7eb; }
        .page-enter { opacity: 0; transform: translateY(10px); }
        .page-enter-active { opacity: 1; transform: translateY(0); transition: all .3s ease; }
        .history-card { background:#ffffff; border-radius:10px; padding:12px; margin:16px auto; width:100%; max-width:960px; }
        .theme-dark .history-card { background:#111827; color:#e5e7eb; }
        .history-header { display:flex; align-items:center; justify-content: space-between; margin-bottom:12px; }
        .history-title { font-weight:700; color:#374151; margin:0; }
        .theme-dark .history-title { color:#e5e7eb; }
        .filter-btn { background: linear-gradient(135deg, #1a9cb0 0%, #0f7b8a 100%); border: none; border-radius: 8px; padding: 6px 12px; font-size: 0.8rem; color: #ffffff; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px; }
        .filter-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
        .refresh-btn { background:#e5e7eb; border:none; border-radius:8px; padding:6px 12px; font-size:0.8rem; color:#374151; cursor:pointer; transition: all .3s ease; display:inline-flex; align-items:center; gap:6px; }
        .refresh-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
        .loading-overlay { position: fixed; inset: 0; z-index: 999; background: rgba(255,255,255,0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); opacity: 1; transition: opacity .25s ease; }
        .theme-dark .loading-overlay { background: rgba(17,24,39,0.6); }
        .loading-spinner { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:40px; height:40px; border-radius:50%; border:4px solid rgba(26,156,176,0.18); border-top-color: #1a9cb0; box-shadow: 0 0 0 6px rgba(26,156,176,0.06), 0 8px 24px rgba(15,23,42,0.08); animation: spin .9s ease-in-out infinite; }
        @keyframes spin { to { transform: translate(-50%,-50%) rotate(360deg); } }
        .table-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        .history-table { width:100%; border-collapse: collapse; min-width: 760px; }
        .history-table th, .history-table td { padding:10px; border-bottom:1px solid #e5e7eb; text-align:left; white-space: nowrap; }
        .theme-dark .history-table th, .theme-dark .history-table td { border-bottom:1px solid #374151; }
        .category-icon { display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:50%; margin-right:8px; font-size:0.75rem; }
        .category-income { background-color: rgba(16,185,129,0.2); color:#10b981; }
        .category-expense { background-color: rgba(239,68,68,0.2); color:#ef4444; }
        .amount-positive { color:#10b981; font-weight:600; }
        .amount-negative { color:#ef4444; font-weight:600; }
        .fade-modal { position: fixed; left: 0; right: 0; top: 0; bottom: 0; display:none; align-items:center; justify-content:center; background: rgba(0,0,0,0.5); z-index: 1000; }
        .fade-modal.show { display:flex; }
        .modal-card { background:#fff;border-radius:12px;padding:16px;width:90%;max-width:360px; }
        .theme-dark .modal-card { background:#111827; color:#e5e7eb; }
        .action-btn { background:#e5e7eb; color:#374151; padding:6px 10px; border-radius:8px; border:none; cursor:pointer; }
        .action-btn.active { background: linear-gradient(135deg, #1a9cb0 0%, #0f7b8a 100%) !important; color:#fff !important; }
        .row-actions { display:inline-flex; gap:8px; opacity:0; transition:opacity .2s ease; padding:0; background: transparent; border: none; box-shadow: none; }
        tr:hover .row-actions, tr.row-active .row-actions { opacity:1; }
        .icon-btn { border:none; border-radius:8px; padding:8px 10px; cursor:pointer; font-weight:600; box-shadow: none; }
        .icon-btn.edit-btn { background: linear-gradient(135deg, #0ea5e9 0%, #1a9cb0 100%); color:#ffffff; }
        .icon-btn.delete-btn { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:#ffffff; }
        .icon-btn:hover { filter: brightness(1.05); transform: translateY(-1px); }
        tr.row-active { background-color: #f9fafb; }
        .theme-dark tr.row-active { background-color: #0f172a; }
        .type-switch { display:flex; gap:8px; }
        .type-chip { flex:1; padding:8px 10px; border-radius:10px; border:none; cursor:pointer; background:#e6f7f9; color:#0f7b8a; font-weight:700; }
        .type-chip.active { background: linear-gradient(135deg, #0ea5e9 0%, #1a9cb0 100%); color:#fff; }
        .category-select { position: relative; }
        .category-selected { display:flex; align-items:center; justify-content:space-between; gap:10px; background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; cursor:pointer; }
        .category-selected .label { display:flex; align-items:center; gap:10px; font-weight:600; }
        .category-options { position:absolute; left:0; right:0; top:calc(100% + 6px); background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 12px 24px rgba(15,23,42,0.12); display:none; max-height:260px; overflow:auto; z-index:1001; transform-origin: top; opacity:0; transform: scaleY(0.96); transition: opacity .18s ease, transform .18s ease; }
        .theme-dark .category-options { background:#111827; border-color:#374151; box-shadow:0 12px 24px rgba(0,0,0,0.35); }
        .category-options.show { display:block; opacity:1; transform: scaleY(1); }
        .category-option { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; cursor:pointer; }
        .category-option:hover { background:#e6f7f9; }
        .theme-dark .category-option:hover { background:#0f172a; }
        .category-search { margin-top:8px; margin-bottom:6px; padding:8px 10px; border-radius:10px; border:1px solid #e5e7eb; width:100%; }
        .category-add { padding:8px 10px; border-top:1px dashed #e5e7eb; }
        .category-add-actions { display:flex; gap:8px; justify-content:flex-end; margin-top:8px; }
        .action-input { background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; }
        .action-input:focus { outline:none; border-color:#1a9cb0; box-shadow: 0 0 0 3px rgba(26,156,176,0.15); }
        .theme-dark .action-input { background:#0f172a; border-color:#374151; color:#e5e7eb; }
        .fade-modal .modal-card { transform: translateY(8px) scale(0.98); opacity:0; transition: transform .2s ease, opacity .2s ease; }
        .fade-modal.show .modal-card { transform: none; opacity:1; }
        .toast { position: fixed; top:16px; right:16px; z-index: 1100; display:none; min-width: 260px; background:#111827; color:#e5e7eb; border-radius:10px; box-shadow:0 12px 24px rgba(0,0,0,0.25); }
        .toast.show { display:flex; align-items:center; gap:10px; padding:10px 12px; }
        .toast-success { background:#10b981; color:#fff; }
        .toast-danger { background:#ef4444; color:#fff; }
        .action-loading { position: fixed; left:0; right:0; top:0; bottom:0; display:none; align-items:center; justify-content:center; background: rgba(0,0,0,0.35); z-index: 1200; }
        .action-loading.show { display:flex; }
        .action-loader { width:36px; height:36px; border-radius:50%; border:3px solid rgba(26,156,176,0.25); border-top-color:#1a9cb0; animation: spin .8s linear infinite; }
    </style>
</head>
<body>

    <x-catat-nav />

    <div id="loadingOverlay" class="loading-overlay"><div class="loading-spinner"></div></div>

    <div id="page" class="page-enter">
        <div class="history-card">
            <div class="history-header">
                <h3 class="history-title">Semua Transaksi</h3>
                <div style="display:flex;align-items:center;gap:8px;">
                    <button class="refresh-btn" id="transactionsRefreshBtn"><i class="fa-solid fa-rotate-right"></i> Refresh</button>
                    <button class="filter-btn" id="transactionsFilterBtn"><i class="fa-solid fa-filter"></i> Filter</button>
                </div>
            </div>
            <div class="table-scroll">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Transaksi</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($transactions ?? []) as $t)
                        @php $isIncome = $t->type === 'income'; @endphp
                        <tr data-id="{{ $t->id }}" data-type="{{ $t->type }}" data-category="{{ $t->category }}" data-amount="{{ (float) $t->amount }}" data-date="{{ \Carbon\Carbon::parse($t->date)->toDateString() }}" data-description="{{ $t->description }}" data-year="{{ \Carbon\Carbon::parse($t->date)->year }}" data-month="{{ \Carbon\Carbon::parse($t->date)->month }}" data-date="{{ \Carbon\Carbon::parse($t->date)->toDateString() }}">
                            <td>{{ \Carbon\Carbon::parse($t->date)->translatedFormat('d M Y') }}</td>
                            <td>{{ $t->category }}</td>
                            <td>
                                <span class="category-icon {{ $isIncome ? 'category-income' : 'category-expense' }}">
                                    <i class="fa-solid {{ $isIncome ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                                </span>
                                {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                            </td>
                            <td class="{{ $isIncome ? 'amount-positive' : 'amount-negative' }}">{{ ($isIncome ? '+' : '-') . 'Rp ' . number_format($t->amount, 0, ',', '.') }}</td>
                            <td>{{ $isIncome ? 'Selesai' : 'Selesai' }}</td>
                            <td>{{ $t->description }}</td>
                            <td>
                                <div class="row-actions">
                                    <button class="icon-btn edit-btn" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="icon-btn delete-btn" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <div id="deleteConfirmModal" class="fade-modal">
        <div class="modal-card">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                <div style="width:40px;height:40px;border-radius:5px;background:#fff0f0;display:flex;align-items:center;justify-content:center;color:#ef4444">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div style="font-weight:700;color:#374151">Hapus Transaksi</div>
            </div>
            <div style="color:#6b7280;margin-bottom:12px">Apakah Anda yakin ingin menghapus transaksi ini?</div>
            <div style="display:flex;justify-content:flex-end;gap:8px">
                <button id="deleteCancelBtn" class="action-btn">Batal</button>
                <button id="deleteConfirmBtn" class="action-btn" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:#fff">Hapus</button>
            </div>
        </div>
    </div>

    <div id="editModal" class="fade-modal">
        <div class="modal-card">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                <div style="width:40px;height:40px;border-radius:5px;background:#e6f7f9;display:flex;align-items:center;justify-content:center;color:#0f7b8a">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div style="font-weight:700;color:#374151">Edit Transaksi</div>
            </div>
            <div style="display:grid;gap:8px;margin-bottom:12px">
                <div class="type-switch">
                    <button class="type-chip" data-type="income">Pemasukan</button>
                    <button class="type-chip" data-type="expense">Pengeluaran</button>
                </div>
                <div class="category-select" id="editCategorySelect">
                    <div class="category-selected" id="editCategorySelected">
                        <div class="label"><i class="fa-solid fa-tag" style="color:#cbd5e1"></i><span id="editCategoryText">Pilih Kategori</span></div>
                        <i class="fa-solid fa-chevron-down" style="color:#6b7280"></i>
                    </div>
                    <div class="category-options" id="editCategoryOptions">
                        <div style="padding:8px 10px"><input type="text" id="editCategorySearch" class="category-search" placeholder="Cari kategori"></div>
                        <div id="editCategoryList"></div>
                        <div class="category-add">
                            <div style="display:flex;align-items:center;gap:8px">
                                <i class="fa-solid fa-plus" style="color:#1a9cb0"></i>
                                <input type="text" id="editCategoryNewInput" class="category-search" placeholder="Tambah kategori baru">
                            </div>
                            <div class="category-add-actions">
                                <button id="editCategoryNewCancel" class="action-btn">Batal</button>
                                <button id="editCategoryNewSave" class="action-btn" style="background: linear-gradient(135deg, #1a9cb0 0%, #0f7b8a 100%); color:#fff">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
                <input id="editAmount" type="text" inputmode="numeric" placeholder="Rp 0" class="action-btn" style="width:100%;text-align:left">
                <input id="editDate" type="date" class="action-btn" style="width:100%;text-align:left">
                <input id="editDescription" type="text" placeholder="Keterangan" class="action-btn" style="width:100%;text-align:left">
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px">
                <button id="editCancelBtn" class="action-btn">Batal</button>
                <button id="editSaveBtn" class="action-btn" style="background: linear-gradient(135deg, #1a9cb0 0%, #0f7b8a 100%); color:#fff">Simpan</button>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"><span id="toastIcon"></span><span id="toastText"></span></div>
    <div id="actionLoading" class="action-loading"><div class="action-loader"></div></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const p = document.getElementById('page');
            requestAnimationFrame(() => { p.classList.add('page-enter-active'); });
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                setTimeout(() => {
                    overlay.style.opacity = '0';
                    overlay.addEventListener('transitionend', () => { overlay.remove(); }, { once: true });
                }, 120);
            }
            const filterBtn = document.getElementById('transactionsFilterBtn');
            const refreshBtn = document.getElementById('transactionsRefreshBtn');
            const modal = document.createElement('div');
            modal.id = 'transactionsFilterModal';
            modal.className = 'fade-modal';
            document.body.appendChild(modal);
            function applyFilter(mode, payload){
                const rows = Array.from(document.querySelectorAll('.history-table tbody tr'));
                rows.forEach(r => {
                    let show = true;
                    if (mode === 'daily') { show = r.getAttribute('data-date') === payload.date; }
                    else if (mode === 'monthly') { show = r.getAttribute('data-year') === String(payload.year) && r.getAttribute('data-month') === String(payload.month); }
                    r.style.display = show ? '' : 'none';
                });
            }
            function openFilter(){
                const now = new Date();
                const years = [];
                for (let y = 2008; y <= now.getFullYear(); y++) years.push(y);
                const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                const monthGrid = months.map((m,i)=>`<button data-month="${i+1}" class="action-btn" style="background:#e5e7eb;color:#374151;padding:8px;border-radius:8px">${m}</button>`).join('');
                modal.innerHTML = '<div class="modal-card">'+
                    '<div style="font-weight:700;margin-bottom:12px;color:#374151">Pilih Filter</div>'+
                    '<div style="display:flex;gap:8px;margin-bottom:12px">'+
                        '<button id="tfDaily" class="action-btn active" style="padding:6px 10px;border-radius:8px">Harian</button>'+
                        '<button id="tfMonthly" class="action-btn" style="padding:6px 10px;border-radius:8px">Bulanan</button>'+
                        '<button id="tfYearly" class="action-btn" style="padding:6px 10px;border-radius:8px">Tahunan</button>'+
                    '</div>'+
                    '<div id="tfPanel"></div>'+
                '</div>';
                modal.classList.add('show');
                const panel = modal.querySelector('#tfPanel');
                modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('show'); });
                function fmt(y,m,d){ const mm = String(m).padStart(2,'0'); const dd = String(d).padStart(2,'0'); return `${y}-${mm}-${dd}`; }
                function renderCalendar(year, month){
                    const first = new Date(year, month-1, 1);
                    const last = new Date(year, month, 0);
                    const days = last.getDate();
                    const startWeekday = (first.getDay() + 6) % 7;
                    const cells = [];
                    for (let i=0;i<startWeekday;i++) cells.push('');
                    for (let d=1; d<=days; d++) cells.push(String(d));
                    const today = new Date();
                    const isCurrentMonth = (year===today.getFullYear() && month===(today.getMonth()+1));
                    const weekdays = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                    const header = `<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">`+
                        `<button id="tfPrev" class="action-btn" style="background:#e5e7eb;color:#374151;padding:6px 10px;border-radius:8px">‹</button>`+
                        `<div style="font-weight:700;color:#374151">${months[month-1]} ${year}</div>`+
                        `<button id="tfNext" class="action-btn" style="background:#e5e7eb;color:#374151;padding:6px 10px;border-radius:8px">›</button>`+
                    `</div>`+
                    '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px;margin-bottom:6px">'+
                        weekdays.map(w => `<div style="text-align:center;color:#6b7280;font-weight:600">${w}</div>`).join('')+
                        '</div>';
                    const grid = cells.map(val => {
                        if (!val) return '<div></div>';
                        const isToday = (isCurrentMonth && parseInt(val,10)===today.getDate());
                        const base = 'background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;border-radius:8px;padding:8px;';
                        const hl = isToday ? 'background:#e6f7f9;border-color:#1a9cb0;color:#0f7b8a;' : '';
                        return `<button data-day="${val}" class="calendar-day" style="${base}${hl}">${val}</button>`;
                    }).join('');
                    panel.innerHTML = header + '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px">'+ grid +'</div>';
                    panel.querySelectorAll('button[data-day]').forEach(b => {
                        b.addEventListener('click', () => { const day = parseInt(b.getAttribute('data-day'),10); const d = fmt(year, month, day); setActiveTab(dailyBtn); modal.classList.remove('show'); applyFilter('daily', { date: d }); });
                    });
                    const prev = panel.querySelector('#tfPrev');
                    const next = panel.querySelector('#tfNext');
                    if (prev) prev.addEventListener('click', () => { month -= 1; if (month < 1) { month = 12; year -= 1; } renderCalendar(year, month); });
                    if (next) next.addEventListener('click', () => { month += 1; if (month > 12) { month = 1; year += 1; } renderCalendar(year, month); });
                }
                renderCalendar(now.getFullYear(), now.getMonth()+1);
                const dailyBtn = modal.querySelector('#tfDaily');
                const monthlyBtn = modal.querySelector('#tfMonthly');
                const yearlyBtn = modal.querySelector('#tfYearly');
                const tabs = [dailyBtn, monthlyBtn];
                function setActiveTab(btn){ [dailyBtn, monthlyBtn, yearlyBtn].forEach(b => b && b.classList.remove('active')); if (btn) btn.classList.add('active'); }
                if (monthlyBtn) monthlyBtn.addEventListener('click', () => { setActiveTab(monthlyBtn); panel.innerHTML = '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">'+ monthGrid +'</div>'; panel.querySelectorAll('button[data-month]').forEach(b => { b.addEventListener('click', () => { const m = parseInt(b.getAttribute('data-month'),10); setActiveTab(monthlyBtn); modal.classList.remove('show'); applyFilter('monthly', { year: now.getFullYear(), month: m }); }); }); });
                if (dailyBtn) dailyBtn.addEventListener('click', () => { setActiveTab(dailyBtn); renderCalendar(now.getFullYear(), now.getMonth()+1); });
                if (yearlyBtn) yearlyBtn.addEventListener('click', () => {
                    setActiveTab(yearlyBtn);
                    const ys = [];
                    for (let y = now.getFullYear(); y >= 2008; y--) ys.push(y);
                    const yGrid = ys.map(y=>`<button data-year="${y}" class="action-btn" style="background:#e5e7eb;color:#374151;padding:8px;border-radius:8px">${y}</button>`).join('');
                    panel.innerHTML = '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">'+ yGrid +'</div>';
                    panel.querySelectorAll('button[data-year]').forEach(b => {
                        b.addEventListener('click', () => { const y = parseInt(b.getAttribute('data-year'),10); setActiveTab(yearlyBtn); modal.classList.remove('show'); applyFilter('yearly', { year: y }); });
                    });
                });
            }
            if (filterBtn) filterBtn.addEventListener('click', openFilter);
            if (refreshBtn) refreshBtn.addEventListener('click', () => {
                const rows = Array.from(document.querySelectorAll('.history-table tbody tr'));
                rows.forEach(r => { r.style.display = ''; });
                modal.classList.remove('show');
            });
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const deleteModal = document.getElementById('deleteConfirmModal');
            const editModal = document.getElementById('editModal');
            const toast = document.getElementById('toast');
            const toastText = document.getElementById('toastText');
            const actionLoading = document.getElementById('actionLoading');
            let currentRow = null;
            let pinnedRow = null;
            const typeChips = Array.from(document.querySelectorAll('.type-chip'));
            const catSelected = document.getElementById('editCategorySelected');
            const catOptions = document.getElementById('editCategoryOptions');
            const catText = document.getElementById('editCategoryText');
            const catSearch = document.getElementById('editCategorySearch');
            const catList = document.getElementById('editCategoryList');
            const catNewInput = document.getElementById('editCategoryNewInput');
            const catNewCancel = document.getElementById('editCategoryNewCancel');
            const catNewSave = document.getElementById('editCategoryNewSave');
            let editTypeVal = 'expense';
            let editCategoryVal = '';
            const categoryMap = { income: new Set(), expense: new Set() };
            Array.from(document.querySelectorAll('.history-table tbody tr')).forEach(r => {
                const t = r.getAttribute('data-type');
                const c = r.getAttribute('data-category') || '';
                if (t && c) categoryMap[t].add(c);
            });
            function persistCategory(val){
                try {
                    const key = 'customCategories_' + editTypeVal;
                    const prev = JSON.parse(localStorage.getItem(key) || '[]');
                    if (!prev.includes(val)) {
                        prev.push(val);
                        localStorage.setItem(key, JSON.stringify(prev));
                    }
                } catch (e) {}
            }
            function loadPersistedCategories(){
                try {
                    ['income','expense'].forEach(tp => {
                        const key = 'customCategories_' + tp;
                        const arr = JSON.parse(localStorage.getItem(key) || '[]');
                        arr.forEach(v => categoryMap[tp].add(v));
                    });
                } catch (e) {}
            }
            loadPersistedCategories();
            function renderCategoryOptions(){
                const q = (catSearch.value || '').toLowerCase();
                const items = Array.from(categoryMap[editTypeVal]).filter(x => x.toLowerCase().includes(q)).sort((a,b)=>a.localeCompare(b));
                catList.innerHTML = items.map(x => `<div class="category-option" data-val="${x}"><div class="label"><i class="fa-solid fa-tag" style="color:#cbd5e1"></i><span>${x}</span></div><i class="fa-solid fa-chevron-right" style="color:#6b7280"></i></div>`).join('') || `<div style="padding:10px;color:#6b7280">Tidak ada kategori</div>`;
                catList.querySelectorAll('.category-option').forEach(opt => {
                    opt.addEventListener('click', () => {
                        editCategoryVal = opt.getAttribute('data-val');
                        catText.textContent = editCategoryVal || 'Pilih Kategori';
                        catOptions.classList.remove('show');
                    });
                });
            }
            catNewCancel.addEventListener('click', () => {
                catNewInput.value = '';
                catNewInput.blur();
            });
            catNewSave.addEventListener('click', () => {
                const val = (catNewInput.value || '').trim();
                if (!val) return;
                categoryMap[editTypeVal].add(val);
                persistCategory(val);
                editCategoryVal = val;
                catText.textContent = editCategoryVal;
                catNewInput.value = '';
                renderCategoryOptions();
                catOptions.classList.remove('show');
            });
            function setType(val){
                editTypeVal = val;
                typeChips.forEach(b => { b.classList.toggle('active', b.getAttribute('data-type') === val); });
                renderCategoryOptions();
            }
            typeChips.forEach(b => {
                b.addEventListener('click', () => setType(b.getAttribute('data-type')));
            });
            catSelected.addEventListener('click', () => {
                catOptions.classList.toggle('show');
                renderCategoryOptions();
                catSearch.focus();
            });
            catSearch.addEventListener('input', renderCategoryOptions);
            document.addEventListener('click', e => {
                if (!e.target.closest('#editCategorySelect')) catOptions.classList.remove('show');
            });
            function pinRow(row){
                if (pinnedRow && pinnedRow !== row) pinnedRow.classList.remove('row-active');
                pinnedRow = row;
                row.classList.add('row-active');
            }
            document.querySelectorAll('.history-table tbody tr').forEach(r => {
                r.addEventListener('click', e => {
                    if (e.target.closest('.row-actions')) return;
                    pinRow(r);
                });
            });
            document.addEventListener('click', e => {
                if (!e.target.closest('.history-table')) {
                    if (pinnedRow) pinnedRow.classList.remove('row-active');
                    pinnedRow = null;
                }
            });
            function openDeleteModal(row){
                currentRow = row;
                deleteModal.classList.add('show');
            }
            function closeDeleteModal(){
                deleteModal.classList.remove('show');
            }
            function openEditModal(row){
                currentRow = row;
                const type = row.getAttribute('data-type');
                const category = row.getAttribute('data-category');
                const amount = row.getAttribute('data-amount');
                const date = row.getAttribute('data-date');
                const description = row.getAttribute('data-description') || '';
                setType(type || 'expense');
                editCategoryVal = category || '';
                catText.textContent = editCategoryVal || 'Pilih Kategori';
                document.getElementById('editAmount').value = 'Rp ' + (Math.round(parseFloat(amount || 0)).toLocaleString('id-ID'));
                document.getElementById('editDate').value = date || '';
                document.getElementById('editDescription').value = description || '';
                editModal.classList.add('show');
            }
            function closeEditModal(){
                editModal.classList.remove('show');
            }
            function showLoading(){ actionLoading.classList.add('show'); }
            function hideLoading(){ actionLoading.classList.remove('show'); }
            function showToast(message, type){
                toast.classList.remove('toast-success','toast-danger','show');
                if (type === 'success') toast.classList.add('toast-success');
                if (type === 'danger') toast.classList.add('toast-danger');
                toastText.textContent = message;
                toast.classList.add('show');
                setTimeout(() => { toast.classList.remove('show'); }, 1800);
            }
            function parseIDR(val){
                const s = String(val || '');
                const digits = s.replace(/[^0-9]/g, '');
                return digits ? parseInt(digits, 10) : 0;
            }
            function formatIDRInput(el){
                const pos = el.selectionStart;
                const raw = parseIDR(el.value);
                const formatted = 'Rp ' + (raw.toLocaleString('id-ID'));
                el.value = formatted;
                try { el.setSelectionRange(formatted.length, formatted.length); } catch (e) {}
            }
            const editAmountEl = document.getElementById('editAmount');
            editAmountEl.addEventListener('input', () => formatIDRInput(editAmountEl));
            editAmountEl.addEventListener('focus', () => formatIDRInput(editAmountEl));
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    const row = e.currentTarget.closest('tr');
                    pinRow(row);
                    openEditModal(row);
                });
            });
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    const row = e.currentTarget.closest('tr');
                    pinRow(row);
                    openDeleteModal(row);
                });
            });
            document.getElementById('deleteCancelBtn').addEventListener('click', closeDeleteModal);
            deleteModal.addEventListener('click', e => { if (e.target === deleteModal) closeDeleteModal(); });
            editModal.addEventListener('click', e => { if (e.target === editModal) closeEditModal(); });
            document.getElementById('editCancelBtn').addEventListener('click', closeEditModal);
            document.getElementById('deleteConfirmBtn').addEventListener('click', async () => {
                if (!currentRow) return;
                const id = currentRow.getAttribute('data-id');
                const url = `{{ route('transactions.destroy', ':id') }}`.replace(':id', id);
                showLoading();
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                if (res.ok) {
                    currentRow.remove();
                    closeDeleteModal();
                    if (pinnedRow === currentRow) pinnedRow = null;
                    showToast('Transaksi berhasil dihapus', 'success');
                } else {
                    showToast('Gagal menghapus transaksi', 'danger');
                }
                hideLoading();
            });
            document.getElementById('editSaveBtn').addEventListener('click', async () => {
                if (!currentRow) return;
                const id = currentRow.getAttribute('data-id');
                const url = `{{ route('transactions.update', ':id') }}`.replace(':id', id);
                const payload = {
                    _method: 'PATCH',
                    type: editTypeVal,
                    category: editCategoryVal || catText.textContent,
                    amount: parseIDR(document.getElementById('editAmount').value),
                    date: document.getElementById('editDate').value,
                    description: document.getElementById('editDescription').value
                };
                showLoading();
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify(payload)
                });
                if (res.ok) {
                    const json = await res.json();
                    const t = json.transaction;
                    currentRow.setAttribute('data-type', t.type);
                    currentRow.setAttribute('data-category', t.category);
                    currentRow.setAttribute('data-amount', t.amount);
                    currentRow.setAttribute('data-date', t.date);
                    currentRow.setAttribute('data-description', t.description || '');
                    currentRow.querySelector('td:nth-child(2)').textContent = t.category;
                    const typeCell = currentRow.querySelector('td:nth-child(3)');
                    const icon = typeCell.querySelector('i');
                    const badge = typeCell.querySelector('.category-icon');
                    if (t.type === 'income') {
                        icon.className = 'fa-solid fa-arrow-trend-up';
                        badge.className = 'category-icon category-income';
                    } else {
                        icon.className = 'fa-solid fa-arrow-trend-down';
                        badge.className = 'category-icon category-expense';
                    }
                    const amountCell = currentRow.querySelector('td:nth-child(4)');
                    const val = Math.round(parseFloat(t.amount || 0));
                    amountCell.className = t.type === 'income' ? 'amount-positive' : 'amount-negative';
                    amountCell.textContent = (t.type === 'income' ? '+' : '-') + 'Rp ' + val.toLocaleString('id-ID');
                    currentRow.querySelector('td:nth-child(6)').textContent = t.description || '';
                    closeEditModal();
                    try { localStorage.setItem('transactionsChanged', String(Date.now())); } catch (e) {}
                    showToast('Transaksi berhasil diperbarui', 'success');
                } else {
                    showToast('Gagal menyimpan perubahan', 'danger');
                }
                hideLoading();
            });
        });
    </script>
</body>
</html>
<x-bottom-nav />
