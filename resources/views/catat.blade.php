<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Transaksi - {{ config('app.name', 'CashFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            --primary-shadow: rgba(8, 145, 178, 0.25);
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --bg-input: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            padding-top: 0;
            padding-bottom: 80px;
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            padding: 24px;
            width: 100%;
        }

        /* Full width on mobile */
        @media (max-width: 640px) {
            .container {
                padding: 0;
                max-width: 100%;
            }
            body {
                background-color: var(--bg-card);
            }
        }

        .card {
            background: var(--bg-card);
            border-radius: 24px;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.08);
            padding: 32px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.5);
        }

        @media (max-width: 640px) {
            .card {
                border-radius: 0;
                box-shadow: none;
                padding: 24px;
                border: none;
                min-height: 100vh;
            }
        }

        .header-section {
            margin-bottom: 32px;
            text-align: center;
        }

        .title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 1rem;
            font-weight: 500;
        }

        /* Modern Toggle */
        .toggle-wrapper {
            background: var(--bg-input);
            padding: 6px;
            border-radius: 16px;
            display: flex;
            position: relative;
            margin-bottom: 32px;
            border: 1px solid #e2e8f0;
        }

        .toggle-btn {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text-muted);
            cursor: pointer;
            z-index: 2;
            transition: color 0.3s ease;
            position: relative;
        }

        .toggle-btn.active {
            color: #ffffff;
        }

        .toggle-bg {
            position: absolute;
            top: 6px;
            left: 6px;
            bottom: 6px;
            width: calc(50% - 6px);
            background: var(--primary-gradient);
            border-radius: 12px;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 1;
            box-shadow: 0 4px 12px var(--primary-shadow);
        }

        /* Form Fields */
        .field {
            margin-bottom: 24px;
        }

        .label {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: block;
            color: var(--text-main);
            margin-left: 4px;
        }

        .input-group {
            position: relative;
            transition: all 0.3s ease;
        }

        .input {
            width: 100%;
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 16px 16px 16px 48px;
            font-size: 1.05rem;
            font-weight: 600;
            transition: all 0.2s ease;
            background-color: var(--bg-input);
            color: var(--text-main);
        }

        .input:focus {
            outline: none;
            background-color: #ffffff;
            border-color: #06b6d4;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: color 0.2s;
            pointer-events: none;
        }

        .input:focus + .input-icon {
            color: #06b6d4;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Save Button */
        .save-split { display:flex; align-items:stretch; width:100%; gap:0; margin-top:16px; }
        .save-primary, .save-draft {
            margin-top: 16px;
            width: auto;
            border: none;
            padding: 18px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            letter-spacing: 0.5px;
        }
        .save-primary {
            flex: 1 1 auto;
            color: #fff;
            background: var(--primary-gradient);
            border-top-left-radius:16px; border-bottom-left-radius:16px;
            box-shadow: 0 8px 20px -4px var(--primary-shadow);
        }
        .save-draft {
            flex: 0 0 180px;
            color: #0f172a;
            background: #f1f5f9;
            border-top-right-radius:16px; border-bottom-right-radius:16px;
            box-shadow: 0 6px 14px rgba(0,0,0,0.06);
            display:flex; align-items:center; justify-content:center; gap:8px;
            border-left: 1px solid #e2e8f0;
        }
        .save-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -6px var(--primary-shadow);
        }
        .save-primary:active {
            transform: translateY(1px);
        }
        .save-draft:hover { transform: translateY(-1px); box-shadow:0 8px 18px rgba(0,0,0,0.08); }
        .bulk-list { margin-top:16px; display:grid; grid-auto-flow: column; grid-template-rows: repeat(3, auto); grid-auto-columns: 420px; gap:10px; align-content:start; overflow-x:auto; overflow-y:hidden; padding-bottom:6px; -webkit-overflow-scrolling:touch; scroll-snap-type:x proximity; width:100%; overscroll-behavior-x: contain; touch-action: pan-x; cursor: grab; user-select: none; }
        .bulk-list.dragging { cursor: grabbing; }
        .bulk-list::-webkit-scrollbar { height:6px; }
        .bulk-list::-webkit-scrollbar-track { background: transparent; }
        .bulk-list::-webkit-scrollbar-thumb { background: linear-gradient(90deg, rgba(6,182,212,0.35), rgba(8,145,178,0.55)); border-radius: 999px; }
        .bulk-list::-webkit-scrollbar-thumb:hover { background: linear-gradient(90deg, rgba(6,182,212,0.6), rgba(8,145,178,0.8)); }
        .bulk-toolbar { margin-top:12px; display:flex; align-items:center; justify-content:space-between; gap:10px; }
        .bulk-title { font-weight:800; color:#0f172a; }
        .bulk-tools { display:flex; align-items:center; gap:8px; }
        .bulk-clear, .bulk-cancel { background:#f1f5f9; border:none; border-radius:10px; padding:8px 10px; font-weight:800; color:#64748b; box-shadow:0 4px 12px rgba(0,0,0,0.06); cursor:pointer; }
        .bulk-clear:hover { color:#ef4444; }
        .bulk-cancel:hover { color:#0ea5e9; }
        .bulk-item { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 14px; border-radius:14px; background:#f8fafc; border:1px solid #e2e8f0; white-space:nowrap; min-width:420px; width:100%; overflow:hidden; scroll-snap-align:start; }
        .bulk-item.editing { background:#ecfeff; border-color:#0ea5e9; box-shadow:0 8px 18px rgba(14,165,233,0.2); }
        .bulk-item-main { display:inline-flex; align-items:center; gap:12px; white-space:nowrap; }
        .bulk-type { font-size:.75rem; font-weight:700; padding:4px 8px; border-radius:999px; }
        .bulk-type.income { background:#ecfeff; color:#0ea5e9; border:1px solid #bae6fd; }
        .bulk-type.expense { background:#fee2e2; color:#ef4444; border:1px solid #fecaca; }
        .bulk-amount { font-weight:800; color:#0f172a; }
        .bulk-meta { font-size:.8rem; color:#64748b; white-space:nowrap; }
        .bulk-actions { display:inline-flex; align-items:center; gap:8px; }
        .bulk-remove { background:#fee2e2; border:1px solid #fecaca; border-radius:999px; width:36px; height:36px; color:#ef4444; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(239,68,68,0.15); }
        .bulk-remove:hover { background:#fca5a5; border-color:#fca5a5; }
        .bulk-remove i { font-size:1rem; }

        /* Custom Dropdown */
        .custom-dropdown { position: relative; }
        .dropdown-selected {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dropdown-options {
            position: absolute;
            top: calc(100% + 8px);
            left: 0; right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px -8px rgba(0,0,0,0.12);
            z-index: 50;
            max-height: 280px;
            overflow-y: auto;
            display: none;
            border: 1px solid #f1f5f9;
            padding: 8px;
        }
        .dropdown-options.show { display: block; animation: slideDown 0.2s ease; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dropdown-option {
            padding: 12px 16px;
            cursor: pointer;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
            color: var(--text-main);
        }
        .dropdown-option:hover { background-color: var(--bg-input); }
        .dropdown-option.add-category {
            color: #06b6d4;
            font-weight: 700;
            border-top: 1px solid #f1f5f9;
            margin-top: 4px;
            padding-top: 12px;
        }
        .dropdown-option .delete-icon {
            color: #ef4444; opacity: 0; transition: all 0.2s;
            padding: 8px; border-radius: 8px;
        }
        .dropdown-option .delete-icon:hover { background: #fee2e2; }
        .dropdown-option:hover .delete-icon { opacity: 1; }

        /* Date Input Specifics */
        input[type="date"] {
            appearance: none;
            -webkit-appearance: none;
            position: relative;
            background-color: var(--bg-input);
            color: var(--text-main);
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            z-index: 9999;
            opacity: 0; visibility: hidden;
            transition: all 0.3s ease;
        }
        .loading-overlay.show { opacity: 1; visibility: visible; }
        .loading-spinner {
            width: 50px; height: 50px;
            border: 4px solid #e2e8f0;
            border-radius: 50%;
            border-top-color: #06b6d4;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-text { margin-top: 16px; font-weight: 700; color: var(--text-main); }

        /* Modal styling */
        .modal {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex; justify-content: center; align-items: center;
            z-index: 2000;
            opacity: 0; visibility: hidden;
            transition: all 0.3s ease;
        }
        .modal.show { opacity: 1; visibility: visible; }
        .modal-content {
            background: white; border-radius: 24px;
            width: 90%; max-width: 400px; padding: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .modal.show .modal-content { transform: scale(1); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .modal-title { font-size: 1.25rem; font-weight: 800; color: var(--text-main); }
        .modal-close { background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer; }
        .modal-body { margin-bottom: 24px; }
        .modal-input { width: 100%; border: 2px solid #e2e8f0; border-radius: 12px; padding: 14px; font-size: 1rem; transition: all 0.2s; }
        .modal-input:focus { outline: none; border-color: #06b6d4; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 12px; }
        .modal-button { padding: 12px 20px; border-radius: 12px; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s; }
        .modal-button-cancel { background: #f1f5f9; color: var(--text-muted); }
        .modal-button-cancel:hover { background: #e2e8f0; }
        .modal-button-save { background: var(--primary-gradient); color: white; box-shadow: 0 4px 12px var(--primary-shadow); }
        .modal-button-save:hover { transform: translateY(-1px); box-shadow: 0 6px 16px var(--primary-shadow); }

        /* Custom Date Picker Focus Style */
        .input-group:focus-within .input {
            background-color: #ffffff;
            border-color: #06b6d4;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
        }
        .alert-success {
            display:flex; align-items:center; gap:10px;
            background: linear-gradient(135deg, #ecfeff 0%, #e0f2fe 100%);
            color:#0ea5e9; border:1px solid #bae6fd;
            border-radius:16px; padding:12px 14px; font-weight:700; margin-bottom:16px;
        }
        .alert-success i { font-size:1.1rem; }
        .alert-error {
            display:flex; align-items:center; gap:10px;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color:#b91c1c; border:1px solid #fecaca;
            border-radius:16px; padding:12px 14px; font-weight:700; margin-bottom:16px;
        }
        .alert-error i { font-size:1.1rem; }
    </style>
</head>
<body class="bg-gray-50">
    <x-catat-nav />

    <div class="container">
        @if(session('pending_saved'))
            <div class="alert-success"><i class="fa-solid fa-check-circle"></i> Transaksi Pending berhasil disimpan</div>
        @endif
        @if(session('insufficient_balance'))
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>
                    Pengeluaran kamu melebihi saldo yang tersedia. Saldo tersisa Rp {{ number_format((float) session('insufficient_available', 0), 0, ',', '.') }}.
                </span>
            </div>
        @endif
        <div class="card">
            <div class="header-section">
                <div class="title">Tambah Transaksi</div>
                <div class="subtitle">Catat pemasukan & pengeluaran Anda</div>
            </div>

            <div class="toggle-wrapper">
                <div class="toggle-bg" id="toggle-bg"></div>
                <button type="button" id="btn-income" class="toggle-btn">Pemasukan</button>
                <button type="button" id="btn-expense" class="toggle-btn active">Pengeluaran</button>
            </div>

            <form method="POST" action="{{ route('transactions.store') }}" id="transaction-form">
                @csrf
                <input type="hidden" name="type" id="type" value="expense">

                <div class="field">
                    <label class="label">Jumlah Uang</label>
                    <div class="input-group">
                        <input type="text" id="amount" name="amount" class="input" placeholder="0" required inputmode="numeric">
                        <span class="input-icon">Rp</span>
                    </div>
                </div>

                <div class="grid-2 field">
                    <div>
                        <label class="label">Kategori</label>
                        <div class="custom-dropdown">
                            <div class="input dropdown-selected" id="category-selected" style="padding-left: 18px;">
                                <span id="category-text" style="display: flex; align-items: center; gap: 10px; overflow: hidden; width: 100%;">
                                    <i class="fa-solid fa-layer-group" style="color: var(--text-muted); flex-shrink: 0;"></i>
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem;">Pilih Kategori</span>
                                </span>
                            </div>
                            <div class="dropdown-options" id="category-options">
                                <!-- Options will be populated by JavaScript -->
                            </div>
                        </div>
                        <input type="hidden" name="category" id="category-value" required>
                    </div>
                    <div>
                        <label class="label">Tanggal</label>
                        <div class="input-group" style="position: relative;">
                            <div class="input" style="display: flex; align-items: center; gap: 10px; padding-left: 18px; overflow: hidden;">
                                <i class="fa-solid fa-calendar" style="color: var(--text-muted); font-size: 1.1rem; flex-shrink: 0;"></i>
                                <span id="date-display-text" style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem;">{{ now()->translatedFormat('d F Y') }}</span>
                            </div>
                            <input type="date" name="date" id="date-input" class="input" value="{{ now()->toDateString() }}" required
                                   style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; padding: 0; z-index: 10;">
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Deskripsi (Opsional)</label>
                    <textarea name="description" class="input" placeholder="Tuliskan keterangan..." rows="3" style="min-height: 100px; resize: vertical;"></textarea>
                </div>

                <div class="save-split">
                    <button class="save-primary" type="submit"><i class="fa-solid fa-check-circle" style="margin-right: 8px"></i> Simpan Transaksi</button>
                    <button type="button" class="save-draft" id="btn-add-bulk"><i class="fa-solid fa-layer-group"></i> Simpan Draf</button>
                </div>
                <div id="bulk-toolbar" class="bulk-toolbar" style="display:none">
                    <div class="bulk-title">Draf Transaksi</div>
                    <div class="bulk-tools">
                        <button type="button" id="btn-clear-bulk" class="bulk-clear" title="Hapus semua"><i class="fa-solid fa-trash"></i></button>
                        <button type="button" id="btn-cancel-bulk" class="bulk-cancel" title="Batal draf"><i class="fa-solid fa-ban"></i></button>
                    </div>
                </div>
                <div id="bulk-list" class="bulk-list" style="display:none"></div>
            </form>
        </div>
    </div>

    <x-bottom-nav />

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Memproses transaksi...</div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal" id="add-category-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Tambah Kategori Baru</div>
                <button class="modal-close" id="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <input type="text" class="modal-input" id="new-category-input" placeholder="Nama kategori">
            </div>
            <div class="modal-footer">
                <button class="modal-button modal-button-cancel" id="modal-cancel">Batal</button>
                <button class="modal-button modal-button-save" id="modal-save">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div class="modal" id="delete-category-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Hapus Kategori</div>
                <button class="modal-close" id="delete-modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori "<span id="delete-category-name"></span>"?</p>
            </div>
            <div class="modal-footer">
                <button class="modal-button modal-button-cancel" id="delete-modal-cancel">Batal</button>
                <button class="modal-button modal-button-save" id="delete-modal-confirm">Hapus</button>
            </div>
        </div>
    </div>

    <div class="modal" id="balance-warning-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" style="color:#b91c1c;">Pengeluaran Terlalu Besar</div>
                <button class="modal-close" id="balance-warning-close">&times;</button>
            </div>
            <div class="modal-body">
                <p id="balance-warning-text" style="color:#4b5563; font-weight:500; line-height:1.5;"></p>
            </div>
            <div class="modal-footer">
                <button class="modal-button modal-button-save" id="balance-warning-ok" style="background: linear-gradient(135deg,#ef4444,#b91c1c);">Mengerti</button>
            </div>
        </div>
    </div>

    <script>
        // Initialize categories from localStorage or use defaults
        let categories = {
            income: JSON.parse(localStorage.getItem('incomeCategories')) || [
                'Gaji', 'Bonus', 'Investasi', 'Hadiah', 'Lainnya'
            ],
            expense: JSON.parse(localStorage.getItem('expenseCategories')) || [
                'Makanan', 'Transportasi', 'Belanja', 'Hiburan', 'Tagihan'
            ]
        };

        // Save categories to localStorage
        function saveCategories() {
            localStorage.setItem('incomeCategories', JSON.stringify(categories.income));
            localStorage.setItem('expenseCategories', JSON.stringify(categories.expense));
        }

        // DOM elements
        const btnIncome = document.getElementById('btn-income');
        const btnExpense = document.getElementById('btn-expense');
        const typeInput = document.getElementById('type');
        const categorySelected = document.getElementById('category-selected');
        const categoryOptions = document.getElementById('category-options');
        const categoryText = document.getElementById('category-text');
        const categoryValue = document.getElementById('category-value');
        const amountInput = document.getElementById('amount');
        const transactionForm = document.getElementById('transaction-form');
        const loadingOverlay = document.getElementById('loading-overlay');
        const btnAddBulk = document.getElementById('btn-add-bulk');
        const bulkListEl = document.getElementById('bulk-list');
        const bulkToolbarEl = document.getElementById('bulk-toolbar');
        const btnClearBulk = document.getElementById('btn-clear-bulk');
        const btnCancelBulk = document.getElementById('btn-cancel-bulk');
        const btnSavePrimary = document.querySelector('.save-primary');
        const dateInput = document.getElementById('date-input');
        const dateDisplayText = document.getElementById('date-display-text');
        const descInput = document.querySelector('textarea[name="description"]');
        const balanceWarningModal = document.getElementById('balance-warning-modal');
        const balanceWarningText = document.getElementById('balance-warning-text');
        const balanceWarningClose = document.getElementById('balance-warning-close');
        const balanceWarningOk = document.getElementById('balance-warning-ok');
        const initialBalance = {{ isset($availableBalance) ? (int) $availableBalance : 0 }};
        let bulkItems = [];
        let editingIndex = null;
        function enableDragScroll(el) {
            if (!el) return;
            let isDown = false;
            let startX = 0;
            let scrollLeft = 0;
            el.addEventListener('mousedown', function(e) {
                isDown = true;
                el.classList.add('dragging');
                startX = e.pageX - el.offsetLeft;
                scrollLeft = el.scrollLeft;
            });
            el.addEventListener('mouseleave', function() { isDown = false; el.classList.remove('dragging'); });
            el.addEventListener('mouseup', function() { isDown = false; el.classList.remove('dragging'); });
            el.addEventListener('mousemove', function(e) {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - el.offsetLeft;
                const walk = (x - startX);
                el.scrollLeft = scrollLeft - walk;
            });
            el.addEventListener('wheel', function(e) {
                if (el.scrollWidth <= el.clientWidth) return;
                if (Math.abs(e.deltaY) >= Math.abs(e.deltaX)) {
                    el.scrollLeft += e.deltaY;
                    e.preventDefault();
                }
            }, { passive: false });
            el.addEventListener('touchstart', function(e) {
                if (!e.touches || e.touches.length === 0) return;
                isDown = true;
                startX = e.touches[0].pageX;
                scrollLeft = el.scrollLeft;
                el.classList.add('dragging');
            }, { passive: true });
            el.addEventListener('touchend', function() {
                isDown = false;
                el.classList.remove('dragging');
            });
            el.addEventListener('touchmove', function(e) {
                if (!isDown || !e.touches || e.touches.length === 0) return;
                const x = e.touches[0].pageX;
                const walk = (x - startX);
                el.scrollLeft = scrollLeft - walk;
            }, { passive: true });
        }

        // Modal elements
        const addCategoryModal = document.getElementById('add-category-modal');
        const modalClose = document.getElementById('modal-close');
        const modalCancel = document.getElementById('modal-cancel');
        const modalSave = document.getElementById('modal-save');
        const newCategoryInput = document.getElementById('new-category-input');

        // Delete category modal elements
        const deleteCategoryModal = document.getElementById('delete-category-modal');
        const deleteModalClose = document.getElementById('delete-modal-close');
        const deleteModalCancel = document.getElementById('delete-modal-cancel');
        const deleteModalConfirm = document.getElementById('delete-modal-confirm');
        const deleteCategoryName = document.getElementById('delete-category-name');

        // Variable to store category data to be deleted
        let categoryToDelete = {
            type: '',
            index: -1
        };

        // Set transaction type
        function setType(t) {
            typeInput.value = t;
            const bg = document.getElementById('toggle-bg');

            if (t === 'income') {
                btnIncome.classList.add('active');
                btnExpense.classList.remove('active');
                bg.style.transform = 'translateX(0)';
            } else {
                btnExpense.classList.add('active');
                btnIncome.classList.remove('active');
                bg.style.transform = 'translateX(100%)';
            }

            // Reset category selection
            const catText = document.getElementById('category-text');
            catText.innerHTML = '<i class="fa-solid fa-layer-group" style="color: var(--text-muted); flex-shrink: 0;"></i> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem;">Pilih Kategori</span>';
            categoryValue.value = '';

            // Update category options
            updateCategoryOptions(t);
        }

        // Update category options based on type
        function updateCategoryOptions(type) {
            categoryOptions.innerHTML = '';

            const cats = categories[type] || [];

            cats.forEach((cat, index) => {
                const option = document.createElement('div');
                option.className = 'dropdown-option';
                option.innerHTML = `
                    <span style="display:flex;align-items:center;gap:10px">
                        <i class="fa-solid fa-tag" style="color:#cbd5e1;font-size:0.8rem"></i> ${cat}
                    </span>
                    <i class="fa-solid fa-trash delete-icon" data-index="${index}" data-type="${type}"></i>
                `;

                // Add click event to select category
                option.querySelector('span').parentElement.addEventListener('click', (e) => {
                    if (!e.target.classList.contains('delete-icon')) {
                        selectCategory(cat);
                    }
                });

                // Add click event to delete category
                option.querySelector('.delete-icon').addEventListener('click', (e) => {
                    e.stopPropagation();
                    deleteCategory(type, index);
                });

                categoryOptions.appendChild(option);
            });

            // Add "Add Category" option
            const addOption = document.createElement('div');
            addOption.className = 'dropdown-option add-category';
            addOption.innerHTML = '<span style="display:flex;align-items:center;gap:10px"><i class="fas fa-plus-circle"></i> Tambah Kategori</span>';
            addOption.addEventListener('click', showAddCategoryModal);
            categoryOptions.appendChild(addOption);
        }

        // Select category
        function selectCategory(category) {
            const catText = document.getElementById('category-text');
            catText.innerHTML = `<i class="fa-solid fa-layer-group" style="color: var(--primary-color); opacity: 0.8; flex-shrink: 0;"></i> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem;">${category}</span>`;
            categoryValue.value = category;
            categoryOptions.classList.remove('show');
        }

        // Delete category
        function deleteCategory(type, index) {
            // Store category data to be deleted
            categoryToDelete = {
                type: type,
                index: index
            };

            // Display category name in modal
            deleteCategoryName.textContent = categories[type][index];

            // Show confirmation modal
            deleteCategoryModal.classList.add('show');
        }

        // Execute category deletion
        function executeDeleteCategory() {
            if (categoryToDelete.index !== -1) {
                categories[categoryToDelete.type].splice(categoryToDelete.index, 1);
                saveCategories();
                updateCategoryOptions(categoryToDelete.type);

                // Reset category selection if the deleted category was selected
                if (categoryValue.value === categories[categoryToDelete.type][categoryToDelete.index]) {
                    categoryText.textContent = 'Pilih Kategori';
                    categoryValue.value = '';
                }

                // Reset category data to be deleted
                categoryToDelete = {
                    type: '',
                    index: -1
                };
            }

            // Hide modal
            deleteCategoryModal.classList.remove('show');
        }

        // Show add category modal
        function showAddCategoryModal() {
            categoryOptions.classList.remove('show');
            newCategoryInput.value = '';
            addCategoryModal.classList.add('show');
            newCategoryInput.focus();
        }

        // Hide add category modal
        function hideAddCategoryModal() {
            addCategoryModal.classList.remove('show');
        }

        // Add new category
        function addNewCategory() {
            const newCategory = newCategoryInput.value.trim();
            if (newCategory) {
                const type = typeInput.value;
                categories[type].push(newCategory);
                saveCategories();
                updateCategoryOptions(type);
                selectCategory(newCategory);
                hideAddCategoryModal();
            }
        }

        // Format amount input
        function formatAmount(value) {
            // Remove all non-digit characters
            value = value.replace(/\D/g, '');

            // Format with thousand separators
            if (value.length > 0) {
                // Convert to number and format
                const num = parseInt(value, 10);
                return num.toLocaleString('id-ID');
            }

            return '';
        }

        // Event listeners
        btnIncome.addEventListener('click', function(e) {
            e.preventDefault();
            setType('income');
        });

        btnExpense.addEventListener('click', function(e) {
            e.preventDefault();
            setType('expense');
        });

        categorySelected.addEventListener('click', function() {
            categoryOptions.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                categoryOptions.classList.remove('show');
            }
        });

        // Format amount input
        amountInput.addEventListener('input', function(e) {
            const formatted = formatAmount(e.target.value);
            e.target.value = formatted;
        });

        // Modal event listeners
        modalClose.addEventListener('click', hideAddCategoryModal);
        modalCancel.addEventListener('click', hideAddCategoryModal);
        modalSave.addEventListener('click', addNewCategory);

        // Close modal when clicking outside
        addCategoryModal.addEventListener('click', function(e) {
            if (e.target === addCategoryModal) {
                hideAddCategoryModal();
            }
        });

        // Handle Enter key in modal input
        newCategoryInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                addNewCategory();
            }
        });

        // Delete category modal event listeners
        deleteModalClose.addEventListener('click', () => {
            deleteCategoryModal.classList.remove('show');
        });

        deleteModalCancel.addEventListener('click', () => {
            deleteCategoryModal.classList.remove('show');
        });

        deleteModalConfirm.addEventListener('click', executeDeleteCategory);

        // Close modal when clicking outside
        deleteCategoryModal.addEventListener('click', function(e) {
            if (e.target === deleteCategoryModal) {
                deleteCategoryModal.classList.remove('show');
            }
        });

        // Date Input Listener
        if (dateInput && dateDisplayText) {
            dateInput.addEventListener('change', function(e) {
                if (e.target.value) {
                    const parts = e.target.value.split('-');
                    const date = new Date(parts[0], parts[1] - 1, parts[2]);
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    dateDisplayText.textContent = date.toLocaleDateString('id-ID', options);
                }
            });
        }

        // Initialize
        setType('expense');
        enableDragScroll(bulkListEl);
        if (balanceWarningClose && balanceWarningModal) {
            balanceWarningClose.addEventListener('click', function() {
                balanceWarningModal.classList.remove('show');
            });
        }
        if (balanceWarningOk && balanceWarningModal) {
            balanceWarningOk.addEventListener('click', function() {
                balanceWarningModal.classList.remove('show');
            });
        }

        function formatRupiah(n) {
            const x = Number(n || 0);
            return x.toLocaleString('id-ID');
        }

        function resetFormFields() {
            amountInput.value = '';
            descInput.value = '';
            dateInput.value = '{{ now()->toDateString() }}';
            if (dateDisplayText) {
                const nowDate = new Date(dateInput.value);
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                dateDisplayText.textContent = nowDate.toLocaleDateString('id-ID', options);
            }
            document.getElementById('category-text').innerHTML = '<i class="fa-solid fa-layer-group" style="color: var(--text-muted); flex-shrink: 0;"></i> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem;">Pilih Kategori</span>';
            categoryValue.value = '';
        }

        function calculateDraftNetChange() {
            let net = 0;
            bulkItems.forEach(function(it) {
                if (it.type === 'income') {
                    net += Number(it.amount || 0);
                } else if (it.type === 'expense') {
                    net -= Number(it.amount || 0);
                }
            });
            return net;
        }

        function getAvailableAfterDrafts() {
            return initialBalance + calculateDraftNetChange();
        }

        function showBalanceWarning(available, requested) {
            if (!balanceWarningModal || !balanceWarningText) return;
            const avail = Number(available || 0);
            const need = Number(requested || 0);
            balanceWarningText.textContent = 'Pengeluaran ini melebihi saldo yang tersedia. Saldo tersisa Rp ' + formatRupiah(String(Math.max(0, avail))) + ', sedangkan pengeluaran yang kamu masukkan Rp ' + formatRupiah(String(need)) + '.';
            balanceWarningModal.classList.add('show');
        }

        function setEditing(index) {
            editingIndex = index;
            if (editingIndex === null || editingIndex < 0 || editingIndex >= bulkItems.length) {
                editingIndex = null;
                if (btnSavePrimary) {
                    btnSavePrimary.innerHTML = '<i class="fa-solid fa-check-circle" style="margin-right: 8px"></i> Simpan Transaksi';
                }
                return;
            }
            const it = bulkItems[editingIndex];
            setType(it.type === 'income' ? 'income' : 'expense');
            const type = typeInput.value;
            if (!categories[type].includes(it.category)) {
                categories[type].push(it.category);
                saveCategories();
            }
            updateCategoryOptions(type);
            selectCategory(it.category);
            amountInput.value = formatRupiah(it.amount);
            descInput.value = it.description || '';
            dateInput.value = it.date;
            if (dateDisplayText && it.date) {
                const parts = it.date.split('-');
                const d = new Date(parts[0], parts[1] - 1, parts[2]);
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                dateDisplayText.textContent = d.toLocaleDateString('id-ID', options);
            }
            if (btnSavePrimary) {
                btnSavePrimary.innerHTML = '<i class="fa-solid fa-pen-to-square" style="margin-right: 8px"></i> Simpan Edit';
            }
            renderBulkList();
        }

        function renderBulkList() {
            if (!bulkItems.length) {
                bulkListEl.style.display = 'none';
                bulkToolbarEl.style.display = 'none';
                bulkListEl.innerHTML = '';
                editingIndex = null;
                transactionForm.removeAttribute('data-bulk');
                transactionForm.noValidate = false;
                if (btnSavePrimary) {
                    btnSavePrimary.innerHTML = '<i class="fa-solid fa-check-circle" style="margin-right: 8px"></i> Simpan Transaksi';
                }
                return;
            }
            bulkListEl.style.display = '';
            bulkToolbarEl.style.display = '';
            bulkListEl.innerHTML = '';
            transactionForm.setAttribute('data-bulk', '1');
            transactionForm.noValidate = true;
            bulkItems.forEach(function(it, idx) {
                const div = document.createElement('div');
                div.className = 'bulk-item' + (editingIndex === idx ? ' editing' : '');
                const typeClass = it.type === 'income' ? 'income' : 'expense';
                const label = document.createElement('div');
                label.className = 'bulk-item-main';
                label.innerHTML = `
                    <span class="bulk-type ${typeClass}">${it.type === 'income' ? 'Pemasukan' : 'Pengeluaran'}</span>
                    <span class="bulk-amount">Rp ${formatRupiah(it.amount)}</span>
                    <span class="bulk-meta">${it.category} · ${it.date}</span>
                `;
                label.addEventListener('click', function() {
                    setEditing(idx);
                });
                const actions = document.createElement('div');
                actions.className = 'bulk-actions';
                const rm = document.createElement('button');
                rm.type = 'button';
                rm.className = 'bulk-remove';
                rm.setAttribute('title', 'Hapus');
                rm.innerHTML = '<i class="fa-solid fa-trash"></i>';
                rm.addEventListener('click', function() {
                    bulkItems.splice(idx, 1);
                    if (editingIndex === idx) {
                        editingIndex = null;
                        resetFormFields();
                        if (btnSavePrimary) {
                            btnSavePrimary.innerHTML = '<i class="fa-solid fa-check-circle" style="margin-right: 8px"></i> Simpan Transaksi';
                        }
                    }
                    renderBulkList();
                });
                actions.appendChild(rm);
                div.appendChild(label);
                div.appendChild(actions);
                bulkListEl.appendChild(div);
            });
        }

        btnAddBulk.addEventListener('click', function() {
            const amountValue = amountInput.value.replace(/\D/g, '');
            if (!amountValue || !categoryValue.value || !dateInput.value) {
                categoryOptions.classList.add('show');
                return;
            }
            if (typeInput.value === 'expense') {
                const availableNow = getAvailableAfterDrafts();
                if (Number(amountValue) > Math.max(0, availableNow)) {
                    showBalanceWarning(availableNow, amountValue);
                    return;
                }
            }
            bulkItems.push({
                type: typeInput.value,
                amount: Number(amountValue),
                category: categoryValue.value,
                date: dateInput.value,
                description: descInput.value || null
            });
            renderBulkList();
            amountInput.value = '';
            categoryValue.value = '';
            document.getElementById('category-text').innerHTML = '<i class="fa-solid fa-layer-group" style="color: var(--text-muted); flex-shrink: 0;"></i> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem;">Pilih Kategori</span>';
            descInput.value = '';
        });

        btnClearBulk.addEventListener('click', function() {
            bulkItems = [];
            editingIndex = null;
            resetFormFields();
            renderBulkList();
        });
        btnCancelBulk.addEventListener('click', function() {
            bulkItems = [];
            editingIndex = null;
            resetFormFields();
            renderBulkList();
        });

        function submitBulkItems() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('transactions.bulk.store') }}';
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            const payload = document.createElement('input');
            payload.type = 'hidden';
            payload.name = 'items_json';
            payload.value = JSON.stringify(bulkItems);
            form.appendChild(csrf);
            form.appendChild(payload);
            document.body.appendChild(form);
            loadingOverlay.classList.add('show');
            form.submit();
        }

        btnSavePrimary.addEventListener('click', function(e) {
            if (editingIndex === null && bulkItems.length > 0) {
                e.preventDefault();
                e.stopPropagation();
                submitBulkItems();
            }
        });

        transactionForm.addEventListener('submit', function(e) {
            const amountValue = amountInput.value.replace(/\D/g, '');
            if (editingIndex !== null && editingIndex >= 0 && editingIndex < bulkItems.length) {
                e.preventDefault();
                if (!amountValue || !categoryValue.value || !dateInput.value) {
                    categoryOptions.classList.add('show');
                    return;
                }
                bulkItems[editingIndex] = {
                    type: typeInput.value,
                    amount: Number(amountValue),
                    category: categoryValue.value,
                    date: dateInput.value,
                    description: descInput.value || null
                };
                editingIndex = null;
                if (btnSavePrimary) {
                    btnSavePrimary.innerHTML = '<i class="fa-solid fa-check-circle" style="margin-right: 8px"></i> Simpan Transaksi';
                }
                resetFormFields();
                renderBulkList();
                return;
            }
            if (bulkItems.length > 0) {
                e.preventDefault();
                submitBulkItems();
                return;
            }
            if (typeInput.value === 'expense') {
                const availableNow = initialBalance;
                if (Number(amountValue) > Math.max(0, availableNow)) {
                    e.preventDefault();
                    showBalanceWarning(availableNow, amountValue);
                    return;
                }
            }
            if (amountValue) {
                const hiddenAmount = document.createElement('input');
                hiddenAmount.type = 'hidden';
                hiddenAmount.name = 'amount_numeric';
                hiddenAmount.value = amountValue;
                transactionForm.appendChild(hiddenAmount);
            }
            if (!categoryValue.value) {
                e.preventDefault();
                categoryOptions.classList.add('show');
                return;
            }
            loadingOverlay.classList.add('show');
        }, { capture: true });
    </script>
</body>
</html>
