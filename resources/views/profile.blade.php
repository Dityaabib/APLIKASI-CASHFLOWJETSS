<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - {{ config('app.name', 'CashFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --secondary: #8b5cf6;
            --secondary-dark: #7c3aed;
            --accent: #06b6d4;
            --success: #10b981;
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --warning: #f59e0b;
            --bg: #f8fafc;
            --bg-gradient: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0f9ff 100%);
            --text: #0f172a;
            --text-secondary: #475569;
            --muted: #64748b;
            --card: rgba(255,255,255,0.98);
            --card-border: rgba(15, 23, 42, 0.08);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 8px 16px rgba(0,0,0,0.08);
            --shadow-lg: 0 16px 32px rgba(0,0,0,0.12);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            padding-top: 70px;
            padding-bottom: 86px;
            margin: 0;
            background: var(--bg-gradient);
            color: var(--text);
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 25% 25%, rgba(14,165,233,0.1) 0%, transparent 40%),
                radial-gradient(circle at 75% 75%, rgba(139,92,246,0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(6,182,212,0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        .theme-dark body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #f1f5f9;
        }

        .theme-dark body::before {
            background:
                radial-gradient(circle at 25% 25%, rgba(14,165,233,0.08) 0%, transparent 40%),
                radial-gradient(circle at 75% 75%, rgba(139,92,246,0.06) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(6,182,212,0.04) 0%, transparent 50%);
        }

        .container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .page-tag {
            text-align: center;
            font-weight: 900;
            letter-spacing: 0.25em;
            color: var(--muted);
            font-size: 0.75rem;
            margin-top: 12px;
            margin-bottom: 20px;
            text-transform: uppercase;
            opacity: 0.7;
        }

        .theme-dark .page-tag {
            color: rgba(226, 232, 240, 0.6);
        }

        .profile-hero {
            margin-top: 12px;
            padding: 32px 24px 36px;
            border-radius: var(--radius-lg);
            background: var(--card);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-lg);
            text-align: center;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .theme-dark .profile-hero {
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            inset: -1px;
            background: linear-gradient(135deg,
                rgba(14,165,233,0.1) 0%,
                rgba(139,92,246,0.08) 50%,
                rgba(6,182,212,0.1) 100%);
            mask-image: linear-gradient(to bottom, black, transparent);
            -webkit-mask-image: linear-gradient(to bottom, black, transparent);
            pointer-events: none;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 24px;
            width: 180px;
            height: 180px;
        }

        .avatar-ring {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: conic-gradient(from 180deg, #0ea5e9, #8b5cf6, #06b6d4, #0ea5e9);
            padding: 4px;
            display: grid;
            place-items: center;
            box-shadow: 0 0 50px rgba(14,165,233,0.4);
        }

        .avatar-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--card);
            display: grid;
            place-items: center;
            overflow: hidden;
            position: relative;
        }

        .theme-dark .avatar-inner {
            background: #1e293b;
        }

        .avatar-icon {
            font-size: 4.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .avatar-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .profile-info {
            position: relative;
            z-index: 1;
            min-height: 80px;
        }

        .profile-name-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 8px;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .profile-name {
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.02em;
            margin: 0;
            background: linear-gradient(135deg, var(--text), var(--text-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 250px;
        }

        .theme-dark .profile-name {
            background: linear-gradient(135deg, #f1f5f9, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .profile-edit {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--card-border);
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7));
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            cursor: pointer;
            flex-shrink: 0;
        }

        .theme-dark .profile-edit {
            background: linear-gradient(135deg, rgba(30,41,59,0.9), rgba(30,41,59,0.7));
            border-color: rgba(255,255,255,0.1);
            color: #cbd5e1;
        }

        .profile-edit:hover {
            transform: scale(1.1);
        }

        .profile-email {
            margin: 0;
            color: var(--muted);
            font-weight: 600;
            font-size: 0.95rem;
            opacity: 0.85;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }

        .theme-dark .profile-email {
            color: rgba(226,232,240,0.7);
        }

        .section {
            margin-top: 32px;
        }

        .section-title {
            font-weight: 800;
            font-size: 0.9rem;
            margin: 0 0 16px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title::before,
        .section-title::after {
            content: '';
            flex: 1;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--primary), transparent);
            box-shadow: 0 0 6px rgba(14, 165, 233, 0.3);
        }

        .theme-dark .section-title {
            color: #94a3b8;
        }

        .theme-dark .section-title::before,
        .theme-dark .section-title::after {
            background: linear-gradient(to right, transparent, var(--secondary), transparent);
            box-shadow: 0 0 6px rgba(139, 92, 246, 0.3);
        }

        .card {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: var(--transition);
        }

        .theme-dark .card {
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .list-item {
            display: grid;
            grid-template-columns: 52px minmax(0, 1fr) auto;
            gap: 16px;
            align-items: center;
            padding: 20px;
            text-decoration: none;
            color: inherit;
            position: relative;
            transition: var(--transition);
        }

        .list-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: transparent;
            transition: var(--transition);
        }

        .list-item + .list-item {
            border-top: 1px solid rgba(15,23,42,0.05);
        }

        .theme-dark .list-item + .list-item {
            border-top-color: rgba(255,255,255,0.05);
        }

        .item-ico {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .item-ico::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 100%);
        }

        .item-ico.primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            box-shadow: 0 8px 20px rgba(14,165,233,0.3);
        }

        .item-ico.secondary {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            box-shadow: 0 8px 20px rgba(139,92,246,0.3);
        }

        .item-ico.warning {
            background: linear-gradient(135deg, var(--warning), #d97706);
            box-shadow: 0 8px 20px rgba(245,158,11,0.3);
        }

        .item-ico i {
            font-size: 1.3rem;
            z-index: 1;
        }

        .item-text {
            min-width: 0;
        }

        .item-title {
            font-weight: 700;
            font-size: 1.05rem;
            margin: 0;
            color: var(--text);
            transition: var(--transition);
        }

        .theme-dark .item-title {
            color: #f1f5f9;
        }

        .item-sub {
            margin-top: 4px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--muted);
            opacity: 0.85;
        }

        .theme-dark .item-sub {
            color: rgba(226,232,240,0.65);
        }

        .item-right {
            color: var(--muted);
            opacity: 0.6;
            transition: var(--transition);
        }

        .theme-dark .item-right {
            color: rgba(226,232,240,0.5);
        }

        .logout-section {
            margin-top: 40px;
            margin-bottom: 32px;
            padding: 0 8px;
        }

        .logout-btn {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: var(--radius-sm);
            padding: 0 24px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: linear-gradient(135deg, var(--danger), var(--danger-dark));
            color: white;
            box-shadow: 0 10px 25px rgba(239,68,68,0.25);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .logout-btn:active {
            transform: scale(0.98);
        }

        .logout-btn i {
            font-size: 1.2rem;
        }

        .coming-soon {
            position: relative;
            opacity: 0.7;
        }

        .coming-soon::after {
            content: 'COMING SOON';
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            background: var(--warning);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 12px rgba(245,158,11,0.3);
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 12px;
            }

            .profile-hero {
                padding: 28px 20px 32px;
            }

            .avatar-ring {
                width: 160px;
                height: 160px;
            }

            .avatar-container {
                width: 160px;
                height: 160px;
            }

            .profile-name {
                font-size: 1.5rem;
                max-width: 200px;
            }

            .list-item {
                padding: 18px 16px;
            }

            .logout-btn {
                height: 52px;
                font-size: 0.95rem;
            }
        }

        /* Modal Styles */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-panel {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -40%) scale(0.95);
            width: 90%;
            max-width: 400px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
        }

        .theme-dark .modal-panel {
            background: #1e293b;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .modal-panel.show {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
            visibility: visible;
        }

        .modal-header {
            padding: 24px 28px;
            background: white;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .theme-dark .modal-header {
            background: #1e293b;
            border-bottom-color: rgba(255,255,255,0.05);
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .theme-dark .modal-title {
            color: #f1f5f9;
        }

        .modal-close {
            background: #f1f5f9;
            border: none;
            color: var(--muted);
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-dark .modal-close {
            background: rgba(255,255,255,0.05);
            color: #94a3b8;
        }

        .modal-close:hover {
            background: #fee2e2;
            color: #ef4444;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 28px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .theme-dark .form-label {
            color: #94a3b8;
        }

        .input-wrapper {
            position: relative;
            transition: all 0.3s ease;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 1rem;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            background: #f8fafc;
            color: var(--text);
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.1);
        }

        .theme-dark .form-input {
            background: rgba(15, 23, 42, 0.5);
            color: #f1f5f9;
            border-color: rgba(255,255,255,0.1);
        }

        .theme-dark .form-input:focus {
             background: rgba(15, 23, 42, 0.8);
             border-color: var(--primary);
        }

        .input-wrapper:focus-within .input-icon {
            color: var(--primary);
            transform: translateY(-50%) scale(1.1);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #64748b;
            z-index: 10;
            padding: 4px;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .modal-actions {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 16px;
            margin-top: 36px;
        }

        .btn {
            padding: 14px 24px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #475569;
            transform: translateY(-2px);
        }

        .theme-dark .btn-secondary {
            background: rgba(255,255,255,0.05);
            color: #94a3b8;
        }

        .theme-dark .btn-secondary:hover {
            background: rgba(255,255,255,0.1);
            color: #f1f5f9;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.35);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Avatar Upload Style */
        .avatar-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 32px;
        }

        .avatar-preview-container {
            width: 120px;
            height: 120px;
            position: relative;
            cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .avatar-preview-container:hover {
            transform: scale(1.05) rotate(2deg);
        }

        .avatar-preview {
            width: 100%;
            height: 100%;
            border-radius: 40px;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            background: #e2e8f0;
            transition: all 0.3s ease;
        }

        .avatar-edit-icon {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: white;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
            transition: all 0.3s ease;
            transform: rotate(-5deg);
        }

        .avatar-preview-container:hover .avatar-edit-icon {
            transform: rotate(0deg) scale(1.1);
        }

        .avatar-hint {
            margin-top: 16px;
            font-size: 0.8rem;
            color: var(--muted);
            font-weight: 600;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <x-catat-nav />

    <div class="container">
        <div class="page-tag">Profile</div>

        <div class="profile-hero">
            <div class="avatar-container">
                <div class="avatar-ring">
                    <div class="avatar-inner">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('avatars/' . auth()->user()->avatar) }}" alt="Profile" class="avatar-photo">
                        @else
                            <i class="fa-solid fa-user avatar-icon"></i>
                        @endif
                    </div>
                </div>
            </div>

            <div class="profile-info">
                <div class="profile-name-row">
                    <h1 class="profile-name">{{ auth()->user()->name }}</h1>
                    <span class="profile-edit" onclick="openEditModal()" role="button">
                        <i class="fa-solid fa-pen"></i>
                    </span>
                </div>
                <div class="profile-email">{{ auth()->user()->email }}</div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Account Settings</h2>
            <div class="card">
                <div class="list-item" onclick="openChangePasswordModal()" role="button">
                    <div class="item-ico primary">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="item-text">
                        <div class="item-title">Change Password</div>
                        <div class="item-sub">Update your security credentials</div>
                    </div>
                    <div class="item-right">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
                <a class="list-item" href="{{ route('budget') }}">
                    <div class="item-ico secondary">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div class="item-text">
                        <div class="item-title">Categories & Budget</div>
                        <div class="item-sub">Manage your expense limits</div>
                    </div>
                    <div class="item-right">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Support & More</h2>
            <div class="card">
                <a class="list-item coming-soon" href="#" data-soon="1">
                    <div class="item-ico warning">
                        <i class="fa-solid fa-circle-question"></i>
                    </div>
                    <div class="item-text">
                        <div class="item-title">Help Center</div>
                        <div class="item-sub">Get answers to your questions</div>
                    </div>
                    <div class="item-right">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
                <a class="list-item coming-soon" href="#" data-soon="1">
                    <div class="item-ico secondary">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div class="item-text">
                        <div class="item-title">Terms & Conditions</div>
                        <div class="item-sub">Read our policies</div>
                    </div>
                    <div class="item-right">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>

        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Custom Toast Notification -->
    <div id="toast-notification" class="fixed top-24 right-5 z-[200] transform transition-all duration-500 translate-x-full opacity-0">
        <div class="flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white rounded-lg shadow-xl border-l-4 border-emerald-500" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg">
                <i class="fa-solid fa-check text-sm"></i>
            </div>
            <div class="ml-3 text-sm font-semibold text-gray-800" id="toast-message">Notification Message</div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" onclick="hideToast()">
                <span class="sr-only">Close</span>
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal">
        <!-- Backdrop -->
        <div class="modal-backdrop" id="modalBackdrop" onclick="closeEditModal()"></div>

        <!-- Modal Panel -->
        <div class="modal-panel" id="modalPanel">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fa-solid fa-user-pen"></i> Edit Profile
                </div>
                <button type="button" class="modal-close" onclick="closeEditModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="editProfileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Photo Upload -->
                    <div class="avatar-upload">
                        <div class="avatar-preview-container" onclick="document.getElementById('avatarInput').click()">
                            <img id="previewAvatar" src="{{ auth()->user()->avatar ? asset('avatars/' . auth()->user()->avatar) : '' }}" class="avatar-preview" style="{{ auth()->user()->avatar ? '' : 'display: none;' }}">

                            <div id="placeholderAvatar" class="avatar-preview" style="display: flex; align-items: center; justify-content: center; background: #f1f5f9; {{ auth()->user()->avatar ? 'display: none;' : '' }}">
                                <i class="fa-solid fa-camera fa-2x" style="color: var(--muted);"></i>
                            </div>

                            <div class="avatar-edit-icon">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </div>
                        </div>
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="previewImage(this)" style="display: none;">
                        <div class="avatar-hint">Tap photo to change</div>
                    </div>

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" class="form-input" placeholder="Enter your name" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-envelope input-icon"></i>
                            <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="form-input" placeholder="Enter your email" required>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal">
        <!-- Backdrop -->
        <div class="modal-backdrop" id="passwordModalBackdrop" onclick="closeChangePasswordModal()"></div>

        <!-- Modal Panel -->
        <div class="modal-panel" id="passwordModalPanel">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fa-solid fa-lock"></i> Change Password
                </div>
                <button type="button" class="modal-close" onclick="closeChangePasswordModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="changePasswordForm" action="{{ route('profile.password.update') }}" method="POST">
                    @csrf

                    <!-- Current Password -->
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-key input-icon"></i>
                            <input type="password" name="current_password" id="current_password" class="form-input" placeholder="Enter current password" required>
                            <span class="toggle-password" onclick="togglePassword('current_password', this)">
                                <i class="fa-solid fa-eye"></i>
                            </span>
                        </div>
                        @error('current_password')
                            <div style="color: var(--danger); font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" name="password" id="password" class="form-input" placeholder="Enter new password 8 chars" required minlength="8">
                            <span class="toggle-password" onclick="togglePassword('password', this)">
                                <i class="fa-solid fa-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <div style="color: var(--danger); font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Confirm new password" required>
                            <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                                <i class="fa-solid fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeChangePasswordModal()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');

        const passwordBackdrop = document.getElementById('passwordModalBackdrop');
        const passwordPanel = document.getElementById('passwordModalPanel');

        function openEditModal() {
            backdrop.classList.add('show');
            panel.classList.add('show');
        }

        function closeEditModal() {
            backdrop.classList.remove('show');
            panel.classList.remove('show');
        }

        function openChangePasswordModal() {
            passwordBackdrop.classList.add('show');
            passwordPanel.classList.add('show');
        }

        function closeChangePasswordModal() {
            passwordBackdrop.classList.remove('show');
            passwordPanel.classList.remove('show');
        }

        function togglePassword(inputId, toggleEl) {
            const input = document.getElementById(inputId);
            const icon = toggleEl.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('previewAvatar');
            const placeholder = document.getElementById('placeholderAvatar');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if(placeholder) placeholder.style.display = 'none';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Custom Toast Notification Logic
        function showToast(message, isError = false) {
            console.log('showToast called with:', message, 'isError:', isError);
            const toast = document.getElementById('toast-notification');
            const messageEl = document.getElementById('toast-message');
            const iconContainer = toast.querySelector('.inline-flex');
            const icon = iconContainer.querySelector('i');

            messageEl.textContent = message;

            if (isError) {
                toast.classList.replace('border-emerald-500', 'border-red-500');
                iconContainer.classList.replace('bg-emerald-100', 'bg-red-100');
                iconContainer.classList.replace('text-emerald-500', 'text-red-500');
                icon.classList.replace('fa-check', 'fa-circle-exclamation');
            } else {
                toast.classList.replace('border-red-500', 'border-emerald-500');
                iconContainer.classList.replace('bg-red-100', 'bg-emerald-100');
                iconContainer.classList.replace('text-red-500', 'text-emerald-500');
                icon.classList.replace('fa-circle-exclamation', 'fa-check');
            }

            // Show toast
            toast.classList.remove('translate-x-full', 'opacity-0');
            console.log('Toast shown');

            // Hide after 3 seconds
            setTimeout(() => {
                hideToast();
            }, 4000);
        }

        function hideToast() {
            const toast = document.getElementById('toast-notification');
            toast.classList.add('translate-x-full', 'opacity-0');
        }

        // Setup semua event listeners setelah DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Loading animation for Change Password form
            const changePassForm = document.getElementById('changePasswordForm');
            if(changePassForm) {
                changePassForm.addEventListener('submit', function(e) {
                    const btn = this.querySelector('button[type="submit"]');

                    // Tampilkan animasi loading
                    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Updating...';
                    btn.disabled = true;
                    btn.style.opacity = '0.8';
                    btn.style.cursor = 'wait';

                    // Izinkan form untuk submit secara normal
                    // Animasi akan tetap berjalan selama proses submit
                });
            }

            // Add interactive effects for list items
            document.querySelectorAll('.list-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(4px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Disable coming soon links
            document.querySelectorAll('[data-soon="1"]').forEach((el) => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                });
            });

            // Check for session messages - PINDAHKAN KE SINI
            @if(session('success'))
                console.log('Success session detected:', "{{ session('success') }}");
                showToast("{{ session('success') }}");
            @endif

            @if($errors->any())
                // Jika ada error pada field password, buka modal change password
                @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                    openChangePasswordModal();
                @endif

                // Tampilkan toast error pertama
                showToast("{{ $errors->first() }}", true);
            @endif

            // Disable coming soon links
            document.querySelectorAll('[data-soon="1"]').forEach((el) => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                });
            });
        });

    </script>

    <x-bottom-nav />
</body>
</html>
