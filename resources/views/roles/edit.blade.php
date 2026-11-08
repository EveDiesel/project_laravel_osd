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
    <div class="app-layout">
        
        <!-- SIDEBAR -->
        @include('sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="main-content">
            <div class="content-body" style="width: 100%;">
                <div class="dashboard-card">
                    
                    <div class="header-section" style="margin-bottom: 20px;">
                        <h2 class="card-title" style="font-size: 1.25rem;">Edit Role</h2>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Ubah data informasi role di bawah ini.</p>
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

                        <!-- Form Edit Role -->
                        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="crud-form" style="width: 100%;">
                            @csrf
                            @method('PUT')

                            <!-- Nama Role -->
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label for="name" class="form-label">Nama Role</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}" placeholder="Masukkan nama role..." required style="width: 100%; padding: 8px 12px;">
                            </div>

                            <!-- Daftar Permission (Checkbox List) -->
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label class="form-label" style="margin-bottom: 8px; display: block;">Daftar Permissions</label>
                                
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; background: #0d121f; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
                                    @foreach($permissions as $permission)
                                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #cbd5e1; cursor: pointer;">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                style="accent-color: #2563eb; width: 16px; height: 16px;">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                        <!-- Tombol Aksi -->
                        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; border-top: 1px solid #1f2937; padding-top: 16px;">
                            <a href="{{ route('roles.index') }}" class="btn-action btn-delete" style="padding: 8px 18px; text-decoration: none; font-size: 0.85rem;">Batal</a>
                            <button type="submit" class="btn-primary" style="padding: 8px 18px; font-size: 0.85rem;">Perbarui Role</button>
                        </div>

                    </form>

                </div>
            </div>
        </main>

    </div>

    <!-- Script JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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