<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit User - OSEDU Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Style CSS -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])

    <style>
        /* Memaksa layout agar kontainer kanan mengambil sisa ruang secara penuh */
        .app-layout {
            display: flex !important;
            width: 100% !important;
        }
        .main-content {
            flex: 1 1 auto !important;
            width: 100% !important;
            min-width: 0 !important;
        }
        .content-body, .dashboard-card {
            width: 100% !important;
            box-sizing: border-box !important;
        }
    </style>
</head>
<body class="dashboard-body">

    <!-- TOP NAVBAR -->
    <header class="navbar-top">
        <a href="{{ route('dashboard') }}" class="navbar-brand" style="display: flex; align-items: center;">
            <img src="{{ Vite::asset('resources/image/logoosedu.png') }}" alt="OSEDU Logo" style="height: 120px; width: auto;">
        </a>

        <!-- Profile & Logout Dropdown -->
        <div class="user-dropdown-wrapper">
            <button class="dropdown-trigger" id="userMenuBtn">
                <span>{{ Auth::user()->name ?? Auth::user()->email }}</span>
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Content -->
            <div class="dropdown-menu-card" id="userMenuDropdown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profile</span>
                </a>

                <hr class="dropdown-divider">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item logout-danger">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- WRAPPER DASHBOARD -->
    <div class="app-layout">
        
        <!-- SIDEBAR -->
        @include('sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="main-content">
            <div class="content-body">
                <div class="dashboard-card">
                    
                    <!-- Header Section (Tanpa Tombol Kembali) -->
                    <div class="header-section" style="margin-bottom: 24px;">
                        <h2 class="card-title" style="font-size: 1.25rem;">Edit Data Pengguna</h2>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Ubah informasi data pengguna di bawah ini.</p>
                    </div>

                    <!-- Alert Error Validation -->
                    @if ($errors->any())
                        <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Edit -->
                    <form action="{{ route('users.update', $user->id) }}" method="POST" style="width: 100%;">
                        @csrf
                        @method('PUT')

                        <div style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                            
                            <!-- Email -->
                            <div style="display: flex; flex-direction: column; gap: 6px; width: 100%;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Alamat Email</label>
                                <input type="email" name="email" class="search-input" value="{{ old('email', $user->email) }}" placeholder="email@gmail.com" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" required>
                            </div>

                            <!-- Password Baru & Konfirmasi Password -->
                            <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">
                                        Password Baru <small style="color: #6b7280; font-weight: normal;">(Kosongkan jika tidak diubah)</small>
                                    </label>
                                    <input type="password" name="password" class="search-input" placeholder="••••••••" style="width: 100%; padding: 10px 14px; box-sizing: border-box;">
                                </div>

                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="search-input" placeholder="••••••••" style="width: 100%; padding: 10px 14px; box-sizing: border-box;">
                                </div>
                            </div>

                            <!-- Role & Status -->
                            <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Role Pengguna</label>
                                    <select name="role" class="filter-select" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" required>
                                        <option value="" disabled>Pilih Role...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Status Akun</label>
                                    <select name="is_active" class="filter-select" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" required>
                                        <option value="1" {{ old('is_active', $user->is_active) == '1' || old('is_active', $user->is_active) === true ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $user->is_active) == '0' || old('is_active', $user->is_active) === false ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px; border-top: 1px solid #1f2937; padding-top: 20px;">
                                <a href="{{ route('users.index') }}" class="btn-action btn-delete" style="padding: 10px 20px; text-decoration: none; font-size: 0.85rem;">Batal</a>
                                <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 0.85rem;">Perbarui Pengguna</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </main>

    </div>

    <!-- Script Dropdown Profile -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('userMenuBtn');
            const dropdown = document.getElementById('userMenuDropdown');

            if (btn && dropdown) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdown.classList.toggle('show');
                });

                document.addEventListener('click', () => {
                    dropdown.classList.remove('show');
                });
            }
        });
    </script>

</body>
</html>