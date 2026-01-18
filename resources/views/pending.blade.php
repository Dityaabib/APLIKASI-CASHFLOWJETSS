<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaksi Pending - {{ config('app.name', 'CashFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { padding-top: 60px; padding-bottom: 70px; margin: 0; background: #f9fafb; }
        .container { max-width: 100%; margin: 0; padding: 0; }
        .page-title { display:flex; align-items:center; gap:10px; font-weight:800; font-size:1.25rem; color:#374151; margin:0 0 12px 0; }
        .card { background:#ffffff; border-radius:0; padding:14px; box-shadow: none; }
        .table-wrap { overflow-x:auto; }
        .table { min-width: 820px; width:100%; border-collapse: collapse; }
        .table th, .table td { text-align:left; padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size:0.95rem; white-space: nowrap; }
        .table th { color:#6b7280; font-weight:700; }
        .type-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:10px; font-weight:700; }
        .type-income { background:#ecfeff; color:#0ea5e9; }
        .type-expense { background:#fef2f2; color:#ef4444; }
        .amount { font-weight:800; }
        .amount.pos { color:#0ea5e9; }
        .amount.neg { color:#ef4444; }
        .empty { text-align:center; color:#6b7280; padding:16px; }
        .toast-success { position:fixed; top:12px; right:12px; background:linear-gradient(135deg,#10b981,#059669); color:#ffffff; border-radius:12px; padding:10px 12px; font-weight:700; display:flex; align-items:center; gap:8px; box-shadow:0 12px 24px rgba(0,0,0,0.12); z-index:1000; opacity:0; transform: translateY(-8px); animation:toastIn .25s ease forwards; }
        .toast-success i { font-size:1.1rem; }
        @keyframes toastIn { to { opacity:1; transform: translateY(0); } }
        .row-actions { display:inline-flex; gap:8px; opacity:0; transition: opacity .2s ease; }
        tr:hover .row-actions, tr.row-active .row-actions { opacity:1; }
        .icon-btn { border:none; border-radius:8px; padding:8px 10px; cursor:pointer; font-weight:700; }
        .edit-btn { background: linear-gradient(135deg, #0ea5e9, #1a9cb0); color:#ffffff; }
        .delete-btn { background: linear-gradient(135deg, #ef4444, #dc2626); color:#ffffff; }
        tr.row-active { background-color:#f9fafb; }
        /* Delete confirm modal */
        .fade-modal { position: fixed; left: 0; right: 0; top: 0; bottom: 0; display:none; align-items:center; justify-content:center; background: rgba(0,0,0,0.5); z-index: 1000; }
        .fade-modal.show { display:flex; }
        .confirm-card { background:#fff;border-radius:12px;padding:16px;width:90%;max-width:360px; box-shadow:0 20px 40px rgba(0,0,0,0.18); }
        .confirm-actions { display:flex;justify-content:flex-end;gap:8px }
        .action-btn { background:#e5e7eb; color:#374151; padding:8px 10px; border-radius:8px; border:none; cursor:pointer; }
        .action-btn.primary { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:#fff }
        .modal { position:fixed; inset:0; background:rgba(17,24,39,0.45); backdrop-filter:saturate(180%) blur(6px); display:none; align-items:center; justify-content:center; z-index:1000; }
        .modal.show { display:flex; }
        .modal-card { width:90%; max-width:420px; background:#ffffff; border-radius:16px; box-shadow:0 20px 40px rgba(0,0,0,0.18); }
        .modal-head { padding:14px 16px; font-weight:800; border-bottom:1px solid #e5e7eb; }
        .modal-body { padding:14px 16px; display:grid; gap:10px; }
        .modal-actions { padding:0 16px 16px 16px; display:flex; gap:10px; justify-content:flex-end; }
        .field { display:grid; gap:6px; }
        .label { font-weight:700; color:#374151; }
        .input { width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; }
        .chips { display:flex; gap:8px; }
        .chip { flex:1; padding:10px 12px; border-radius:10px; border:1px solid #e5e7eb; cursor:pointer; font-weight:700; text-align:center; }
        .chip.active.income { background:#ecfeff; color:#0ea5e9; border-color:#a5f3fc; }
        .chip.active.expense { background:#fef2f2; color:#ef4444; border-color:#fecaca; }
        .btn { border:none; border-radius:10px; padding:10px 12px; font-weight:700; cursor:pointer; }
        .btn-primary { background: linear-gradient(135deg, #0ea5e9, #1a9cb0); color:#ffffff; }
        .btn-secondary { background:#f3f4f6; color:#374151; }
    </style>
</head>
<body>
    <div class="fixed inset-x-0 top-0 z-50" style="background:#ffffff; border-bottom:1px solid #e5e7eb;">
        <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('catat') }}" class="nav-icon" style="color:#4b5563; font-size:1.2rem;"><i class="fa-solid fa-arrow-left"></i></a>
            <div class="page-title"><i class="fa-solid fa-clock"></i> <span>Transaksi Pending</span></div>
            <div style="width:24px;height:24px"></div>
        </div>
    </div>

    @if(session('pending_saved'))
        <div class="toast-success" id="toastSuccess">
            <i class="fa-solid fa-check-circle"></i>
            <span>Berhasil menyimpan data</span>
        </div>
    @endif
    <div class="container">
        <div class="card table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($items as $t)
                    <tr>
                        <td>
                            @php $isIncome = ($t['type'] ?? '') === 'income'; @endphp
                            <span class="type-badge {{ $isIncome ? 'type-income' : 'type-expense' }}">
                                <i class="fa-solid {{ $isIncome ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                                {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td>{{ $t['category'] ?? '-' }}</td>
                        <td class="amount {{ ($t['type'] ?? '') === 'income' ? 'pos' : 'neg' }}">
                            {{ (($t['type'] ?? '') === 'income' ? '+' : '-') . 'Rp ' . number_format((float)($t['amount'] ?? 0), 0, ',', '.') }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($t['date'] ?? now())->translatedFormat('d F Y') }}</td>
                        <td>{{ $t['description'] ?? '-' }}</td>
                        <td>
                            <div class="row-actions">
                                <button class="icon-btn edit-btn" data-id="{{ $t['id'] ?? '' }}" data-type="{{ $t['type'] ?? '' }}" data-category="{{ $t['category'] ?? '' }}" data-amount="{{ (float)($t['amount'] ?? 0) }}" data-date="{{ $t['date'] ?? '' }}" data-description="{{ $t['description'] ?? '' }}"><i class="fa-solid fa-pen"></i></button>
                                <button class="icon-btn delete-btn" data-id="{{ $t['id'] ?? '' }}"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Belum ada transaksi pending</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-bottom-nav />
    <div class="modal" id="editModal">
        <div class="modal-card">
            <div class="modal-head">Edit Transaksi Pending</div>
            <form id="pendingEditForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="chips">
                        <div class="chip income" id="chipIncome">Pemasukan</div>
                        <div class="chip expense" id="chipExpense">Pengeluaran</div>
                    </div>
                    <input type="hidden" name="type" id="editType">
                    <div class="field">
                        <div class="label">Kategori</div>
                        <input class="input" type="text" name="category" id="editCategory">
                    </div>
                    <div class="field">
                        <div class="label">Jumlah</div>
                        <input class="input" type="number" name="amount" id="editAmount" min="0" step="1">
                    </div>
                    <div class="field">
                        <div class="label">Tanggal</div>
                        <input class="input" type="date" name="date" id="editDate">
                    </div>
                    <div class="field">
                        <div class="label">Deskripsi</div>
                        <input class="input" type="text" name="description" id="editDescription">
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" id="editCancel">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <form id="pendingDeleteForm" method="POST" style="display:none">
        @csrf
        @method('DELETE')
    </form>
    <div id="deleteConfirmModal" class="fade-modal">
        <div class="confirm-card">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                <div style="width:40px;height:40px;border-radius:5px;background:#fff0f0;display:flex;align-items:center;justify-content:center;color:#ef4444">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div style="font-weight:700;color:#374151">Hapus Transaksi Pending</div>
            </div>
            <div style="color:#6b7280;margin-bottom:12px">Apakah Anda yakin ingin menghapus transaksi ini?</div>
            <div class="confirm-actions">
                <button id="deleteCancelBtn" class="action-btn">Batal</button>
                <button id="deleteConfirmBtn" class="action-btn primary">Hapus</button>
            </div>
        </div>
    </div>
    <script>
        (function(){
            var t=document.getElementById('toastSuccess');
            if(t){ setTimeout(function(){ t.style.transition='opacity .3s ease, transform .3s ease'; t.style.opacity='0'; t.style.transform='translateY(-8px)'; setTimeout(function(){ t.style.display='none'; },300); },2500); }
            var modal=document.getElementById('editModal');
            var chipIncome=document.getElementById('chipIncome');
            var chipExpense=document.getElementById('chipExpense');
            var editType=document.getElementById('editType');
            var editCategory=document.getElementById('editCategory');
            var editAmount=document.getElementById('editAmount');
            var editDate=document.getElementById('editDate');
            var editDescription=document.getElementById('editDescription');
            var editCancel=document.getElementById('editCancel');
            var editForm=document.getElementById('pendingEditForm');
            function setType(val){
                editType.value=val;
                if(val==='income'){ chipIncome.classList.add('active','income'); chipExpense.classList.remove('active','expense'); }
                else { chipExpense.classList.add('active','expense'); chipIncome.classList.remove('active','income'); }
            }
            chipIncome.addEventListener('click',function(){ setType('income'); });
            chipExpense.addEventListener('click',function(){ setType('expense'); });
            editCancel.addEventListener('click',function(){ modal.classList.remove('show'); });
            modal.addEventListener('click',function(e){ if(e.target===modal){ modal.classList.remove('show'); } });
            document.querySelectorAll('.edit-btn').forEach(function(btn){
                btn.addEventListener('click',function(){
                    var id=this.dataset.id||'';
                    var type=this.dataset.type||'expense';
                    var category=this.dataset.category||'';
                    var amount=parseFloat(this.dataset.amount||'0')||0;
                    var date=this.dataset.date||'';
                    var description=this.dataset.description||'';
                    setType(type==='income'?'income':'expense');
                    editCategory.value=category;
                    editAmount.value=amount;
                    editDate.value=date;
                    editDescription.value=description;
                    editForm.action='/pending/'+id;
                    modal.classList.add('show');
                });
            });
            var deleteModal=document.getElementById('deleteConfirmModal');
            var currentDeleteId=null;
            document.querySelectorAll('.delete-btn').forEach(function(btn){
                btn.addEventListener('click',function(){
                    currentDeleteId=this.dataset.id||'';
                    if(!currentDeleteId) return;
                    deleteModal.classList.add('show');
                });
            });
            document.getElementById('deleteCancelBtn').addEventListener('click',function(){ deleteModal.classList.remove('show'); currentDeleteId=null; });
            deleteModal.addEventListener('click',function(e){ if(e.target===deleteModal){ deleteModal.classList.remove('show'); currentDeleteId=null; } });
            document.getElementById('deleteConfirmBtn').addEventListener('click',function(){
                if(!currentDeleteId) return;
                var f=document.getElementById('pendingDeleteForm');
                f.action='/pending/'+currentDeleteId;
                f.submit();
            });
            // Pin row on click to persist actions
            var rows=[].slice.call(document.querySelectorAll('.table tbody tr'));
            var pinned=null;
            rows.forEach(function(r){
                r.addEventListener('click',function(e){
                    if(e.target.closest('.row-actions')) return;
                    if(pinned && pinned!==r) pinned.classList.remove('row-active');
                    pinned=r;
                    r.classList.add('row-active');
                });
            });
            document.addEventListener('click',function(e){
                if(!e.target.closest('.table')){ if(pinned){ pinned.classList.remove('row-active'); pinned=null; } }
            });
        })();
    </script>
</body>
</html>
