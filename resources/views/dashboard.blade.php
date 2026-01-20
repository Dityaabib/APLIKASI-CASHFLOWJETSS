<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'CashFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <style>
        /* Style untuk Body */
        body {
            padding-top: 80px; /* Ruang untuk navigasi atas */
            padding-bottom: 70px; /* Ruang untuk bottom nav */
            margin: 0;
            padding-left: 0;
            padding-right: 0;
            background:
                radial-gradient(ellipse at top left, rgba(79, 70, 229, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at bottom right, rgba(236, 72, 153, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at center, rgba(6, 182, 212, 0.08) 0%, transparent 60%),
                linear-gradient(135deg, #f8fafc 0%, #e0e7ff 30%, #fce7f3 60%, #e0f2fe 100%);
        }




        /* Style untuk card ringkasan dengan background putih */
        .summary-card {
            background:
                radial-gradient(circle at top left, rgba(59,130,246,0.12), transparent 55%),
                radial-gradient(circle at bottom right, rgba(16,185,129,0.12), transparent 55%),
                #ffffff;
            border: 1px solid rgba(148,163,184,0.35);
            border-radius: 18px;
            width: 100%;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 14px 30px rgba(15,23,42,0.12);
            padding: 10px 0 12px;
        }
        .summary-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 16px 8px;
        }
        .summary-main {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }
        .section-sep { height: 1px; background: linear-gradient(to right, #e5e7eb, #f3f4f6, #e5e7eb); margin: 8px 0 12px; }
        .full-bleed { margin-left: 0; margin-right: 0; }

        .balance-section {
            margin-top: 2px;
        }

        .balance-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .balance-amount {
            font-size: 1.9rem;
            font-weight: 800;
            color: #0f172a;
        }

        .summary-trend {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-right: 8px;
        }

        .summary-trend-inner {
            width: 72px;
            height: 72px;
            border-radius: 9999px;
            background: radial-gradient(circle at 30% 20%, rgba(34,197,94,0.35), transparent 55%), rgba(240,253,244,0.98);
            border: 1px solid rgba(34,197,94,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(22,163,74,0.28);
        }

        .finance-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3px;
            margin: 0; /* Full layar kiri-kanan */
            padding: 0; /* Nempel ke sisi layar */
            overflow: hidden;
            transition: grid-template-columns 0.35s ease-in-out;
        }

        .finance-item {
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Pastikan tidak ada overflow */
            cursor: pointer; /* Tambahkan cursor pointer untuk menunjukkan bisa diklik */
            transition: transform 0.35s ease, box-shadow 0.2s ease, opacity 0.35s ease;
            will-change: transform, opacity;
            position: relative;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 10px 20px rgba(15,23,42,0.06);
        }

        .finance-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .finance-grid.expand-left { grid-template-columns: 1fr 0 0; }
        .finance-grid.expand-mid { grid-template-columns: 0 1fr 0; }
        .finance-grid.expand-right { grid-template-columns: 0 0 1fr; }
        .finance-item.expanded-item { opacity: 1; transform: translateX(0); }
        .finance-item.collapsed-left { opacity: 0; transform: translateX(-12px); pointer-events: none; }
        .finance-item.collapsed-right { opacity: 0; transform: translateX(12px); pointer-events: none; }
        .finance-grid.expand-left .finance-item.expanded-item .chart-container,
        .finance-grid.expand-mid .finance-item.expanded-item .chart-container,
        .finance-grid.expand-right .finance-item.expanded-item .chart-container { height: 240px; transition: height 0.35s ease-in-out; }

        .finance-item.expanded-item .finance-header { margin-bottom: 12px; }
        .finance-item.expanded-item .chart-container { margin-top: 16px; }
        .finance-item.expanded-item .finance-header { align-items: flex-start; text-align: left; }
        .finance-item.expanded-item .finance-header .finance-label { justify-content: flex-start; margin-left: 10px;}
        .finance-item.expanded-item .finance-amount { text-align: left; margin-left: 10px;}

        .expand-switch { position: absolute; top: 8px; right: 8px; display: none; align-items: flex-start; gap: 8px; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 5px; padding: 6px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); z-index: 2; }
        .finance-item.expanded-item .expand-switch { display: flex; }
        .switch-block { display:flex; flex-direction:column; align-items:flex-start; gap: 2px; cursor: pointer; line-height: 1.2; }
        .switch-block:hover { transform: translateY(-1px); }
        .switch-sep { width: 1px; align-self: stretch; background: #e5e7eb; }
        .expand-switch .finance-label { margin: 0; display: flex; align-items: center; gap: 5px; font-size: 0.8rem; }
        .expand-switch .finance-label { justify-content: flex-start; }
        .expand-switch .finance-amount { margin: 0; font-size: 0.85rem; font-weight: 700; text-align: left; }



        .finance-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 5px;
        }

        .finance-label {
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 3px;
        }
        .finance-label i {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        .finance-header .finance-label { justify-content: center; }
        .finance-header .income-mode-period,
        .finance-header .expense-mode-period,
        .finance-header .saving-mode-period { align-self: flex-start; text-align: left; margin-left: 10px;}

        .finance-amount {
            font-size: 0.85rem;
            font-weight: bold;
            text-align: center;
        }

        .income-color {
            color: #10b981;
        }
        .income-color i {
            background: rgba(16,185,129,0.12);
            color: #10b981;
        }

        .expense-color {
            color: #ef4444;
        }
        .expense-color i {
            background: rgba(239,68,68,0.12);
            color: #ef4444;
        }

        .saving-color {
            color: #3b82f6;
        }
        .saving-color i {
            background: rgba(59,130,246,0.12);
            color: #3b82f6;
        }

        /* Perubahan: Style untuk chart container dengan lebar 110px */
        .chart-container {
            height: 110px;
            width: 100%;
            margin: 0;
            flex-grow: 0;
            position: relative;
            overflow: hidden;
        }
        /* Tooltip eksternal untuk Chart.js */
        .chartjs-tooltip { position: absolute; left:0; top:0; background: rgba(255,255,255,0.55); border: 1px solid rgba(229,231,235,0.8); border-radius: 10px; box-shadow: 0 10px 24px rgba(0,0,0,0.08); padding: 8px 10px; font-size: 0.85rem; color: #111827; pointer-events: none; z-index: 1000; white-space: normal; line-height: 1.25; max-width: calc(100% - 12px); backdrop-filter: blur(8px) saturate(1.1); transition: opacity .12s ease-out, transform .18s ease-out; will-change: transform, opacity; transform-origin: top left; }
        .chartjs-tooltip.hidden { opacity: 0; }
        .tooltip-title { display:block; font-weight:700; color:#374151; margin-bottom:6px; }
        .tooltip-row { display:flex; align-items:center; gap:8px; margin: 3px 0; }
        .tooltip-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }

        /* Style untuk detail chart container */
        .detail-chart-container {
            margin-top: 20px;
            padding: 0;
            background-color: #ffffff;
            border-radius: 0;
            box-shadow: none;
            display: none; /* Awalnya disembunyikan */
            animation: fadeIn 0.5s ease-in-out;
        }

        .detail-chart-container.show {
            display: block;
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px 0;
        }

        .detail-header h3 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #111827;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            transition: color 0.2s;
        }

        .close-btn:hover {
            color: #374151;
        }

        .detail-chart-wrapper {
            height: 320px;
            width: 100%;
        }

        /* Style untuk card grafik baru */
        .comparison-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            width: 100%;
        }

        .comparison-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .comparison-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #111827;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 70%;
        }

        .comparison-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            background: linear-gradient(135deg, #1a9cb0 0%, #0f7b8a 100%);
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 0.85rem;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        .comparison-chart-container {
            height: 300px;
            width: 100%;
            position: relative;
        }

        /* Custom legend style */
        .custom-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .income-legend {
            background-color: rgba(16, 185, 129, 0.8);
        }

        .expense-legend {
            background-color: rgba(239, 68, 68, 0.8);
        }

        .trend-legend {
            background-color: rgba(59, 130, 246, 0.8);
        }

        .distribution-card {
            position: relative;
            background:
                radial-gradient(circle at top left, rgba(59,130,246,0.12), transparent 55%),
                radial-gradient(circle at bottom right, rgba(14,165,233,0.12), transparent 55%),
                #ffffff;
            border: 1px solid rgba(148,163,184,0.25);
            border-radius: 18px;
            box-shadow: 0 14px 32px rgba(15,23,42,0.12);
            padding: 14px 14px 10px;
            margin-top: 14px;
            margin-bottom: 18px;
            overflow: hidden;
        }

        .distribution-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            padding: 2px 2px 8px;
        }

        .distribution-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #111827;
        }

        .distribution-subtitle {
            margin: 2px 0 0;
            font-size: 0.8rem;
            color: #6b7280;
        }

        .distribution-chip {
            align-self: center;
            padding: 6px 11px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #1d4ed8;
            border: 1px solid rgba(37,99,235,0.25);
            background: rgba(37,99,235,0.06);
            white-space: nowrap;
        }

        .distribution-divider {
            width: 100%;
            height: 2px;
            border-radius: 9999px;
            background: linear-gradient(to right, #3b82f6, #10b981);
            opacity: 0.9;
            margin-bottom: 6px;
        }

        .distribution-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 10px;
            padding: 8px 4px 4px;
        }

        .section-card-full {
            background: transparent;
            margin: 0 0 12px 0;
            border-radius: 0;
            padding: 0;
        }

        .chart-card-full {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.9) 100%);
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            margin: 0;
            box-shadow: 0 14px 30px rgba(15,23,42,0.12);
            padding-top: 20px;
            backdrop-filter: blur(15px);
            position: relative;
            overflow: hidden;
        }

        .chart-card-full::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
        }

        .chart-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 20px;
        }

        .chart-title i {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.08);
            padding: 8px;
            border-radius: 10px;
        }

        .dist-layout {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 0 20px 16px 20px;
        }

        .dist-col-chart {
            flex: 0 0 52%;
            min-width: 260px;
        }

        .dist-col-legend {
            flex: 1;
            min-width: 240px;
            display: flex;
            align-items: center;
        }

        .chart-wrap-3d {
            position: relative;
            height: 320px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 0;
            padding: 0;
        }

        #homeDistChart {
            width: 100%;
            max-height: 100%;
        }

        .chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
            z-index: 10;
            width: 55%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart-total-value {
            font-size: clamp(1rem, 4vw, 1.8rem);
            font-weight: 800;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            display: block;
        }

        .dist-separator {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(0, 0, 0, 0.1), transparent);
            margin: 0 20px 20px 20px;
        }

        .dist-amount-wrap {
            padding: 0 20px 16px 20px;
        }

        .dist-amount-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 20px 0 0;
        }

        .dist-amount-title i {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.08);
            padding: 8px;
            border-radius: 10px;
        }

        .legend-3d.legend-vertical {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
            margin-top: 0;
            padding: 0;
        }

        .legend-item-3d {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .legend-item-percent {
            padding: 4px 0;
        }

        .legend-item-amount {
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 16px rgba(15,23,42,0.06);
        }

        .legend-left-3d {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .legend-right-3d {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
        }

        .legend-color-3d {
            flex: 0 0 auto;
            flex-shrink: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            margin-right: 10px;
            box-shadow: 0 4px 8px rgba(15,23,42,0.12);
            border: 1px solid rgba(15,23,42,0.12);
        }

        .legend-label-3d {
            font-size: 0.86rem;
            color: #111827;
            font-weight: 700;
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .legend-item-percent .legend-label-3d {
            font-size: 0.8rem;
            font-weight: 800;
            white-space: normal;
            line-height: 1.2;
            max-height: 2.4em;
        }

        .legend-amount-3d {
            font-size: 0.85rem;
            color: #111827;
            font-weight: 800;
        }

        .legend-percent-3d {
            font-size: 0.8rem;
            font-weight: 900;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
        }

        .legend-amount-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        @media (min-width: 768px) {
            .legend-amount-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            .dist-layout {
                flex-wrap: nowrap;
                gap: 12px;
                padding: 0 12px 12px 12px;
            }
            .dist-col-chart {
                flex: 0 0 58%;
                min-width: 0;
            }
            .dist-col-legend {
                flex: 1 1 auto;
                min-width: 0;
            }
            .chart-wrap-3d {
                height: 220px;
            }
            .chart-total-value {
                font-size: 1.15rem;
            }
        }

        .dist-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            text-align: center;
            padding: 4px 2px 6px;
            border-radius: 9999px;
            background: transparent;
            box-shadow: none;
            border: none;
            transition: transform 0.16s ease, opacity 0.16s ease;
        }

        .dist-item:hover {
            transform: translateY(-2px) scale(1.02);
            opacity: 0.95;
        }

        .dist-icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.25rem;
            box-shadow: 0 6px 12px rgba(15,23,42,0.2);
        }

        .dist-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #111827;
            line-height: 1.2;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dist-meta {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            width: 100%;
        }

        .dist-amount {
            font-size: 0.8rem;
            font-weight: 700;
            color: #111827;
        }

        .dist-percent {
            font-size: 0.72rem;
            font-weight: 600;
            color: #047857;
            background: rgba(16,185,129,0.09);
            border-radius: 9999px;
            padding: 2px 9px;
        }

        .theme-dark .distribution-card { background: rgba(17,24,39,0.9); border-color: rgba(255,255,255,0.08); }
        .theme-dark .dist-label { color: #e5e7eb; }

        .legend-chip { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; background: rgba(255,255,255,0.9); border: 1px solid rgba(15,23,42,0.06); border-radius: 9999px; box-shadow: 0 8px 16px rgba(0,0,0,0.06); backdrop-filter: blur(6px); font-weight: 700; font-size: .86rem; color: #374151; }
        .legend-dot { width: 12px; height: 12px; border-radius: 3px; border: 1px solid rgba(0,0,0,0.1); box-shadow: 0 4px 8px rgba(0,0,0,0.06); }

        /* Style untuk histori transaksi */
        .history-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            width: 100%;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .history-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #111827;
            margin: 0;
        }

        .filter-btn {
            background: linear-gradient(135deg, #1a9cb0 0%, #0f7b8a 100%);
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.8rem;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .filter-btn.active {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff;
        }
        .refresh-btn {}
            background: #e5e7eb;
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.8rem;
            color: #374151;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-right: 8px;
        }
        .refresh-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }

        /* MODIFIKASI: Container untuk tabel dengan scroll horizontal */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Untuk scroll yang lebih halus di iOS */
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .history-table {
            width: 100%;
            min-width: 600px; /* Set minimum width untuk mencegah wrap */
            border-collapse: collapse;
            margin-top: 10px;
        }

        .history-table th, .history-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap; /* Mencegah text wrap */
        }

        .history-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            position: sticky; /* Membuat header tetap terlihat saat scroll */
            top: 0;
            z-index: 10;
        }

        .history-table tr:hover {
            background-color: #f9fafb;
        }

        .history-table td {
            font-size: 0.85rem;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap; /* Mencegah text wrap */
        }

        .status-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-failed {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .category-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 8px;
            font-size: 0.75rem;
        }

        .category-income {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .category-expense {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .category-saving {
            background-color: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .dist-all-btn {
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #374151;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: background .18s ease, color .18s ease, border-color .18s ease;
        }
        .dist-all-btn:hover {
            background: #f3f4f6;
            border-color: #cfd3d8;
            color: #111827;
        }
        .dist-all-btn .chip-icon {
            width: 24px;
            height: 24px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            background: #e5e7eb;
        }

        .amount-positive {
            color: #10b981;
            font-weight: 600;
        }

        .amount-negative {
            color: #ef4444;
            font-weight: 600;
        }

        .view-all-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px;
            margin-top: 15px;
            background-color: #f3f4f6;
            border: none;
            border-radius: 8px;
            color: #4b5563;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .view-all-btn:hover {
            background-color: #e5e7eb;
            color: #1f2937;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive design - tetap 3 kolom di semua ukuran layar */
        @media (max-width: 380px) {
            .finance-grid { gap: 3px; margin: 0; }

            .finance-header {
                padding: 0 3px; /* Kurangi padding untuk layar kecil */
            }

            .finance-label {
                font-size: 0.75rem;
            }

            .finance-amount {
                font-size: 0.8rem;
            }

            .chart-container {
                height: 80px;
                width: 100%; /* Kurangi ukuran untuk layar kecil */
            }

            .detail-chart-wrapper {
                height: 250px;
            }

            .comparison-chart-container {
                height: 250px;
            }

            .comparison-title {
                font-size: 1.1rem;
                max-width: 60%;
            }

            .action-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .custom-legend {
                gap: 15px;
            }

            .legend-item {
                font-size: 0.8rem;
            }

            .polar-area-container {
                height: 380px;
            }

            .color-explanation {
                gap: 10px;
            }

            .color-item {
                padding: 6px 10px;
            }

            .color-circle {
                width: 14px;
                height: 14px;
            }

            .color-label {
                font-size: 0.8rem;
            }

            .history-table th, .history-table td {
                padding: 8px 10px;
                font-size: 0.8rem;
            }

            .history-title {
                font-size: 1.1rem;
            }

            .filter-btn {
                padding: 5px 10px;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 320px) {
            .comparison-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .comparison-title {
                max-width: 100%;
            }

            .color-explanation {
                gap: 8px;
            }

            .color-item {
                padding: 5px 8px;
            }

            .history-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .history-table {
                font-size: 0.75rem;
            }

            .history-table th, .history-table td {
                padding: 6px 8px;
            }
        }
        .ringkasan-line {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .ringkasan-line img{
            width: 190px;
            max-width: 100%;
            height: auto;
            display: block;
        }
        .divider {
        width: clamp(17rem, 10vw, 5rem);
        height: 0.25rem;
        background: linear-gradient(to right, #3b82f6, #10b981);
        border-radius: 9999px;
        margin: 0.125rem 0 0.25rem;
        margin-left: auto; margin-right: auto;
    }
    .divider-1 {
        width: clamp(17rem, 10vw, 5rem);
        height: 0.25rem;
        background: linear-gradient(to right, #3b82f6, #10b981);
        border-radius: 9999px;
        margin-top: 6px;
        margin-left: auto; margin-right: auto;
    }
    .fade-modal {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.5);
        opacity: 0;
        transition: opacity .2s ease;
    }
    .fade-modal.show { display: flex; opacity: 1; }
    .fade-modal .modal-card { background:#fff; border-radius:12px; padding:16px; width:92%; max-width:420px; box-sizing:border-box; }
    .modal-scroll { max-height: 260px; overflow-y: auto; }
    @media (max-width: 360px) { .fade-modal .modal-card { width:96%; max-width:360px; } }
    .detail-chart-container { will-change: opacity, transform; opacity: 0; transform: translateY(8px); }
    .detail-chart-container.show { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">

    <x-top-nav />
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var navbar = document.getElementById('navbar');
            if (navbar) { navbar.classList.add('no-shadow','rounded-bottom'); }
        });
    </script>

 <div class="" style="display: flex;justify-content: center;padding-bottom: 20px;">
     <div class="divider"></div>
 </div>
    <div class="w-full">
        <!-- Card Ringkasan -->
        <div class="summary-card">
            <div class="summary-header">
                <div class="summary-main">
                    <div class="ringkasan-line">
                        <img src="{{ asset('img/logotext-551.png') }}" alt="">
                    </div>
                    <div class="balance-section">
                        <div class="balance-label">Saldo Saat Ini</div>
                        <div class="balance-amount">{{ 'Rp' . number_format(($balance ?? 0), 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="summary-trend">
                    <div class="summary-trend-inner">
                        <img src="{{ asset('img/tren_naik.png') }}" alt="" style="width: 52px; height: auto;">
                    </div>
                </div>
            </div>


            <div class="section-sep"></div>
            <!-- Pemasukan, Pengeluaran, dan Penghematan dalam 3 kolom grid -->
            <div class="finance-grid full-bleed">
                <!-- Pemasukan -->
                <div class="finance-item" id="income-item">
                    <div class="finance-header">
                        <div class="finance-label income-color">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            Pemasukan
                        </div>
                        <div class="finance-amount income-color">{{ 'Rp' . number_format(($incomeTotal ?? 0), 0, ',', '.') }}</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>

                <!-- Pengeluaran -->
                <div class="finance-item" id="expense-item">
                    <div class="finance-header">
                        <div class="finance-label expense-color">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                            Pengeluaran
                        </div>
                        <div class="finance-amount expense-color">{{ 'Rp' . number_format(($expenseTotal ?? 0), 0, ',', '.') }}</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>

                <!-- Penghematan -->
                <div class="finance-item" id="saving-item">
                    <div class="finance-header">
                        <div class="finance-label saving-color">
                            <i class="fa-solid fa-piggy-bank"></i>
                            Penghematan
                        </div>
                        <div class="finance-amount saving-color">{{ 'Rp' . number_format(($savings ?? 0), 0, ',', '.') }}</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="savingChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Area untuk grafik detail (awalnya tersembunyi) -->
            <div id="detailChartContainer" class="detail-chart-container">
                <div class="detail-header" style="display: flex; justify-content: space-between; align-items: flex-start; padding-top: 12px;">
                    <div style="margin-left: 15px;">
                        <h3 id="detailChartTitle">Detail Grafik</h3>
                        <p id="detailChartSubtitle" style="font-size: 0.85rem; color: #6b7280; margin: 0; font-weight: 500; display: none;"></p>
                    </div>
                    <div class="detail-actions" style="display: flex; gap: 8px; align-items: center; margin-right: 15px;">
                        <button id="detailRefreshBtn" class="refresh-btn"><i class="fa-solid fa-rotate-right"></i> Refresh</button>
                        <button id="detailFilterBtn" class="filter-btn"><i class="fa-solid fa-filter"></i> Filter</button>
                        <button id="closeDetailChart" class="close-btn">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                <div class="detail-chart-wrapper">
                    <canvas id="detailChart"></canvas>
                </div>
            </div>
        </div>


    </div>

            <div class="distribution-card">
                <div class="distribution-header">
                    <div>
                        <h3 class="distribution-title">Distribusi Pengeluaran</h3>
                        <p class="distribution-subtitle">Ringkasan semua kategori pengeluaran</p>
                    </div>
                    <div class="distribution-chip">Bulan ini</div>
                </div>
                <div class="distribution-divider"></div>
                <div id="distributionGrid" class="distribution-grid"></div>
            </div>

            <div class="section-card-full">
                <div class="chart-card-full">
                    <div class="chart-title">
                        <i class="fa-solid fa-chart-pie"></i>
                        Distribusi Pengeluaran
                    </div>
                    <div class="dist-layout">
                        <div class="dist-col-chart">
                            <div class="chart-wrap-3d">
                                <canvas id="homeDistChart"></canvas>
                                <div class="chart-center-text">
                                    <div class="chart-total-value" id="homeTotalExpense">Rp 0</div>
                                </div>
                            </div>
                        </div>
                        <div class="dist-col-legend">
                            <div class="legend-3d legend-vertical" id="homeLegendPercentContainer"></div>
                        </div>
                    </div>
                    <div class="dist-separator"></div>
                    <div class="dist-amount-wrap">
                        <div class="dist-amount-title">
                            <i class="fa-solid fa-list-ul"></i>
                            Rincian Nominal
                        </div>
                        <div class="legend-amount-grid" id="homeLegendAmountContainer"></div>
                    </div>
                </div>
            </div>


    <!-- Card Histori Transaksi -->
    <div class="history-card">
        <div class="history-header">
            <h3 class="history-title">Histori Transaksi</h3>
            <div style="display:flex;align-items:center;gap:8px;">
            <button class="refresh-btn" id="historyRefreshBtn"><i class="fa-solid fa-rotate-right"></i> Refresh</button>
            <button class="filter-btn" id="historyFilterBtn">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            </div>
        </div>

        <!-- MODIFIKASI: Tambahkan container untuk scroll horizontal -->
        <div class="table-container">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Transaksi</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($transactions ?? []) as $t)
                        <tr data-year="{{ \Carbon\Carbon::parse($t->date)->year }}" data-month="{{ \Carbon\Carbon::parse($t->date)->month }}" data-date="{{ \Carbon\Carbon::parse($t->date)->toDateString() }}">
                            <td>{{ \Carbon\Carbon::parse($t->date)->translatedFormat('d M Y') }}</td>
                            <td>{{ $t->category }}</td>
                            <td>
                                @php $isIncome = $t->type === 'income'; @endphp
                                <span class="category-icon {{ $isIncome ? 'category-income' : 'category-expense' }}">
                                    <i class="fa-solid {{ $isIncome ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                                </span>
                                {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                            </td>
                            <td class="{{ $isIncome ? 'amount-positive' : 'amount-negative' }}">{{ ($isIncome ? '+' : '-') . 'Rp ' . number_format($t->amount, 0, ',', '.') }}</td>
                            <td><span class="status-badge status-success">Berhasil</span></td>
                            <td>{{ $t->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button class="view-all-btn" id="viewAllBtn">Lihat History Lengkap</button>
    </div>

    <x-bottom-nav />

    <script>
        window.onload = function () {
            const fmtIDR = v => 'Rp ' + (Math.round(v).toLocaleString('id-ID'));
            // Fungsi untuk menghasilkan label bulan
            const generateLabels = (count = 8) => {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                return months.slice(0, count);
            };

            // Fungsi untuk menghasilkan data acak
            const generateData = (count = 8, min = 500000, max = 2000000) => {
                const data = [];
                for (let i = 0; i < count; i++) {
                    data.push(Math.floor(Math.random() * (max - min + 1)) + min);
                }
                return data;
            };

            // Konfigurasi untuk grafik Pemasukan
            const incomeData = {
                labels: [],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: [],
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        fill: 'start',
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        borderWidth: 2
                    }
                ]
            };

            const incomeConfig = {
                type: 'line',
                data: incomeData,
                options: {
                    events: [],
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    layout: { padding: 0 },
                    scales: {
                        x: { display: false },
                        y: { display: false, beginAtZero: true }
                    },
                    elements: {
                        point: { radius: 0, hoverRadius: 0 },
                        line: { borderWidth: 2, borderJoinStyle: 'round', borderCapStyle: 'round' }
                    },
                    interaction: { intersect: false, mode: 'index' },
                    animation: { duration: 1000, easing: 'easeOutQuart' }
                }
            };

            // Konfigurasi untuk grafik Pengeluaran
            const expenseData = {
                labels: [],
                datasets: [
                    {
                        label: 'Pengeluaran',
                        data: [],
                        borderColor: 'rgba(239, 68, 68, 1)',
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        fill: 'start',
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        borderWidth: 2
                    }
                ]
            };

            const expenseConfig = {
                type: 'line',
                data: expenseData,
                options: {
                    events: [],
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    layout: { padding: 0 },
                    scales: {
                        x: { display: false },
                        y: { display: false, beginAtZero: true }
                    },
                    elements: {
                        point: { radius: 0, hoverRadius: 0 },
                        line: { borderWidth: 2, borderJoinStyle: 'round', borderCapStyle: 'round' }
                    },
                    interaction: { intersect: false, mode: 'index' },
                    animation: { duration: 1000, easing: 'easeOutQuart' }
                }
            };

            // Chart untuk Penghematan
            const savingData = {
                labels: [],
                datasets: [
                    {
                        label: 'Penghematan',
                        data: [],
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: 'start',
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        borderWidth: 2
                    }
                ]
            };

            const savingConfig = {
                type: 'line',
                data: savingData,
                options: {
                    events: [],
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    layout: { padding: 0 },
                    scales: {
                        x: { display: false },
                        y: { display: false, beginAtZero: true }
                    },
                    elements: {
                        point: { radius: 0, hoverRadius: 0 },
                        line: { borderWidth: 2, borderJoinStyle: 'round', borderCapStyle: 'round' }
                    },
                    interaction: { intersect: false, mode: 'index' },
                    animation: { duration: 1000, easing: 'easeOutQuart' }
                }
            };

            // Membuat grafik Pemasukan
            const incomeCtx = document.getElementById('incomeChart').getContext('2d');
            const incomeChart = new Chart(incomeCtx, incomeConfig);

            // Membuat grafik Pengeluaran
            const expenseCtx = document.getElementById('expenseChart').getContext('2d');
            const expenseChart = new Chart(expenseCtx, expenseConfig);

            // Membuat grafik Penghematan
            const savingCtx = document.getElementById('savingChart').getContext('2d');
            const savingChart = new Chart(savingCtx, savingConfig);

            // Ekspos ke global jika ingin dipanggil dari console / tombol
            window.incomeChartInstance = incomeChart;
            window.expenseChartInstance = expenseChart;
            window.savingChartInstance = savingChart;
            window.incomeFullChartActive = false;
            window.expenseFullChartActive = false;
            window.savingFullChartActive = false;

            const statsCache = new Map();
            async function fetchStats(type, granularity, year, month) {
                const key = `${type}|${granularity}|${year ?? ''}|${month ?? ''}`;
                if (statsCache.has(key)) return statsCache.get(key);
                const params = new URLSearchParams();
                params.set('type', type);
                params.set('granularity', granularity);
                if (year) params.set('year', year);
                if (month) params.set('month', month);
                const res = await fetch(`/transactions/stats?${params.toString()}`);
                const json = await res.json();
                statsCache.set(key, json);
                return json;
            }

            function sliceRecentMonths(labels, data) {
                const m = new Date().getMonth();
                const start = Math.max(0, m - 5);
                const end = m + 1;
                return { labels: labels.slice(start, end), data: data.slice(start, end) };
            }

            (async function initMonthlyCharts(){
                const now = new Date();
                const y = now.getFullYear();
                const m = now.getMonth() + 1;
                const todayDay = now.getDate();
                const days = Array.from({length: todayDay}, (_, i) => i + 1);

                const incDaily = await fetchStats('income', 'daily', y, m);
                const mapInc = new Map((incDaily.labels || []).map((d, i) => [d, incDaily.data[i] || 0]));
                const incData = days.map(d => mapInc.get(d) || 0);
                incomeChart.data.labels = days.map(String);
                incomeChart.data.datasets[0].data = incData;

                const excDaily = await fetchStats('expense', 'daily', y, m);
                const mapExc = new Map((excDaily.labels || []).map((d, i) => [d, excDaily.data[i] || 0]));
                const excData = days.map(d => mapExc.get(d) || 0);
                expenseChart.data.labels = days.map(String);
                expenseChart.data.datasets[0].data = excData;

                const savDaily = await fetchStats('saving', 'daily', y, m);
                const mapSav = new Map((savDaily.labels || []).map((d, i) => [d, savDaily.data[i] || 0]));
                const savData = days.map(d => mapSav.get(d) || 0);
                savingChart.data.labels = days.map(String);
                savingChart.data.datasets[0].data = savData;

                incomeChart.update();
                expenseChart.update();
                savingChart.update();
            })();

            async function makeIncomeFullChart(){
                const ctx = document.getElementById('incomeChart').getContext('2d');
                if (window.incomeChartInstance) { window.incomeChartInstance.destroy(); }
                const now = new Date();
                const y = now.getFullYear();
                const m = now.getMonth() + 1;
                const prevM = (m === 1) ? 12 : (m - 1);
                const prevY = (m === 1) ? (y - 1) : y;
                const curr = await fetchStats('income', 'daily', y, m);
                const prev = await fetchStats('income', 'daily', prevY, prevM);
                const todayDay = now.getDate();
                const days = Array.from({length: todayDay}, (_, i) => i + 1);
                const labels = days.map(d => new Date(y, m - 1, d));
                const mapCurr = new Map((curr.labels || []).map((d, i) => [d, curr.data[i] || 0]));
                const mapPrev = new Map((prev.labels || []).map((d, i) => [d, prev.data[i] || 0]));
                const dataCurr = days.map(d => mapCurr.get(d) || 0);
                const dataPrev = days.map(d => mapPrev.get(d) || 0);
                function movingAverage(arr, windowSize = 7) {
                    const res = [];
                    for (let i = 0; i < arr.length; i++) {
                        const start = Math.max(0, i - windowSize + 1);
                        const slice = arr.slice(start, i + 1);
                        const avg = slice.reduce((a, b) => a + b, 0) / slice.length;
                        res.push(Math.round(avg));
                    }
                    return res;
                }
                const cfg = {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Bulan ini',
                                backgroundColor: 'rgba(16, 185, 129, 0.55)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                data: dataCurr,
                                borderRadius: 8,
                                borderWidth: 1,
                                borderSkipped: false,
                                barPercentage: 0.9,
                                categoryPercentage: 0.9
                            },
                            {
                                type: 'line',
                                label: 'Tren',
                                backgroundColor: 'rgba(16, 185, 129, 0.12)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                fill: true,
                                tension: 0.35,
                                pointRadius: 0,
                                data: movingAverage(dataCurr)
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false, external: makeExternalTooltip('plain'), mode: 'index', intersect: false } },
                        scales: {
                            x: { type: 'time', display: true, offset: true, time: { unit: 'day' }, ticks: { source: 'data', maxRotation: 0, minRotation: 0, callback: (val) => { try { return new Date(val).getDate(); } catch(e){ return ''; } } }, grid: { display: false } },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                ticks: { display: false }
                            }
                        },
                        animation: { duration: 600, easing: 'easeOutQuart' }
                    }
                };
                window.incomeChartInstance = new Chart(ctx, cfg);
                window.incomeFullChartActive = true;
                // Tambah badge bulan-tahun di pojok kanan atas
                (function(){
                    const incomeItem = document.getElementById('income-item');
                    if (!incomeItem) return;
                    const header = incomeItem.querySelector('.finance-header');
                    let period = header ? header.querySelector('.income-mode-period') : null;
                    if (!period && header) {
                        period = document.createElement('div');
                        period.className = 'income-mode-period';
                        period.style.color = '#374151';
                        period.style.fontSize = '0.75rem';
                        period.style.fontWeight = '700';
                        period.style.marginTop = '8px';
                        header.appendChild(period);
                    }
                    let badge = incomeItem.querySelector('.income-mode-badge');
                    if (!badge) {
                        badge = document.createElement('div');
                        badge.className = 'income-mode-badge';
                        badge.style.position = 'absolute';
                        badge.style.top = '8px';
                        badge.style.right = '8px';
                        badge.style.background = '#10b981';
                        badge.style.color = '#ffffff';
                        badge.style.padding = '4px 8px';
                        badge.style.borderRadius = '6px';
                        badge.style.fontSize = '0.75rem';
                        badge.style.fontWeight = '700';
                        incomeItem.appendChild(badge);
                    }
                    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    if (period) period.textContent = 'Bulan: ' + months[m-1] + ' • Tahun: ' + y;
                    badge.textContent = months[m-1] + ' ' + y;
                })();
            }

            async function restoreIncomeCompactChart(){
                if (!window.incomeFullChartActive) return;
                const ctx = document.getElementById('incomeChart').getContext('2d');
                if (window.incomeChartInstance) { window.incomeChartInstance.destroy(); }

                const now = new Date();
                const y = now.getFullYear();
                const m = now.getMonth() + 1;

                const chart = new Chart(ctx, incomeConfig);

                const incDaily = await fetchStats('income', 'daily', y, m);
                const todayDay = now.getDate();
                const days = Array.from({length: todayDay}, (_, i) => i + 1);
                const mapInc = new Map((incDaily.labels || []).map((d, i) => [d, incDaily.data[i] || 0]));
                const incData = days.map(d => mapInc.get(d) || 0);

                chart.data.labels = days.map(String);
                chart.data.datasets[0].data = incData;

                chart.update();
                window.incomeChartInstance = chart;
                window.incomeFullChartActive = false;
                const incomeItem = document.getElementById('income-item');
                if (incomeItem) {
                    const left = incomeItem.querySelector('.income-mode-period');
                    const badge = incomeItem.querySelector('.income-mode-badge');
                    if (left) left.remove();
                    if (badge) badge.remove();
                }
            }

            async function makeExpenseFullChart(){
                const ctx = document.getElementById('expenseChart').getContext('2d');
                if (window.expenseChartInstance) { window.expenseChartInstance.destroy(); }
                const now = new Date();
                const y = now.getFullYear();
                const m = now.getMonth() + 1;
                const prevM = (m === 1) ? 12 : (m - 1);
                const prevY = (m === 1) ? (y - 1) : y;
                const curr = await fetchStats('expense', 'daily', y, m);
                const prev = await fetchStats('expense', 'daily', prevY, prevM);
                const todayDay = now.getDate();
                const days = Array.from({length: todayDay}, (_, i) => i + 1);
                const labels = days.map(d => new Date(y, m - 1, d));
                const mapCurr = new Map((curr.labels || []).map((d, i) => [d, curr.data[i] || 0]));
                const mapPrev = new Map((prev.labels || []).map((d, i) => [d, prev.data[i] || 0]));
                const dataCurr = days.map(d => mapCurr.get(d) || 0);
                const dataPrev = days.map(d => mapPrev.get(d) || 0);
                function movingAverage(arr, windowSize = 7) {
                    const res = [];
                    for (let i = 0; i < arr.length; i++) {
                        const start = Math.max(0, i - windowSize + 1);
                        const slice = arr.slice(start, i + 1);
                        const avg = slice.reduce((a, b) => a + b, 0) / slice.length;
                        res.push(Math.round(avg));
                    }
                    return res;
                }
                const cfg = {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Bulan ini',
                                backgroundColor: 'rgba(239, 68, 68, 0.55)',
                                borderColor: 'rgba(239, 68, 68, 1)',
                                data: dataCurr,
                                borderRadius: 8,
                                borderWidth: 1,
                                borderSkipped: false,
                                barPercentage: 0.9,
                                categoryPercentage: 0.9
                            },
                            {
                                type: 'line',
                                label: 'Tren',
                                backgroundColor: 'rgba(239, 68, 68, 0.12)',
                                borderColor: 'rgba(239, 68, 68, 1)',
                                fill: true,
                                tension: 0.35,
                                pointRadius: 0,
                                data: movingAverage(dataCurr)
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false, external: makeExternalTooltip('plain'), mode: 'index', intersect: false } },
                        scales: {
                            x: { type: 'time', display: true, offset: true, time: { unit: 'day' }, ticks: { source: 'data', maxRotation: 0, minRotation: 0, callback: (val) => { try { return new Date(val).getDate(); } catch(e){ return ''; } } }, grid: { display: false } },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                ticks: { display: false }
                            }
                        },
                        animation: { duration: 600, easing: 'easeOutQuart' }
                    }
                };
                window.expenseChartInstance = new Chart(ctx, cfg);
                window.expenseFullChartActive = true;
                const expenseItem = document.getElementById('expense-item');
                if (expenseItem) {
                    const header = expenseItem.querySelector('.finance-header');
                    let period = header ? header.querySelector('.expense-mode-period') : null;
                    if (!period && header) {
                        period = document.createElement('div');
                        period.className = 'expense-mode-period';
                        period.style.color = '#374151';
                        period.style.fontSize = '0.75rem';
                        period.style.fontWeight = '700';
                        period.style.marginTop = '8px';
                        header.appendChild(period);
                    }
                    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    if (period) period.textContent = 'Bulan: ' + months[m-1] + ' • Tahun: ' + y;
                }
            }

            async function restoreExpenseCompactChart(){
                if (!window.expenseFullChartActive) return;
                const ctx = document.getElementById('expenseChart').getContext('2d');
                if (window.expenseChartInstance) { window.expenseChartInstance.destroy(); }
                const now = new Date();
                const y = now.getFullYear();
                const m = now.getMonth() + 1;
                const excDaily = await fetchStats('expense', 'daily', y, m);
                const todayDay = now.getDate();
                const days = Array.from({length: todayDay}, (_, i) => i + 1);
                const mapExc = new Map((excDaily.labels || []).map((d, i) => [d, excDaily.data[i] || 0]));
                const excData = days.map(d => mapExc.get(d) || 0);
                const chart = new Chart(ctx, expenseConfig);
                chart.data.labels = days.map(String);
                chart.data.datasets[0].data = excData;
                chart.update();
                window.expenseChartInstance = chart;
                window.expenseFullChartActive = false;
                const expenseItem = document.getElementById('expense-item');
                if (expenseItem) {
                    const period = expenseItem.querySelector('.expense-mode-period');
                    if (period) period.remove();
                    const center = expenseItem.querySelector('.expense-center-text');
                    if (center) center.remove();
                    const legend = expenseItem.querySelector('.expense-legend-right');
                    if (legend) legend.remove();
                    const container = expenseItem.querySelector('.chart-container');
                    if (container) {
                        container.style.display = '';
                        container.style.alignItems = '';
                        container.style.gap = '';
                        const canvasEl = container.querySelector('canvas#expenseChart');
                        if (canvasEl) {
                            canvasEl.style.flex = '';
                            canvasEl.style.maxWidth = '';
                        }
                    }
                }
            }

            async function makeSavingFullChart(){
                const ctx = document.getElementById('savingChart').getContext('2d');
                if (window.savingChartInstance) { window.savingChartInstance.destroy(); }
                const now = new Date();
                const y = now.getFullYear();
                const m = now.getMonth() + 1;

                // Fetch stats for Saving (Income - Expense)
                const curr = await fetchStats('saving', 'daily', y, m);

                const todayDay = now.getDate();
                const days = Array.from({length: todayDay}, (_, i) => i + 1);
                const labels = days.map(d => new Date(y, m - 1, d));
                const mapCurr = new Map((curr.labels || []).map((d, i) => [d, curr.data[i] || 0]));
                const dataCurr = days.map(d => mapCurr.get(d) || 0);

                function movingAverage(arr, windowSize = 7) {
                    const res = [];
                    for (let i = 0; i < arr.length; i++) {
                        const start = Math.max(0, i - windowSize + 1);
                        const slice = arr.slice(start, i + 1);
                        const avg = slice.reduce((a, b) => a + b, 0) / slice.length;
                        res.push(Math.round(avg));
                    }
                    return res;
                }

                const cfg = {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Bulan ini',
                                backgroundColor: 'rgba(59, 130, 246, 0.55)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                data: dataCurr,
                                borderRadius: 8,
                                borderWidth: 1,
                                borderSkipped: false,
                                barPercentage: 0.9,
                                categoryPercentage: 0.9
                            },
                            {
                                type: 'line',
                                label: 'Tren',
                                backgroundColor: 'rgba(59, 130, 246, 0.12)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                fill: true,
                                tension: 0.35,
                                pointRadius: 0,
                                data: movingAverage(dataCurr)
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                enabled: false,
                                external: makeExternalTooltip('plain'),
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            x: {
                                type: 'time',
                                display: true,
                                offset: true,
                                time: { unit: 'day' },
                                ticks: {
                                    source: 'data',
                                    maxRotation: 0,
                                    minRotation: 0,
                                    callback: (val) => {
                                        try { return new Date(val).getDate(); } catch(e){ return ''; }
                                    }
                                },
                                grid: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                ticks: { display: false }
                            }
                        },
                        animation: { duration: 600, easing: 'easeOutQuart' }
                    }
                };
                window.savingChartInstance = new Chart(ctx, cfg);
                window.savingFullChartActive = true;

                const savingItem = document.getElementById('saving-item');
                if (savingItem) {
                    const header = savingItem.querySelector('.finance-header');
                    let period = header ? header.querySelector('.saving-mode-period') : null;
                    if (!period && header) {
                        period = document.createElement('div');
                        period.className = 'saving-mode-period';
                        period.style.color = '#374151';
                        period.style.fontSize = '0.75rem';
                        period.style.fontWeight = '700';
                        period.style.marginTop = '8px';
                        header.appendChild(period);
                    }

                    let badge = savingItem.querySelector('.saving-mode-badge');
                    if (!badge) {
                        badge = document.createElement('div');
                        badge.className = 'saving-mode-badge';
                        badge.style.position = 'absolute';
                        badge.style.top = '8px';
                        badge.style.right = '8px';
                        badge.style.background = '#3b82f6';
                        badge.style.color = '#ffffff';
                        badge.style.padding = '4px 8px';
                        badge.style.borderRadius = '6px';
                        badge.style.fontSize = '0.75rem';
                        badge.style.fontWeight = '700';
                        savingItem.appendChild(badge);
                    }

                    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    if (period) period.textContent = 'Bulan: ' + months[m-1] + ' • Tahun: ' + y;
                    badge.textContent = months[m-1] + ' ' + y;

                    // Cleanup previous specific elements if any (from Doughnut version)
                    const legend = savingItem.querySelector('.saving-legend-right');
                    if (legend) legend.remove();

                    const container = savingItem.querySelector('.chart-container');
                    if (container) {
                        container.style.display = '';
                        container.style.alignItems = '';
                        container.style.gap = '';
                        const canvasEl = container.querySelector('canvas#savingChart');
                        if (canvasEl) {
                            canvasEl.style.flex = '';
                            canvasEl.style.maxWidth = '';
                        }
                    }
                }
            }
            async function restoreSavingCompactChart(){
                if (!window.savingFullChartActive) return;
                const ctx = document.getElementById('savingChart').getContext('2d');
                if (window.savingChartInstance) { window.savingChartInstance.destroy(); }
                const chart = new Chart(ctx, savingConfig);

                const now = new Date();
                const y = now.getFullYear();
                const m = now.getMonth() + 1;
                const savDaily = await fetchStats('saving', 'daily', y, m);
                const todayDay = now.getDate();
                const days = Array.from({length: todayDay}, (_, i) => i + 1);
                const mapSav = new Map((savDaily.labels || []).map((d, i) => [d, savDaily.data[i] || 0]));
                const savData = days.map(d => mapSav.get(d) || 0);

                chart.data.labels = days.map(String);
                chart.data.datasets[0].data = savData;
                chart.update();

                window.savingChartInstance = chart;
                window.savingFullChartActive = false;
                const savingItem = document.getElementById('saving-item');
                if (savingItem) {
                    const period = savingItem.querySelector('.saving-mode-period');
                    if (period) period.remove();
                    const badge = savingItem.querySelector('.saving-mode-badge');
                    if (badge) badge.remove();
                    const legend = savingItem.querySelector('.saving-legend-right');
                    if (legend) legend.remove();
                    const container = savingItem.querySelector('.chart-container');
                    if (container) {
                        container.style.display = '';
                        container.style.alignItems = '';
                        container.style.gap = '';
                        const canvasEl = container.querySelector('canvas#savingChart');
                        if (canvasEl) {
                            canvasEl.style.flex = '';
                            canvasEl.style.maxWidth = '';
                        }
                    }
                }
            }

            // Variabel untuk menyimpan instance grafik detail
            let detailChartInstance = null;

            // Fungsi legacy (tidak digunakan) untuk menampilkan grafik detail
            function showDetailChartLegacy(type) {
                const container = document.getElementById('detailChartContainer');
                const titleElement = document.getElementById('detailChartTitle');
                const canvas = document.getElementById('detailChart');
                const ctx = canvas.getContext('2d');
                window.currentDetailType = type;

                // Hancurkan grafik detail sebelumnya jika ada
                if (detailChartInstance) {
                    detailChartInstance.destroy();
                }

                // Set judul berdasarkan jenis
                let title, data, config;
                switch(type) {
                    case 'income':
                        title = 'Detail Pemasukan';
                        // Data untuk grafik detail pemasukan (lebih lengkap)
                        data = {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu'],
                            datasets: [{
                                label: 'Pemasukan',
                                data: [800000, 900000, 700000, 900000, 1000000, 850000, 950000, 1100000],
                                borderColor: 'rgba(16, 185, 129, 1)',
                                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                                fill: true,
                                tension: 0.4
                            }]
                        };
                        config = {
                            type: 'line',
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false
                                    }
                                },
                                scales: {
                                    x: {
                                        display: true,
                                        grid: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        display: true,
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                }
                            }
                        };
                        break;
                    case 'expense':
                        title = 'Detail Pengeluaran';
                        data = {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu'],
                            datasets: [{
                                label: 'Pengeluaran',
                                data: [1200000, 1400000, 1100000, 1800000, 1500000, 1300000, 1600000, 1700000],
                                borderColor: 'rgba(239, 68, 68, 1)',
                                backgroundColor: 'rgba(239, 68, 68, 0.2)',
                                fill: true,
                                tension: 0.4
                            }]
                        };
                        config = {
                            type: 'line',
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false
                                    }
                                },
                                scales: {
                                    x: {
                                        display: true,
                                        grid: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        display: true,
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                }
                            }
                        };
                        break;
                    case 'saving':
                        title = 'Detail Penghematan';
                        data = {
                            labels: ['Q1', 'Q2', 'Q3'],
                            datasets: [{
                                label: 'Penghematan',
                                data: [65, 80, 75],
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.7)',
                                    'rgba(37, 99, 235, 0.7)',
                                    'rgba(29, 78, 216, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(59, 130, 246, 1)',
                                    'rgba(37, 99, 235, 1)',
                                    'rgba(29, 78, 216, 1)'
                                ],
                                borderWidth: 1
                            }]
                        };
                        config = {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return 'Penghematan: ' + context.parsed.y + '%';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        display: true,
                                        grid: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        display: true,
                                        beginAtZero: true,
                                        max: 100,
                                        ticks: {
                                            callback: function(value) {
                                                return value + '%';
                                            }
                                        }
                                    }
                                }
                            }
                        };
                        break;
                }

                titleElement.textContent = title;
                container.classList.remove('hidden');
                container.classList.add('show');

                // Buat grafik detail
                detailChartInstance = new Chart(ctx, config);
            }

            function showDetailChart(type) {
                const container = document.getElementById('detailChartContainer');
                const titleElement = document.getElementById('detailChartTitle');
                const subtitleElement = document.getElementById('detailChartSubtitle');
                const canvas = document.getElementById('detailChart');
                const ctx = canvas.getContext('2d');
                window.currentDetailType = type;
                const y = new Date().getFullYear();
                const m = new Date().getMonth() + 1;
                const defaultGranularity = 'daily';
                titleElement.textContent = type === 'income' ? 'Detail Pemasukan' : (type === 'expense' ? 'Detail Pengeluaran' : 'Detail Penghematan');
                if (subtitleElement) {
                    subtitleElement.textContent = '';
                    subtitleElement.style.display = 'none';
                }
                container.classList.remove('hidden');
                container.classList.add('show');

                // Hancurkan grafik lama segera jika tipe berubah untuk menghindari kebingungan visual
                if (detailChartInstance) {
                    const isSaving = (type === 'saving');
                    const oldIsSaving = (detailChartInstance.config.type === 'bar'); // Saving uses bar, others use line
                    if (isSaving !== oldIsSaving) {
                        detailChartInstance.destroy();
                        detailChartInstance = null;
                    }
                }

                requestAnimationFrame(async () => {
                    if (type === 'saving') {
                        async function updateSavingDetailByRange(start, end){
                            const dist = await fetch(`/transactions/distribution?${new URLSearchParams({ start, end }).toString()}`).then(r=>r.json());
                            const labels = dist.labels || [];
                            const totals = dist.totals || [];
                            const bg = labels.map(lbl => {
                                const base = baseColorFor(lbl);
                                return `hsla(${base.h}, ${base.s}%, ${Math.max(0, Math.min(100, base.l + 10))}%, 0.65)`;
                            });
                            const borders = labels.map(lbl => {
                                const base = baseColorFor(lbl);
                                return `hsl(${base.h}, ${base.s}%, ${Math.max(0, Math.min(100, base.l - 5))}%)`;
                            });
                            if (!detailChartInstance || detailChartInstance.config.type !== 'bar') {
                                if (detailChartInstance) detailChartInstance.destroy();
                                // Re-create in the next block
                            } else {
                                detailChartInstance.data.labels = labels;
                                detailChartInstance.data.datasets[0].data = totals;
                                detailChartInstance.data.datasets[0].backgroundColor = bg;
                                detailChartInstance.data.datasets[0].borderColor = borders;
                                if (detailChartInstance.data.datasets[1]) {
                                    detailChartInstance.data.datasets[1].data = totals;
                                }
                                detailChartInstance.update();
                                return;
                            }

                            // Create new saving chart if needed (logic moved here to ensure data availability)
                            const savingTicksPlugin = {
                                id: 'savingTicksPlugin',
                                afterBuildTicks(scale) {
                                    if (!scale || scale.id !== 'y') return;
                                    const chart = scale.chart;
                                    const ds = chart && chart.data && chart.data.datasets && chart.data.datasets[0];
                                    if (!ds || !Array.isArray(ds.data)) return;
                                    const vals = ds.data.map(v => +v || 0);
                                    const ordered = vals.slice().sort((a,b)=>a-b);
                                    scale.ticks = ordered.map(v => ({ value: v }));
                                    scale.min = 0;
                                    scale.max = Math.max(...ordered);
                                }
                            };
                            const config = {
                                type: 'bar',
                                data: {
                                    labels,
                                    datasets: [
                                        { type: 'bar', label: 'Penghematan', data: totals, backgroundColor: bg, borderColor: borders, borderWidth: 1.5, borderRadius: 6, borderSkipped: false },
                                        { type: 'line', label: 'Garis Data', data: totals, borderColor: 'rgba(59, 130, 246, 1)', backgroundColor: 'rgba(59, 130, 246, 0.15)', tension: 0.35, fill: false, pointRadius: 2, pointHoverRadius: 3 }
                                    ]
                                },
                                plugins: [savingTicksPlugin],
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { display: false }, tooltip: { enabled: false, external: makeExternalTooltip('currency:noline'), mode: 'index', intersect: false } },
                                    scales: {
                                        x: { display: true, grid: { display: false }, ticks: { display: false } },
                                        y: {
                                            display: true,
                                            beginAtZero: true,
                                            grid: { color: 'rgba(0,0,0,0.08)' },
                                            ticks: { display: false }
                                        }
                                    },
                                    animation: { duration: 800, easing: 'easeOutQuart' }
                                }
                            };
                            detailChartInstance = new Chart(ctx, config);
                        }
                        window.updateSavingDetailByRange = updateSavingDetailByRange;

                        // Initial load for saving
                        const now = new Date();
                        const y = now.getFullYear();
                        const m = now.getMonth() + 1;
                        const start = new Date(y, m - 1, 1).toISOString().slice(0,10);
                        const end = new Date().toISOString().slice(0,10);
                        await updateSavingDetailByRange(start, end);
                        return;
                    }

                    // Income / Expense
                    if (type === 'expense') {
                        const dist = await fetchDistribution(y, m);
                        const labels = dist.labels || [];
                        const dataVals = dist.totals || [];
                        const colors = dist.colors || [];
                        const config = {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: dataVals,
                                    backgroundColor: colors,
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: { display: true, position: 'right', labels: { boxWidth: 12, padding: 10, font: { size: 11 } } },
                                    tooltip: {
                                        enabled: true,
                                        callbacks: {
                                            label: function(context) {
                                                const val = context.parsed;
                                                const total = context.chart._metasets[context.datasetIndex].total;
                                                const pct = total > 0 ? Math.round((val / total) * 100) : 0;
                                                return context.label + ': Rp ' + val.toLocaleString('id-ID') + ' (' + pct + '%)';
                                            }
                                        }
                                    }
                                },
                                animation: { duration: 800, easing: 'easeOutQuart' }
                            }
                        };
                        if (!detailChartInstance || detailChartInstance.config.type !== 'doughnut') {
                            if (detailChartInstance) detailChartInstance.destroy();
                            detailChartInstance = new Chart(ctx, config);
                        } else {
                            detailChartInstance.data.labels = labels;
                            detailChartInstance.data.datasets[0].data = dataVals;
                            detailChartInstance.data.datasets[0].backgroundColor = colors;
                            detailChartInstance.update();
                        }
                    } else {
                        // Income
                        const stat = await fetchStats(type, defaultGranularity, y, m);
                        let bgColor = 'rgba(16, 185, 129, 0.2)';
                        try {
                            const h = canvas.height || 300;
                            const grad = ctx.createLinearGradient(0, 0, 0, h);
                            grad.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
                            grad.addColorStop(1, 'rgba(255, 255, 255, 0)');
                            bgColor = grad;
                        } catch(e) {}
                        const config = {
                            type: 'line',
                            data: {
                                labels: stat.labels,
                                datasets: [{
                                    label: 'Pemasukan',
                                    data: stat.data,
                                    borderColor: 'rgba(16, 185, 129, 1)',
                                    backgroundColor: bgColor,
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    pointBackgroundColor: '#fff',
                                    pointBorderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: { enabled: false, external: makeExternalTooltip('currency'), mode: 'index', intersect: false }
                                },
                                scales: {
                                    x: { display: true, grid: { display: false } },
                                    y: {
                                        display: true,
                                        beginAtZero: true,
                                        grid: { color: 'rgba(0,0,0,0.06)' },
                                        ticks: { display: false }
                                    }
                                },
                                animation: { duration: 800, easing: 'easeOutQuart' }
                            }
                        };
                        if (!detailChartInstance || detailChartInstance.config.type !== 'line') {
                            if (detailChartInstance) detailChartInstance.destroy();
                            detailChartInstance = new Chart(ctx, config);
                        } else {
                            detailChartInstance.data.labels = config.data.labels;
                            detailChartInstance.data.datasets[0].data = config.data.datasets[0].data;
                            detailChartInstance.data.datasets[0].label = config.data.datasets[0].label;
                            detailChartInstance.data.datasets[0].borderColor = config.data.datasets[0].borderColor;
                            detailChartInstance.data.datasets[0].backgroundColor = config.data.datasets[0].backgroundColor;
                            detailChartInstance.update();
                        }
                    }
                });
            }

            function hideDetailChart() {
                const container = document.getElementById('detailChartContainer');
                container.classList.remove('show');
                container.classList.add('hidden');
                if (detailChartInstance) {
                    detailChartInstance.destroy();
                    detailChartInstance = null;
                }
                if (window.financeGridUnlock) window.financeGridUnlock();
            }

            // Interaksi hover/gestur untuk memperluas 1 kolom menjadi full-lebar
            (function(){
                const grid = document.querySelector('.finance-grid');
                const income = document.getElementById('income-item');
                const expense = document.getElementById('expense-item');
                const saving = document.getElementById('saving-item');
                const items = [income, expense, saving];
                let gridExpandLocked = false;

                function applyExpand(idx){
                    if (!grid) return;
                    grid.classList.remove('expand-left','expand-mid','expand-right');
                    if (idx === 0) grid.classList.add('expand-left');
                    else if (idx === 1) grid.classList.add('expand-mid');
                    else grid.classList.add('expand-right');
                    items.forEach((el, i) => {
                        el.classList.remove('expanded-item','collapsed-left','collapsed-right');
                        if (i === idx) {
                            el.classList.add('expanded-item');
                        } else if (i < idx) {
                            el.classList.add('collapsed-left');
                        } else {
                            el.classList.add('collapsed-right');
                        }
                    });
                    renderExpandSwitch(idx);
                }

                function resetExpand(){
                    if (!grid) return;
                    if (window.incomeFullChartActive) { restoreIncomeCompactChart(); }
                    if (window.expenseFullChartActive) { restoreExpenseCompactChart(); }
                    if (window.savingFullChartActive) { restoreSavingCompactChart(); }
                    grid.classList.remove('expand-left','expand-mid','expand-right');
                    items.forEach(el => el.classList.remove('expanded-item','collapsed-left','collapsed-right'));
                }

                function idxToType(idx){ return idx===0?'income':(idx===1?'expense':'saving'); }

                function expandWithLock(idx){
                    gridExpandLocked = true;
                    const prevIdx = window.currentExpandedIdx;
                    window.currentExpandedIdx = idx;
                    if (prevIdx === 0 && idx !== 0) { restoreIncomeCompactChart(); }
                    if (prevIdx === 1 && idx !== 1) { restoreExpenseCompactChart(); }
                    if (prevIdx === 2 && idx !== 2) { restoreSavingCompactChart(); }
                    applyExpand(idx);
                    if (idx === 0) { makeIncomeFullChart(); }
                    else if (idx === 1) { makeExpenseFullChart(); }
                    else if (idx === 2) { makeSavingFullChart(); }
                }

                function renderExpandSwitch(idx){
                    const expanded = items[idx];
                    let box = expanded.querySelector('.expand-switch');
                    if (!box) {
                        box = document.createElement('div');
                        box.className = 'expand-switch';
                        expanded.appendChild(box);
                    }
                    box.innerHTML = '';
                    const others = [0,1,2].filter(i => i !== idx);
                    function makeItem(i){
                        const nameEl = items[i].querySelector('.finance-label');
                        const amtEl = items[i].querySelector('.finance-amount');
                        const div = document.createElement('div');
                        div.className = 'switch-block';
                        const colorClass = (i===0?'income-color': i===1?'expense-color':'saving-color');
                        div.innerHTML = `
                            <div class="finance-label ${colorClass}">${nameEl ? nameEl.innerHTML : ''}</div>
                            <div class="finance-amount ${colorClass}">${amtEl ? amtEl.textContent.trim() : ''}</div>
                        `;
                        div.addEventListener('click', () => { expandWithLock(i); });
                        return div;
                    }
                    const a = makeItem(others[0]);
                    const sep = document.createElement('div');
                    sep.className = 'switch-sep';
                    const b = makeItem(others[1]);
                    box.appendChild(a);
                    box.appendChild(sep);
                    box.appendChild(b);
                }

                function setupCardClick(itemEl, idx){
                    itemEl.addEventListener('click', () => {
                        if (gridExpandLocked) return;
                        expandWithLock(idx);
                    });
                }

                setupCardClick(income, 0);
                setupCardClick(expense, 1);
                setupCardClick(saving, 2);

                grid.addEventListener('pointerleave', () => {});

                window.financeGridUnlock = function(){ gridExpandLocked = false; resetExpand(); };

                document.addEventListener('click', function(event) {
                    const isClickInside = grid.contains(event.target);
                    if (!isClickInside) {
                        resetExpand();
                        gridExpandLocked = false;
                    }
                });
            })();

            // Event listener untuk tombol tutup
            document.getElementById('closeDetailChart').addEventListener('click', hideDetailChart);

            const filterBtn = document.getElementById('detailFilterBtn');
            const detailRefreshBtn = document.getElementById('detailRefreshBtn');

            const modal = document.createElement('div');
            modal.id = 'detailFilterModal';
            modal.style.position = 'fixed';
            modal.style.left = '0';
            modal.style.right = '0';
            modal.style.top = '0';
            modal.style.bottom = '0';
            modal.style.display = 'none';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.background = 'rgba(0,0,0,0.5)';
            modal.innerHTML = '<div style="background:#fff;border-radius:12px;padding:16px;width:90%;max-width:360px;">'+
                '<div style="font-weight:700;margin-bottom:12px;">Pilih Filter</div>'+
                '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:10px;">'+
                '<button data-g="daily" class="action-btn">Harian</button>'+
                '<button data-g="monthly" class="action-btn">Bulanan</button>'+
                '<button data-g="yearly" class="action-btn">Tahunan</button>'+
                '</div>'+
                '<div id="detailPanel"></div>'+
            '</div>';
            document.body.appendChild(modal);

            const detailPanel = () => modal.querySelector('#detailPanel');

            filterBtn.addEventListener('click', () => { modal.style.display = 'flex'; });
            modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });
            modal.querySelectorAll('button[data-g]').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const g = btn.getAttribute('data-g');
                    const now = new Date();
                    const currentYear = now.getFullYear();
                    const currentMonth = now.getMonth() + 1;
                    const tileBase = 'background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;border-radius:8px;padding:8px;';
                    const tileHighlight = 'background:#ffffff;color:#1a9cb0;border:1px solid #1a9cb0;';
                    const panel = detailPanel();
                    if (g === 'daily') {
                        let curY = currentYear;
                        let curM = currentMonth;
                        const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                        function renderCalendar(year, month) {
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
                            const headerWeek = '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px;margin-bottom:6px">'+
                                weekdays.map(w => `<div style=\"text-align:center;color:#6b7280;font-weight:600\">${w}</div>`).join('')+
                                '</div>';
                            const navBar = `<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">`+
                                `<button id="calPrev" class="action-btn" style="background:#e5e7eb;color:#374151;padding:6px 10px;border-radius:8px">‹</button>`+
                                `<div style="font-weight:700;color:#374151">${monthNames[month-1]} ${year}</div>`+
                                `<button id="calNext" class="action-btn" style="background:#e5e7eb;color:#374151;padding:6px 10px;border-radius:8px">›</button>`+
                            `</div>`;
                            const grid = cells.map(val => {
                                if (!val) return '<div></div>';
                                const isToday = (isCurrentMonth && parseInt(val,10)===today.getDate());
                                const baseStyle = tileBase;
                                const highlight = isToday ? tileHighlight : '';
                                return `<button data-day="${val}" class="calendar-day" style="${baseStyle}${highlight}">${val}</button>`;
                            }).join('');
                            panel.innerHTML = navBar + headerWeek + '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px">'+ grid +'</div>';
                            panel.querySelectorAll('button[data-day]').forEach(b => {
                                b.addEventListener('click', async () => {
                                    const dd = String(parseInt(b.getAttribute('data-day'),10)).padStart(2,'0');
                                    const mm = String(month).padStart(2,'0');
                                    const day = `${year}-${mm}-${dd}`;
                                    if ((window.currentDetailType || 'income') === 'saving') {
                                        if (typeof window.updateSavingDetailByRange === 'function') { await window.updateSavingDetailByRange(day, day); }
                                    } else {
                                        const stat = await fetchStats(window.currentDetailType || 'income', 'daily', year, month);
                                        if (detailChartInstance) {
                                            detailChartInstance.data.labels = stat.labels;
                                            detailChartInstance.data.datasets[0].data = stat.data;
                                            detailChartInstance.update();
                                        }
                                    }
                                    const t = document.getElementById('detailChartTitle');
                                    const st = document.getElementById('detailChartSubtitle');
                                    if (t) t.textContent = (window.currentDetailType === 'income' ? 'Detail Pemasukan' : (window.currentDetailType === 'expense' ? 'Detail Pengeluaran' : 'Detail Penghematan'));
                                    if (st) {
                                        st.textContent = 'Tanggal ' + dd + ' ' + monthNames[month-1] + ' ' + year;
                                        st.style.display = 'block';
                                    }
                                    modal.style.display = 'none';
                                    filterBtn.classList.add('active');
                                });
                            });
                            const prev = panel.querySelector('#calPrev');
                            const next = panel.querySelector('#calNext');
                            if (prev) prev.addEventListener('click', () => { curM -= 1; if (curM < 1) { curM = 12; curY -= 1; } renderCalendar(curY, curM); });
                            if (next) next.addEventListener('click', () => { curM += 1; if (curM > 12) { curM = 1; curY += 1; } renderCalendar(curY, curM); });
                        }
                        renderCalendar(currentYear, currentMonth);
                        return;
                    }
                    if (g === 'monthly') {
                        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                        const monthGrid = months.map((m, idx) => `<button data-month="${idx+1}" class="action-btn" style="${(idx+1)===currentMonth?tileHighlight:tileBase}">${m}</button>`).join('');
                        panel.innerHTML = '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">'+ monthGrid +'</div>';
                        panel.querySelectorAll('button[data-month]').forEach(b => {
                            b.addEventListener('click', async () => {
                                const m = parseInt(b.getAttribute('data-month'),10);
                                if ((window.currentDetailType || 'income') === 'saving') {
                                    const start = new Date(currentYear, m-1, 1).toISOString().slice(0,10);
                                    const end = new Date(currentYear, m, 0).toISOString().slice(0,10);
                                    if (typeof window.updateSavingDetailByRange === 'function') { await window.updateSavingDetailByRange(start, end); }
                                } else {
                                    const stat = await fetchStats(window.currentDetailType || 'income', 'daily', currentYear, m);
                                    if (detailChartInstance) {
                                        detailChartInstance.data.labels = stat.labels;
                                        detailChartInstance.data.datasets[0].data = stat.data;
                                        detailChartInstance.update();
                                    }
                                }
                                const t = document.getElementById('detailChartTitle');
                                const st = document.getElementById('detailChartSubtitle');
                                if (t) t.textContent = (window.currentDetailType === 'income' ? 'Detail Pemasukan' : (window.currentDetailType === 'expense' ? 'Detail Pengeluaran' : 'Detail Penghematan'));
                                if (st) {
                                    st.textContent = 'Bulan ' + months[m-1] + ' ' + currentYear;
                                    st.style.display = 'block';
                                }
                                modal.style.display = 'none';
                                filterBtn.classList.add('active');
                            });
                        });
                        return;
                    }
                    if (g === 'yearly') {
                        const years = []; for (let y=2008; y<=currentYear; y++) years.push(y);
                        const yearGrid = years.map(y => `<button data-year="${y}" class="action-btn" style="${y===currentYear?tileHighlight:tileBase}">${y}</button>`).join('');
                        panel.innerHTML = '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">'+ yearGrid +'</div>';
                        panel.querySelectorAll('button[data-year]').forEach(b => {
                            b.addEventListener('click', async () => {
                                const y = parseInt(b.getAttribute('data-year'),10);
                                if ((window.currentDetailType || 'income') === 'saving') {
                                    const start = `${y}-01-01`;
                                    const end = `${y}-12-31`;
                                    if (typeof window.updateSavingDetailByRange === 'function') { await window.updateSavingDetailByRange(start, end); }
                                } else {
                                    const stat = await fetchStats(window.currentDetailType || 'income', 'monthly', y);
                                    if (detailChartInstance) {
                                        detailChartInstance.data.labels = stat.labels;
                                        detailChartInstance.data.datasets[0].data = stat.data;
                                        detailChartInstance.update();
                                    }
                                }
                                const t = document.getElementById('detailChartTitle');
                                const st = document.getElementById('detailChartSubtitle');
                                if (t) t.textContent = (window.currentDetailType === 'income' ? 'Detail Pemasukan' : (window.currentDetailType === 'expense' ? 'Detail Pengeluaran' : 'Detail Penghematan'));
                                if (st) {
                                    st.textContent = 'Tahun ' + y;
                                    st.style.display = 'block';
                                }
                                modal.style.display = 'none';
                                filterBtn.classList.add('active');
                            });
                        });
                        return;
                    }
                });
            });

            const detailRefreshHandler = async () => {
                const now = new Date();
                const y = now.getFullYear();
                const type = window.currentDetailType || 'income';
                if (type === 'saving') {
                    const m = now.getMonth() + 1;
                    const start = new Date(y, m-1, 1).toISOString().slice(0,10);
                    const end = new Date(y, m, 0).toISOString().slice(0,10);
                    if (typeof window.updateSavingDetailByRange === 'function') { await window.updateSavingDetailByRange(start, end); }
                } else if (type === 'expense') {
                     showDetailChart('expense');
                } else {
                    const m = now.getMonth() + 1;
                    const stat = await fetchStats(type, 'daily', y, m);
                    if (detailChartInstance && detailChartInstance.config.type === 'line') {
                        detailChartInstance.data.labels = stat.labels;
                        detailChartInstance.data.datasets[0].data = stat.data;
                        detailChartInstance.update();
                    } else {
                        showDetailChart(type);
                    }
                }
                const titleElement = document.getElementById('detailChartTitle');
                const subtitleElement = document.getElementById('detailChartSubtitle');
                if (titleElement) titleElement.textContent = window.currentDetailType === 'income' ? 'Detail Pemasukan' : (window.currentDetailType === 'expense' ? 'Detail Pengeluaran' : 'Detail Penghematan');
                if (subtitleElement) {
                    subtitleElement.textContent = '';
                    subtitleElement.style.display = 'none';
                }
                filterBtn.classList.remove('active');
            };
            document.getElementById('detailRefreshBtn').addEventListener('click', detailRefreshHandler);

            const historyFilterBtn = document.getElementById('historyFilterBtn');
            const historyRefreshBtn = document.getElementById('historyRefreshBtn');
            let historyFilterActive = false;

            const historyFilterModal = document.createElement('div');
            historyFilterModal.id = 'historyFilterModal';
            historyFilterModal.className = 'fade-modal';
            document.body.appendChild(historyFilterModal);

            function applyHistoryFilter(mode, payload) {
                const rows = Array.from(document.querySelectorAll('.history-table tbody tr'));
                rows.forEach(r => {
                    let show = true;
                    if (mode === 'daily') {
                        show = r.getAttribute('data-date') === payload.date;
                    } else if (mode === 'monthly') {
                        show = r.getAttribute('data-year') === String(payload.year) && r.getAttribute('data-month') === String(payload.month);
                    } else if (mode === 'yearly') {
                        show = r.getAttribute('data-year') === String(payload.year);
                    }
                    r.style.display = show ? '' : 'none';
                });
                historyFilterActive = !!mode;
                if (historyFilterBtn) historyFilterBtn.classList.toggle('active', historyFilterActive);
            }

            function openHistoryFilterModal() {
                const now = new Date();
                const currentYear = now.getFullYear();
                const currentMonth = now.getMonth() + 1;
                let calYear = currentYear;
                let calMonth = currentMonth;
                const years = [];
                for (let y = 2008; y <= currentYear; y++) years.push(y);
                const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                const monthNames = months;
                const tileBase = 'background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;border-radius:8px;padding:8px;';
                const tileHighlight = 'background:#ffffff;color:#1a9cb0;border:1px solid #1a9cb0;';
                const monthGrid = months.map((m, idx) => `<button data-month="${idx+1}" class="action-btn" style="${(idx+1)===currentMonth?tileHighlight:tileBase}">${m}</button>`).join('');
                const yearGrid = years.map(y => `<button data-year="${y}" class="action-btn" style="${y===currentYear?tileHighlight:tileBase}">${y}</button>`).join('');
                historyFilterModal.innerHTML = '<div class="modal-card">'+
                    '<div style="font-weight:700;margin-bottom:12px;">Filter Histori</div>'+
                    '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:10px;">'+
                        '<button id="hfDaily" class="action-btn">Harian</button>'+
                        '<button id="hfMonthly" class="action-btn">Bulanan</button>'+
                        '<button id="hfYearly" class="action-btn">Tahunan</button>'+
                    '</div>'+
                    '<div id="hfPanel"></div>'+
                '</div>';
                historyFilterModal.classList.add('show');

                const panel = historyFilterModal.querySelector('#hfPanel');
                historyFilterModal.addEventListener('click', (e) => { if (e.target === historyFilterModal) historyFilterModal.classList.remove('show'); });
                function renderCalendar(year, month) {
                    function fmt(y, m, d) {
                        const mm = String(m).padStart(2,'0');
                        const dd = String(d).padStart(2,'0');
                        return `${y}-${mm}-${dd}`;
                    }
                    const first = new Date(year, month-1, 1);
                    const last = new Date(year, month, 0);
                    const days = last.getDate();
                    const startWeekday = (first.getDay() + 6) % 7; // Senin sebagai awal minggu
                    const cells = [];
                    for (let i=0;i<startWeekday;i++) cells.push('');
                    for (let d=1; d<=days; d++) cells.push(String(d));
                    const today = new Date();
                    const isCurrentMonth = (year===today.getFullYear() && month===(today.getMonth()+1));
                    const weekdays = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                    const header = `<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">`+
                        `<button id="hfCalPrev" class="action-btn" style="background:#e5e7eb;color:#374151;padding:6px 10px;border-radius:8px">‹</button>`+
                        `<div style="font-weight:700;color:#374151">${monthNames[month-1]} ${year}</div>`+
                        `<button id="hfCalNext" class="action-btn" style="background:#e5e7eb;color:#374151;padding:6px 10px;border-radius:8px">›</button>`+
                    `</div>`+
                    '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px;margin-bottom:6px">'+
                        weekdays.map(w => `<div style=\"text-align:center;color:#6b7280;font-weight:600\">${w}</div>`).join('')+
                        '</div>';
                    const grid = cells.map(val => {
                        if (!val) return '<div></div>';
                        const isToday = (isCurrentMonth && parseInt(val,10)===today.getDate());
                        const baseStyle = tileBase;
                        const highlight = isToday ? tileHighlight : '';
                        return `<button data-day="${val}" class="calendar-day" style="${baseStyle}${highlight}">${val}</button>`;
                    }).join('');
                    panel.innerHTML = header + '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px">'+ grid +'</div>';
                    panel.querySelectorAll('button[data-day]').forEach(b => {
                        b.addEventListener('click', () => {
                            const day = parseInt(b.getAttribute('data-day'),10);
                            const d = fmt(year, month, day); // format lokal tanpa timezone
                            historyFilterModal.classList.remove('show');
                            applyHistoryFilter('daily', { date: d });
                        });
                    });
                    const prev = panel.querySelector('#hfCalPrev');
                    const next = panel.querySelector('#hfCalNext');
                    if (prev) prev.addEventListener('click', () => { calMonth -= 1; if (calMonth < 1) { calMonth = 12; calYear -= 1; } renderCalendar(calYear, calMonth); });
                    if (next) next.addEventListener('click', () => { calMonth += 1; if (calMonth > 12) { calMonth = 1; calYear += 1; } renderCalendar(calYear, calMonth); });
                }
                historyFilterModal.querySelector('#hfDaily').addEventListener('click', () => {
                    renderCalendar(calYear, calMonth);
                });
                historyFilterModal.querySelector('#hfMonthly').addEventListener('click', () => {
                    panel.innerHTML = '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">'+ monthGrid +'</div>';
                    panel.querySelectorAll('button[data-month]').forEach(b => {
                        b.addEventListener('click', () => {
                            const m = parseInt(b.getAttribute('data-month'),10);
                            historyFilterModal.classList.remove('show');
                            applyHistoryFilter('monthly', { year: currentYear, month: m });
                        });
                    });
                });
                historyFilterModal.querySelector('#hfYearly').addEventListener('click', () => {
                    panel.innerHTML = '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">'+ yearGrid +'</div>';
                    panel.querySelectorAll('button[data-year]').forEach(b => {
                        b.addEventListener('click', () => {
                            const y = parseInt(b.getAttribute('data-year'),10);
                            historyFilterModal.classList.remove('show');
                            applyHistoryFilter('yearly', { year: y });
                        });
                    });
                });
            }

            if (historyFilterBtn) {
                historyFilterBtn.addEventListener('click', openHistoryFilterModal);
            }
            if (historyRefreshBtn) {
                historyRefreshBtn.addEventListener('click', () => {
                    document.querySelectorAll('.history-table tbody tr').forEach(r => { r.style.display = ''; });
                    historyFilterActive = false;
                    if (historyFilterBtn) historyFilterBtn.classList.remove('active');
                    if (window.applyHistoryLimit) window.applyHistoryLimit();
                });
            }

            const viewAllBtn = document.getElementById('viewAllBtn');
            const historyRows = Array.from(document.querySelectorAll('.history-table tbody tr'));
            const HISTORY_LIMIT = 7;
            function applyHistoryLimit(){
                let shown = 0;
                historyRows.forEach(r => {
                    const hiddenByFilter = r.style.display === 'none';
                    if (hiddenByFilter) return;
                    if (shown >= HISTORY_LIMIT) { r.style.display = 'none'; } else { r.style.display = ''; }
                    if (r.style.display !== 'none') shown++;
                });
            }
            window.applyHistoryLimit = applyHistoryLimit;
            applyHistoryLimit();
            if (viewAllBtn) {
                viewAllBtn.addEventListener('click', () => {
                    window.location.href = '{{ route('transactions.all') }}';
                });
            }

            function makeExternalTooltip(format) {
                return function(context) {
                    const { chart, tooltip } = context;
                    const fmt = typeof format === 'string' ? format : '';
                    const excludeLine = fmt.includes('noline');
                    const isPie = fmt.includes('pie');

                    let tooltipEl = chart._externalTooltip;
                    if (!tooltipEl) {
                        tooltipEl = document.createElement('div');
                        tooltipEl.className = 'chartjs-tooltip hidden';
                        tooltipEl.style.background = 'rgba(255, 255, 255, 0.98)';
                        tooltipEl.style.borderRadius = '12px';
                        tooltipEl.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
                        tooltipEl.style.padding = '10px 14px';
                        tooltipEl.style.pointerEvents = 'none';
                        tooltipEl.style.position = 'absolute';
                        tooltipEl.style.transition = 'opacity 0.15s ease, transform 0.1s ease';
                        tooltipEl.style.zIndex = '100';
                        tooltipEl.style.border = '1px solid #f3f4f6';
                        tooltipEl.style.fontFamily = "'Inter', sans-serif";
                        chart._externalTooltip = tooltipEl;
                        chart.canvas.parentNode.appendChild(tooltipEl);
                    }

                    if (tooltip.opacity === 0) {
                        tooltipEl.style.opacity = 0;
                        tooltipEl.classList.add('hidden');
                        return;
                    }
                    tooltipEl.classList.remove('hidden');
                    tooltipEl.style.opacity = 1;

                    const title = (tooltip.title && tooltip.title[0]) || '';
                    let displayTitle = title;
                    if (isPie) displayTitle = '';

                    const rows = (tooltip.dataPoints || []).filter(dp => {
                        if (!excludeLine) return true;
                        const lbl = (dp.dataset && dp.dataset.label) ? dp.dataset.label.toLowerCase() : '';
                        const typ = (dp.dataset && dp.dataset.type) ? String(dp.dataset.type).toLowerCase() : '';
                        return !(typ === 'line' || lbl.includes('garis data'));
                    }).map(dp => {
                        let lbl = dp.dataset.label || '';
                        let color = dp.dataset.borderColor;

                        if (isPie) {
                            lbl = dp.label || '';
                            if (Array.isArray(dp.dataset.backgroundColor)) {
                                color = dp.dataset.backgroundColor[dp.dataIndex];
                            } else {
                                color = dp.dataset.backgroundColor;
                            }
                        } else {
                            if (Array.isArray(color)) color = color[0];
                        }

                        const raw = (dp.parsed && (dp.parsed.y ?? dp.parsed.r) != null) ? (dp.parsed.y ?? dp.parsed.r) : (dp.raw ?? 0);

                        let val = '';
                        if (isPie) {
                             const total = dp.dataset.data.reduce((a,b)=>a+b,0);
                             const pct = total ? Math.round((raw/total)*100) : 0;
                             val = `<span style="color:#6b7280;font-weight:500">Rp</span> ${raw.toLocaleString('id-ID')} <span style="color:${color};font-weight:700">(${pct}%)</span>`;
                        } else {
                             val = fmt.startsWith('percent') ? `${raw}%` : (fmt.startsWith('plain') ? `${raw}` : `Rp ${raw.toLocaleString('id-ID')}`);
                        }

                        return `
                        <div class="tooltip-row" style="display:flex;align-items:center;justify-content:space-between;width:100%;margin-bottom:4px;gap:16px">
                            <div style="display:flex;align-items:center;gap:8px">
                                <span class="tooltip-dot" style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${color};box-shadow: 0 0 0 2px rgba(255,255,255,0.8)"></span>
                                <span style="font-weight:600;color:#374151;font-size:0.85rem">${lbl}</span>
                            </div>
                            <span style="font-weight:700;color:#111827;font-size:0.9rem;font-family:'Monaco', 'Consolas', monospace">${val}</span>
                        </div>`;
                    }).join('');

                    let html = '';
                    if (displayTitle) {
                         html += `<div style="font-weight:700;color:#111827;font-size:0.9rem;margin-bottom:8px;border-bottom:1px solid #f3f4f6;padding-bottom:6px">${displayTitle}</div>`;
                    }
                    html += rows;
                    tooltipEl.innerHTML = html;

                    const parentNode = chart.canvas.parentNode;
                    const parentRect = parentNode.getBoundingClientRect();
                    const canvasRect = chart.canvas.getBoundingClientRect();

                    const caretX = (typeof tooltip.caretX === 'number') ? tooltip.caretX : (tooltip.x || 0);
                    const caretY = (typeof tooltip.caretY === 'number') ? tooltip.caretY : (tooltip.y || 0);

                    let tx = caretX + canvasRect.left - parentRect.left;
                    let ty = caretY + canvasRect.top - parentRect.top;

                    // Position tweaks
                    const maxLeft = parentRect.width - tooltipEl.offsetWidth - 8;
                    const minLeft = 8;

                    // Center align horizontally roughly
                    let finalX = tx - (tooltipEl.offsetWidth / 2);
                    finalX = Math.max(minLeft, Math.min(maxLeft, finalX));

                    let finalY = ty - tooltipEl.offsetHeight - 12;
                    if (finalY < 0) finalY = ty + 20;

                    tooltipEl.style.transform = `translate(${finalX}px, ${finalY}px)`;
                };
            }



            const cache = new Map();
            let width = null;
            let height = null;

            function colorForLabel(label) {
                let h = 0;
                for (let i = 0; i < label.length; i++) h = (h * 31 + label.charCodeAt(i)) % 360;
                const s = 70;
                const l = 55;
                return { h, s, l };
            }
            function hsl(h, s, l) { return `hsl(${h}, ${s}%, ${l}%)`; }
            function hsla(c, a) { return `hsla(${c.h}, ${c.s}%, ${c.l}%, ${a})`; }
            function lighten(c, amt) { return hsl(c.h, c.s, Math.max(0, Math.min(100, c.l + amt))); }
            function darken(c, amt) { return hsl(c.h, c.s, Math.max(0, Math.min(100, c.l - amt))); }

            function parseRgbToHsl(str) {
                const m = /rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/i.exec(str || '');
                if (!m) return { h: 210, s: 50, l: 50 };
                const r = parseInt(m[1],10)/255, g = parseInt(m[2],10)/255, b = parseInt(m[3],10)/255;
                const max = Math.max(r,g,b), min = Math.min(r,g,b);
                let h, s; const l = (max+min)/2;
                if (max === min) { h = 0; s = 0; }
                else {
                    const d = max - min;
                    s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                    switch(max){
                        case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                        case g: h = (b - r) / d + 2; break;
                        case b: h = (r - g) / d + 4; break;
                    }
                    h /= 6;
                }
                return { h: Math.round(h*360), s: Math.round(s*100), l: Math.round(l*100) };
            }

            function baseColorFor(label) { return colorForLabel(label); }

            let distGradCache = new Map();
            let distGradW = null;
            let distGradH = null;

            function createRadialGradient3(context, c1, c2, c3) {
                const chartArea = context.chart.chartArea;
                if (!chartArea) return;
                const chartWidth = chartArea.right - chartArea.left;
                const chartHeight = chartArea.bottom - chartArea.top;
                if (distGradW !== chartWidth || distGradH !== chartHeight) {
                    distGradCache.clear();
                }
                let gradient = distGradCache.get(c1 + c2 + c3);
                if (!gradient) {
                    distGradW = chartWidth;
                    distGradH = chartHeight;
                    const centerX = (chartArea.left + chartArea.right) / 2;
                    const centerY = (chartArea.top + chartArea.bottom) / 2;
                    const r = Math.min((chartArea.right - chartArea.left) / 2, (chartArea.bottom - chartArea.top) / 2);
                    const ctx = context.chart.ctx;
                    gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, r);
                    gradient.addColorStop(0, c1);
                    gradient.addColorStop(0.5, c2);
                    gradient.addColorStop(1, c3);
                    distGradCache.set(c1 + c2 + c3, gradient);
                }
                return gradient;
            }

            function distExternalTooltip(context) {
                const { chart, tooltip } = context;
                const parent = chart.canvas && chart.canvas.parentNode;
                if (!parent) return;

                let tooltipEl = parent.querySelector('.chartjs-tooltip');
                if (!tooltipEl) {
                    tooltipEl = document.createElement('div');
                    tooltipEl.className = 'chartjs-tooltip hidden';
                    tooltipEl.innerHTML = '<span class="tooltip-title"></span><div class="tooltip-body"></div>';
                    parent.appendChild(tooltipEl);
                }

                if (!tooltip || tooltip.opacity === 0) {
                    tooltipEl.classList.add('hidden');
                    return;
                }

                tooltipEl.classList.remove('hidden');

                const dp = (tooltip.dataPoints && tooltip.dataPoints[0]) ? tooltip.dataPoints[0] : null;
                const label = (dp && dp.label != null) ? String(dp.label) : '';
                const raw = (dp && dp.raw != null) ? (+dp.raw || 0) : 0;
                const dataArr = (chart.data && chart.data.datasets && chart.data.datasets[0] && chart.data.datasets[0].data) ? chart.data.datasets[0].data : [];
                const total = (dataArr || []).reduce((a, b) => a + (+b || 0), 0);
                const pct = total ? Math.round((raw / total) * 100) : 0;
                const dot = (tooltip.labelColors && tooltip.labelColors[0] && tooltip.labelColors[0].backgroundColor) ? tooltip.labelColors[0].backgroundColor : '#64748b';

                const titleEl = tooltipEl.querySelector('.tooltip-title');
                const bodyEl = tooltipEl.querySelector('.tooltip-body');
                if (titleEl) titleEl.textContent = label;
                if (bodyEl) {
                    bodyEl.innerHTML = '<div class="tooltip-row"><span class="tooltip-dot" style="background:' + dot + '"></span><span>' + fmtIDR(raw) + ' (' + pct + '%)</span></div>';
                }

                const parentRect = parent.getBoundingClientRect();
                const caretX = (typeof tooltip.caretX === 'number') ? tooltip.caretX : 0;
                const caretY = (typeof tooltip.caretY === 'number') ? tooltip.caretY : 0;

                const maxLeft = parentRect.width - tooltipEl.offsetWidth - 8;
                const minLeft = 8;

                let finalX = caretX - (tooltipEl.offsetWidth / 2);
                finalX = Math.max(minLeft, Math.min(maxLeft, finalX));

                let finalY = caretY - tooltipEl.offsetHeight - 12;
                if (finalY < 0) finalY = caretY + 20;

                tooltipEl.style.transform = 'translate(' + finalX + 'px, ' + finalY + 'px)';
            }

            const homeDistCtxElement = document.getElementById('homeDistChart');
            let homeDistChart = null;
            if (homeDistCtxElement) {
                const homeDistCtx = homeDistCtxElement.getContext('2d');
                homeDistChart = new Chart(homeDistCtx, {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            borderColor: '#ffffff',
                            borderWidth: 5,
                            hoverOffset: 0,
                            borderRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        elements: {
                            arc: {
                                backgroundColor: function(context) {
                                    const label = context.chart.data.labels[context.dataIndex] || '';
                                    const base = baseColorFor(label);
                                    const start = lighten(base, 15);
                                    const mid = darken(base, 10);
                                    const end = lighten(base, -5);
                                    return createRadialGradient3(context, start, mid, end);
                                }
                            }
                        },
                        cutout: '62%',
                        interaction: { mode: 'nearest', intersect: true },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false,
                                external: distExternalTooltip
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: false,
                            duration: 800,
                            easing: 'easeOutQuart'
                        },
                        layout: {
                            padding: 0
                        }
                    }
                });

                homeDistChart.canvas.addEventListener('click', function(evt) {
                    const points = homeDistChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
                    const center = document.getElementById('homeTotalExpense');
                    if (points && points.length) {
                        const idx = points[0].index;
                        const data = homeDistChart.data.datasets[0].data || [];
                        const total = data.reduce(function(a, b) { return a + b; }, 0);
                        const value = data[idx] || 0;
                        const pct = total ? Math.round((value / total) * 100) : 0;
                        center.textContent = pct + '%';
                    } else {
                        const fallbackTotal = (homeDistChart.data.datasets[0].data || []).reduce(function(a, b) { return a + b; }, 0);
                        center.textContent = fmtIDR(fallbackTotal || 0);
                    }
                });
            }

            function createHomeLegend(labels, data) {
                const percentContainer = document.getElementById('homeLegendPercentContainer');
                const amountContainer = document.getElementById('homeLegendAmountContainer');
                if (percentContainer) percentContainer.innerHTML = '';
                if (amountContainer) amountContainer.innerHTML = '';

                const total = (data || []).reduce(function(a, b) { return a + b; }, 0);
                const items = (labels || []).map(function(label, idx) {
                    return { label: label, value: (data && data[idx]) ? data[idx] : 0 };
                });
                const withPct = items.map(function(it) {
                    return { label: it.label, value: it.value, pct: total ? Math.round((it.value / total) * 100) : 0 };
                }).sort(function(a, b) { return b.pct - a.pct; });

                const MAX_PCT_ITEMS = 4;
                const topPctList = withPct.slice(0, MAX_PCT_ITEMS);
                const remainingPctList = withPct.slice(MAX_PCT_ITEMS);
                const topAmountList = withPct; // tampilkan semua nominal

                topPctList.forEach(function(item) {
                    const label = item.label;
                    const pct = item.pct;
                    const base = baseColorFor(label);
                    const dotColor = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l + 15)));
                    const pillBg = hsla({ h: base.h, s: base.s, l: Math.max(0, Math.min(100, base.l + 22)) }, 0.18);
                    const pillBorder = hsla(base, 0.35);
                    const pillText = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l - 15)));

                    if (percentContainer) {
                        const legendItem = document.createElement('div');
                        legendItem.className = 'legend-item-3d legend-item-percent';
                        legendItem.innerHTML =
                            '<div class="legend-left-3d">' +
                                '<div class="legend-color-3d" style="background-color:' + dotColor + '"></div>' +
                                '<div class="legend-label-3d">' + label + '</div>' +
                            '</div>' +
                            '<div class="legend-right-3d">' +
                                '<div class="legend-percent-3d" style="background:' + pillBg + ';border-color:' + pillBorder + ';color:' + pillText + '">' + pct + '%</div>' +
                            '</div>';
                        percentContainer.appendChild(legendItem);
                        legendItem.addEventListener('click', function() {
                            const center = document.getElementById('homeTotalExpense');
                            center.textContent = pct + '%';
                        });
                    }
                });

                if (remainingPctList.length > 0 && percentContainer) {
                    const toggleBtn = document.createElement('button');
                    toggleBtn.type = 'button';
                    toggleBtn.className = 'dist-all-btn';
                    toggleBtn.style.marginTop = '10px';
                    toggleBtn.innerHTML = '<span class="chip-icon"><i class="fa-solid fa-eye"></i></span><span>Lihat semua</span>';
                    toggleBtn.addEventListener('click', function() {
                        openDistAllModal(withPct);
                    });
                    percentContainer.appendChild(toggleBtn);
                }

                topAmountList.forEach(function(item) {
                    const label = item.label;
                    const value = item.value;
                    const base = baseColorFor(label);
                    const dotColor = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l + 15)));

                    if (!amountContainer) return;
                    const tile = document.createElement('div');
                    tile.className = 'legend-item-3d legend-item-amount';
                    tile.innerHTML =
                        '<div class="legend-left-3d">' +
                            '<div class="legend-color-3d" style="background-color:' + dotColor + '"></div>' +
                            '<div class="legend-label-3d">' + label + '</div>' +
                        '</div>' +
                        '<div class="legend-right-3d">' +
                            '<div class="legend-amount-3d">' + fmtIDR(value) + '</div>' +
                        '</div>';
                    amountContainer.appendChild(tile);
                    tile.addEventListener('click', function() {
                        const center = document.getElementById('homeTotalExpense');
                        center.textContent = fmtIDR(value);
                    });
                });

                // tidak ada tombol "Lihat semua nominal"; semua item sudah ditampilkan
            }

            async function refreshHomeDistribution() {
                if (!homeDistChart) return;
                const now = new Date();
                const y = now.getFullYear();
                const m = now.getMonth() + 1;
                const dist = await fetchDistribution(y, m);
                const labels = dist.labels || [];
                const totals = dist.totals || [];
                const adjustedTotals = (totals || []).map(function(v){ return v > 0 ? v : 0.0001; });
                homeDistChart.data.labels = labels;
                homeDistChart.data.datasets[0].data = adjustedTotals;
                homeDistChart.update();

                const totalExpense = (totals || []).reduce(function(a, b) { return a + b; }, 0);
                const center = document.getElementById('homeTotalExpense');
                if (center) center.textContent = fmtIDR(totalExpense);
                createHomeLegend(labels, totals);
            }

            const distCache = new Map();
            function clearDistCache() { distCache.clear(); }
            async function fetchDistribution(year, month) {
                const key = `${year ?? ''}|${month ?? ''}`;
                if (distCache.has(key)) return distCache.get(key);
                const params = new URLSearchParams();
                if (year) params.set('year', year);
                if (month) params.set('month', month);
                const res = await fetch(`/transactions/distribution?${params.toString()}`);
                const json = await res.json();
                distCache.set(key, json);
                return json;
            }

            let budgets = {};
            async function fetchBudgets() {
                const res = await fetch('/transactions/budgets');
                const json = await res.json();
                budgets = json.budgets || {};
                return budgets;
            }

            async function renderDistributionGrid(year, month) {
                const dist = await fetchDistribution(year, month);
                const labels = dist.labels || [];
                const totals = dist.totals || [];
                const icons = dist.icons || [];
                const colors = dist.colors || [];

                const container = document.getElementById('distributionGrid');
                if (!container) return;
                container.innerHTML = '';

                const count = labels.length;
                for (let i = 0; i < count; i++) {
                    const label = labels[i];
                    const total = totals[i] || 0;
                    const icon = icons[i] || 'fa-wallet';
                    const color = colors[i] || '#3b82f6';

                    const item = document.createElement('div');
                    item.className = 'dist-item';

                    const iconWrapper = document.createElement('div');
                    iconWrapper.className = 'dist-icon-wrapper';
                    iconWrapper.style.backgroundColor = color;
                    iconWrapper.innerHTML = `<i class="fa-solid ${icon}"></i>`;

                    const labelEl = document.createElement('div');
                    labelEl.className = 'dist-label';
                    labelEl.textContent = label;

                    item.appendChild(iconWrapper);
                    item.appendChild(labelEl);

                    item.addEventListener('click', () => {
                         const max = budgets[label] || 0;
                         const pct = max > 0 ? (total / max * 100) : 0;
                         const displayPct = Math.round(pct);
                         openPolarSettings(label, displayPct, total);
                    });

                    container.appendChild(item);
                }
            }

            const polarSettingsModal = document.createElement('div');
            polarSettingsModal.id = 'polarSettingsModal';
            polarSettingsModal.className = 'fade-modal';
            document.body.appendChild(polarSettingsModal);
            polarSettingsModal.addEventListener('click', (e) => { if (e.target === polarSettingsModal) polarSettingsModal.classList.remove('show'); });

            function formatRupiahStr(n){ return n ? Number(n).toLocaleString('id-ID') : ''; }
            function unformatRupiahStr(s){ const v = String(s||'').replace(/\D/g,''); return v ? parseInt(v,10) : 0; }

            // Helper functions for budget month settings
            const BUDGET_MONTHS_KEY = 'budgetCategoryMonths';

            function normalizeLabel(label){
                return String(label || '').trim();
            }

            function loadMonthsSettings() {
                try {
                    const s = localStorage.getItem(BUDGET_MONTHS_KEY);
                    return s ? JSON.parse(s) : {};
                } catch(e) { return {}; }
            }
            function ensureSettingsForLabel(label) {
                const all = loadMonthsSettings();
                const key = normalizeLabel(label);
                if (!all[key]) {
                    all[key] = { selected: 1, options: [1] };
                }
                return all[key];
            }

            function openPolarSettings(label, currentPercent, currentExpense = 0) {
                const currentMax = budgets[label] || 0;
                const st = ensureSettingsForLabel(label);
                const months = st.selected || 1;
                // Updated logic: effectiveMax is just currentMax (Total Budget)
                const effectiveMax = currentMax;
                const pct = effectiveMax > 0 ? (currentExpense / effectiveMax * 100) : 0;
                const displayPct = Math.min(100, Math.max(0, Math.round(pct)));
                const base = baseColorFor(label);
                const colorStr = hsl(base.h, base.s, base.l);

                // Progress bar color based on percentage
                let progressColor = colorStr;
                if (displayPct >= 100) progressColor = '#ef4444'; // red
                else if (displayPct >= 75) progressColor = '#f59e0b'; // amber

                polarSettingsModal.innerHTML = '<div class="modal-card" style="max-width:420px;padding:24px;">'+
                    '<div style="text-align:center;margin-bottom:24px">'+
                        '<div style="font-weight:800;font-size:1.25rem;color:#1f2937">Pengaturan Pengeluaran</div>'+
                        '<div style="color:#6b7280;font-size:0.9rem;margin-top:4px">Atur maksimal pengeluaran per kategori</div>'+
                    '</div>'+

                    '<div style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;margin-bottom:24px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.05)">'+
                        '<div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">'+
                             `<div style="width:52px;height:52px;border-radius:14px;background:${colorStr};display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:white;box-shadow:0 4px 6px -2px rgba(0,0,0,0.1)">`+
                                `<i class="fa-solid fa-tag"></i>`+
                             '</div>'+
                             '<div style="flex:1">'+
                                 `<div style="font-weight:700;font-size:1.1rem;color:#111827;margin-bottom:2px">${label}</div>`+
                                 `<div style="font-size:0.9rem;color:#6b7280">${formatRupiahStr(currentExpense)} <span style="font-size:0.8rem;color:#9ca3af">terpakai</span></div>`+
                             '</div>'+
                             `<div style="text-align:right">`+
                                 `<div style="font-weight:700;font-size:1.2rem;color:${progressColor}">${displayPct}%</div>`+
                             `</div>`+
                        '</div>'+

                        '<div style="margin-bottom:16px">'+
                            '<div style="height:8px;background:#f3f4f6;border-radius:4px;overflow:hidden">'+
                                `<div style="width:${displayPct}%;height:100%;background:${progressColor};border-radius:4px;transition:width 0.5s ease-out"></div>`+
                            '</div>'+
                        '</div>'+

                        '<div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:1px solid #f3f4f6">'+
                             '<div>'+
                                 '<div style="font-size:0.75rem;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:0.025em;margin-bottom:2px">Maksimal (Total)</div>'+
                                 `<div style="font-weight:700;color:#374151;font-size:1rem">${formatRupiahStr(effectiveMax)}</div>`+
                             '</div>'+
                             '<div style="text-align:right">'+
                                 '<div style="font-size:0.75rem;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:0.025em;margin-bottom:2px">Durasi</div>'+
                                 `<div style="font-weight:700;color:#374151;font-size:1rem">${months} Bulan</div>`+
                             '</div>'+
                        '</div>'+
                    '</div>'+

                    '<div style="margin-bottom:24px">'+
                        '<label style="display:block;font-size:0.9rem;font-weight:600;color:#374151;margin-bottom:8px;">Maksimal Pengeluaran (Total)</label>'+
                        '<div style="position:relative">'+
                            '<span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#9ca3af;font-weight:500">Rp</span>'+
                            `<input id="polarMaxInput" type="text" inputmode="numeric" value="${formatRupiahStr(currentMax)}" placeholder="0" style="width:100%;padding:14px 14px 14px 44px;border:1px solid #d1d5db;border-radius:12px;font-size:1.1rem;font-weight:600;color:#1f2937;outline:none;transition:all 0.2s;background:#f9fafb">`+
                        '</div>'+
                        '<div style="font-size:0.8rem;color:#6b7280;margin-top:8px;padding-left:4px;line-height:1.4"><i class="fa-solid fa-circle-info" style="margin-right:4px;color:#3b82f6"></i>Nilai ini adalah total budget untuk durasi ${months} bulan.</div>'+
                    '</div>'+

                    '<div style="display:flex;gap:12px;">'+
                        '<button id="polarCancel" class="action-btn" style="flex:1;background:white;border:1px solid #d1d5db;color:#374151;justify-content:center;padding:12px;font-weight:600;border-radius:12px">Batal</button>'+
                        '<button id="polarSave" class="action-btn" style="flex:1;justify-content:center;background:#10b981;color:white;padding:12px;font-weight:600;box-shadow:0 4px 6px -1px rgba(16, 185, 129, 0.3);border-radius:12px">Simpan</button>'+
                    '</div>'+
                '</div>';

                polarSettingsModal.classList.add('show');
                const cancel = polarSettingsModal.querySelector('#polarCancel');
                const save = polarSettingsModal.querySelector('#polarSave');
                const input = polarSettingsModal.querySelector('#polarMaxInput');

                input.addEventListener('focus', () => { input.style.borderColor = '#10b981'; input.style.background = 'white'; });
                input.addEventListener('blur', () => { input.style.borderColor = '#d1d5db'; input.style.background = '#f9fafb'; });

                input.addEventListener('input',()=>{ const v = input.value.replace(/\D/g,''); input.value = v ? parseInt(v,10).toLocaleString('id-ID') : ''; });
                cancel.addEventListener('click', () => { polarSettingsModal.classList.remove('show'); });
                save.addEventListener('click', async () => {
                    const val = unformatRupiahStr(input.value);
                    await fetch('/transactions/budgets', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ label, max_amount: val })
                    });
                    await fetchBudgets();
                    polarSettingsModal.classList.remove('show');
                    await renderDistributionGrid(currentYear);
                });
            }

            const currentYear = new Date().getFullYear();
            const distAllModal = document.createElement('div');
            distAllModal.id = 'distAllModal';
            distAllModal.className = 'fade-modal';
            document.body.appendChild(distAllModal);
            distAllModal.addEventListener('click', (e) => { if (e.target === distAllModal) distAllModal.classList.remove('show'); });

            function openDistAllModal(items) {
                const total = items.reduce((a, b) => a + (b.value || 0), 0);
                const listHtml = items.map(function(it){
                    const label = it.label;
                    const value = Number(it.value || 0);
                    const pct = total ? Math.round((value / total) * 100) : 0;
                    const base = baseColorFor(label);
                    const dotColor = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l + 15)));
                    const pillBg = hsla({ h: base.h, s: base.s, l: Math.max(0, Math.min(100, base.l + 22)) }, 0.18);
                    const pillBorder = hsla(base, 0.35);
                    const pillText = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l - 15)));
                    return '' +
                        '<div class="legend-item-3d" style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px">' +
                            '<div class="legend-left-3d">' +
                                '<div class="legend-color-3d" style="background-color:' + dotColor + ';box-shadow:0 0 0 2px rgba(0,0,0,0.03)"></div>' +
                                '<div class="legend-label-3d">' + label + '</div>' +
                            '</div>' +
                            '<div class="legend-right-3d" style="display:flex;align-items:center;gap:10px">' +
                                '<div class="legend-amount-3d">' + fmtIDR(value) + '</div>' +
                                '<div class="legend-percent-3d" style="background:' + pillBg + ';border-color:' + pillBorder + ';color:' + pillText + '">' + pct + '%</div>' +
                            '</div>' +
                        '</div>';
                }).join('');

                distAllModal.innerHTML =
                    '<div class="modal-card" style="max-width:460px;padding:18px 18px 14px 18px">' +
                        '<div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px">' +
                            '<div style="display:flex;align-items:center;gap:10px;font-weight:800;color:#374151">' +
                                '<div style="width:36px;height:36px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;color:#6366f1">' +
                                    '<i class="fa-solid fa-chart-pie"></i>' +
                                '</div>' +
                                '<div>Semua Kategori Pengeluaran</div>' +
                            '</div>' +
                            '<button id="distAllCloseX" class="action-btn" style="background:#f3f4f6;border:1px solid #e5e7eb;border-radius:10px;width:36px;height:36px;display:flex;align-items:center;justify-content:center;color:#374151"><i class="fa-solid fa-xmark"></i></button>' +
                        '</div>' +
                        '<div class="modal-scroll" style="margin:6px 0 12px 0">' + listHtml + '</div>' +
                        '<div style="display:flex;gap:10px;justify-content:flex-end">' +
                            '<button id="distAllClose" class="action-btn" style="background:white;border:1px solid #d1d5db;color:#374151;justify-content:center;padding:10px 12px;font-weight:600;border-radius:12px"><i class="fa-solid fa-check"></i> Tutup</button>' +
                        '</div>' +
                    '</div>';
                distAllModal.classList.add('show');
                const cx = distAllModal.querySelector('#distAllCloseX');
                const cb = distAllModal.querySelector('#distAllClose');
                if (cx) cx.addEventListener('click', () => { distAllModal.classList.remove('show'); });
                if (cb) cb.addEventListener('click', () => { distAllModal.classList.remove('show'); });
            }

            (async () => {
                await fetchBudgets();
                clearDistCache();
                await renderDistributionGrid(currentYear);
                await refreshHomeDistribution();
                window.addEventListener('focus', async function() {
                    clearDistCache();
                    await renderDistributionGrid(currentYear);
                    await refreshHomeDistribution();
                }, { passive: true });
            })();
        }
    </script>
</body>
</html>
