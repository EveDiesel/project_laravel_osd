<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar - Portal Osedu Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/style.css'])
</head>
<body>

    <div class="login-container">
        <!-- Header & Logo -->
        <div class="brand-header">
            <div class="brand-logo">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m0 0v-5a2 2 0 012-2h2a2 2 0 012 2v5m-4 0h4" />
                </svg>
            </div>
            <h1 class="brand-title">Portal Osedu Bright Future</h1>
            <p class="brand-subtitle">Buat akun baru untuk mulai mengakses sistem</p>
        </div>

        <!-- Kartu Form Registrasi -->
        <div class="login-card">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Input Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="username" 
                        placeholder="email@gmail.com"
                        class="form-input"
                    >
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="form-input"
                    >
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Konfirmasi Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="form-input"
                    >
                    @error('password_confirmation')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn-submit">
                    Daftar Akun
                </button>

                <!-- Prompt Login -->
                <div class="register-prompt">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="register-link">Masuk di sini</a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="footer-text">
            &copy; {{ date('Y') }} Osedu Bright Future. Seluruh hak cipta dilindungi.
        </div>
    </div>

</body>
</html>