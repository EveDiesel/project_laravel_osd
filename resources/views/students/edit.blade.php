<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Siswa - OSEDU Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Style CSS -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- WRAPPER DASHBOARD -->
    <div class="app-layout" style="display: flex; width: 100%; min-height: calc(100vh - 80px);">

        <!-- SIDEBAR -->
        @include('sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="main-content">
            <div class="content-body">
                <div class="dashboard-card">

                    <!-- Header -->
                    <div class="header-section" style="margin-bottom: 24px;">
                        <h2 class="card-title" style="font-size: 1.25rem;">Edit Siswa</h2>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Perbarui profil dan status akun siswa.</p>
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
                    <form action="{{ route('students.update', $student->id) }}" method="POST" style="width: 100%;">
                        @csrf
                        @method('PUT')

                        <div style="display: flex; flex-direction: column; gap: 20px; width: 100%;">

                            <!-- Nama -->
                            <div style="display: flex; flex-direction: column; gap: 6px; width: 100%;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="name" class="search-input" placeholder="Nama siswa" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('name', $student->entity->name) }}" required>
                            </div>

                            <!-- Email & Phone -->
                            <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Alamat Email <span style="color: #ef4444;">*</span></label>
                                    <input type="email" name="email" class="search-input" placeholder="email@gmail.com" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('email', $student->email) }}" required>
                                </div>
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">No. Telepon <span style="color: #ef4444;">*</span></label>
                                    <input type="text" name="phone" class="search-input" placeholder="08xxxxxxxxxx" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('phone', $student->entity->phone) }}" required>
                                </div>
                            </div>

                            <!-- Password (opsional) -->
                            <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Password Baru <span style="color: #94a3b8;">(opsional)</span></label>
                                    <input type="password" name="password" class="search-input" placeholder="Biarkan kosong jika tidak diubah" style="width: 100%; padding: 10px 14px; box-sizing: border-box;">
                                </div>
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="search-input" placeholder="••••••••" style="width: 100%; padding: 10px 14px; box-sizing: border-box;">
                                </div>
                            </div>

                            <!-- City & Birthdate -->
                            <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Kota <span style="color: #ef4444;">*</span></label>
                                    <input type="text" name="city" class="search-input" placeholder="Kota domisili" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('city', $student->entity->city) }}" required>
                                </div>
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Tanggal Lahir</label>
                                    <input type="date" name="birthdate" class="search-input" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" value="{{ old('birthdate', $student->entity->birthdate) }}">
                                </div>
                            </div>

                            <!-- Address -->
                            <div style="display: flex; flex-direction: column; gap: 6px; width: 100%;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Alamat <span style="color: #ef4444;">*</span></label>
                                <textarea name="address" class="search-input" rows="3" placeholder="Alamat lengkap" style="width: 100%; padding: 10px 14px; box-sizing: border-box; resize: vertical;" required>{{ old('address', $student->entity->address) }}</textarea>
                            </div>

                            <!-- Gender & Status -->
                            <div style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Jenis Kelamin</label>
                                    <select name="gender" class="filter-select" style="width: 100%; padding: 10px 14px; box-sizing: border-box;">
                                        <option value="">Pilih...</option>
                                        <option value="male" {{ old('gender', $student->entity->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $student->entity->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="rather_not_say" {{ old('gender', $student->entity->gender) === 'rather_not_say' ? 'selected' : '' }}>Rather Not Say</option>
                                    </select>
                                </div>
                                <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Status Akun <span style="color: #ef4444;">*</span></label>
                                    <select name="is_active" class="filter-select" style="width: 100%; padding: 10px 14px; box-sizing: border-box;" required>
                                        <option value="1" {{ old('is_active', $student->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $student->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px; border-top: 1px solid #1f2937; padding-top: 20px;">
                                <a href="{{ route('students.index') }}" class="btn-action btn-delete" style="padding: 10px 20px; text-decoration: none; font-size: 0.85rem;">Batal</a>
                                <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 0.85rem;">Simpan Perubahan</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </main>

    </div>

    <!-- Script JavaScript Dropdown Profile -->
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
