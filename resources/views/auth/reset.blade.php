<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Kata Sandi - {{ config('app.name', 'CashFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-6">
                <img src="/logo.svg" alt="Logo" class="mx-auto h-16 w-16">
                <h1 class="mt-4 text-2xl font-semibold">Reset Kata Sandi</h1>
                <p class="mt-1 text-sm text-gray-600">Fitur reset kata sandi belum diaktifkan.</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-sm text-gray-700">Token: {{ $token }}</p>
                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500">Kembali ke Masuk</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>