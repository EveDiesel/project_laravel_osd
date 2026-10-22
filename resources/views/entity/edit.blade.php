<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Entity - OSEDU Bright Future</title>

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
            <div class="content-body" style="width: 100%;">
                <div class="dashboard-card">
                    
                    <div class="header-section" style="margin-bottom: 20px;">
                        <h2 class="card-title" style="font-size: 1.25rem;">Edit Entity</h2>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Ubah data informasi entity/perusahaan di bawah ini.</p>
                    </div>

                    <!-- Alert Validasi Error -->
                    @if ($errors->any())
                        <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Edit Data Entity -->
                    <form action="{{ route('entity.update', $entity->id) }}" method="POST" enctype="multipart/form-data" style="width: 100%;">
                        @csrf
                        @method('PUT')

                        <div style="display: flex; flex-direction: column; gap: 18px;">
                            
                            <!-- Tipe Akun -->
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Tipe Akun <span style="color: #ef4444;">*</span></label>
                                <select name="is_company" id="is_company" class="filter-select" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" required>
                                    <option value="0" {{ old('is_company', $entity->is_company) == 0 ? 'selected' : '' }}>Personal</option>
                                    <option value="1" {{ old('is_company', $entity->is_company) == 1 ? 'selected' : '' }}>Company</option>
                                </select>
                            </div>

                            <!-- Pilih User (Hanya Tampil Jika Personal) -->
                            <div id="user_id_field" style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Pilih User Terkait <span style="color: #ef4444;">*</span></label>
                                <select name="user_id" id="user_id" class="filter-select" style="width: 100%; padding: 8px 12px; box-sizing: border-box;">
                                    <option value="">-- Pilih User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $entity->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nama Entity -->
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Nama Entity / Perusahaan <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="name" class="search-input" placeholder="Masukkan nama..." style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('name', $entity->name) }}" required>
                            </div>

                            <!-- Email & Phone -->
                            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 240px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Email</label>
                                    <input type="email" name="email" class="search-input" placeholder="email@example.com" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('email', $entity->email) }}">
                                </div>
                                <div style="flex: 1; min-width: 240px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Telepon / Whatsapp <span style="color: #ef4444;">*</span></label>
                                    <input type="text" name="phone" class="search-input" placeholder="+62..." style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('phone', $entity->phone) }}" required>
                                </div>
                            </div>

                            <!-- Address & City -->
                            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                <div style="flex: 2; min-width: 240px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Alamat Lengkap <span style="color: #ef4444;">*</span></label>
                                    <input type="text" name="address" class="search-input" placeholder="Masukkan alamat..." style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('address', $entity->address) }}" required>
                                </div>
                                <div style="flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Kota</label>
                                    <input type="text" name="city" class="search-input" placeholder="Contoh: Batam" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('city', $entity->city) }}">
                                </div>
                            </div>

                            <!-- Birthdate & Gender (Khusus Personal) -->
                            <div id="personal_fields" style="display: flex; gap: 15px; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Tanggal Lahir</label>
                                    <input type="date" name="birthdate" id="birthdate" class="search-input" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('birthdate', $entity->birthdate ? \Carbon\Carbon::parse($entity->birthdate)->format('Y-m-d') : '') }}">
                                </div>
                                <div style="flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 6px;">
                                    <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Jenis Kelamin</label>
                                    <select name="gender" id="gender" class="filter-select" style="width: 100%; padding: 8px 12px; box-sizing: border-box;">
                                        <option value="">-- Pilih Gender --</option>
                                        <option value="male" {{ old('gender', $entity->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ old('gender', $entity->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                        <option value="rather_not_say" {{ old('gender', $entity->gender) == 'rather_not_say' ? 'selected' : '' }}>Privasi</option>
                                    </select>
                                </div>
                            </div>

                            <!-- File / URL Avatar -->
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">Avatar URL (Opsional)</label>
                                <input type="url" name="avatar_url" class="search-input" placeholder="https://..." style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('avatar_url', $entity->avatar_url) }}">
                            </div>

                            <!-- Tombol Aksi -->
                            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; border-top: 1px solid #1f2937; padding-top: 16px;">
                                <a href="{{ route('entity.index') }}" class="btn-action btn-delete" style="padding: 8px 18px; text-decoration: none; font-size: 0.85rem;">Batal</a>
                                <button type="submit" class="btn-primary" style="padding: 8px 18px; font-size: 0.85rem;">Update Entity</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </main>

    </div>

    <!-- Script JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isCompanySelect = document.getElementById('is_company');
            const userIdField = document.getElementById('user_id_field');
            const personalFields = document.getElementById('personal_fields');

            const userIdInput = document.getElementById('user_id');
            const birthdateInput = document.getElementById('birthdate');
            const genderInput = document.getElementById('gender');

            function toggleFields() {
                if (isCompanySelect.value === "1") {
                    userIdField.style.display = 'none';
                    personalFields.style.display = 'none';

                    userIdInput.removeAttribute('required');
                    if(birthdateInput) birthdateInput.removeAttribute('required');
                    if(genderInput) genderInput.removeAttribute('required');
                } else {
                    userIdField.style.display = 'flex';
                    personalFields.style.display = 'flex';

                    userIdInput.setAttribute('required', 'required');
                }
            }

            isCompanySelect.addEventListener('change', toggleFields);
            toggleFields();

            // Dropdown Profile Navbar
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