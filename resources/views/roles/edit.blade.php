<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Role - OSEDU Bright Future</title>

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
    <div class="app-layout" style="display: flex; width: 100%; min-height: calc(100vh - 80px);">
        
        <!-- SIDEBAR -->
        @include('sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="main-content">
            <div class="content-body" style="width: 100%;">
                
                <!-- Notifikasi Error Validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger" style="padding: 12px 16px; background-color: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="dashboard-card">
                    
                    <!-- Header Section -->
                    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <div>
                            <h2 class="card-title" style="font-size: 1.25rem;">Edit Role: {{ $role->name }}</h2>
                            <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Ubah nama role dan kelola hak akses (permission) terkait.</p>
                        </div>
                        <a href="{{ route('roles.index') }}" class="btn-secondary" style="text-decoration: none; font-size: 0.85rem; padding: 8px 16px; background: #334155; color: #fff; border-radius: 6px;">← Kembali</a>
                    </div>

                    <!-- Form Edit Role -->
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Input Nama Role -->
                        <div style="margin-bottom: 20px;">
                            <label for="name" style="display: block; font-size: 0.9rem; font-weight: 500; color: #e2e8f0; margin-bottom: 8px;">Nama Role</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" placeholder="Contoh: Editor, Staff, dll." required 
                                style="width: 100%; padding: 10px 14px; background: #1e293b; border: 1px solid #334155; border-radius: 6px; color: #f8fafc; font-size: 0.9rem;">
                        </div>

                        <!-- Daftar Checkbox Permission -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-size: 0.9rem; font-weight: 500; color: #e2e8f0; margin-bottom: 10px;">Assign Permissions</label>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; background: #1e293b; padding: 16px; border: 1px solid #334155; border-radius: 6px;">
                                @forelse($permissions as $permission)
                                    <label style="display: flex; align-items: center; gap: 8px; color: #cbd5e1; font-size: 0.85rem; cursor: pointer;">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                            {{ in_array($permission->name, $rolePermissions ?? []) ? 'checked' : '' }}
                                            style="accent-color: #2563eb; width: 16px; height: 16px;">
                                        {{ $permission->name }}
                                    </label>
                                @empty
                                    <span style="color: #94a3b8; font-size: 0.85rem;">Belum ada permission yang dibuat.</span>
                                @endforelse
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 30px;">
                            <a href="{{ route('roles.index') }}" style="text-decoration: none; padding: 10px 20px; background: #334155; color: #cbd5e1; border-radius: 6px; font-size: 0.9rem;">Batal</a>
                            <button type="submit" class="btn-primary" style="padding: 10px 20px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 0.9rem; cursor: pointer;">Simpan Perubahan</button>
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