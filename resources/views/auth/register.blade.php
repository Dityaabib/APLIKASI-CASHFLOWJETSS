<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - {{ config('app.name', 'CashFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts untuk typography yang lebih baik -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #3b82f6;
            --primary-green: #10b981;
            --dark-blue: #2563eb;
            --dark-green: #059669;
            --text-color: #454646;
            --light-gray: #f3f4f6;
            --border-color: #e5e7eb;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --card-bg: rgba(255, 255, 255, 0.95);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            text-gray-900;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            overflow-x: hidden;
        }

        /* Gradien warna sesuai logo */
        .gradient-button {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .gradient-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--dark-blue), var(--dark-green));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .gradient-button:hover::before {
            opacity: 1;
        }

        .gradient-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Styling untuk input dengan ikon */
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group i.left-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .input-group input {
            padding-left: 45px;
            padding-right: 15px;
            height: 50px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background-color: #fff;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 0.95rem;
        }

        .input-group input:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .input-group input:focus + i.left-icon {
            color: var(--primary-blue);
        }

        .input-group input.with-toggle {
            padding-right: 45px;
        }

        .input-group .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .input-group .toggle-password:hover {
            color: var(--primary-blue);
        }

        /* Animasi untuk form */
        .form-container {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Step transition animation */
        .step-content {
            display: none;
            animation: slideIn 0.4s ease-out;
        }

        .step-content.active {
            display: block;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Placeholder styling */
        input::placeholder {
            color: #9ca3af;
            font-style: italic;
            opacity: 0.8;
        }

        /* Responsif untuk logo dan teks */
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1rem;
            position: relative;
        }

        .logo-img {
            height: clamp(6rem, 23vw, 10rem);
            width: auto;
            margin-bottom: 60px;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.05);
        }

        .title-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }

        .title-text {
            font-size: clamp(1.5rem, 5vw, 2rem);
            font-weight: 700;
            color: var(--text-color);
            margin-right: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .brand-logo {
           height: clamp(1.5rem, 4.5vw, 2rem);
           width: auto;
           filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.05));
        }

        .divider {
            width: clamp(14rem, 15vw, 6rem);
            height: 0.25rem;
            background: linear-gradient(to right, var(--primary-blue), var(--primary-green));
            border-radius: 9999px;
            margin-bottom: 1rem;
        }

        .divider-1 {
            width: clamp(9rem, 15vw, 6rem);
            height: 0.25rem;
            background: linear-gradient(to right, var(--primary-blue), var(--primary-green));
            border-radius: 9999px;
            opacity: 0.7;
        }

        .form-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem 1.5rem 0 0;
            box-shadow: 0 -10px 30px -10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 100%;
            margin: 0;
            height: calc(100vh - var(--header-height, 0px));
            overflow-y: auto;
        }

        /* Label styling */
        .form-label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.95rem;
        }

        /* Checkbox styling */
        .checkbox-container {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .checkbox-container input[type="checkbox"] {
            margin-right: 0.75rem;
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--primary-blue);
            flex-shrink: 0;
            margin-top: 0.2rem;
        }

        .checkbox-content {
            display: flex;
            flex-direction: column;
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: #4b5563;
            line-height: 1.5;
        }

        .terms-link {
            color: var(--primary-blue);
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .terms-link:hover {
            color: var(--dark-blue);
        }

        /* Container styling */
        .full-width-container {
            width: 100%;
            max-width: 100%;
            padding: 0;
        }

        /* Styling untuk login link di dalam form */
        .login-link-container {
            margin-top: 1rem;
            padding-top: 1rem;
            text-align: center;
            border-top: 1px solid var(--border-color);
        }

        .login-link-text {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .login-link {
            color: var(--primary-blue);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-link:hover {
            color: var(--dark-blue);
            text-decoration: underline;
        }

        /* Menyesuaikan tinggi container utama */
        .main-container {
            display: flex;
            flex-direction: column;
        }

        .form-section {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .jus-2 {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        /* Floating shapes for modern look */
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
        }

        .shape-1 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            top: -50px;
            left: -50px;
            animation: float 15s infinite ease-in-out;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-blue));
            bottom: -30px;
            right: -30px;
            animation: float 20s infinite ease-in-out reverse;
        }

        .shape-3 {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            top: 50%;
            right: 10%;
            animation: float 18s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(20px, -20px) rotate(5deg); }
            50% { transform: translate(-10px, 10px) rotate(-5deg); }
            75% { transform: translate(15px, 5px) rotate(3deg); }
        }

        /* Enhanced form styling */
        .form-wrapper {
            padding: 2rem 1.5rem;
        }

        .form-title {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .form-title h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .form-title p {
            color: #6b7280;
            font-size: 0.9rem;
        }

        /* Progress indicator */
        .progress-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            width: 80px;
        }

        .progress-step::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            width: 100%;
            height: 2px;
            background-color: var(--border-color);
            z-index: 1;
        }

        .progress-step:last-child::before {
            display: none;
        }

        .progress-step.active .step-number {
            background-color: var(--primary-blue);
            color: white;
            transform: scale(1.1);
        }

        .progress-step.completed .step-number {
            background-color: var(--primary-green);
            color: white;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: white;
            border: 2px solid var(--border-color);
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: #6b7280;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .step-label {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #6b7280;
            text-align: center;
            transition: color 0.3s ease;
        }

        .progress-step.active .step-label {
            color: var(--primary-blue);
            font-weight: 600;
        }

        .progress-step.completed .step-label {
            color: var(--primary-green);
        }

        /* Navigation buttons */
        .step-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .btn-nav {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-prev {
            background-color: #e5e7eb;
            color: #6b7280;
        }

        .btn-prev:hover {
            background-color: #d1d5db;
        }

        .btn-next {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: white;
        }

        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Success step styling */
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 1.5rem;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .success-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .success-message {
            text-align: center;
            margin-bottom: 2rem;
        }

        .success-message h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .success-message p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        /* Enhanced input validation - Fixed positioning */
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group.success input {
            border-color: var(--primary-green);
        }

        .input-group.error input {
            border-color: #ef4444;
        }

        .input-group .validation-message {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            display: none;
            position: absolute;
            bottom: -20px;
            left: 0;
        }

        .input-group.success .validation-message.success-message {
            color: var(--primary-green);
            display: block;
        }

        .input-group.error .validation-message.error-message {
            color: #ef4444;
            display: block;
        }

        /* Error message styling */
        .error-message {
            background-color: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: #b91c1c;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        /* Enhanced button styling with loading animation */
        .btn-register {
            width: 100%;
            padding: 0.875rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .btn-register::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .btn-register:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 1;
            }
            20% {
                transform: scale(25, 25);
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }

        /* Enhanced loading spinner */
        .btn-content {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading .spinner {
            display: inline-block;
        }

        .loading .btn-text {
            display: inline-block;
        }

        /* Form field wrapper to ensure consistent spacing */
        .form-field {
            margin-bottom: 2rem;
            position: relative;
        }

        /* Button untuk menuju login dari halaman sukses */
        .btn-login {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: white;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 1rem;
            cursor: pointer;
            border: none;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <!-- Floating shapes for modern look -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <div class="main-container">
        <div class="flex justify-center items-center px-0 py-4">
            <div class="full-width-container">
                <div class="logo-container">
                    <img src="{{ asset('img/logocashflowjets.png') }}" alt="Logo" class="logo-img">
                    <div class="title-container">
                        <h1 class="title-text">Buat akun</h1>
                        <img src="{{ asset('img/textlogo.png') }}" class="brand-logo" alt="CashFlowJets">
                    </div>
                    <div class="divider"></div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="jus-2">
                <div class="divider-1"></div>
                <div class="divider-1"></div>
            </div>
            <div class="full-width-container">
                @if ($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/register" id="multiStepForm" class="form-card form-container">
                    @csrf
                    <div class="form-wrapper">
                        <div class="form-title">
                            <h2>Bergabung dengan CashFlowJets</h2>
                            <p>Isi formulir di bawah untuk membuat akun baru</p>
                        </div>

                        <!-- Progress indicator -->
                        <div class="progress-indicator">
                            <div class="progress-step active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-label">Info Pribadi</div>
                            </div>
                            <div class="progress-step" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-label">Akun</div>
                            </div>
                            <div class="progress-step" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-label">Selesai</div>
                            </div>
                        </div>

                        <!-- Step 1: Info Pribadi -->
                        <div class="step-content active" id="step1">
                            <div class="pb-8 space-y-5">
                                <div class="form-field">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <i class="fa-solid fa-user left-icon"></i>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                               placeholder="Masukkan nama lengkap"
                                               class="block px-3 py-3 w-full form-input" />
                                        <div class="validation-message error-message">Nama lengkap harus diisi</div>
                                    </div>
                                </div>
                                <div class="form-field">
                                    <label for="email" class="form-label">Alamat Email</label>
                                    <div class="input-group">
                                        <i class="fa-solid fa-envelope left-icon"></i>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                               placeholder="Masukkan alamat email"
                                               class="block px-3 py-3 w-full form-input" />
                                        <div class="validation-message error-message">Format email tidak valid</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Akun -->
                        <div class="step-content" id="step2">
                            <div class="pb-8 space-y-5">
                                <div class="form-field">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group">
                                        <i class="fa-solid fa-users left-icon"></i>
                                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                                               placeholder="Masukkan username"
                                               class="block px-3 py-3 w-full form-input" />
                                        <div class="validation-message error-message">Username tidak valid atau sudah digunakan</div>
                                    </div>
                                </div>
                                <div class="form-field">
                                    <label for="password" class="form-label">Kata Sandi</label>
                                    <div class="input-group">
                                        <i class="fa-solid fa-lock left-icon"></i>
                                        <input type="password" id="password" name="password" required
                                               placeholder="Minimal 8 karakter"
                                               class="block px-3 py-3 w-full form-input with-toggle" />
                                        <i class="fa-solid fa-key toggle-password" data-target="password"></i>
                                        <div class="validation-message error-message">Kata sandi minimal 8 karakter</div>
                                    </div>
                                </div>
                                <div class="form-field">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                                    <div class="input-group">
                                        <i class="fa-solid fa-lock left-icon"></i>
                                        <input type="password" id="password_confirmation" name="password_confirmation" required
                                               placeholder="Ulangi kata sandi Anda"
                                               class="block px-3 py-3 w-full form-input with-toggle" />
                                        <i class="fa-solid fa-key toggle-password" data-target="password_confirmation"></i>
                                        <div class="validation-message error-message">Kata sandi tidak cocok</div>
                                    </div>
                                </div>

                                <!-- Checkbox Syarat & Ketentuan -->
                                <div class="checkbox-container">
                                    <input type="checkbox" id="terms" name="terms" required>
                                    <div class="checkbox-content">
                                        <label for="terms" class="checkbox-label">Saya Menyetujui Syarat & Ketentuan</label>
                                        <a href="#" class="terms-link">Syarat & Ketentuan</a>
                                    </div>
                                </div>

                                <button type="button" class="btn-register" id="registerBtn">
                                    <div class="btn-content">
                                        <div class="spinner"></div>
                                        <span class="btn-text">Daftar Sekarang</span>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Selesai -->
                        <div class="step-content" id="step3">
                            <div class="success-icon">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="success-message">
                                <h3>Pendaftaran Berhasil!</h3>
                                <p>Akun Anda telah berhasil dibuat. Silakan masuk untuk melanjutkan.</p>
                            </div>
                            <div class="login-link-container">
                                <p class="login-link-text">Sudah punya akun?
                                    <button type="button" id="loginBtn" class="btn-login">Masuk di sini</button>
                                </p>
                            </div>
                        </div>

                        <!-- Navigation buttons -->
                        <div class="step-navigation">
                            <button type="button" class="btn-nav btn-prev" id="prevBtn" style="display: none;">
                                <i class="fa-solid fa-arrow-left"></i> Kembali
                            </button>
                            <button type="button" class="btn-nav btn-next" id="nextBtn">
                                Lanjut <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set variabel CSS untuk tinggi header agar card mengisi sisa tinggi layar
            const headerSection = document.querySelector('.main-container > .flex');
            function updateHeaderVariable() {
                const h = headerSection ? headerSection.offsetHeight : 0;
                document.documentElement.style.setProperty('--header-height', h + 'px');
            }
            updateHeaderVariable();
            window.addEventListener('resize', updateHeaderVariable);

            // Multi-step form logic
            const form = document.getElementById('multiStepForm');
            const steps = document.querySelectorAll('.step-content');
            const progressSteps = document.querySelectorAll('.progress-step');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const registerBtn = document.getElementById('registerBtn');
            const loginBtn = document.getElementById('loginBtn');
            let currentStep = 1;

            // Toggle password visibility
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-key');
                        this.classList.add('fa-eye');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-key');
                    }
                });
            });

            // Function to show specific step
            function showStep(step) {
                // Hide all steps
                steps.forEach(stepElement => {
                    stepElement.classList.remove('active');
                });

                // Show current step
                document.getElementById(`step${step}`).classList.add('active');

                // Update progress indicator
                progressSteps.forEach((progressStep, index) => {
                    if (index < step - 1) {
                        progressStep.classList.add('completed');
                        progressStep.classList.remove('active');
                    } else if (index === step - 1) {
                        progressStep.classList.add('active');
                        progressStep.classList.remove('completed');
                    } else {
                        progressStep.classList.remove('active', 'completed');
                    }
                });

                // Update navigation buttons
                if (step === 1) {
                    prevBtn.style.display = 'none';
                    nextBtn.style.display = 'block';
                } else if (step === 2) {
                    prevBtn.style.display = 'block';
                    nextBtn.style.display = 'none';
                } else if (step === 3) {
                    prevBtn.style.display = 'block';
                    nextBtn.style.display = 'none';
                }
            }

            // Next button click
            nextBtn.addEventListener('click', function() {
                if (validateStep(currentStep)) {
                    if (currentStep < 3) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }
            });

            // Previous button click
            prevBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Validate current step
            function validateStep(step) {
                let isValid = true;

                if (step === 1) {
                    // Validate name
                    const nameInput = document.getElementById('name');
                    const nameGroup = nameInput.closest('.input-group');
                    if (nameInput.value.trim() === '') {
                        nameGroup.classList.add('error');
                        nameGroup.classList.remove('success');
                        isValid = false;
                    } else {
                        nameGroup.classList.remove('error');
                        nameGroup.classList.add('success');
                    }

                    // Validate email
                    const emailInput = document.getElementById('email');
                    const emailGroup = emailInput.closest('.input-group');
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailInput.value)) {
                        emailGroup.classList.add('error');
                        emailGroup.classList.remove('success');
                        isValid = false;
                    } else {
                        emailGroup.classList.remove('error');
                        emailGroup.classList.add('success');
                    }
                }

                return isValid;
            }

            // Real-time validation for inputs
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');

            nameInput.addEventListener('blur', function() {
                const inputGroup = this.closest('.input-group');
                if (this.value.trim() === '') {
                    inputGroup.classList.add('error');
                    inputGroup.classList.remove('success');
                } else {
                    inputGroup.classList.remove('error');
                    inputGroup.classList.add('success');
                }
            });

            emailInput.addEventListener('blur', function() {
                const inputGroup = this.closest('.input-group');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.value)) {
                    inputGroup.classList.add('error');
                    inputGroup.classList.remove('success');
                } else {
                    inputGroup.classList.remove('error');
                    inputGroup.classList.add('success');
                }
            });

            usernameInput.addEventListener('blur', function() {
                const inputGroup = this.closest('.input-group');
                if (this.value.trim() === '' || this.value.length < 3) {
                    inputGroup.classList.add('error');
                    inputGroup.classList.remove('success');
                } else {
                    inputGroup.classList.remove('error');
                    inputGroup.classList.add('success');
                }
            });

            passwordInput.addEventListener('blur', function() {
                const inputGroup = this.closest('.input-group');
                if (this.value.length < 8) {
                    inputGroup.classList.add('error');
                    inputGroup.classList.remove('success');
                } else {
                    inputGroup.classList.remove('error');
                    inputGroup.classList.add('success');
                }
            });

            passwordConfirmInput.addEventListener('blur', function() {
                const inputGroup = this.closest('.input-group');
                if (this.value !== passwordInput.value || this.value === '') {
                    inputGroup.classList.add('error');
                    inputGroup.classList.remove('success');
                } else {
                    inputGroup.classList.remove('error');
                    inputGroup.classList.add('success');
                }
            });

            // Register button click - show success step
            registerBtn.addEventListener('click', function() {
                // Simple client-side validation for step 2
                let isValid = true;

                if (usernameInput.value.trim() === '' || usernameInput.value.length < 3) {
                    usernameInput.closest('.input-group').classList.add('error');
                    isValid = false;
                }

                if (passwordInput.value.length < 8) {
                    passwordInput.closest('.input-group').classList.add('error');
                    isValid = false;
                }

                if (passwordConfirmInput.value !== passwordInput.value || passwordConfirmInput.value === '') {
                    passwordConfirmInput.closest('.input-group').classList.add('error');
                    isValid = false;
                }

                if (!document.getElementById('terms').checked) {
                    isValid = false;
                }

                if (isValid) {
                    // Add loading state to button
                    this.classList.add('loading');

                    // Simulate processing time
                    setTimeout(() => {
                        // Remove loading state
                        this.classList.remove('loading');

                        // Show success step
                        currentStep = 3;
                        showStep(currentStep);
                    }, 1500);
                } else {
                    // Scroll to first error
                    const firstError = document.querySelector('.input-group.error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });

            // Login button click - submit form
            loginBtn.addEventListener('click', function() {
                // Add loading state to button
                this.classList.add('loading');
                this.disabled = true;

                // Submit the form
                form.submit();
            });

            // Initialize first step
            showStep(currentStep);
        });
    </script>
</body>
</html>
