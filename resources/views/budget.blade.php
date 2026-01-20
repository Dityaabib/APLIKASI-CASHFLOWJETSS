<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Budget - {{ config('app.name', 'CashFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a9cb0;
            --primary-2: #0f7b8a;
            --bg: #f8fafc;
            --text: #0f172a;
            --muted: #64748b;
            --card: rgba(255,255,255,0.92);
            --border: rgba(15, 23, 42, 0.08);
            --shadow: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
        }

        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        body {
            padding-top: 70px;
            padding-bottom: 76px;
            margin: 0;
            background: radial-gradient(1200px 800px at 10% 10%, #ffffff 0%, #f7fafc 45%, #eef2f7 100%);
            color: var(--text);
        }

        .theme-dark body {
            background: radial-gradient(1200px 800px at 10% 10%, rgba(15,23,42,1) 0%, rgba(17,24,39,1) 60%, rgba(2,6,23,1) 100%);
            color: #e5e7eb;
        }

        .container {
            width: 100%;
            max-width: none;
            margin: 0 auto;
            padding: 5px;
        }

        @media (min-width: 768px) {
            .container { padding: 5px; }
        }

        @media (min-width: 1280px) {
            .container { padding: 5px; }
        }

        .page-title {
            font-size: 1.55rem;
            font-weight: 900;
            letter-spacing: -0.02em;
            margin-top: 8px;
            line-height: 1.15;
        }

        .page-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.35;
        }

        .theme-dark .page-subtitle { color: rgba(226,232,240,0.72); }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .theme-dark .card {
            background: rgba(17,24,39,0.78);
            border-color: rgba(255,255,255,0.08);
            box-shadow: 0 12px 24px rgba(0,0,0,0.28);
        }

        .summary {
            margin-top: 14px;
            padding: 16px;
            border-radius: 16px;
            width: 100%;
            position: relative;
        }

        @media (min-width: 768px) {
            .summary {
                border-radius: 16px;
                width: 100%;
            }
        }

        .summary-top {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 12px;
            align-items: start;
        }

        @media (max-width: 560px) {
            .summary-top { grid-template-columns: minmax(0, 1fr); }
        }

        .summary-main { min-width: 0; }
        .summary-head {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }
        .summary-head-text { min-width: 0; }
        .summary-head .percent-circle { justify-self: end; }

        .summary-label {
            font-weight: 800;
            color: rgba(15,23,42,0.84);
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .theme-dark .summary-label { color: rgba(226,232,240,0.9); }

        .summary-amount {
            margin-top: 4px;
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .summary-sub {
            margin-top: 8px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-right: 4px;
            scrollbar-width: none;
        }

        .summary-sub::-webkit-scrollbar { display: none; }

        .theme-dark .summary-sub { color: rgba(226,232,240,0.7); }

        .summary-chip {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            border-radius: 5px;
            background: rgba(255,255,255,0.72);
            border: 1px solid rgba(15, 23, 42, 0.08);
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 10px 18px rgba(15,23,42,0.06);
        }

        .theme-dark .summary-chip {
            border-color: rgba(255,255,255,0.10);
            background: rgba(2,6,23,0.18);
        }

        .summary-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 0;
            justify-content: flex-start;
            min-width: 0;
        }

        .summary-badge > div { display: contents; }

        .badge-pill,
        .badge-note { justify-self: end; }

        .theme-dark .summary-badge > div {
            border-color: rgba(255,255,255,0.08);
            background: rgba(2,6,23,0.18);
        }

        @media (max-width: 420px) {
            .summary-badge { justify-content: flex-start; }
            .badge-note { text-align: left; }
        }

        .percent-circle {
            width: 42px;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.82rem;
            color: rgba(15,23,42,0.92);
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 10px 18px rgba(15,23,42,0.06);
            background: radial-gradient(120% 120% at 30% 30%, rgba(255,255,255,1) 0%, rgba(250,250,250,1) 45%, rgba(245,247,250,1) 100%);
        }

        .percent-circle.badge-bad {
            color: #dc2626;
            border-color: rgba(239,68,68,0.22);
            background: radial-gradient(120% 120% at 30% 30%, rgba(255,255,255,1) 0%, rgba(255,240,240,1) 40%, rgba(255,228,228,1) 100%);
        }

        .percent-circle.badge-neutral {
            color: rgba(15,23,42,0.85);
            border-color: rgba(100,116,139,0.22);
            background: radial-gradient(120% 120% at 30% 30%, rgba(255,255,255,1) 0%, rgba(246,248,250,1) 45%, rgba(236,240,244,1) 100%);
        }

        .theme-dark .percent-circle {
            color: rgba(226,232,240,0.92);
            border-color: rgba(255,255,255,0.10);
            background: radial-gradient(120% 120% at 30% 30%, rgba(31,41,55,1) 0%, rgba(17,24,39,1) 55%, rgba(2,6,23,1) 100%);
        }

        .theme-dark .percent-circle.badge-bad {
            color: rgba(252,165,165,1);
            border-color: rgba(239,68,68,0.28);
            background: radial-gradient(120% 120% at 30% 30%, rgba(55,20,20,1) 0%, rgba(39,12,12,1) 55%, rgba(24,8,8,1) 100%);
        }

        .theme-dark .percent-circle.badge-neutral {
            color: rgba(226,232,240,0.85);
            border-color: rgba(148,163,184,0.22);
            background: radial-gradient(120% 120% at 30% 30%, rgba(38,48,61,1) 0%, rgba(24,33,45,1) 55%, rgba(12,18,28,1) 100%);
        }

        .summary-note {
            margin-top: 6px;
            color: var(--muted);
            font-weight: 700;
            font-size: 0.86rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .theme-dark .summary-note { color: rgba(226,232,240,0.72); }

        .summary-progress {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
        }

        .theme-dark .summary-progress { border-top-color: rgba(255,255,255,0.08); }

        .summary-duo {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-left: -18px;
            margin-right: -18px;
        }

        .theme-dark .summary-duo { border-top-color: rgba(255,255,255,0.08); }

        @media (max-width: 420px) {
            .summary-duo { grid-template-columns: 1fr; }
        }

        .duo-panel {
            --accent: rgba(37,99,235,1);
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.06);
            background: rgba(255,255,255,0.75);
            padding: 12px;
            overflow: hidden;
            position: relative;
            isolation: isolate;
        }

        .duo-panel::before {
            content: "";
            position: absolute;
            inset: -1px;
            background:
                radial-gradient(520px 140px at 0% 0%,
                    color-mix(in srgb, var(--accent) 22%, transparent 78%) 0%,
                    transparent 60%),
                radial-gradient(520px 140px at 100% 0%,
                    color-mix(in srgb, var(--accent) 14%, transparent 86%) 0%,
                    transparent 60%);
            opacity: 0.9;
            z-index: 0;
        }

        .duo-panel > * { position: relative; z-index: 1; }

        .duo-panel.usage-panel { --accent: rgba(37,99,235,1); }
        .duo-panel.remaining-panel { --accent: rgba(16,185,129,1); }

        .theme-dark .duo-panel {
            background: rgba(2,6,23,0.25);
            border-color: rgba(255,255,255,0.08);
        }

        .duo-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .duo-title {
            font-weight: 900;
            font-size: 0.9rem;
            color: rgba(15,23,42,0.86);
            letter-spacing: -0.01em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .theme-dark .duo-title { color: rgba(226,232,240,0.9); }

        .duo-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 9999px;
            font-weight: 900;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .duo-chip.usage {
            background: rgba(37,99,235,0.12);
            color: rgba(37,99,235,1);
            border: 1px solid rgba(37,99,235,0.22);
        }

        .theme-dark .duo-chip.usage {
            background: rgba(37,99,235,0.18);
            border-color: rgba(147,197,253,0.22);
            color: rgba(191,219,254,1);
        }

        .duo-chip.remaining {
            background: rgba(16,185,129,0.12);
            color: rgba(5,150,105,1);
            border: 1px solid rgba(16,185,129,0.22);
        }

        .theme-dark .duo-chip.remaining {
            background: rgba(16,185,129,0.18);
            border-color: rgba(110,231,183,0.22);
            color: rgba(110,231,183,1);
        }

        .duo-track {
            height: 10px;
            border-radius: 9999px;
            margin-top: 12px;
            background: rgba(148,163,184,0.28);
            overflow: hidden;
        }

        .theme-dark .duo-track { background: rgba(148,163,184,0.18); }

        .duo-fill {
            height: 100%;
            border-radius: 9999px;
            width: 0%;
        }

        .duo-fill.usage {
            background: linear-gradient(90deg, rgba(96,165,250,1) 0%, rgba(37,99,235,1) 100%);
        }

        .duo-fill.remaining {
            background: linear-gradient(90deg, rgba(52,211,153,1) 0%, rgba(16,185,129,1) 100%);
        }

        .duo-foot {
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--muted);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .theme-dark .duo-foot { color: rgba(226,232,240,0.62); }

        .duo-ring-wrap {
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .duo-ring {
            --p: 0;
            --ring: rgba(16,185,129,1);
            width: 74px;
            height: 74px;
            border-radius: 9999px;
            background: conic-gradient(var(--ring) calc(var(--p) * 1%), rgba(148,163,184,0.22) 0);
            display: grid;
            place-items: center;
        }

        .theme-dark .duo-ring {
            background: conic-gradient(var(--ring) calc(var(--p) * 1%), rgba(148,163,184,0.16) 0);
        }

        .duo-ring-inner {
            width: 56px;
            height: 56px;
            border-radius: 9999px;
            background: rgba(255,255,255,0.86);
            border: 1px solid rgba(15, 23, 42, 0.06);
            display: grid;
            place-items: center;
            font-weight: 900;
            color: rgba(15,23,42,0.86);
            letter-spacing: -0.01em;
        }

        .theme-dark .duo-ring-inner {
            background: rgba(2,6,23,0.22);
            border-color: rgba(255,255,255,0.08);
            color: rgba(226,232,240,0.92);
        }

        .progress-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .progress-label {
            font-weight: 900;
            font-size: 0.9rem;
            color: rgba(15,23,42,0.78);
        }

        .theme-dark .progress-label { color: rgba(226,232,240,0.82); }

        .progress-value {
            font-weight: 900;
            font-size: 0.9rem;
            color: rgba(15,23,42,0.82);
        }

        .theme-dark .progress-value { color: rgba(226,232,240,0.9); }

        .progress-track {
            height: 10px;
            border-radius: 9999px;
            margin-top: 10px;
            background: rgba(148,163,184,0.28);
            overflow: hidden;
        }

        .theme-dark .progress-track { background: rgba(148,163,184,0.18); }

        .progress-fill {
            height: 100%;
            border-radius: 9999px;
            background: linear-gradient(90deg, rgba(96,165,250,1) 0%, rgba(37,99,235,1) 100%);
            width: 0%;
        }

        .budget-bar-row { position: relative; }
        .bar-center-label {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 2px 8px;
            border-radius: 9999px;
            font-weight: 800;
            font-size: 0.72rem;
            color: rgba(15,23,42,0.88);
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 6px 10px rgba(15,23,42,0.06);
            pointer-events: none;
            white-space: nowrap;
        }
        .theme-dark .bar-center-label {
            color: rgba(226,232,240,0.92);
            background: rgba(17,24,39,0.64);
            border-color: rgba(255,255,255,0.10);
            box-shadow: 0 8px 16px rgba(0,0,0,0.28);
        }

        .summary-grid {
            margin-top: 14px;
            display: grid;
            gap: 5px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-left: -18px;
            margin-right: -18px;
        }

        @media (min-width: 640px) {
            .summary-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (min-width: 1024px) {
            .summary-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        .metric {
            --metric: #3b82f6;
            border-radius: 14px;
            padding: 12px 8px 10px;
            border: 1px solid rgba(15, 23, 42, 0.06);
            background: rgba(255,255,255,0.75);
            text-align: center;
        }

        .theme-dark .metric {
            background: rgba(2,6,23,0.25);
            border-color: rgba(255,255,255,0.08);
        }

        .metric.danger { --metric: #ef4444; }
        .metric.success { --metric: #10b981; }
        .metric.neutral { --metric: #64748b; }

        .metric-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
            min-width: 0;
        }

        .metric-label {
            font-weight: 900;
            font-size: 0.75rem;
            color: rgba(15,23,42,0.72);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 0;
        }

        .theme-dark .metric-label { color: rgba(226,232,240,0.72); }

        .metric-ico {
            width: 28px;
            height: 28px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
            background: linear-gradient(135deg,
                color-mix(in srgb, var(--metric) 72%, #ffffff 28%) 0%,
                color-mix(in srgb, var(--metric) 90%, #000000 10%) 100%
            );
            box-shadow: 0 10px 18px color-mix(in srgb, var(--metric) 30%, transparent 70%);
        }

        .metric-value {
            margin-top: 6px;
            font-weight: 900;
            font-size: 0.9rem;
            letter-spacing: -0.01em;
        }

        .metric-sub {
            margin-top: 2px;
            font-weight: 800;
            font-size: 0.7rem;
            color: var(--muted);
        }

        .theme-dark .metric-sub { color: rgba(226,232,240,0.62); }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 9999px;
            background: rgba(16,185,129,0.12);
            color: #059669;
            font-weight: 800;
            font-size: 0.85rem;
            border: 1px solid rgba(16,185,129,0.22);
            white-space: nowrap;
        }

        .theme-dark .badge-pill {
            background: rgba(16,185,129,0.18);
            border-color: rgba(16,185,129,0.28);
            color: rgba(110,231,183,1);
        }

        .badge-pill.badge-bad {
            background: rgba(239,68,68,0.12);
            border-color: rgba(239,68,68,0.22);
            color: #dc2626;
        }

        .theme-dark .badge-pill.badge-bad {
            background: rgba(239,68,68,0.18);
            border-color: rgba(239,68,68,0.28);
            color: rgba(252,165,165,1);
        }

        .badge-pill.badge-neutral {
            background: rgba(100,116,139,0.12);
            border-color: rgba(100,116,139,0.22);
            color: rgba(15,23,42,0.8);
        }

        .theme-dark .badge-pill.badge-neutral {
            background: rgba(148,163,184,0.14);
            border-color: rgba(148,163,184,0.22);
            color: rgba(226,232,240,0.85);
        }

        .badge-note {
            color: var(--muted);
            font-weight: 600;
            font-size: 0.78rem;
            line-height: 1.25;
            text-align: right;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .theme-dark .badge-note { color: rgba(226,232,240,0.7); }

        .toggle {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 5px;
            margin-top: 14px;
        }

        .toggle-btn {
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.8);
            border-radius: 10px;
            padding: 12px 16px;
            min-height: 44px;
            font-weight: 700;
            font-size: 1rem;
            color: rgba(0, 0, 0, 0.72);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            transition: background 80ms linear, color 80ms linear, border-color 80ms linear, box-shadow 80ms linear;
        }

        .toggle-btn i { line-height: 1; }

        .theme-dark .toggle-btn {
            background: rgba(17,24,39,0.64);
            border-color: rgba(255,255,255,0.1);
            color: rgba(226,232,240,0.8);
        }

        .toggle-btn.active {
            background: linear-gradient(135deg, rgba(96,165,250,1) 0%, rgba(37,99,235,1) 100%);
            border-color: rgba(37,99,235,0.45);
            color: #ffffff;
            box-shadow: 0 10px 18px rgba(37,99,235,0.22);
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.45);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 60;
        }

        .modal-overlay.show { display: flex; }

        .modal {
            width: 100%;
            max-width: 560px;
            background: rgba(255,255,255,0.96);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 24px 48px rgba(0,0,0,0.18);
            overflow: hidden;
        }

        .theme-dark .modal {
            background: rgba(17,24,39,0.92);
            border-color: rgba(255,255,255,0.12);
            box-shadow: 0 24px 48px rgba(0,0,0,0.45);
        }

        .modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            padding: 14px;
            border-bottom: 1px solid rgba(15,23,42,0.06);
        }

        .theme-dark .modal-head { border-bottom-color: rgba(255,255,255,0.08); }

        .modal-title {
            font-weight: 900;
            font-size: 1.05rem;
            letter-spacing: -0.01em;
        }

        .modal-sub {
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.88rem;
            font-weight: 600;
        }

        .theme-dark .modal-sub { color: rgba(226,232,240,0.65); }

        .modal-close {
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.75);
            color: rgba(15,23,42,0.72);
            border-radius: 12px;
            padding: 8px 10px;
            font-weight: 900;
            line-height: 1;
        }

        .theme-dark .modal-close {
            background: rgba(2,6,23,0.28);
            border-color: rgba(255,255,255,0.12);
            color: rgba(226,232,240,0.82);
        }

        .modal-body { padding: 14px; }

        .form-grid { display: grid; gap: 10px; }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .field-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 800;
            color: rgba(15,23,42,0.7);
            margin-bottom: 6px;
        }

        .theme-dark .field-label { color: rgba(226,232,240,0.72); }

        .field {
            width: 100%;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.85);
            border-radius: 12px;
            padding: 10px 12px;
            font-weight: 700;
            color: rgba(15,23,42,0.86);
            outline: none;
        }

        .theme-dark .field {
            background: rgba(2,6,23,0.28);
            border-color: rgba(255,255,255,0.12);
            color: rgba(226,232,240,0.9);
        }

        .modal-actions {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: center;
            margin-top: 12px;
        }

        .btn-primary {
            border: 1px solid rgba(37,99,235,0.35);
            background: linear-gradient(135deg, rgba(96,165,250,1) 0%, rgba(37,99,235,1) 100%);
            color: #ffffff;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 900;
            letter-spacing: -0.01em;
            box-shadow: 0 12px 20px rgba(37,99,235,0.18);
            white-space: nowrap;
        }

        .theme-dark .btn-primary {
            border-color: rgba(147,197,253,0.35);
            box-shadow: 0 14px 26px rgba(0,0,0,0.32);
        }

        .btn-ghost {
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.75);
            color: rgba(15,23,42,0.72);
            border-radius: 12px;
            padding: 10px 12px;
            font-weight: 900;
            text-decoration: none;
            text-align: center;
        }

        .theme-dark .btn-ghost {
            background: rgba(17,24,39,0.64);
            border-color: rgba(255,255,255,0.12);
            color: rgba(226,232,240,0.82);
        }

        .list-card {
            margin-top: 14px;
            padding: 14px;
        }

        .section-title {
            font-weight: 900;
            font-size: 1.05rem;
            letter-spacing: -0.01em;
        }

        .section-sub {
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.88rem;
            font-weight: 600;
        }

        .theme-dark .section-sub { color: rgba(226,232,240,0.65); }

        .budget-list { margin-top: 12px; display: grid; gap: 7px; }

        .budget-item {
            --bar-color: #36a2eb;
            display: grid;
            grid-template-columns: 40px minmax(0, 1fr) auto;
            column-gap: 12px;
            row-gap: 8px;
            align-items: center;
            padding: 12px;
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.06);
            background: rgba(255,255,255,0.75);
        }

        .budget-item.unconfigured {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .theme-dark .budget-item {
            background: rgba(2,6,23,0.25);
            border-color: rgba(255,255,255,0.08);
        }

        .theme-dark .budget-item.unconfigured {
            background: rgba(120,53,15,0.22);
            border-color: rgba(251,191,36,0.25);
        }

        .budget-icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg,
                color-mix(in srgb, var(--bar-color) 72%, #ffffff 28%) 0%,
                color-mix(in srgb, var(--bar-color) 90%, #000000 10%) 100%
            );
            box-shadow: 0 10px 18px color-mix(in srgb, var(--bar-color) 30%, transparent 70%);
            grid-row: 1 / span 2;
        }

        .budget-item.unconfigured .budget-icon {
            color: #7c2d12;
            background: linear-gradient(135deg, #fde68a 0%, #f59e0b 100%);
            box-shadow: 0 10px 18px rgba(245, 158, 11, 0.28);
        }

        .budget-main { min-width: 0; }

        .budget-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-width: 0;
        }

        .budget-label {
            font-weight: 900;
            font-size: 0.95rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .budget-meta {
            text-align: right;
            min-width: 0;
            align-self: start;
        }

        .budget-bar-row {
            grid-column: 2 / 4;
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .budget-percent {
            flex: 0 0 auto;
            text-align: right;
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--muted);
            white-space: nowrap;
        }

        .theme-dark .budget-percent { color: rgba(226,232,240,0.62); }

        .budget-amount {
            font-weight: 900;
            font-size: 0.9rem;
            color: rgba(15,23,42,0.9);
        }

        .theme-dark .budget-amount { color: rgba(226,232,240,0.92); }

        .budget-note {
            margin-top: 2px;
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--muted);
        }

        .theme-dark .budget-note { color: rgba(226,232,240,0.62); }

        .bar-track {
            flex: 1;
            height: 9px;
            border-radius: 9999px;
            background: rgba(148,163,184,0.28);
            overflow: hidden;
        }

        .theme-dark .bar-track { background: rgba(148,163,184,0.18); }

        .bar-fill {
            height: 100%;
            border-radius: 9999px;
            background: linear-gradient(90deg, color-mix(in srgb, var(--bar-color) 84%, #ffffff 16%) 0%, var(--bar-color) 100%);
            width: 0%;
            box-shadow: 0 10px 18px color-mix(in srgb, var(--bar-color) 26%, transparent 74%);
        }

        .budget-item.unconfigured .bar-fill {
            background: linear-gradient(90deg, #fcd34d 0%, #f59e0b 100%);
            box-shadow: 0 10px 18px rgba(245, 158, 11, 0.26);
        }

        .trend-up {
            color: #ef4444;
            font-weight: 900;
        }

        .trend-down {
            color: #10b981;
            font-weight: 900;
        }

        .budget-item[data-label] { cursor: pointer; }

        .custom-dropdown { position: relative; }

        .dropdown-selected {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            cursor: pointer;
        }

        .dropdown-selected .dropdown-selected-left {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .dropdown-options {
            display: none;
            position: absolute;
            left: 0;
            right: 0;
            top: calc(100% + 8px);
            background: rgba(255,255,255,0.98);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 18px 30px rgba(15,23,42,0.12);
            z-index: 90;
            max-height: 260px;
            overflow-y: auto;
        }

        .theme-dark .dropdown-options {
            background: rgba(17,24,39,0.96);
            border-color: rgba(255,255,255,0.12);
            box-shadow: 0 22px 40px rgba(0,0,0,0.45);
        }

        .dropdown-options.show { display: block; }

        .dropdown-option {
            padding: 12px 14px;
            cursor: pointer;
            transition: background-color 0.15s;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 800;
            color: rgba(15,23,42,0.84);
            gap: 10px;
        }

        .theme-dark .dropdown-option { color: rgba(226,232,240,0.9); }

        .dropdown-option:hover { background-color: rgba(148,163,184,0.16); }
        .theme-dark .dropdown-option:hover { background-color: rgba(148,163,184,0.12); }

        .dropdown-option.add-category {
            color: var(--primary-2);
            font-weight: 900;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-start;
        }

        .theme-dark .dropdown-option.add-category { border-top-color: rgba(255,255,255,0.10); }

        .dropdown-option .delete-icon {
            color: #ef4444;
            opacity: 0;
            transition: opacity 0.15s;
            flex: 0 0 auto;
        }

        .dropdown-option:hover .delete-icon { opacity: 1; }

        .inline-readonly {
            background: rgba(255,255,255,0.65);
        }

        .theme-dark .inline-readonly {
            background: rgba(2,6,23,0.22);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .empty-state-icon {
            width: 72px;
            height: 72px;
            background: rgba(148,163,184,0.12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            color: #94a3b8;
            font-size: 28px;
            transition: transform 0.3s ease;
        }
        .empty-state:hover .empty-state-icon {
            transform: scale(1.05) rotate(5deg);
            background: rgba(148,163,184,0.18);
            color: #64748b;
        }
        .theme-dark .empty-state-icon {
            background: rgba(255,255,255,0.06);
            color: #64748b;
        }
        .theme-dark .empty-state:hover .empty-state-icon {
            background: rgba(255,255,255,0.1);
            color: #94a3b8;
        }
        .empty-state-title {
            font-size: 16px;
            font-weight: 800;
            color: rgba(15,23,42,0.9);
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }
        .theme-dark .empty-state-title {
            color: rgba(255,255,255,0.95);
        }
        .empty-state-desc {
            font-size: 14px;
            color: #64748b;
            max-width: 300px;
            line-height: 1.5;
            margin: 0 auto;
        }
        .theme-dark .empty-state-desc {
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <x-catat-nav />

    @php
        $rp = fn ($v) => 'Rp ' . number_format((int) round((float) $v), 0, ',', '.');
        $isPrev = ($filters['preset'] ?? null) === 'previous';
        $totalBudget = (float) ($summary['totalBudget'] ?? 0);
        $spentTotal = (float) ($summary['spent'] ?? 0);
        $remainingTotal = (float) ($summary['remaining'] ?? 0);
        $usagePercent = (float) ($summary['usagePercent'] ?? 0);
        $usageClamped = (int) max(0, min(100, round($usagePercent)));
        $remainingPercent = $totalBudget > 0 ? (int) max(0, min(100, round(($remainingTotal / $totalBudget) * 100))) : 0;
        $delta = (float) ($summary['spentChangePercent'] ?? 0);
        $deltaRounded = (int) round($delta);
        $badgeClass = $deltaRounded > 0 ? 'badge-bad' : ($deltaRounded < 0 ? '' : 'badge-neutral');
        $badgeIcon = $deltaRounded > 0 ? 'fa-arrow-trend-up' : ($deltaRounded < 0 ? 'fa-arrow-trend-down' : 'fa-minus');
        $badgeText = ($deltaRounded > 0 ? '+' : '') . $deltaRounded . '%';
        $badgeNote = $deltaRounded > 0 ? 'Lebih tinggi dari ' . ($summary['prevLabel'] ?? 'periode sebelumnya') : ($deltaRounded < 0 ? 'Lebih rendah dari ' . ($summary['prevLabel'] ?? 'periode sebelumnya') : 'Seimbang dengan ' . ($summary['prevLabel'] ?? 'periode sebelumnya'));
    @endphp

    <div class="container">
        <div class="page-title">Rencanakan Budgetmu</div>
        <div class="page-subtitle">Atur batasan pengeluaranmu</div>

        <div class="card summary">
            <div class="summary-top">
                <div class="summary-main">
                    <div class="summary-head">
                        <div class="summary-head-text">
                            <div class="summary-label">Ringkasan Budget · {{ $summary['periodLabel'] ?? 'Periode Ini' }}</div>
                            <div class="summary-note">{{ $badgeNote }}</div>
                        </div>
                        <button type="button" class="percent-circle {{ $badgeClass }}">{{ $badgeText }}</button>
                    </div>
                    <div class="summary-amount" id="summaryRemainingMain">
                        @if($totalBudget > 0)
                            {{ $rp($remainingTotal) }}
                        @else
                            {{ $rp(0) }}
                        @endif
                    </div>
                    <div class="summary-sub">
                        <span class="summary-chip"><i class="fa-solid fa-wallet"></i> Total <span id="summaryTotalBudgetValue">{{ $rp($totalBudget) }}</span></span>
                        @if($totalBudget > 0)
                            <span class="summary-chip"><i class="fa-solid fa-receipt"></i> Pengeluaran <span id="summarySpentValue">{{ $rp($spentTotal) }}</span></span>
                        @else
                            <span class="summary-chip"><i class="fa-solid fa-circle-info"></i> Belum ada budget</span>
                            <span class="summary-chip"><i class="fa-solid fa-receipt"></i> Pengeluaran <span id="summarySpentValue">{{ $rp($spentTotal) }}</span></span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="summary-grid">
                <div class="metric">
                    <div class="metric-head">
                        <div class="metric-label">Total Budget</div>
                        <div class="metric-ico"><i class="fa-solid fa-wallet"></i></div>
                    </div>
                    <div class="metric-value" id="metricTotalBudget">{{ $rp($totalBudget) }}</div>
                    <div class="metric-sub">Batas total</div>
                </div>

                <div class="metric danger">
                    <div class="metric-head">
                        <div class="metric-label">Pengeluaran</div>
                        <div class="metric-ico"><i class="fa-solid fa-receipt"></i></div>
                    </div>
                    <div class="metric-value" id="metricSpent">{{ $rp($spentTotal) }}</div>
                    <div class="metric-sub">Realisasi</div>
                </div>

                <div class="metric success">
                    <div class="metric-head">
                        <div class="metric-label">Sisa Budget</div>
                        <div class="metric-ico"><i class="fa-solid fa-coins"></i></div>
                    </div>
                    <div class="metric-value" id="metricRemaining">{{ $rp($totalBudget > 0 ? $remainingTotal : 0) }}</div>
                    <div class="metric-sub">Tersisa</div>
                </div>
            </div>

            <div class="summary-duo">
                <div class="duo-panel usage-panel">
                    <div class="duo-head">
                        <div class="duo-title">Pemakaian</div>
                        <div class="duo-chip usage" id="duoUsageChip"><i class="fa-solid fa-chart-line"></i> <span id="duoUsagePercent">{{ $totalBudget > 0 ? $usageClamped . '%' : '—' }}</span></div>
                    </div>
                    <div class="duo-track">
                        <div class="duo-fill usage" id="duoUsageFill" style="width: {{ $totalBudget > 0 ? $usageClamped : 0 }}%;"></div>
                    </div>
                    <div class="duo-foot">
                        <span>Terpakai</span>
                        <span id="duoUsageSpent">{{ $rp($spentTotal) }}</span>
                    </div>
                </div>

                <div class="duo-panel remaining-panel">
                    <div class="duo-head">
                        <div class="duo-title">Sisa</div>
                        <div class="duo-chip remaining" id="duoRemainingChip"><i class="fa-solid fa-coins"></i> <span id="duoRemainingPercent">{{ $totalBudget > 0 ? $remainingPercent . '%' : '—' }}</span></div>
                    </div>
                    <div class="duo-ring-wrap">
                        <div class="duo-ring" id="duoRemainingRing" style="--p: {{ $totalBudget > 0 ? $remainingPercent : 0 }}; --ring: rgba(16,185,129,1);">
                            <div class="duo-ring-inner" id="duoRemainingRingInner">{{ $totalBudget > 0 ? $remainingPercent . '%' : '—' }}</div>
                        </div>
                    </div>
                    <div class="duo-foot">
                        <span>Tersisa</span>
                        <span id="duoRemainingAmount">{{ $rp($totalBudget > 0 ? $remainingTotal : 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="toggle">
            <a href="{{ route('budget') }}" class="toggle-btn {{ $isPrev ? '' : 'active' }}">
                <span>Bulan Ini</span>
                @if(!$isPrev)<i class="fa-solid fa-check"></i>@endif
            </a>
            <a href="{{ route('budget', ['preset' => 'previous']) }}" class="toggle-btn {{ $isPrev ? 'active' : '' }}">
                <span>Bulan Lalu</span>
                @if($isPrev)<i class="fa-solid fa-check"></i>@endif
            </a>
            <button type="button" id="openBudgetFilter" class="toggle-btn" data-role="filter">
                <span>Filter</span>
                <i class="fa-solid fa-sliders"></i>
            </button>
        </div>

        <div class="card list-card">
            <div class="section-title">Pengeluaran per Kategori</div>
            <div class="section-sub">Terisi otomatis dari transaksi, dibandingkan dengan budget per kategori</div>

            <div id="budgetCategoryList" class="budget-list">
                @forelse(($categories ?? []) as $c)
                    @php
                        $max = (int) ($c['max'] ?? 0);
                        $spent = (float) ($c['spent'] ?? 0);
                        $remaining = $c['remaining'];
                        $usage = $c['usagePercent'];
                        $fill = (int) ($c['fillPercent'] ?? 0);
                        $change = (float) ($c['changePercent'] ?? 0);
                        $changeRounded = (int) round($change);
                        $trendClass = $changeRounded > 0 ? 'trend-up' : ($changeRounded < 0 ? 'trend-down' : '');
                        $trendIcon = $changeRounded > 0 ? 'fa-caret-up' : ($changeRounded < 0 ? 'fa-caret-down' : '');
                    @endphp

                    <div class="budget-item" style="--bar-color: {{ $c['color'] ?? 'rgb(54, 162, 235)' }};" data-label="{{ $c['label'] ?? '' }}" data-spent="{{ (float) $spent }}" data-max="{{ (int) $max }}">
                        <div class="budget-icon">
                            <i class="fa-solid {{ $c['icon'] ?? 'fa-wallet' }}"></i>
                        </div>
                        <div class="budget-main">
                            <div class="budget-row">
                                <div class="budget-label">{{ $c['label'] ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="budget-meta">
                            <div class="budget-amount">
                                <span class="budget-amount-spent">{{ $rp($spent) }}</span>
                                <span class="budget-amount-sep" style="{{ $max > 0 ? '' : 'display:none;' }}"> / </span>
                                <span class="budget-amount-max" style="{{ $max > 0 ? '' : 'display:none;' }}">{{ $rp($max) }}</span>
                            </div>
                        </div>
                        <div class="budget-bar-row">
                            <div class="bar-track">
                                <div class="bar-fill" style="width: {{ $fill }}%;"></div>
                            </div>
                            <div class="bar-center-label"><span class="bar-center-text"></span></div>
                            <div class="budget-percent">
                                <span class="budget-percent-main" data-mode="{{ $max > 0 ? 'budget' : 'share' }}">
                                    @if($max > 0)
                                        {{ (int) round((float) $usage) }}%
                                    @else
                                        {{ $fill }}% dari total
                                    @endif
                                </span>
                                @if($trendIcon)
                                    <span class="budget-trend {{ $trendClass }}"> · {{ $changeRounded > 0 ? '+' : '' }}{{ $changeRounded }}% <i class="fa-solid {{ $trendIcon }} {{ $trendClass }}"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </div>
                        <div class="empty-state-title">Belum Ada Pengeluaran</div>
                        <div class="empty-state-desc">
                            Belum ada data transaksi untuk periode ini. Pengeluaran Anda akan muncul di sini secara otomatis.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>



    <x-bottom-nav />

    <script>
        window.addEventListener('DOMContentLoaded', function(){
            const filterOpenBtn = document.getElementById('openBudgetFilter');
            const filterModalOverlay = document.getElementById('budgetFilterModal');
            const filterCloseBtn = document.getElementById('closeBudgetFilter');
            const filterResetBtn = document.getElementById('resetBudgetFilter');
            const periodEl = document.getElementById('budgetFilterPeriod');
            const monthFields = document.getElementById('budgetFilterMonthFields');
            const rangeFields = document.getElementById('budgetFilterRangeFields');

            const categoryModalOverlay = document.getElementById('categoryBudgetModal');
            const categoryCloseBtn = document.getElementById('closeCategoryBudget');
            const cbCancelBtn = document.getElementById('cbCancel');
            const cbSaveBtn = document.getElementById('cbSave');
            const cbDeleteBtn = document.getElementById('cbDelete');
            const cbLabelEl = document.getElementById('cbLabel');
            const cbMaxInput = document.getElementById('cbMaxInput');
            const cbMonthsSelected = document.getElementById('cbMonthsSelected');
            const cbMonthsText = document.getElementById('cbMonthsText');
            const cbMonthsOptions = document.getElementById('cbMonthsOptions');
            const monthsSelectModalOverlay = document.getElementById('monthsSelectModal');
            const monthsSelectCloseBtn = document.getElementById('closeMonthsSelect');
            const monthsSelectList = document.getElementById('monthsSelectList');

            const addMonthModalOverlay = document.getElementById('addMonthOptionModal');
            const addMonthCloseBtn = document.getElementById('closeAddMonthOption');
            const addMonthCancelBtn = document.getElementById('addMonthCancel');
            const addMonthSaveBtn = document.getElementById('addMonthSave');
            const addMonthInput = document.getElementById('addMonthInput');

            const deleteMonthModalOverlay = document.getElementById('deleteMonthOptionModal');
            const deleteMonthCloseBtn = document.getElementById('closeDeleteMonthOption');
            const deleteMonthCancelBtn = document.getElementById('deleteMonthCancel');
            const deleteMonthConfirmBtn = document.getElementById('deleteMonthConfirm');
            const deleteMonthNameEl = document.getElementById('deleteMonthName');

            const deleteBudgetModalOverlay = document.getElementById('deleteBudgetConfirmModal');
            const deleteBudgetCloseBtn = document.getElementById('closeDeleteBudgetConfirm');
            const deleteBudgetCancelBtn = document.getElementById('cancelDeleteBudget');
            const deleteBudgetConfirmBtn = document.getElementById('confirmDeleteBudget');
            const deleteBudgetCategoryNameEl = document.getElementById('deleteBudgetCategoryName');

            const spentTotal = Number(@json($spentTotal));
            const BUDGET_MONTHS_KEY = 'budgetCategoryMonths';

            function formatRupiahStr(n){
                const v = Number(n || 0);
                return v > 0 ? v.toLocaleString('id-ID') : '';
            }

            function unformatRupiahStr(s){
                const v = String(s || '').replace(/\D/g, '');
                return v ? parseInt(v, 10) : 0;
            }

            function fmtIDR(n){
                const v = Math.round(Number(n || 0));
                return 'Rp ' + v.toLocaleString('id-ID');
            }

            function loadMonthsSettings(){
                try {
                    const raw = localStorage.getItem(BUDGET_MONTHS_KEY);
                    const parsed = raw ? JSON.parse(raw) : {};
                    return parsed && typeof parsed === 'object' ? parsed : {};
                } catch {
                    return {};
                }
            }

            function saveMonthsSettings(obj){
                localStorage.setItem(BUDGET_MONTHS_KEY, JSON.stringify(obj || {}));
            }

            function normalizeLabel(label){
                return String(label || '').trim();
            }

            function ensureSettingsForLabel(label){
                const key = normalizeLabel(label);
                const all = loadMonthsSettings();
                const existing = all[key];
                let options = [];
                let selected = 1;
                if (existing && typeof existing === 'object') {
                    if (Array.isArray(existing.options)) options = existing.options.map(v => parseInt(v, 10)).filter(v => Number.isFinite(v) && v >= 1);
                    if (Number.isFinite(parseInt(existing.selected, 10))) selected = parseInt(existing.selected, 10);
                }
                if (!options.includes(1)) options.push(1);
                options = Array.from(new Set(options)).sort((a, b) => a - b);
                if (!options.includes(selected)) selected = 1;
                all[key] = { options, selected };
                saveMonthsSettings(all);
                return all[key];
            }

            function setOverlayOpen(overlay, open){
                if (!overlay) return;
                if (open) {
                    overlay.classList.add('show');
                    overlay.setAttribute('aria-hidden', 'false');
                } else {
                    overlay.classList.remove('show');
                    overlay.setAttribute('aria-hidden', 'true');
                }
                const anyOpen = document.querySelectorAll('.modal-overlay.show').length > 0;
                document.body.style.overflow = anyOpen ? 'hidden' : '';
            }

            function syncFields(){
                const p = (periodEl && periodEl.value) ? periodEl.value : 'month';
                if (monthFields) monthFields.style.display = (p === 'month') ? 'grid' : 'none';
                if (rangeFields) rangeFields.style.display = (p === 'custom') ? 'grid' : 'none';
            }

            function openFilterModal(){
                if (!filterModalOverlay) return;
                setOverlayOpen(filterModalOverlay, true);
                if (filterOpenBtn) filterOpenBtn.classList.add('active');
                syncFields();
            }

            function closeFilterModal(){
                if (filterOpenBtn) filterOpenBtn.classList.remove('active');
                setOverlayOpen(filterModalOverlay, false);
            }

            if (filterOpenBtn) filterOpenBtn.addEventListener('click', openFilterModal);
            if (filterCloseBtn) filterCloseBtn.addEventListener('click', closeFilterModal);
            if (filterModalOverlay) filterModalOverlay.addEventListener('click', (e) => { if (e.target === filterModalOverlay) closeFilterModal(); });
            if (periodEl) periodEl.addEventListener('change', syncFields);
            if (filterResetBtn) filterResetBtn.addEventListener('click', closeFilterModal);

            let activeLabel = '';
            let activeItemEl = null;
            let monthToDelete = null;

            function updateMonthsSelectedDisplay(label){
                const st = ensureSettingsForLabel(label);
                if (cbMonthsText) cbMonthsText.textContent = 'Maks ' + st.selected + ' bulan';
            }

            function closeAllDropdowns(){
                if (cbMonthsOptions) cbMonthsOptions.classList.remove('show');
            }

            function renderMonthsOptions(label){
                if (!cbMonthsOptions) return;
                const st = ensureSettingsForLabel(label);
                cbMonthsOptions.innerHTML = '';

                st.options.forEach((m) => {
                    const opt = document.createElement('div');
                    opt.className = 'dropdown-option';
                    const left = document.createElement('span');
                    left.textContent = 'Maks ' + m + ' bulan';
                    opt.appendChild(left);

                    if (m !== 1) {
                        const del = document.createElement('i');
                        del.className = 'fa-solid fa-trash delete-icon';
                        del.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            monthToDelete = m;
                            if (deleteMonthNameEl) deleteMonthNameEl.textContent = 'Maks ' + m + ' bulan';
                            setOverlayOpen(deleteMonthModalOverlay, true);
                        });
                        opt.appendChild(del);
                    } else {
                        const spacer = document.createElement('span');
                        spacer.style.width = '18px';
                        spacer.style.flex = '0 0 18px';
                        opt.appendChild(spacer);
                    }

                    opt.addEventListener('click', () => {
                        const all = loadMonthsSettings();
                        const key = normalizeLabel(label);
                        const next = ensureSettingsForLabel(label);
                        next.selected = m;
                        all[key] = next;
                        saveMonthsSettings(all);
                        updateMonthsSelectedDisplay(label);
                        closeAllDropdowns();
                    });

                    cbMonthsOptions.appendChild(opt);
                });

                const addOpt = document.createElement('div');
                addOpt.className = 'dropdown-option add-category';
                addOpt.innerHTML = '<i class="fa-solid fa-plus-circle"></i> Tambah Opsi Bulan';
                addOpt.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (addMonthInput) addMonthInput.value = '';
                    setOverlayOpen(addMonthModalOverlay, true);
                    if (addMonthInput) addMonthInput.focus();
                });
                cbMonthsOptions.appendChild(addOpt);
            }

            function renderMonthsSelectModal(label){
                if (!monthsSelectList) return;
                const st = ensureSettingsForLabel(label);
                monthsSelectList.innerHTML = '';
                st.options.forEach((m) => {
                    const row = document.createElement('div');
                    row.className = 'dropdown-option';
                    const left = document.createElement('span');
                    left.textContent = 'Maks ' + m + ' bulan';
                    row.appendChild(left);
                    if (m !== 1) {
                        const del = document.createElement('i');
                        del.className = 'fa-solid fa-trash delete-icon';
                        del.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            monthToDelete = m;
                            if (deleteMonthNameEl) deleteMonthNameEl.textContent = 'Maks ' + m + ' bulan';
                            setOverlayOpen(deleteMonthModalOverlay, true);
                        });
                        row.appendChild(del);
                    } else {
                        const spacer = document.createElement('span');
                        spacer.style.width = '18px';
                        spacer.style.flex = '0 0 18px';
                        row.appendChild(spacer);
                    }
                    row.addEventListener('click', () => {
                        const all = loadMonthsSettings();
                        const key = normalizeLabel(label);
                        const next = ensureSettingsForLabel(label);
                        next.selected = m;
                        all[key] = next;
                        saveMonthsSettings(all);
                        updateMonthsSelectedDisplay(label);
                        setOverlayOpen(monthsSelectModalOverlay, false);
                        applyAllRows();
                        updateSummaryFromRows();
                    });
                    monthsSelectList.appendChild(row);
                });
                const addRow = document.createElement('div');
                addRow.className = 'dropdown-option add-category';
                addRow.innerHTML = '<i class="fa-solid fa-plus-circle"></i> Tambah Opsi Bulan';
                addRow.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (addMonthInput) addMonthInput.value = '';
                    setOverlayOpen(addMonthModalOverlay, true);
                    if (addMonthInput) addMonthInput.focus();
                });
                monthsSelectList.appendChild(addRow);
            }

            function openCategoryModal(itemEl){
                if (!categoryModalOverlay) return;
                const label = itemEl ? itemEl.getAttribute('data-label') : '';
                activeLabel = normalizeLabel(label);
                if (!activeLabel) return;
                activeItemEl = itemEl;
                closeAllDropdowns();
                ensureSettingsForLabel(activeLabel);
                renderMonthsOptions(activeLabel);
                updateMonthsSelectedDisplay(activeLabel);
                if (cbLabelEl) cbLabelEl.textContent = activeLabel;
                const max = parseInt(itemEl.getAttribute('data-max') || '0', 10) || 0;
                if (cbMaxInput) cbMaxInput.value = formatRupiahStr(max);
                setOverlayOpen(categoryModalOverlay, true);
            }

            function closeCategoryModal(){
                closeAllDropdowns();
                setOverlayOpen(categoryModalOverlay, false);
                activeLabel = '';
                activeItemEl = null;
            }

            if (categoryCloseBtn) categoryCloseBtn.addEventListener('click', closeCategoryModal);
            if (cbCancelBtn) cbCancelBtn.addEventListener('click', closeCategoryModal);
            if (categoryModalOverlay) categoryModalOverlay.addEventListener('click', (e) => { if (e.target === categoryModalOverlay) closeCategoryModal(); });

            if (cbMaxInput) {
                cbMaxInput.addEventListener('input', () => {
                    const v = cbMaxInput.value.replace(/\D/g, '');
                    cbMaxInput.value = v ? parseInt(v, 10).toLocaleString('id-ID') : '';
                });
            }

            if (cbMonthsSelected) {
                cbMonthsSelected.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (!activeLabel) return;
                    renderMonthsSelectModal(activeLabel);
                    setOverlayOpen(monthsSelectModalOverlay, true);
                });
            }

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-dropdown')) closeAllDropdowns();
            });
            if (monthsSelectCloseBtn) monthsSelectCloseBtn.addEventListener('click', () => setOverlayOpen(monthsSelectModalOverlay, false));
            if (monthsSelectModalOverlay) monthsSelectModalOverlay.addEventListener('click', (e) => { if (e.target === monthsSelectModalOverlay) setOverlayOpen(monthsSelectModalOverlay, false); });

            function applyCategoryRow(el){
                if (!el) return;
                const label = normalizeLabel(el.getAttribute('data-label'));
                if (!label) return;
                const spent = Number(el.getAttribute('data-spent') || 0);
                const monthlyMax = parseInt(el.getAttribute('data-max') || '0', 10) || 0;
                const st = ensureSettingsForLabel(label);
                // Updated logic: effectiveMax is just the raw value (user input is treated as Total Budget for X months)
                const effectiveMax = monthlyMax > 0 ? monthlyMax : 0;

                const amountSpentEl = el.querySelector('.budget-amount-spent');
                if (amountSpentEl) amountSpentEl.textContent = fmtIDR(spent);
                const amountSepEl = el.querySelector('.budget-amount-sep');
                const amountMaxEl = el.querySelector('.budget-amount-max');

                const percentMainEl = el.querySelector('.budget-percent-main');
                const barFillEl = el.querySelector('.bar-fill');

                if (effectiveMax > 0) {
                    el.classList.remove('unconfigured');
                    if (amountSepEl) amountSepEl.style.display = '';
                    if (amountMaxEl) {
                        amountMaxEl.style.display = '';
                        amountMaxEl.textContent = fmtIDR(effectiveMax);
                    }
                    const pct = effectiveMax > 0 ? Math.round((spent / effectiveMax) * 100) : 0;
                    const pctClamped = Math.max(0, Math.min(100, pct));
                    if (percentMainEl) {
                        percentMainEl.setAttribute('data-mode', 'budget');
                        percentMainEl.textContent = pct + '%';
                    }
                    if (barFillEl) barFillEl.style.width = pctClamped + '%';

                    const barLabel = el.querySelector('.bar-center-label');
                    if (barLabel) {
                        if (effectiveMax > 0) {
                            barLabel.textContent = `${pct}% · Maks ${st.selected} bulan`;
                        } else {
                            barLabel.textContent = `${pct}% · Maks 1 bulan`;
                        }
                    }
                } else {
                    el.classList.add('unconfigured');
                    if (amountSepEl) amountSepEl.style.display = 'none';
                    if (amountMaxEl) amountMaxEl.style.display = 'none';
                    const pct = spentTotal > 0 ? Math.round((spent / spentTotal) * 100) : 0;
                    const pctClamped = Math.max(0, Math.min(100, pct));
                    if (percentMainEl) {
                        percentMainEl.setAttribute('data-mode', 'unconfigured');
                        percentMainEl.textContent = 'Belum diatur';
                    }
                    if (barFillEl) barFillEl.style.width = pctClamped + '%';

                    const barLabel = el.querySelector('.bar-center-label');
                    if (barLabel) {
                         barLabel.textContent = `Belum diatur · Atur maksimal`;
                    }
                }
            }

            function applyAllRows(){
                document.querySelectorAll('#budgetCategoryList .budget-item[data-label]').forEach(applyCategoryRow);
            }

            function updateSummaryFromRows(){
                const totalBudgetEl = document.getElementById('metricTotalBudget');
                const remainingEl = document.getElementById('metricRemaining');
                const remainingMainEl = document.getElementById('summaryRemainingMain');
                const summaryTotalBudgetValue = document.getElementById('summaryTotalBudgetValue');
                const duoUsagePercentEl = document.getElementById('duoUsagePercent');
                const duoUsageFillEl = document.getElementById('duoUsageFill');
                const duoRemainingPercentEl = document.getElementById('duoRemainingPercent');
                const duoRemainingRingEl = document.getElementById('duoRemainingRing');
                const duoRemainingRingInnerEl = document.getElementById('duoRemainingRingInner');
                const duoRemainingAmountEl = document.getElementById('duoRemainingAmount');

                let totalBudgetEffective = 0;
                document.querySelectorAll('#budgetCategoryList .budget-item[data-label]').forEach((el) => {
                    const label = normalizeLabel(el.getAttribute('data-label'));
                    if (!label) return;
                    const monthlyMax = parseInt(el.getAttribute('data-max') || '0', 10) || 0;
                    if (monthlyMax <= 0) return;
                    const st = ensureSettingsForLabel(label);
                    // Update: Total budget is just the sum of max values (no multiplier)
                    totalBudgetEffective += monthlyMax;
                });

                const remaining = totalBudgetEffective > 0 ? (totalBudgetEffective - spentTotal) : 0;
                const usagePercent = totalBudgetEffective > 0 ? Math.round((spentTotal / totalBudgetEffective) * 100) : 0;
                const usageClamped = Math.max(0, Math.min(100, usagePercent));
                const remainingPercent = totalBudgetEffective > 0 ? Math.round((Math.max(0, remaining) / totalBudgetEffective) * 100) : 0;
                const remainingClamped = Math.max(0, Math.min(100, remainingPercent));

                if (summaryTotalBudgetValue) summaryTotalBudgetValue.textContent = fmtIDR(totalBudgetEffective);
                if (totalBudgetEl) totalBudgetEl.textContent = fmtIDR(totalBudgetEffective);
                if (remainingEl) remainingEl.textContent = fmtIDR(Math.max(0, remaining));
                if (remainingMainEl) remainingMainEl.textContent = fmtIDR(Math.max(0, remaining));

                if (duoUsagePercentEl) duoUsagePercentEl.textContent = totalBudgetEffective > 0 ? (usageClamped + '%') : '—';
                if (duoUsageFillEl) duoUsageFillEl.style.width = totalBudgetEffective > 0 ? (usageClamped + '%') : '0%';

                if (duoRemainingPercentEl) duoRemainingPercentEl.textContent = totalBudgetEffective > 0 ? (remainingClamped + '%') : '—';
                if (duoRemainingRingEl) duoRemainingRingEl.style.setProperty('--p', totalBudgetEffective > 0 ? remainingClamped : 0);
                if (duoRemainingRingInnerEl) duoRemainingRingInnerEl.textContent = totalBudgetEffective > 0 ? (remainingClamped + '%') : '—';
                if (duoRemainingAmountEl) duoRemainingAmountEl.textContent = fmtIDR(Math.max(0, remaining));
            }

            async function saveBudgetForActive(){
                if (!activeLabel) return;
                const maxVal = cbMaxInput ? unformatRupiahStr(cbMaxInput.value) : 0;
                await fetch('/transactions/budgets', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ label: activeLabel, max_amount: maxVal })
                });

                // Jika budget dihapus (maxVal 0), hapus juga setting durasi bulan di localStorage
                if (maxVal <= 0) {
                    try {
                        const all = loadMonthsSettings();
                        const key = normalizeLabel(activeLabel);
                        if (all[key]) {
                            delete all[key];
                            saveMonthsSettings(all);
                        }
                    } catch(e) { console.error(e); }
                }

                if (activeItemEl) {
                    activeItemEl.setAttribute('data-max', String(maxVal));
                    const spent = Number(activeItemEl.getAttribute('data-spent') || 0);
                    if (maxVal <= 0 && spent <= 0) {
                        activeItemEl.remove();
                        const list = document.getElementById('budgetCategoryList');
                        if (list && list.querySelectorAll('.budget-item').length === 0) {
                             location.reload();
                             return;
                        }
                    }
                }
                closeCategoryModal();
                applyAllRows();
                updateSummaryFromRows();
            }

            if (cbSaveBtn) cbSaveBtn.addEventListener('click', saveBudgetForActive);

            function closeDeleteBudgetModal() { setOverlayOpen(deleteBudgetModalOverlay, false); }
            if (deleteBudgetCloseBtn) deleteBudgetCloseBtn.addEventListener('click', closeDeleteBudgetModal);
            if (deleteBudgetCancelBtn) deleteBudgetCancelBtn.addEventListener('click', closeDeleteBudgetModal);
            if (deleteBudgetModalOverlay) deleteBudgetModalOverlay.addEventListener('click', (e) => { if (e.target === deleteBudgetModalOverlay) closeDeleteBudgetModal(); });

            if (cbDeleteBtn) {
                cbDeleteBtn.addEventListener('click', () => {
                     if (!activeLabel) return;
                     if (deleteBudgetCategoryNameEl) deleteBudgetCategoryNameEl.textContent = activeLabel;
                     setOverlayOpen(deleteBudgetModalOverlay, true);
                });
            }

            if (deleteBudgetConfirmBtn) {
                deleteBudgetConfirmBtn.addEventListener('click', async () => {
                    if (!activeLabel) return;

                    await fetch('/transactions/budgets', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ label: activeLabel, max_amount: 0 })
                    });

                    // Hapus setting durasi bulan di localStorage agar reset ke default (1 bulan)
                    try {
                        const all = loadMonthsSettings();
                        const key = normalizeLabel(activeLabel);
                        if (all[key]) {
                            delete all[key];
                            saveMonthsSettings(all);
                        }
                    } catch(e) { console.error(e); }

                    if (activeItemEl) {
                        activeItemEl.setAttribute('data-max', '0');
                        const spent = Number(activeItemEl.getAttribute('data-spent') || 0);
                        if (spent <= 0) {
                            activeItemEl.remove();
                            const list = document.getElementById('budgetCategoryList');
                            if (list && list.querySelectorAll('.budget-item').length === 0) {
                                location.reload();
                                return;
                            }
                        }
                    }
                    closeDeleteBudgetModal();
                    closeCategoryModal();
                    applyAllRows();
                    updateSummaryFromRows();
                });
            }

            function closeAddMonthModal(){ setOverlayOpen(addMonthModalOverlay, false); }
            if (addMonthCloseBtn) addMonthCloseBtn.addEventListener('click', closeAddMonthModal);
            if (addMonthCancelBtn) addMonthCancelBtn.addEventListener('click', closeAddMonthModal);
            if (addMonthModalOverlay) addMonthModalOverlay.addEventListener('click', (e) => { if (e.target === addMonthModalOverlay) closeAddMonthModal(); });

            if (addMonthSaveBtn) addMonthSaveBtn.addEventListener('click', () => {
                if (!activeLabel) return;
                const raw = addMonthInput ? addMonthInput.value : '';
                const nextVal = parseInt(String(raw || '').replace(/\D/g, ''), 10);
                if (!Number.isFinite(nextVal) || nextVal < 1) return;
                const all = loadMonthsSettings();
                const key = normalizeLabel(activeLabel);
                const st = ensureSettingsForLabel(activeLabel);
                if (!st.options.includes(nextVal)) st.options.push(nextVal);
                st.options = Array.from(new Set(st.options)).sort((a, b) => a - b);
                st.selected = nextVal;
                all[key] = st;
                saveMonthsSettings(all);
                renderMonthsOptions(activeLabel);
                updateMonthsSelectedDisplay(activeLabel);
                closeAddMonthModal();
                setOverlayOpen(monthsSelectModalOverlay, false);
                applyAllRows();
                updateSummaryFromRows();
            });

            function closeDeleteMonthModal(){ setOverlayOpen(deleteMonthModalOverlay, false); monthToDelete = null; }
            if (deleteMonthCloseBtn) deleteMonthCloseBtn.addEventListener('click', closeDeleteMonthModal);
            if (deleteMonthCancelBtn) deleteMonthCancelBtn.addEventListener('click', closeDeleteMonthModal);
            if (deleteMonthModalOverlay) deleteMonthModalOverlay.addEventListener('click', (e) => { if (e.target === deleteMonthModalOverlay) closeDeleteMonthModal(); });

            if (deleteMonthConfirmBtn) deleteMonthConfirmBtn.addEventListener('click', () => {
                if (!activeLabel || !monthToDelete || monthToDelete === 1) return;
                const all = loadMonthsSettings();
                const key = normalizeLabel(activeLabel);
                const st = ensureSettingsForLabel(activeLabel);
                st.options = st.options.filter(v => v !== monthToDelete);
                if (!st.options.includes(1)) st.options.push(1);
                st.options = Array.from(new Set(st.options)).sort((a, b) => a - b);
                if (!st.options.includes(st.selected)) st.selected = 1;
                all[key] = st;
                saveMonthsSettings(all);
                renderMonthsOptions(activeLabel);
                updateMonthsSelectedDisplay(activeLabel);
                closeDeleteMonthModal();
                renderMonthsSelectModal(activeLabel);
                applyAllRows();
                updateSummaryFromRows();
            });

            document.querySelectorAll('#budgetCategoryList .budget-item[data-label]').forEach((el) => {
                const label = normalizeLabel(el.getAttribute('data-label'));
                if (!label) return;
                ensureSettingsForLabel(label);
                el.addEventListener('click', () => openCategoryModal(el));
            });

            window.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;
                if (deleteBudgetModalOverlay && deleteBudgetModalOverlay.classList.contains('show')) { closeDeleteBudgetModal(); return; }
                if (deleteMonthModalOverlay && deleteMonthModalOverlay.classList.contains('show')) { closeDeleteMonthModal(); return; }
                if (addMonthModalOverlay && addMonthModalOverlay.classList.contains('show')) { closeAddMonthModal(); return; }
                if (monthsSelectModalOverlay && monthsSelectModalOverlay.classList.contains('show')) { setOverlayOpen(monthsSelectModalOverlay, false); return; }
                if (categoryModalOverlay && categoryModalOverlay.classList.contains('show')) { closeCategoryModal(); return; }
                if (filterModalOverlay && filterModalOverlay.classList.contains('show')) { closeFilterModal(); return; }
            });

            setOverlayOpen(filterModalOverlay, false);
            setOverlayOpen(categoryModalOverlay, false);
            setOverlayOpen(addMonthModalOverlay, false);
            setOverlayOpen(deleteMonthModalOverlay, false);
            setOverlayOpen(deleteBudgetModalOverlay, false);
            setOverlayOpen(monthsSelectModalOverlay, false);
            window.addEventListener('pageshow', () => {
                setOverlayOpen(filterModalOverlay, false);
                setOverlayOpen(categoryModalOverlay, false);
                setOverlayOpen(addMonthModalOverlay, false);
                setOverlayOpen(deleteMonthModalOverlay, false);
                setOverlayOpen(deleteBudgetModalOverlay, false);
                setOverlayOpen(monthsSelectModalOverlay, false);
                if (filterOpenBtn) filterOpenBtn.classList.remove('active');
            });

            applyAllRows();
            updateSummaryFromRows();
        });
    </script>

    <div id="budgetFilterModal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-head">
                <div>
                    <div class="modal-title">Filter Budget</div>
                    <div class="modal-sub">Atur periode tanpa bikin halaman berat</div>
                </div>
                <button type="button" class="modal-close" id="closeBudgetFilter"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ route('budget') }}" class="form-grid">
                    <div>
                        <label class="field-label" for="budgetFilterPeriod">Periode</label>
                        <select id="budgetFilterPeriod" name="period" class="field">
                            <option value="month" {{ ($filters['period'] ?? 'month') === 'month' ? 'selected' : '' }}>Bulanan</option>
                            <option value="week" {{ ($filters['period'] ?? '') === 'week' ? 'selected' : '' }}>Mingguan</option>
                            <option value="custom" {{ ($filters['period'] ?? '') === 'custom' ? 'selected' : '' }}>Rentang Tanggal</option>
                        </select>
                    </div>

                    <div id="budgetFilterMonthFields" class="form-row">
                        <div>
                            <label class="field-label" for="budgetFilterMonth">Bulan</label>
                            <select id="budgetFilterMonth" name="month" class="field">
                                @foreach(($filters['months'] ?? []) as $m => $label)
                                    <option value="{{ $m }}" {{ (int)($filters['month'] ?? now()->month) === (int)$m ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="field-label" for="budgetFilterYear">Tahun</label>
                            <select id="budgetFilterYear" name="year" class="field">
                                @foreach(($filters['years'] ?? [now()->year]) as $y)
                                    <option value="{{ $y }}" {{ (int)($filters['year'] ?? now()->year) === (int)$y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="budgetFilterRangeFields" class="form-row" style="display:none;">
                        <div>
                            <label class="field-label" for="budgetFilterStart">Mulai</label>
                            <input id="budgetFilterStart" name="start" type="date" class="field" value="{{ $filters['start'] ?? '' }}">
                        </div>
                        <div>
                            <label class="field-label" for="budgetFilterEnd">Sampai</label>
                            <input id="budgetFilterEnd" name="end" type="date" class="field" value="{{ $filters['end'] ?? '' }}">
                        </div>
                    </div>

                    <div class="modal-actions">
                        <a href="{{ route('budget') }}" class="btn-ghost" id="resetBudgetFilter">Reset</a>
                        <button type="submit" class="btn-primary">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="categoryBudgetModal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-head">
                <div>
                    <div class="modal-title">Pengaturan Pengeluaran</div>
                    <div class="modal-sub">Atur maksimal pengeluaran per kategori</div>
                </div>
                <button type="button" class="modal-close" id="closeCategoryBudget"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-grid">
                    <div>
                        <label class="field-label">Kategori</label>
                        <div class="field inline-readonly" id="cbLabel"></div>
                    </div>
                    <div>
                        <label class="field-label" for="cbMaxInput">Maksimal Pengeluaran / Bulan (Rp)</label>
                        <input id="cbMaxInput" type="text" inputmode="numeric" class="field" placeholder="0">
                    </div>
                    <div>
                        <label class="field-label">Maksimal Bulan</label>
                        <div class="custom-dropdown">
                            <div class="dropdown-selected field" id="cbMonthsSelected">
                                <div class="dropdown-selected-left">
                                    <i class="fa-solid fa-calendar"></i>
                                    <span id="cbMonthsText">Maks 1 bulan</span>
                                </div>
                                <i class="fa-solid fa-chevron-down" style="opacity:.7"></i>
                            </div>
                            <div class="dropdown-options" id="cbMonthsOptions"></div>
                        </div>
                    </div>
                    <div class="modal-actions" style="justify-content:space-between;gap:12px;">
                        <button type="button" class="btn-ghost" id="cbDelete" style="color:#ef4444;background:white;border:1px solid #e5e7eb;font-weight:700;padding:10px 20px;border-radius:12px;display:flex;align-items:center;gap:8px;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                            <i class="fa-solid fa-trash-can"></i> Hapus
                        </button>
                        <div style="display:flex;gap:12px;flex:1;justify-content:flex-end;">
                            <button type="button" class="btn-ghost" id="cbCancel">Batal</button>
                            <button type="button" class="btn-primary" id="cbSave">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="monthsSelectModal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-head">
                <div>
                    <div class="modal-title">Maksimal Bulan</div>
                    <div class="modal-sub">Pilih atau tambah opsi maksimal bulan</div>
                </div>
                <button type="button" class="modal-close" id="closeMonthsSelect"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div id="monthsSelectList" class="form-grid">
                </div>
            </div>
        </div>
    </div>

    <div id="addMonthOptionModal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-head">
                <div>
                    <div class="modal-title">Tambah Maksimal Bulan</div>
                    <div class="modal-sub">Tambahkan opsi maksimal berapa bulan</div>
                </div>
                <button type="button" class="modal-close" id="closeAddMonthOption"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-grid">
                    <div>
                        <label class="field-label" for="addMonthInput">Jumlah Bulan</label>
                        <input id="addMonthInput" type="text" inputmode="numeric" class="field" placeholder="Contoh: 3">
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-ghost" id="addMonthCancel">Batal</button>
                        <button type="button" class="btn-primary" id="addMonthSave">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteMonthOptionModal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-head">
                <div>
                    <div class="modal-title">Hapus Opsi</div>
                    <div class="modal-sub">Opsi maksimal bulan akan dihapus</div>
                </div>
                <button type="button" class="modal-close" id="closeDeleteMonthOption"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-grid">
                    <div class="field inline-readonly" style="text-align:center; font-weight:800; color:#dc2626; display:flex; align-items:center; justify-content:center; gap:8px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Konfirmasi penghapusan</span>
                    </div>
                    <div class="field inline-readonly" style="text-align:center;">
                        Opsi "<span id="deleteMonthName"></span>" akan dihapus. Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-ghost" id="deleteMonthCancel">Batal</button>
                        <button type="button" class="btn-primary" id="deleteMonthConfirm">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteBudgetConfirmModal" class="modal-overlay" aria-hidden="true" style="backdrop-filter: blur(4px);">
        <div class="modal" role="dialog" aria-modal="true" style="max-width:400px;border-radius:24px;overflow:hidden;box-shadow:0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <div class="modal-body" style="padding:32px 24px;">
                <div style="display:flex;flex-direction:column;align-items:center;text-align:center;">
                    <div style="width:64px;height:64px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                        <i class="fa-solid fa-trash-can" style="font-size:28px;color:#dc2626;"></i>
                    </div>

                    <h3 style="font-size:1.25rem;font-weight:800;color:#111827;margin:0 0 8px 0;">Hapus Budget?</h3>

                    <p style="color:#6b7280;font-size:0.95rem;line-height:1.5;margin-bottom:24px;">
                        Anda akan menghapus budget untuk kategori <br>
                        <span id="deleteBudgetCategoryName" style="font-weight:700;color:#1f2937;"></span>.
                        <br><span style="font-size:0.85rem;color:#ef4444;margin-top:4px;display:block;">Tindakan ini tidak dapat dibatalkan.</span>
                    </p>

                    <div style="display:flex;gap:12px;width:100%;">
                        <button type="button" id="cancelDeleteBudget" class="btn-ghost" style="flex:1;justify-content:center;border:1px solid #d1d5db;border-radius:12px;padding:12px;font-weight:600;color:#374151;">Batal</button>
                        <button type="button" id="confirmDeleteBudget" style="flex:1;justify-content:center;background:#dc2626;color:white;border:none;border-radius:12px;padding:12px;font-weight:600;box-shadow:0 4px 6px -1px rgba(220, 38, 38, 0.3);cursor:pointer;display:flex;align-items:center;gap:8px;">
                            <i class="fa-solid fa-trash-can"></i> Hapus
                        </button>
                    </div>
                </div>
                <button type="button" id="closeDeleteBudgetConfirm" style="position:absolute;top:16px;right:16px;background:transparent;border:none;color:#9ca3af;cursor:pointer;font-size:1.2rem;padding:4px;"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    </div>
</body>
</html>
