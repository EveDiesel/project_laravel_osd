<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Course Level - OSEDU Bright Future</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/style.css', 'resources/css/crud.css'])
</head>
<body class="dashboard-body">

    <!-- TOP NAVBAR -->
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

            <div class="dropdown-menu-card" id="userMenuDropdown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7-7h14a7 7 0 00-7 7z" />
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

    <!-- WRAPPER -->
    <div class="app-layout" style="margin-top: 72px; display: flex; width: 100%;">

        <!-- SIDEBAR -->
        @include('sidebar')

        <!-- MAIN CONTENT -->
        <main class="main-content" style="margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; flex: 1;">
            <div class="content-body" style="width: 100%;">
                <div class="dashboard-card" style="padding: 24px; width: 100% !important; max-width: 100% !important; box-sizing: border-box;">

                    <div class="card-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h2 class="card-title" style="font-size: 1.25rem;">Tambah Course Level Baru</h2>
                            <p class="card-desc" style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">
                                Isi formulir di bawah ini untuk menambahkan course level baru.
                            </p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('course-level.store') }}" method="POST" class="crud-form" style="width: 100%;">
                        @csrf

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="course_id" class="form-label">Course</label>
                                <select name="course_id" id="course_id" class="form-control" required style="width: 100%; padding: 8px 12px;">
                                    <option value="">Pilih Course</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>

                        <!-- NAME -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="name" class="form-label">Nama Level</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama level..." required style="width: 100%; padding: 8px 12px;">
                        </div>

                        <!-- LEVEL -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="level" class="form-label">Level</label>
                            <input type="number" name="level" id="level" class="form-control" value="{{ old('level') }}" placeholder="Masukkan level..." min="1" required style="width: 100%; padding: 8px 12px;">
                        </div>

                        <!-- MEETING MINUTES -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="duration" class="form-label">Meeting Duration</label>
                            <input type="number" name="duration" id="duration" class="form-control" value="{{ old('duration') }}" placeholder="Masukkan durasi meeting..." min="1" required style="width: 100%; padding: 8px 12px;">
                        </div>

                        <!-- ORDER INDEX -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="order_index" class="form-label">Order Index</label>
                            <input type="number" name="order_index" id="order_index" class="form-control" value="{{ old('order_index') }}" placeholder="Masukkan urutan..." min="1" required style="width: 100%; padding: 8px 12px;">
                        </div>

                        <!-- STATUS -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="is_active" class="form-label">Status</label>
                            <select name="is_active" id="is_active" class="form-control filter-select" required style="width: 100%; padding: 8px 12px;">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- ACTIONS -->
                        <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 16px;">
                            <a href="{{ route('course-level.index') }}" class="btn-action btn-delete" style="padding: 8px 18px; text-decoration: none; font-size: 0.85rem;">Batal</a>
                            <button type="submit" class="btn-primary" style="padding: 8px 18px; font-size: 0.85rem;">Simpan Course Level</button>
                        </div>
                    </form>

                </div>
            </div>
        </main>
    </div>

    <!-- DROPDOWN SCRIPT -->
    <script>
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
    </script>
</body>
</html>