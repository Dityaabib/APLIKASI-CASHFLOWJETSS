<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - {{ config('app.name', 'CashFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    /* Gradien warna sesuai logo */
    .gradient-button {
        background: linear-gradient(135deg, #3b82f6, #10b981);
        transition: all 0.3s ease;
    }
    .gradient-button:hover {
        background: linear-gradient(135deg, #2563eb, #059669);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    /* Styling untuk input dengan ikon */
    .input-group { position: relative; }
    .input-group i.left-icon {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280;
    }
    .input-group input { padding-left: 40px; }
    .input-group input.with-toggle { padding-right: 40px; }
    .input-group .toggle-password {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        cursor: pointer; color: #6b7280; z-index: 10;
    }
    /* Animasi untuk form */
    .form-container { animation: fadeIn 0.5s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    /* Placeholder styling */
    input::placeholder { color: #9ca3af; font-style: italic; opacity: 0.8; }
    /* Header/logo dan divider */
    .header-content {
        margin-bottom: 0.25rem;
    }
    .logo-img {
        height: clamp(6rem, 15vw, 8rem);
        width: auto;
        display: block;
        margin: 1rem auto 5rem auto;
    }

    /* PERUBAHAN: Styling untuk judul utama dan subtitle */
    .title-container {
        margin-bottom: 0.5rem;
    }
    .title-text {
        /* PERUBAHAN: Ukuran font minimum diperbesar dari 3.25rem menjadi 3.50rem */
         font-size: clamp(3.1rem, 8vw, 4.5rem);
        font-weight: 800;
        color: #1f2937;
        text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.08);
        letter-spacing: -0.03em;
        line-height: 1.1;
        white-space: nowrap; /* Mencegah teks pindah baris */
        display: inline-block; /* Memungkinkan white-space bekerja dengan baik */
    }
    .subtitle-text {
        font-size: clamp(1.1rem, 3.5vw, 1.75rem);
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    .welcome-back {
        /* PERUBAHAN: Ukuran font minimum diperbesar dari 3.25rem menjadi 3.50rem */
        font-size: clamp(3.1rem, 8vw, 4.5rem);
        font-weight: 800;
        color: #1f2937;
        text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.08);
        letter-spacing: -0.03em;
        line-height: 1.1;
        margin-top: -0.3rem; /* Mengurangi jarak antar baris */
        display: block; /* Memastikan berada di baris baru */
    }

    .divider {
        width: clamp(3rem, 10vw, 5rem);
        height: 0.25rem;
        background: linear-gradient(to right, #3b82f6, #10b981);
        border-radius: 9999px;
        margin: 0.125rem 0 0.25rem;
    }
    .divider-1 {
        width: clamp(8rem, 25vw, 15rem);
        height: 0.25rem;
        background: linear-gradient(to right, #3b82f6, #10b981);
        border-radius: 9999px;
        margin: 0.125rem 0 clamp(2rem, 5vw, 3rem);
        margin-bottom: 5rem;
    }
    .divider-2 {
        width: clamp(3rem, 8vw, 5rem);
        height: 0.25rem;
        background: linear-gradient(to right, #3b82f6, #10b981);
        border-radius: 9999px;
        margin: 0.125rem 0 0.25rem;
    }
    .styledivider{
        display: flex;
        justify-content: center;
        justify-content: space-between;
        width: 90%;
    }
    /* Card form */
    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1rem 1rem 0 0;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        width: 100%; max-width: 100%; margin: 0;
        min-height: calc(100vh - var(--header-height, 0px));
    }
    .jus-1{
        width: 100%;
        display: flex;
        justify-content: center;
    }
    /* Label & input */
    .form-label { font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block; }
    .form-input { border-radius: 0.5rem; border: 1px solid #d1d5db; transition: all 0.2s; width: 100%; }
    .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    /* Container lebar penuh */
    .full-width-container { width: 100%; max-width: 100%; padding: 0; }
    /* Link login/register */
    .login-link-container { margin-top: 0.75rem; padding-top: 0.75rem; text-align: center; }
    .login-link-text { color: #6b7280; font-size: 0.875rem; }
    .login-link { color: #3b82f6; font-weight: 500; text-decoration: none; transition: color 0.2s; }
    .login-link:hover { color: #2563eb; text-decoration: underline; }
    /* Layout utama */
    .main-container { display: flex; flex-direction: column; }
    .form-section { flex-grow: 1; display: flex; flex-direction: column; }

    /* Responsif improvements */
    @media (max-width: 640px) {
        .space-y-5 > * + * {
            margin-top: 1.25rem;
        }
        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        /* Pastikan heading tetap bagus di layar sangat kecil */
        .title-text {
            letter-spacing: -0.01em;
        }
        .welcome-back {
            letter-spacing: -0.01em;
        }
    }

    /* Loading state untuk tombol */
    .loading-state {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }
    .loading-state::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Desktop Responsive Styles */
    @media (min-width: 1024px) {
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            overflow: hidden; /* Prevent scroll on desktop if content fits */
        }
        .main-container {
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 6rem;
            width: 100%;
            max-width: 1280px;
            padding: 2rem;
        }
        .header-wrapper {
            flex: 1;
            max-width: 600px;
            display: block !important;
            padding: 0 !important;
        }
        .header-content {
            text-align: left !important;
            margin-bottom: 2rem;
        }
        .logo-img {
            margin: 0 0 1rem 0 !important;
            height: 5rem !important;
        }
        /* Override text alignment for desktop */
        .header-wrapper .text-left {
            padding: 0 !important;
            text-align: left !important;
        }
        .form-section {
            width: 420px;
            flex: none;
        }
        .form-card {
            min-height: auto !important;
            height: auto !important;
            border-radius: 1.5rem !important;
            padding: 2.5rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        }
        /* Hide decorative dividers on desktop to clean up UI */
        .jus-1, .styledivider, .divider-1 {
            display: none !important;
        }
        .divider {
            margin-top: 1.5rem;
            width: 100px;
        }
    }
</style>
</head>
<body class="min-h-screen text-gray-900 bg-gradient-to-br from-blue-50 to-green-50">
    <div class="main-container">
        <div class="flex justify-center items-center px-0 py-2">
            <div class="full-width-container">
                <!-- Struktur diubah: Logo di tengah, Teks di kiri -->
                <div class="text-center header-content">
                    <img src="{{ asset('img/logocashflowjets.png') }}" alt="Logo" class="logo-img">
                </div>
                <div class="px-6 text-left"> <!-- Teks rata kiri dengan padding -->
                    <!-- PERUBAHAN: Memisahkan "Selamat Datang" dan "Kembali" -->
                    <div class="title-container">
                        <h1 class="title-text">Selamat Datang</h1>
                        @if(request('just_registered') || session('just_registered'))
                            <h2 class="welcome-back">Kembali</h2>
                        @endif
                    </div>
                    <p class="subtitle-text">Kelola Keuangan & Capailah Impian</p>
                    <div class="divider"></div>
                    <div class="divider-1"></div>
                </div>
            </div>
        </div>

        <div class="form-section">
           <div class="jus-1">
              <div class="styledivider">
                        <div class="divider-2"></div>
                        <div class="divider-2"></div>
                    </div>
           </div>
            <div class="full-width-container">
                <!-- Hapus notifikasi HTML biasa dan ganti dengan SweetAlert di JavaScript -->

                <form method="POST" action="/login" id="loginForm" class="px-6 py-4 form-card form-container">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <i class="fa-solid fa-users left-icon"></i>
                                <input type="text" id="username" name="username" value="{{ old('username') }}" required
                                       placeholder="Masukkan username"
                                       class="block px-3 py-3 w-full form-input" />
                            </div>
                        </div>
                        <div>
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <i class="fa-solid fa-lock left-icon"></i>
                                <input type="password" id="password" name="password" required
                                       placeholder="Masukkan kata sandi"
                                       class="block px-3 py-3 w-full form-input with-toggle" />
                                <i class="fa-solid fa-key toggle-password" data-target="password"></i>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">Lupa kata sandi?</a>
                        </div>

                        <button type="submit" id="loginButton" class="px-4 py-3 w-full font-medium text-white rounded-lg transition duration-300 gradient-button focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Masuk
                        </button>

                        <div class="login-link-container">
                            <p class="login-link-text">Belum punya akun?
                                <a href="{{ route('register') }}" class="login-link">Daftar sekarang</a>
                            </p>
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

            // Handle form submission with SweetAlert
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');

            loginForm.addEventListener('submit', function(e) {
                // Tambahkan state loading pada tombol
                loginButton.classList.add('loading-state');
                loginButton.disabled = true;

                // Tidak perlu mencegah default, biarkan form submit normal
                // Response akan ditangani di bagian bawah
            });

            // Tampilkan SweetAlert untuk notifikasi pendaftaran berhasil
            @if(request('just_registered') || session('just_registered'))
                Swal.fire({
                    title: 'Pendaftaran Berhasil!',
                    text: 'Akun Anda telah berhasil dibuat. Silakan masuk untuk melanjutkan.',
                    icon: 'success',
                    confirmButtonText: 'OK, Siap!',
                    confirmButtonColor: '#10b981',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    backdrop: `
                        rgba(16, 185, 129, 0.1)
                        left top
                        no-repeat
                    `
                });
            @endif

            // Check for error messages from server and display with SweetAlert
            @if(session('error_type'))
                const errorType = '{{ session('error_type') }}';
                let title, icon, text;

                switch(errorType) {
                    case 'username':
                        title = 'Username Tidak Ditemukan';
                        text = 'Username yang Anda masukkan tidak terdaftar dalam sistem kami.';
                        icon = 'error';
                        break;
                    case 'password':
                        title = 'Kata Sandi Salah';
                        text = 'Kata sandi yang Anda masukkan tidak cocok dengan username tersebut.';
                        icon = 'warning';
                        break;
                    case 'both':
                        title = 'Login Gagal';
                        text = 'Username dan kata sandi yang Anda masukkan tidak cocok.';
                        icon = 'error';
                        break;
                    default:
                        title = 'Terjadi Kesalahan';
                        text = 'Silakan coba lagi atau hubungi administrator.';
                        icon = 'error';
                }

                // Tampilkan SweetAlert
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    timer: 3000, // Tampilkan selama 3 detik
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            @endif

            // Hapus state loading jika ada error
            @if($errors->any())
                loginButton.classList.remove('loading-state');
                loginButton.disabled = false;
            @endif
        });
    </script>
</body>
</html>
