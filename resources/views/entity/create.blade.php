<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tambah Entity - OSEDU Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Style CSS -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])
</head>
<body class="dashboard-body">

    <!-- TOP NAVBAR (FIXED ON TOP) -->
    <header class="navbar-top" style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 72px; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; background-color: #0d121f; border-bottom: 1px solid #1a2235;">
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

    <!-- Wrapper Dashboard -->
    <div class="app-layout" style="margin-top: 72px; display: flex; width: 100%;">
        
        <!-- SIDEBAR -->
        @include('sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="main-content" style="margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; flex: 1;">
            <div class="content-body" style="width: 100%;">
                <div class="dashboard-card" style="padding: 24px; width: 100% !important; max-width: 100% !important; box-sizing: border-box;">
                    
                    <div class="card-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h2 class="card-title" style="font-size: 1.25rem;">Tambah Entity Baru</h2>
                            <p class="card-desc" style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Isi formulir di bawah ini untuk menambahkan entity baru.</p>
                        </div>
                    </div>

                    <!-- Alert Error Validation -->
                    @if ($errors->any())
                        <div class="alert alert-danger" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Create Entity -->
                    <form action="{{ route('entity.store') }}" method="POST" enctype="multipart/form-data" class="crud-form" style="width: 100%;">
                        @csrf

                        <!-- Tipe Entity -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="is_company" class="form-label">Tipe Entity</label>
                            <select name="is_company" id="is_company" class="form-control filter-select" required style="width: 100%; padding: 8px 12px;">
                                <option value="0" {{ old('is_company') == '0' ? 'selected' : '' }}>Personal (Terhubung ke Akun User)</option>
                                <option value="1" {{ old('is_company') == '1' ? 'selected' : '' }}>Company / Perusahaan</option>
                            </select>
                        </div>

                        <!-- Pilih User -->
                        <div class="form-group" id="user_select_group" style="margin-bottom: 16px;">
                            <label for="user_id" class="form-label">Pilih Akun User</label>
                            <select name="user_id" id="user_id" class="form-control filter-select" style="width: 100%; padding: 8px 12px;">
                                <option value="" disabled selected>Pilih User Eksisting...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->email }} (ID: {{ $user->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="name" class="form-label">Nama Entity / Nama Perusahaan</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama..." required style="width: 100%; padding: 8px 12px;">
                        </div>

                        <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                            <div class="form-group flex-1" style="flex: 1;">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="email@entity.com" style="width: 100%; padding: 8px 12px;">
                            </div>

                            <div class="form-group flex-1" style="flex: 1;">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="..." required style="width: 100%; padding: 8px 12px;">
                            </div>
                        </div>

                        <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                            <div class="form-group flex-1" style="flex: 1;">
                                <label for="address" class="form-label">Alamat Lengkap</label>
                                <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" placeholder="Jl. Contoh No. 123" required style="width: 100%; padding: 8px 12px;">
                            </div>

                            <div class="form-group flex-1" style="flex: 1;">
                                <label for="city" class="form-label">Kota / City</label>
                                <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" placeholder="Contoh: Batam" style="width: 100%; padding: 8px 12px;">
                            </div>
                        </div>

                        <!-- Personal Fields -->
                        <div class="form-row" id="personal_fields" style="display: flex; gap: 16px; margin-bottom: 16px;">
                            <div class="form-group flex-1" style="flex: 1;">
                                <label for="birthdate" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ old('birthdate') }}" style="width: 100%; padding: 8px 12px;">
                            </div>

                            <div class="form-group flex-1" style="flex: 1;">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select name="gender" id="gender" class="form-control filter-select" style="width: 100%; padding: 8px 12px;">
                                    <option value="" selected disabled>Pilih Jenis Kelamin...</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki (Male)</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan (Female)</option>
                                    <option value="rather_not_say" {{ old('gender') == 'rather_not_say' ? 'selected' : '' }}>Rather Not Say</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom File Upload Field -->
                        <div class="form-group" style="margin-bottom: 24px;">
                            <label class="form-label">Logo / Avatar File (Atau URL)</label>
                            
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

                            <input type="url" name="avatar_url" id="avatar_url" class="form-control" style="margin-top: 12px; width: 100%; padding: 8px 12px;" placeholder="Atau tempel URL Gambar https://..." value="{{ old('avatar_url') }}">
                        </div>

                        <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 16px;">
                            <a href="{{ route('entity.index') }}" class="btn-action btn-delete" style="padding: 8px 18px; text-decoration: none; font-size: 0.85rem;">Batal</a>
                            <button type="submit" class="btn-primary" style="padding: 8px 18px; font-size: 0.85rem;">Simpan Entity</button>
                        </div>
                    </form>

                </div>
            </div>
        </main>

    </div>

    <!-- Script Dynamic Toggle, Profile Dropdown & File Name Display -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isCompanySelect = document.getElementById('is_company');
            const userSelectGroup = document.getElementById('user_select_group');
            const personalFields = document.getElementById('personal_fields');

            function toggleFields() {
                if (isCompanySelect.value === '1') { 
                    userSelectGroup.style.display = 'none';
                    personalFields.style.display = 'none';
                } else { 
                    userSelectGroup.style.display = 'block';
                    personalFields.style.display = 'flex';
                }
            }

            isCompanySelect.addEventListener('change', toggleFields);
            toggleFields();

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

            // Script menampilkan nama file terpilih
            const fileInput = document.getElementById('avatar');
            const fileNameDisplay = document.getElementById('fileNameDisplay');

            if (fileInput) {
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