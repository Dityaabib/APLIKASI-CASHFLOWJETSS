<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan - {{ config('app.name', 'CashFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-2: #4338ca;
            --primary-3: #7c3aed;
            --secondary: #ec4899;
            --accent: #06b6d4;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.95);
            --text: #1f2937;
            --muted: #6b7280;
            --card: rgba(255, 255, 255, 0.8);
            --border: rgba(229, 231, 235, 0.8);
            --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --radius-card: 18px;
        }
        body {
            padding-top: 60px;
            padding-bottom: 70px;
            margin: 0;
            background:
                radial-gradient(ellipse at top left, rgba(79, 70, 229, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at bottom right, rgba(236, 72, 153, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at center, rgba(6, 182, 212, 0.08) 0%, transparent 60%),
                linear-gradient(135deg, #f8fafc 0%, #e0e7ff 30%, #fce7f3 60%, #e0f2fe 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            min-height: 100vh;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(79, 70, 229, 0.08) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(236, 72, 153, 0.08) 0%, transparent 20%),
                radial-gradient(circle at 40% 80%, rgba(6, 182, 212, 0.08) 0%, transparent 20%);
            z-index: -1;
            animation: floatingParticles 20s ease-in-out infinite;
        }

        @keyframes floatingParticles {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
            50% { transform: translate(-20px, -20px) scale(1.1); opacity: 0.4; }
        }

        .container {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .filter-bar {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            justify-content: stretch;
            margin-bottom: 16px;
            margin-top: 20px;
            padding: 0 8px;
        }

        .filter-btn {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.7);
            color: var(--text);
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .filter-btn:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-3) 100%);
            color: #fff;
            border-color: var(--primary);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .custom-range {
            display:flex;
            gap:8px;
            justify-content:center;
            align-items:center;
            margin-bottom: 16px;
            padding: 0 16px;
        }

        .card {
            background: var(--card);
            border-radius: var(--radius-card);
            padding:16px;
            margin-bottom:12px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
        }

        /* Ringkasan Periode - Full Width Tanpa Card Background */
        .summary-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.9) 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 24px;
            margin: 0;
            box-shadow: var(--shadow);
            backdrop-filter: blur(15px);
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
        }

        .summary-header {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 10px;
        }

        .summary-header i {
            color: var(--accent);
            font-size: 1.5rem;
            background: rgba(6, 182, 212, 0.1);
            padding: 10px;
            border-radius: 12px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            padding: 0 10px;
        }

        .summary-item {
            background: transparent;
            border-radius: 0;
            padding: 0;
            border: none;
            box-shadow: none;
        }

        .summary-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .summary-label {
            font-size: 1rem;
            color: var(--muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .income-icon {
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
        }

        .expense-icon {
            background: linear-gradient(135deg, #ef4444 0%, #ec4899 100%);
        }

        .summary-value {
            font-size: 1.8rem;
            font-weight: 900;
            margin-bottom: 12px;
            color: var(--text);
        }

        .summary-description {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .percent-card {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 14px;
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(5px);
        }

        .percent-divider {
            width: 1px;
            height: 70%;
            background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.1), transparent);
        }

        .summary-percent {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            font-weight: 800;
            padding: 8px 12px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .summary-percent i {
            font-size: 1rem;
        }

        .balance-value {
            font-weight: 900;
            font-size: 1.3rem;
            margin-top: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 12px;
            text-align: center;
        }

        .up { color: var(--success); }
        .down { color: var(--danger); }

        /* Chart Card Styling */
        .chart-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.9) 100%);
            margin: 0 0 12px 0;
        }

        /* Special styling for full width chart cards */
        .chart-card-full {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.9) 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            margin: 0;
            box-shadow: var(--shadow);
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
            background: radial-gradient(circle, rgba(6, 182, 212, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
        }

        /* White full-width section wrapper */
        .section-card-full {
            background: transparent;
            margin: 0 0 12px 0;
            border-radius: 0;
            padding: 0;
        }

        .filter-section-full {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.9) 100%);
            margin: 0 0 12px 0;
            border-radius: var(--radius-card);
            padding: 20px 8px;
            backdrop-filter: blur(15px);
            border: 1px solid var(--border);
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
            background: rgba(255, 255, 255, 0.9);
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .fade-modal.show { display: flex; }

        .modal-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 252, 0.95) 100%);
            border-radius: 16px;
            padding: 24px;
            width: 90%;
            max-width: 420px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            color: var(--text);
        }

        .page-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 10px;
            padding: 0 16px;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .page-title i {
            color: var(--accent);
            background: rgba(6, 182, 212, 0.1);
            padding: 8px;
            border-radius: 10px;
        }

        .chart-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 20px;
        }

        .chart-title i {
            color: var(--accent);
            background: rgba(6, 182, 212, 0.1);
            padding: 8px;
            border-radius: 10px;
        }

        /* Chart Container untuk Grafik Tren Arus Kas - Diperbesar */
        .chart-wrap {
            position:relative;
            height: 350px;
            padding: 0 20px;
        }

        .table-scroll {
            overflow-x:auto;
        }

        .legend-inline {
            display:flex;
            gap:16px;
            justify-content:center;
            margin-top:16px;
            padding: 0 20px;
        }

        .legend-dot {
            width:12px;
            height:12px;
            border-radius:50%;
            display:inline-block;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .history-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.9) 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 20px;
            margin: 0;
            box-shadow: var(--shadow);
            backdrop-filter: blur(15px);
        }

        .history-header {
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:16px;
        }

        .history-title {
            font-weight:700;
            color:var(--text);
            margin:0;
            font-size: 1.1rem;
        }

        .table-container {
            overflow-x:auto;
            -webkit-overflow-scrolling:touch;
        }

        .history-table {
            width:100%;
            border-collapse: collapse;
            min-width:760px;
        }

        .history-table th, .history-table td {
            padding:12px;
            border-bottom:1px solid var(--border);
            text-align:left;
            white-space:nowrap;
        }

        .history-table th {
            background: rgba(255, 255, 255, 0.5);
            color: var(--muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .refresh-btn {
            background: rgba(255, 255, 255, 0.7);
            border:1px solid var(--border);
            border-radius:10px;
            padding:8px 14px;
            font-size:0.85rem;
            color:var(--text);
            cursor:pointer;
            transition: all .3s ease;
            display:inline-flex;
            align-items:center;
            gap:8px;
            backdrop-filter: blur(5px);
        }

        .refresh-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.9);
        }

        .category-icon {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:28px;
            height:28px;
            border-radius:50%;
            margin-right:10px;
            font-size:0.8rem;
        }

        .category-income {
            background-color: rgba(16, 185, 129, 0.1);
            color:var(--success);
        }

        .category-expense {
            background-color: rgba(239, 68, 68, 0.1);
            color:var(--danger);
        }

        .amount-positive {
            color:var(--success);
            font-weight:700;
        }

        .amount-negative {
            color:var(--danger);
            font-weight:700;
        }

        .dist-separator {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(0, 0, 0, 0.1), transparent);
            margin: 0 20px 20px 20px;
        }

        .dist-amount-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 20px;
        }

        .dist-amount-title i {
            color: var(--accent);
            background: rgba(6, 182, 212, 0.1);
            padding: 8px;
            border-radius: 10px;
        }

        .dist-layout {
            display: flex;
            align-items: stretch;
            gap: 20px;
            padding: 0 20px 16px 20px;
        }

        .dist-col-chart { flex: 0 0 52%; min-width: 260px; }
        .dist-col-legend { flex: 1; min-width: 240px; display: flex; }
        .dist-amount-wrap { padding: 0 20px 16px 20px; }
        .legend-amount-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        @media (min-width: 768px) {
            .legend-amount-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (max-width: 560px) {
            .dist-layout { flex-wrap: nowrap; gap: 12px; padding: 0 12px 12px 12px; }
            .dist-col-chart { flex: 0 0 58%; min-width: 0; }
            .dist-col-legend { flex: 1 1 auto; min-width: 0; }
            .chart-wrap-3d { height: 220px; }
            .chart-total-value { font-size: 1.15rem; }
            .dist-amount-wrap { padding: 0 12px 12px 12px; }
            .legend-3d.legend-vertical { gap: 6px; }
            .legend-left-3d { gap: 8px; }
            .legend-right-3d { gap: 6px; }
            .legend-item-3d.legend-item-percent { padding: 2px 0; }
        }

        /* Chart Container untuk Distribusi Pengeluaran */
        .chart-wrap-3d {
            position: relative;
            height: 320px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 0;
            padding: 0;
        }

        #distChart {
            width: 100%;
            max-height: 100%;
        }

        /* Center text styling */
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
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            display: block;
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
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px 16px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .legend-item-3d:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.08);
        }

        .legend-item-percent {
            padding: 10px 12px;
            border-radius: 12px;
            background: transparent;
            border: none;
            box-shadow: none;
        }

        .legend-item-3d.legend-item-percent {
            background: transparent;
            border: none;
            box-shadow: none;
            padding: 4px 0;
        }

        .legend-item-amount {
            padding: 12px 14px;
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
            width: 18px;
            height: 18px;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            margin-right: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .legend-item-percent .legend-color-3d {
            flex: 0 0 auto;
            flex-shrink: 0;
            width: 12px;
            height: 12px;
            aspect-ratio: 1 / 1;
            margin-right: 10px;
        }

        .legend-label-3d {
            font-size: 0.95rem;
            color: var(--text);
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
            overflow: hidden;
            text-overflow: clip;
            line-height: 1.2;
            max-height: 2.4em;
        }

        .chartjs-tooltip {
            position: absolute;
            left: 0;
            top: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 252, 0.95) 100%);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            padding: 12px 16px;
            font-size: 0.9rem;
            color: var(--text);
            pointer-events: none;
            z-index: 1000;
            white-space: normal;
            line-height: 1.4;
            max-width: calc(100% - 12px);
            backdrop-filter: blur(10px);
            transition: opacity .12s ease-out, transform .18s ease-out;
            will-change: transform, opacity;
            transform-origin: top left;
        }

        .chartjs-tooltip.hidden { opacity: 0; }

        .tooltip-title {
            display:block;
            font-weight:800;
            color:var(--text);
            margin-bottom:8px;
            font-size: 1rem;
        }

        .tooltip-row {
            display:flex;
            align-items:center;
            gap:8px;
            margin: 4px 0;
        }

        .tooltip-dot {
            width:12px;
            height:12px;
            border-radius:50%;
            flex-shrink:0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .legend-amount-3d {
            font-size: 0.9rem;
            color: var(--text);
            font-weight: 700;
        }

        .legend-item-amount .legend-amount-3d {
            font-size: 0.85rem;
            font-weight: 800;
        }

        .legend-percent-3d {
            font-size: 0.9rem;
            font-weight: 800;
            margin-left: 10px;
            padding: 8px 12px;
            border-radius: 9999px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(5px);
        }

        .legend-item-percent .legend-percent-3d {
            font-size: 0.8rem;
            font-weight: 900;
            padding: 6px 10px;
        }

        .view-all-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 12px;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .view-all-btn:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .view-all-btn.view-all-btn-compact {
            margin-top: 10px;
            padding: 10px;
            font-size: 0.85rem;
        }

        .legend-3d.legend-vertical .view-all-btn.view-all-btn-compact {
            padding: 8px 10px;
            font-size: 0.8rem;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .filter-bar {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 8px;
                padding: 0 6px;
            }
            .filter-btn {
                font-size: 0.9rem;
                padding: 10px 12px;
            }
            .summary-card {
                padding: 20px 16px;
            }
            .summary-header {
                font-size: 1.2rem;
                margin-bottom: 20px;
                padding: 0;
            }
            .summary-header i {
                font-size: 1.3rem;
            }
            .summary-grid {
                grid-template-columns: 1fr 1fr;
                gap: 16px;
                padding: 0;
            }
            .summary-item {
                padding: 0;
            }
            .summary-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }
            .summary-value {
                font-size: 1.6rem;
            }
            .summary-label {
                font-size: 0.9rem;
            }
            .summary-description {
                font-size: 0.8rem;
                margin-bottom: 12px;
            }
            .percent-card {
                padding: 12px;
                border-radius: 12px;
            }
            .summary-percent {
                font-size: 0.8rem;
                padding: 6px 10px;
            }
            .summary-percent i {
                font-size: 0.9rem;
            }
            .balance-value {
                font-size: 1.1rem;
            }
            .chart-wrap {
                height: 280px;
                padding: 0 16px;
            }
            .chart-wrap-3d {
                height: 280px;
            }
            .chart-total-value {
                font-size: 1.1rem;
            }
            .legend-item-3d {
                padding: 10px 12px;
            }
            .legend-label-3d, .legend-percent-3d {
                font-size: 0.9rem;
            }
            .chart-title {
                padding: 0 16px;
            }
            .chart-card-full { padding-top: 16px; }
            .legend-inline {
                padding: 0 16px;
            }
        }

        /* Untuk tablet dan layar sedang */
        @media (min-width: 481px) and (max-width: 768px) {
            .summary-card {
                padding: 25px 18px;
            }
            .summary-header {
                font-size: 1.3rem;
                margin-bottom: 22px;
            }
            .summary-grid {
                gap: 20px;
            }
            .summary-value {
                font-size: 1.7rem;
            }
            .chart-wrap {
                height: 320px;
                padding: 0 16px;
            }
            .chart-wrap-3d {
                height: 340px;
            }
            .chart-total-value {
                font-size: 1.3rem;
            }
            .chart-title {
                padding: 0 16px;
            }
            .legend-inline {
                padding: 0 16px;
            }
        }

        /* Untuk desktop besar */
        @media (min-width: 1200px) {
            .summary-card {
                padding: 35px 25px;
            }
            .summary-header {
                font-size: 1.5rem;
                margin-bottom: 28px;
            }
            .summary-grid {
                gap: 30px;
            }
            .summary-value {
                font-size: 2rem;
            }
            .summary-icon {
                width: 48px;
                height: 48px;
                font-size: 1.3rem;
            }
            .balance-value {
                font-size: 1.4rem;
            }
            .chart-wrap {
                height: 400px;
                padding: 0 20px;
            }
            .chart-wrap-3d {
                height: 360px;
            }
            .chart-total-value {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <x-catat-nav />
    <div class="container">
        <div class="filter-section-full">
            <div class="page-title">
                Analisis Lengkap Arus Kas
            </div>
            <div class="filter-bar">
                <button class="filter-btn" data-period="week">
                    <i class="fa-solid fa-calendar-week"></i> Minggu ini
                </button>
                <button class="filter-btn active" data-period="month">
                    <i class="fa-solid fa-calendar-days"></i> Bulan ini
                </button>
                <button class="filter-btn" data-period="custom">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </div>
            <div class="custom-range" id="customRange" style="display:none;">
                <input type="date" id="startDate">
                <span>–</span>
                <input type="date" id="endDate">
                <button class="filter-btn" id="applyCustom">Terapkan</button>
            </div>
        </div>

        <!-- Card ringkasan periode dibungkus full putih (pembungkus) -->
        <div class="section-card-full">
        <div class="summary-card">
            <div class="summary-header">
                <i class="fa-solid fa-chart-line"></i>
                Ringkasan Periode
            </div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label-row">
                        <div class="summary-label">Pemasukan</div>
                        <div class="summary-icon income-icon">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>
                    </div>
                    <div class="summary-value up" id="sumIncome">0</div>
                    <div class="summary-description">Total Pemasukan</div>
                    <div class="percent-card">
                        <div class="summary-percent">
                            <i class="fa-solid fa-circle-arrow-up up" id="incDir"></i>
                            <span id="incPct" class="up">0%</span>
                        </div>
                        <div class="percent-divider"></div>
                        <div class="summary-percent">
                            <i class="fa-solid fa-circle-arrow-up down" id="expDir"></i>
                            <span id="expPct" class="down">0%</span>
                        </div>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-label-row">
                        <div class="summary-label">Pengeluaran</div>
                        <div class="summary-icon expense-icon">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                        </div>
                    </div>
                    <div class="summary-value down" id="sumExpense">0</div>
                    <div class="summary-description">Selisih Bersih</div>
                    <div id="sumBalance" class="balance-value">0</div>
                </div>
            </div>
        </div>
        </div>

        <!-- Card grafik tren arus kas dibungkus full putih (pembungkus) -->
        <div class="section-card-full">
        <div class="chart-card-full">
            <div class="chart-title">
                <i class="fa-solid fa-chart-line"></i>
                Grafik Tren Arus Kas
            </div>
            <div class="chart-wrap"><canvas id="trendChart"></canvas></div>
            <div class="legend-inline">
                <span><span class="legend-dot" style="background:var(--success)"></span> Pemasukan</span>
                <span><span class="legend-dot" style="background:var(--danger)"></span> Pengeluaran</span>
            </div>
        </div>
        </div>

        <div class="section-card-full" style="display:none;">
        <div class="chart-card-full">
            <div class="chart-title">
                <i class="fa-solid fa-chart-pie"></i>
                Distribusi Pengeluaran
            </div>
            <div class="dist-layout">
                <div class="dist-col-chart">
                    <div class="chart-wrap-3d">
                        <canvas id="distChart"></canvas>
                        <div class="chart-center-text">
                            <div class="chart-total-value" id="totalExpense">Rp 0</div>
                        </div>
                    </div>
                </div>
                <div class="dist-col-legend">
                    <div class="legend-3d legend-vertical" id="legendPercentContainer"></div>
                </div>
            </div>

            <div class="dist-separator"></div>

            <div class="dist-amount-wrap">
                <div class="dist-amount-title">
                    <i class="fa-solid fa-list-ul"></i>
                    Rincian Nominal
                </div>
                <div class="legend-amount-grid" id="legendAmountContainer"></div>
            </div>
        </div>
        </div>
        <div class="section-card-full">
        <div class="history-card">
            <div class="history-header">
                <h3 class="history-title">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Histori Transaksi
                </h3>
                <div style="display:flex;align-items:center;gap:10px;">
                    <button class="refresh-btn" id="historyRefreshBtn">
                        <i class="fa-solid fa-rotate-right"></i> Refresh
                    </button>
                    <button class="filter-btn" id="historyFilterBtn">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-calendar"></i> Tanggal</th>
                            <th><i class="fa-solid fa-tag"></i> Nama Transaksi</th>
                            <th><i class="fa-solid fa-layer-group"></i> Kategori</th>
                            <th><i class="fa-solid fa-money-bill"></i> Jumlah</th>
                            <th><i class="fa-solid fa-circle-check"></i> Status</th>
                            <th><i class="fa-solid fa-note-sticky"></i> Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($transactions ?? []) as $t)
                            @php $isIncome = $t->type === 'income'; @endphp
                            <tr data-year="{{ \Carbon\Carbon::parse($t->date)->year }}" data-month="{{ \Carbon\Carbon::parse($t->date)->month }}" data-date="{{ \Carbon\Carbon::parse($t->date)->toDateString() }}">
                                <td>{{ \Carbon\Carbon::parse($t->date)->translatedFormat('d M Y') }}</td>
                                <td>{{ $t->category }}</td>
                                <td>
                                    <span class="category-icon {{ $isIncome ? 'category-income' : 'category-expense' }}">
                                        <i class="fa-solid {{ $isIncome ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                                    </span>
                                    {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                                </td>
                                <td class="{{ $isIncome ? 'amount-positive' : 'amount-negative' }}">{{ ($isIncome ? '+' : '-') . 'Rp ' . number_format($t->amount, 0, ',', '.') }}</td>
                                <td>{{ 'Selesai' }}</td>
                                <td>{{ $t->description }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <button class="view-all-btn" id="viewAllBtn">
                <i class="fa-solid fa-eye"></i> Lihat History Lengkap
            </button>
        </div>
        </div>
    </div>

    <script>
        const fmtIDR = v => 'Rp ' + (Math.round(v).toLocaleString('id-ID'));
        function setSummary(json){
            document.getElementById('sumIncome').textContent = fmtIDR(json.income || 0);
            document.getElementById('sumExpense').textContent = fmtIDR(json.expense || 0);
            document.getElementById('sumBalance').textContent = fmtIDR((json.balance || 0));
            const incPct = document.getElementById('incPct');
            const expPct = document.getElementById('expPct');
            const incDir = document.getElementById('incDir');
            const expDir = document.getElementById('expDir');
            const ip = Math.round((json.incomeChange || 0) * 100) / 100;
            const ep = Math.round((json.expenseChange || 0) * 100) / 100;

            // Pemasukan: naik = hijau, turun = merah
            incPct.textContent = (ip>=0? '+' : '') + ip + '%';
            incPct.className = ip>=0 ? 'up' : 'down';
            incDir.className = 'fa-solid ' + (ip>=0 ? 'fa-circle-arrow-up up' : 'fa-circle-arrow-down down');

            // Pengeluaran: naik = merah, turun = hijau
            expPct.textContent = (ep>=0? '+' : '') + ep + '%';
            expPct.className = ep>=0 ? 'down' : 'up';
            expDir.className = 'fa-solid ' + (ep>=0 ? 'fa-circle-arrow-up down' : 'fa-circle-arrow-down up');
        }

        async function fetchSummary(period, start, end){
            const p = new URLSearchParams();
            p.set('period', period);
            if (start) p.set('start', start);
            if (end) p.set('end', end);
            const r = await fetch('/transactions/summary?' + p.toString());
            return await r.json();
        }

        async function fetchStats(type, granularity, month){
            const p = new URLSearchParams();
            p.set('type', type);
            p.set('granularity', granularity);
            if (month) p.set('month', month);
            const r = await fetch('/transactions/stats?' + p.toString());
            return await r.json();
        }

        async function fetchDistribution(start, end){
            const p = new URLSearchParams();
            if (start) p.set('start', start);
            if (end) p.set('end', end);
            const r = await fetch('/transactions/distribution?' + p.toString());
            return await r.json();
        }

        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const distCtx = document.getElementById('distChart').getContext('2d');
        function hsl(h, s, l) { return `hsl(${h}, ${s}%, ${l}%)`; }
        function hsla(c, a){ return `hsla(${c.h}, ${c.s}%, ${c.l}%, ${a})`; }
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
        const CATEGORY_PALETTE = { 'Makanan': 'rgb(255, 99, 132)', 'Transportasi': 'rgb(255, 159, 64)', 'Hiburan': 'rgb(255, 205, 86)', 'Tagihan': 'rgb(75, 192, 192)', 'Lainnya': 'rgb(54, 162, 235)' };
        function colorForLabel(label) { let h = 0; for (let i=0; i<label.length; i++) h = (h*31 + label.charCodeAt(i)) % 360; const s = 70; const l = 55; return { h, s, l }; }
        function baseColorFor(label) { const rgb = CATEGORY_PALETTE[label]; if (rgb) return parseRgbToHsl(rgb); return colorForLabel(label); }
        let _gradCache = new Map(); let _gradW = null; let _gradH = null;
        function createRadialGradient3(context, c1, c2, c3) {
            const chartArea = context.chart.chartArea;
            if (!chartArea) return;
            const chartWidth = chartArea.right - chartArea.left;
            const chartHeight = chartArea.bottom - chartArea.top;
            if (_gradW !== chartWidth || _gradH !== chartHeight) { _gradCache.clear(); }
            let gradient = _gradCache.get(c1 + c2 + c3);
            if (!gradient) {
                _gradW = chartWidth; _gradH = chartHeight;
                const centerX = (chartArea.left + chartArea.right) / 2;
                const centerY = (chartArea.top + chartArea.bottom) / 2;
                const r = Math.min((chartArea.right - chartArea.left)/2, (chartArea.bottom - chartArea.top)/2);
                const ctx = context.chart.ctx;
                gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, r);
                gradient.addColorStop(0, c1);
                gradient.addColorStop(0.5, c2);
                gradient.addColorStop(1, c3);
                _gradCache.set(c1 + c2 + c3, gradient);
            }
            return gradient;
        }

        // Konfigurasi grafik tren arus kas - Diperbesar
        const trendChart = new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: [],
                        borderColor: '#10b981',
                        backgroundColor: function(ctx){
                            const chart = ctx.chart; const {ctx: k, chartArea} = chart;
                            if (!chartArea) return 'rgba(16, 185, 129, 0.25)';
                            const g = k.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            g.addColorStop(0, 'rgba(16, 185, 129, 0.6)');
                            g.addColorStop(1, 'rgba(16, 185, 129, 0.1)');
                            return g;
                        },
                        borderWidth: 2,
                        borderRadius: Number.MAX_VALUE,
                        borderSkipped: false
                    },
                    {
                        label: 'Pengeluaran',
                        data: [],
                        borderColor: '#ef4444',
                        backgroundColor: function(ctx){
                            const chart = ctx.chart; const {ctx: k, chartArea} = chart;
                            if (!chartArea) return 'rgba(239, 68, 68, 0.25)';
                            const g = k.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            g.addColorStop(0, 'rgba(239, 68, 68, 0.6)');
                            g.addColorStop(1, 'rgba(239, 68, 68, 0.1)');
                            return g;
                        },
                        borderWidth: 2,
                        borderRadius: 5,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                events: ['click'],
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1f2937',
                        bodyColor: '#1f2937',
                        borderColor: 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        filter: (item) => item.datasetIndex === 0,
                        callbacks: {
                            title: (contexts) => 'Tanggal: ' + (contexts[0].label || ''),
                            label: (context) => {
                                const idx = context.dataIndex;
                                const inc = (trendChart.$income && trendChart.$income[idx]) || 0;
                                const exp = (trendChart.$expense && trendChart.$expense[idx]) || 0;
                                const net = inc - exp;
                                return [
                                    'Pemasukan: ' + fmtIDR(inc),
                                    'Pengeluaran: ' + fmtIDR(exp),
                                    'Bersih: ' + fmtIDR(net)
                                ];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#6b7280'
                        },
                        title: {
                            display: false,
                            text: 'Jumlah'
                        }
                    }
                },
                animation: { duration: 800, easing: 'easeOutQuart' }
            }
        });

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
                bodyEl.innerHTML = `<div class="tooltip-row"><span class="tooltip-dot" style="background:${dot}"></span><span>${fmtIDR(raw)} (${pct}%)</span></div>`;
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

            tooltipEl.style.transform = `translate(${finalX}px, ${finalY}px)`;
        }

        // Konfigurasi grafik distribusi - SUPER BESAR dan Bulat Sempurna
        const distChart = new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    borderColor: '#ffffff',
                    borderWidth: 5,
                    hoverOffset: 0,
                    borderRadius: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    arc: {
                        backgroundColor: function(context){
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
        distChart.canvas.addEventListener('click', (evt) => {
            const points = distChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
            const center = document.getElementById('totalExpense');
            if (points && points.length) {
                const idx = points[0].index;
                const data = distChart.data.datasets[0].data || [];
                const total = data.reduce((a, b) => a + b, 0);
                const value = data[idx] || 0;
                const pct = total ? Math.round((value / total) * 100) : 0;
                center.textContent = pct + '%';
            } else {
                const fallbackTotal = distChart.$total ?? (distChart.data.datasets[0].data || []).reduce((a, b) => a + b, 0);
                center.textContent = fmtIDR(fallbackTotal || 0);
            }
        });

        function createCustomLegend(labels, data) {
            const percentContainer = document.getElementById('legendPercentContainer');
            const amountContainer = document.getElementById('legendAmountContainer');
            if (percentContainer) percentContainer.innerHTML = '';
            if (amountContainer) amountContainer.innerHTML = '';

            const total = (data || []).reduce((a, b) => a + b, 0);
            const items = (labels || []).map((label, idx) => ({ label, value: (data && data[idx]) ? data[idx] : 0 }));
            const withPct = items
                .map(it => ({ ...it, pct: total ? Math.round((it.value / total) * 100) : 0 }))
                .sort((a, b) => b.pct - a.pct);

            function openLegendModal() {
                let modal = document.getElementById('distLegendModal');
                if (!modal) {
                    modal = document.createElement('div');
                    modal.id = 'distLegendModal';
                    modal.className = 'fade-modal';
                    document.body.appendChild(modal);
                    modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.remove('show'); });
                }
                const rows = withPct.map(({ label, pct }) => {
                    const base = baseColorFor(label);
                    const dotColor = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l + 15)));
                    const pillBg = hsla({ h: base.h, s: base.s, l: Math.max(0, Math.min(100, base.l + 22)) }, 0.18);
                    const pillBorder = hsla(base, 0.35);
                    const pillText = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l - 15)));
                    return `<div style="display:flex;align-items:center;gap:10px;margin:8px 0">
                        <div style="width:10px;height:10px;border-radius:50%;background:${dotColor};flex:0 0 auto"></div>
                        <div style="flex:1;min-width:0;color:#1f2937;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${label}</div>
                        <div style="font-size:.85rem;padding:2px 10px;border-radius:999px;border:1px solid ${pillBorder};background:${pillBg};color:${pillText};font-weight:900;flex:0 0 auto">${pct}%</div>
                    </div>`;
                }).join('');
                modal.innerHTML = '<div class="modal-card">'
                    + '<div style="font-weight:800;margin-bottom:10px;">Semua Kategori Pengeluaran</div>'
                    + `<div style="max-height:60vh;overflow:auto;">${rows}</div>`
                    + '<div style="margin-top:12px;display:flex;justify-content:center">'
                        + '<button class="refresh-btn" id="distLegendCloseBtn">Tutup</button>'
                    + '</div>'
                + '</div>';
                const btnClose = modal.querySelector('#distLegendCloseBtn');
                if (btnClose) btnClose.addEventListener('click', () => { modal.classList.remove('show'); });
                modal.classList.add('show');
            }

            const MAX_PCT_ITEMS = 6;
            const MAX_AMOUNT_ITEMS = 5;
            const topPctList = withPct.slice(0, MAX_PCT_ITEMS);
            const topAmountList = withPct.slice(0, MAX_AMOUNT_ITEMS);

            topPctList.forEach(({ label, value, pct }) => {
                const base = baseColorFor(label);
                const dotColor = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l + 15)));
                const pillBg = hsla({ h: base.h, s: base.s, l: Math.max(0, Math.min(100, base.l + 22)) }, 0.18);
                const pillBorder = hsla(base, 0.35);
                const pillText = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l - 15)));

                if (percentContainer) {
                    const legendItem = document.createElement('div');
                    legendItem.className = 'legend-item-3d legend-item-percent';
                    legendItem.innerHTML = `
                        <div class="legend-left-3d">
                            <div class="legend-color-3d" style="background-color: ${dotColor}"></div>
                            <div class="legend-label-3d">${label}</div>
                        </div>
                        <div class="legend-right-3d">
                            <div class="legend-percent-3d" style="background:${pillBg};border-color:${pillBorder};color:${pillText}">${pct}%</div>
                        </div>
                    `;
                    percentContainer.appendChild(legendItem);
                    legendItem.addEventListener('click', () => {
                        const center = document.getElementById('totalExpense');
                        center.textContent = pct + '%';
                    });
                }
            });

            topAmountList.forEach(({ label, value, pct }) => {
                const base = baseColorFor(label);
                const dotColor = hsl(base.h, base.s, Math.max(0, Math.min(100, base.l + 15)));

                if (!amountContainer) return;
                const tile = document.createElement('div');
                tile.className = 'legend-item-3d legend-item-amount';
                tile.innerHTML = `
                    <div class="legend-left-3d">
                        <div class="legend-color-3d" style="background-color: ${dotColor}"></div>
                        <div class="legend-label-3d">${label}</div>
                    </div>
                    <div class="legend-right-3d">
                        <div class="legend-amount-3d">${fmtIDR(value)}</div>
                    </div>
                `;
                amountContainer.appendChild(tile);
                tile.addEventListener('click', () => {
                    const center = document.getElementById('totalExpense');
                    center.textContent = fmtIDR(value);
                });
            });

            if (percentContainer && withPct.length > MAX_PCT_ITEMS) {
                const btn = document.createElement('button');
                btn.className = 'view-all-btn view-all-btn-compact';
                btn.textContent = 'Lihat Semua';
                btn.addEventListener('click', openLegendModal);
                percentContainer.appendChild(btn);
            }
        }

        async function refresh(period, start, end){
            const sum = await fetchSummary(period, start, end);
            setSummary(sum);
            const today = new Date();
            const month = today.getMonth()+1;
            const inc = await fetchStats('income','daily', month);
            const exc = await fetchStats('expense','daily', month);
            const incLabels = (inc.labels || []).map(l => String(l));
            const excLabels = (exc.labels || []).map(l => String(l));
            const incData = inc.data || [];
            const excData = exc.data || [];
            const year = today.getFullYear();
            const daysInMonth = new Date(year, month, 0).getDate();
            const baseLabels = Array.from({length: daysInMonth}, (_,i) => String(i+1));
            const incMap = {};
            incLabels.forEach((l,i) => { incMap[String(l)] = incData[i] || 0; });
            const excMap = {};
            excLabels.forEach((l,i) => { excMap[String(l)] = excData[i] || 0; });
            const d = today.getDate();
            let lbl = baseLabels.slice(0, d);
            if (period === 'week'){
                const sliceStart = Math.max(0, d-7);
                lbl = baseLabels.slice(sliceStart, d);
            } else if (period === 'custom' && start && end){
                const sd = new Date(start).getDate();
                const ed = new Date(end).getDate();
                lbl = baseLabels.slice(sd-1, ed);
            }
            const di = lbl.map(l => incMap[l] || 0);
            const de = lbl.map(l => excMap[l] || 0);
            const net = di.map((v,i) => (v||0) - ((de && de[i])||0));
            trendChart.data.labels = lbl;
            trendChart.data.datasets[0].data = di;
            trendChart.data.datasets[1].data = de;
            trendChart.$income = di;
            trendChart.$expense = de;
            trendChart.update();
            const dist = await fetchDistribution(sum.range[0], sum.range[1]);
            distChart.data.labels = dist.labels || [];
            distChart.data.datasets[0].data = dist.totals || [];
            distChart.update();

            // Perbarui total pengeluaran di tengah grafik
            const totalExpense = (dist.totals || []).reduce((a, b) => a + b, 0);
            document.getElementById('totalExpense').textContent = fmtIDR(totalExpense);
            distChart.$total = totalExpense;

            // Buat legend kustom dengan 3 grid
            createCustomLegend(
                dist.labels || [],
                dist.totals || []
            );
        }

        const MONTH_NAMES = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        const YEARS_LIST = (() => { const arr=[]; const now=new Date().getFullYear(); for(let y=now; y>=2020; y--) arr.push(y); return arr; })();
        const customFilterModal = document.createElement('div');
        customFilterModal.id = 'customFilterModal';
        customFilterModal.className = 'fade-modal';
        document.body.appendChild(customFilterModal);
        function fmtDate(y,m,d){ const mm=String(m).padStart(2,'0'); const dd=String(d).padStart(2,'0'); return `${y}-${mm}-${dd}`; }
        function openCustomFilterModal(triggerBtn){
            const now = new Date();
            let cfMode = 'range';
            let selectedMonth = now.getMonth()+1;
            let selectedYear = now.getFullYear();
            let selectedDailyDate = '';
            customFilterModal.innerHTML = '<div class="modal-card">'+
                '<div style="font-weight:700;margin-bottom:12px;display:flex;align-items:center;gap:10px;">'+
                    '<i class="fa-solid fa-filter" style="color:var(--accent)"></i>'+
                    '<span>Filter Kustom</span>'+
                '</div>'+
                '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:10px;">'+
                    '<button id="cfDaily" class="filter-btn" style="padding:8px;"><i class="fa-regular fa-calendar" style="margin-right:6px;"></i>Harian</button>'+
                    '<button id="cfMonthly" class="filter-btn" style="padding:8px;"><i class="fa-solid fa-calendar-days" style="margin-right:6px;"></i>Bulanan</button>'+
                    '<button id="cfRange" class="filter-btn" style="padding:8px;"><i class="fa-solid fa-arrows-left-right" style="margin-right:6px;"></i>Rentang</button>'+
                '</div>'+
                '<div id="cfPanel"></div>'+
                '<div style="display:flex;gap:8px;justify-content:flex-end;margin-top:12px;">'+
                    '<button id="cfCancel" class="filter-btn" style="padding:8px;">Batal</button>'+
                    '<button id="cfApply" class="filter-btn" style="padding:8px;background:#4f46e5;color:#fff;border-color:#4f46e5;">Terapkan</button>'+
                '</div>'+
            '</div>';
            customFilterModal.classList.add('show');
            const panel = customFilterModal.querySelector('#cfPanel');
            const tileBase = 'background:rgba(255,255,255,0.7);color:#1f2937;border:1px solid rgba(0,0,0,0.1);border-radius:8px;padding:8px;';
            const tileHighlight = 'background:rgba(79, 70, 229, 0.1);color:#4f46e5;border:1px solid rgba(79, 70, 229, 0.3);border-radius:8px;padding:8px;';
            function renderDaily(){
                let curY = now.getFullYear();
                let curM = now.getMonth()+1;
                const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                const weekdays = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                function drawCalendar(year, month){
                    const first = new Date(year, month-1, 1);
                    const last = new Date(year, month, 0);
                    const days = last.getDate();
                    const startWeekday = (first.getDay() + 6) % 7;
                    const cells = [];
                    for (let i=0;i<startWeekday;i++) cells.push('');
                    for (let d=1; d<=days; d++) cells.push(String(d));
                    const today = new Date();
                    const isCurrentMonth = (year===today.getFullYear() && month===(today.getMonth()+1));
                    const header = '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">'
                        +'<button id="cfCalPrev" class="action-btn" style="background:rgba(255,255,255,0.7);color:#1f2937;padding:6px 10px;border-radius:8px">‹</button>'
                        +`<div style="font-weight:700;color:#1f2937">${monthNames[month-1]} ${year}</div>`
                        +'<button id="cfCalNext" class="action-btn" style="background:rgba(255,255,255,0.7);color:#1f2937;padding:6px 10px;border-radius:8px">›</button>'
                        +'</div>';
                    const weekHeader = '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px;margin-bottom:6px">'
                        +weekdays.map(w => `<div style="text-align:center;color:#6b7280;font-weight:600">${w}</div>`).join('')
                        +'</div>';
                    const grid = cells.map(val => {
                        if (!val) return '<div></div>';
                        const isToday = (isCurrentMonth && parseInt(val,10)===today.getDate());
                        const highlight = isToday ? tileHighlight : tileBase;
                        return `<button data-day="${val}" class="calendar-day" style="${highlight}">${val}</button>`;
                    }).join('');
                    panel.innerHTML = header + weekHeader + '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px">'+ grid +'</div>';
                    const prev = panel.querySelector('#cfCalPrev');
                    const next = panel.querySelector('#cfCalNext');
                    if (prev) prev.addEventListener('click', () => { curM -= 1; if (curM < 1) { curM = 12; curY -= 1; } drawCalendar(curY, curM); });
                    if (next) next.addEventListener('click', () => { curM += 1; if (curM > 12) { curM = 1; curY += 1; } drawCalendar(curY, curM); });
                    panel.querySelectorAll('button[data-day]').forEach(b => {
                        b.addEventListener('click', () => {
                            const dd = parseInt(b.getAttribute('data-day'),10);
                            const mm = String(curM).padStart(2,'0');
                            const dds = String(dd).padStart(2,'0');
                            const start = `${curY}-${mm}-${dds}`;
                            const end = start;
                            selectedDailyDate = start;
                            customFilterModal.classList.remove('show');
                            refresh('custom', start, end);
                            triggerBtn.classList.add('active');
                        });
                    });
                }
                drawCalendar(curY, curM);
            }
            function renderMonthly(){
                const monthGrid = MONTH_NAMES.map((m,i)=>`<button data-month="${i+1}" class="filter-btn" style="${(i+1)===selectedMonth?tileHighlight:tileBase}">${m}</button>`).join('');
                const yearGrid = YEARS_LIST.map(y=>`<button data-year="${y}" class="filter-btn" style="${y===selectedYear?tileHighlight:tileBase}">${y}</button>`).join('');
                panel.innerHTML = '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">'+monthGrid+'</div>'+
                    '<div style="height:1px;background:rgba(0,0,0,0.1);margin:12px 0;"></div>'+
                    '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">'+yearGrid+'</div>';
                panel.querySelectorAll('button[data-month]').forEach(b=>{
                    b.addEventListener('click',()=>{ selectedMonth = parseInt(b.getAttribute('data-month')); renderMonthly(); });
                });
                panel.querySelectorAll('button[data-year]').forEach(b=>{
                    b.addEventListener('click',()=>{ selectedYear = parseInt(b.getAttribute('data-year')); renderMonthly(); });
                });
            }
            function renderRange(){
                panel.innerHTML =
                    '<div style="display:flex;gap:10px;align-items:center;">'+
                        '<div style="flex:1;background:rgba(255,255,255,0.7);border:1px solid rgba(0,0,0,0.1);border-radius:10px;padding:10px;display:flex;align-items:center;gap:8px;">'+
                            '<i class="fa-regular fa-calendar" style="color:#6b7280"></i>'+
                            '<div style="flex:1;display:flex;flex-direction:column;gap:4px">'+
                                '<div style="font-size:0.8rem;color:#6b7280">Mulai</div>'+
                                '<input type="date" id="cfStart" style="border:none;outline:none;background:transparent;color:#1f2937;" />'+
                            '</div>'+
                        '</div>'+
                        '<span style="color:#6b7280;">–</span>'+
                        '<div style="flex:1;background:rgba(255,255,255,0.7);border:1px solid rgba(0,0,0,0.1);border-radius:10px;padding:10px;display:flex;align-items:center;gap:8px;">'+
                            '<i class="fa-regular fa-calendar" style="color:#6b7280"></i>'+
                            '<div style="flex:1;display:flex;flex-direction:column;gap:4px">'+
                                '<div style="font-size:0.8rem;color:#6b7280">Selesai</div>'+
                                '<input type="date" id="cfEnd" style="border:none;outline:none;background:transparent;color:#1f2937;" />'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div id="cfPreview" style="margin-top:10px;font-size:0.9rem;color:#1f2937"></div>'+
                    '<div id="cfErr" style="margin-top:6px;font-size:0.85rem;color:#ef4444"></div>';

                function setRange(start,end){
                    const s = panel.querySelector('#cfStart');
                    const e = panel.querySelector('#cfEnd');
                    s.value = start; e.value = end;
                    panel.querySelector('#cfPreview').textContent = (start && end) ? `Rentang: ${start} — ${end}` : 'Pilih tanggal mulai dan selesai.';
                    panel.querySelector('#cfErr').textContent = '';
                }
                const s = panel.querySelector('#cfStart');
                const e = panel.querySelector('#cfEnd');
                [s,e].forEach(inp=>{
                    inp.addEventListener('change',()=>{
                        const sv = s.value; const ev = e.value;
                        if (sv && ev){
                            if (sv > ev){ panel.querySelector('#cfErr').textContent = 'Tanggal mulai tidak boleh setelah tanggal selesai.'; }
                            else { panel.querySelector('#cfErr').textContent = ''; panel.querySelector('#cfPreview').textContent = `Rentang: ${sv} — ${ev}`; }
                        } else {
                            panel.querySelector('#cfErr').textContent = '';
                            panel.querySelector('#cfPreview').textContent = 'Pilih tanggal mulai dan selesai.';
                        }
                    });
                });
                setRange('', '');
            }
            function setActiveToggle(d,m,r){
                d.classList.remove('active'); m.classList.remove('active'); r.classList.remove('active');
            }
            const dBtn = customFilterModal.querySelector('#cfDaily');
            const mBtn = customFilterModal.querySelector('#cfMonthly');
            const rBtn = customFilterModal.querySelector('#cfRange');
            rBtn.classList.add('active'); renderRange();
            dBtn.addEventListener('click',()=>{ cfMode='daily'; setActiveToggle(dBtn,mBtn,rBtn); dBtn.classList.add('active'); renderDaily(); });
            mBtn.addEventListener('click',()=>{ cfMode='monthly'; setActiveToggle(dBtn,mBtn,rBtn); mBtn.classList.add('active'); renderMonthly(); });
            rBtn.addEventListener('click',()=>{ cfMode='range'; setActiveToggle(dBtn,mBtn,rBtn); rBtn.classList.add('active'); renderRange(); });
            customFilterModal.querySelector('#cfCancel').addEventListener('click',()=>{ customFilterModal.classList.remove('show'); });
            customFilterModal.querySelector('#cfApply').addEventListener('click',()=>{
                let start='', end='';
                if (cfMode==='daily'){
                    if (!selectedDailyDate) return;
                    start = selectedDailyDate;
                    end = selectedDailyDate;
                } else if (cfMode==='monthly'){
                    const last = new Date(selectedYear, selectedMonth, 0).getDate();
                    start = fmtDate(selectedYear, selectedMonth, 1);
                    end = fmtDate(selectedYear, selectedMonth, last);
                } else {
                    start = customFilterModal.querySelector('#cfStart').value; end = customFilterModal.querySelector('#cfEnd').value; if (!start || !end) return;
                }
                customFilterModal.classList.remove('show');
                refresh('custom', start, end);
                triggerBtn.classList.add('active');
            });
            customFilterModal.addEventListener('click',(e)=>{ if (e.target === customFilterModal) customFilterModal.classList.remove('show'); });
        }
        document.querySelectorAll('.filter-btn[data-period]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn[data-period]').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const pr = btn.getAttribute('data-period');
                if (pr==='custom') { document.getElementById('customRange').style.display = 'none'; openCustomFilterModal(btn); return; }
                document.getElementById('customRange').style.display = 'none';
                refresh(pr);
            });
        });
        document.getElementById('applyCustom').addEventListener('click', () => {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            if (start && end) {
                refresh('custom', start, end);
            }
        });

        // Logika Filter & Refresh untuk Histori Transaksi (samakan dengan dashboard)
        (function initHistoryControls(){
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
                if (window.applyHistoryLimit) window.applyHistoryLimit();
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
                const tileBase = 'background:rgba(255,255,255,0.7);color:#1f2937;border:1px solid rgba(0,0,0,0.1);border-radius:8px;padding:8px;';
                const tileHighlight = 'background:rgba(79, 70, 229, 0.1);color:#4f46e5;border:1px solid rgba(79, 70, 229, 0.3);';
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
                    const startWeekday = (first.getDay() + 6) % 7;
                    const cells = [];
                    for (let i=0;i<startWeekday;i++) cells.push('');
                    for (let d=1; d<=days; d++) cells.push(String(d));
                    const today = new Date();
                    const isCurrentMonth = (year===today.getFullYear() && month===(today.getMonth()+1));
                    const weekdays = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                    const header = `<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">`+
                        `<button id="hfCalPrev" class="action-btn" style="background:rgba(255,255,255,0.7);color:#1f2937;padding:6px 10px;border-radius:8px">‹</button>`+
                        `<div style="font-weight:700;color:#1f2937">${monthNames[month-1]} ${year}</div>`+
                        `<button id="hfCalNext" class="action-btn" style="background:rgba(255,255,255,0.7);color:#1f2937;padding:6px 10px;border-radius:8px">›</button>`+
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
                            const d = fmt(year, month, day);
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
            if (historyFilterBtn) historyFilterBtn.addEventListener('click', openHistoryFilterModal);
            if (historyRefreshBtn) historyRefreshBtn.addEventListener('click', () => {
                document.querySelectorAll('.history-table tbody tr').forEach(r => { r.style.display = ''; });
                historyFilterActive = false;
                if (historyFilterBtn) historyFilterBtn.classList.remove('active');
                if (window.applyHistoryLimit) window.applyHistoryLimit();
            });
        })();

        (function initHistoryLimit(){
            const viewAllBtn = document.getElementById('viewAllBtn');
            const historyRows = Array.from(document.querySelectorAll('.history-table tbody tr'))
                .filter(r => r.getAttribute('data-date'));
            const HISTORY_LIMIT = 10;
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
        })();

        // Inisialisasi dengan periode bulan ini
        refresh('month');
    </script>
    <x-bottom-nav />
</body>
</html>
