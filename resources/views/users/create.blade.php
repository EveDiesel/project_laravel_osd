<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create User - OSEDU Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Style CSS -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])

    <style>
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
        .section-divider {
            border-top: 1px solid #1e293b;
            padding-top: 20px;
            margin-top: 8px;
        }
        .section-subtitle {
            font-size: 0.95rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 14px;
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
                    
                    <!-- Header Section -->
                    <div class="header-section" style="margin-bottom: 24px;">
                        <h2 class="card-title" style="font-size: 1.25rem;">Tambah Pengguna Baru</h2>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Lengkapi data akun dan profil personal (entity) pengguna baru.</p>
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

                    <!-- Form Create (Tambahkan enctype untuk upload avatar) -->
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" style="width: 100%;">
                        @csrf

                        <div style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                            
                            <!-- BAGIAN 1: INFORMASI AKUN -->
                            <div class="section-subtitle" style="margin-bottom: 0;">1. Informasi Akun</div>

                            <!-- Nama Lengkap & Email -->
                            <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                                    <input type="text" name="name" class="search-input" placeholder="Masukkan nama lengkap..." style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('name') }}" required>
                                </div>

                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Alamat Email <span style="color: #ef4444;">*</span></label>
                                    <input type="email" name="email" class="search-input" placeholder="email@gmail.com" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <!-- Password & Konfirmasi Password -->
                            <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Password <span style="color: #ef4444;">*</span></label>
                                    <input type="password" name="password" class="search-input" placeholder="••••••••" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" required>
                                </div>

                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Konfirmasi Password <span style="color: #ef4444;">*</span></label>
                                    <input type="password" name="password_confirmation" class="search-input" placeholder="••••••••" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" required>
                                </div>
                            </div>

                            <!-- Role & Status -->
                            <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Role Pengguna <span style="color: #ef4444;">*</span></label>
                                    <select name="role" class="filter-select" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" required>
                                        <option value="" disabled selected>Pilih Role...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Status Akun <span style="color: #ef4444;">*</span></label>
                                    <select name="is_active" class="filter-select" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" required>
                                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- BAGIAN 2: PROFIL ENTITY (PERSONAL) -->
                            <div class="section-divider">
                                <div class="section-subtitle">2. Data Profil Entity</div>

                                <div style="display: flex; flex-direction: column; gap: 16px;">
                                    <!-- Nomor Telepon & Kota -->
                                    <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                        <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                            <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Nomor Telepon <span style="color: #ef4444;">*</span></label>
                                            <input type="text" name="phone" class="search-input" placeholder="0812xxxxxxxx" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('phone') }}" required>
                                        </div>

                                        <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                            <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Kota / City <span style="color: #ef4444;">*</span></label>
                                            <input type="text" name="city" class="search-input" placeholder="Contoh: Batam" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('city') }}" required>
                                        </div>
                                    </div>

                                    <!-- Alamat Lengkap -->
                                    <div style="display: flex; flex-direction: column; gap: 6px; width: 100%;">
                                        <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Alamat Lengkap <span style="color: #ef4444;">*</span></label>
                                        <input type="text" name="address" class="search-input" placeholder="Jl. Contoh No. 123" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('address') }}" required>
                                    </div>

                                    <!-- Tanggal Lahir & Jenis Kelamin -->
                                    <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                        <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                            <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Tanggal Lahir</label>
                                            <input type="date" name="birthdate" class="search-input" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('birthdate') }}">
                                        </div>

                                        <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                            <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Jenis Kelamin</label>
                                            <select name="gender" class="filter-select" style="width: 100%; padding: 10px 14px; box-sizing: border-box;">
                                                <option value="" selected disabled>Pilih Jenis Kelamin...</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki (Male)</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan (Female)</option>
                                                <option value="rather_not_say" {{ old('gender') == 'rather_not_say' ? 'selected' : '' }}>Rather Not Say</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Avatar Upload / URL -->
                                    <div style="display: flex; flex-direction: column; gap: 6px; width: 100%;">
                                        <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Avatar / Foto Profil (Opsional)</label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" name="avatar" id="avatar" class="file-upload-input" accept="image/*">
                                            <div class="file-upload-box">
                                                <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                <div class="file-upload-text">Klik untuk pilih gambar atau <span>drag & drop</span></div>
                                                <div class="file-upload-hint">PNG, JPG, JPEG (Maks. 2MB)</div>
                                                <div class="file-selected-name" id="fileNameDisplay"></div>
                                            </div>
                                        </div>
                                        <input type="url" name="avatar_url" class="search-input" style="margin-top: 8px; width: 100%; padding: 10px 14px; box-sizing: border-box;" placeholder="Atau tempel URL Gambar https://... (Kosongkan untuk auto-avatar)" value="{{ old('avatar_url') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px; border-top: 1px solid #1f2937; padding-top: 20px;">
                                <a href="{{ route('users.index') }}" class="btn-action btn-delete" style="padding: 10px 20px; text-decoration: none; font-size: 0.85rem;">Batal</a>
                                <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 0.85rem;">Simpan Pengguna</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </main>

    </div>

    <!-- Script Dropdown & File Name Display -->
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

            const fileInput = document.getElementById('avatar');
            const fileNameDisplay = document.getElementById('fileNameDisplay');

            if (fileInput && fileNameDisplay) {
                fileInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        fileNameDisplay.textContent = '📄 File terpilih: ' + this.files[0].name;
                        fileNameDisplay.style.display = 'block';
                    } else {
                        fileNameDisplay.style.display = 'none';
                    }
                });
            }
        });
    </script>

</body>
</html>