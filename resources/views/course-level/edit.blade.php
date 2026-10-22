<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

```
<title>Edit Course Level - OSEDU Bright Future</title>

<!-- Font Inter -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

<!-- Style CSS -->
@vite(['resources/css/style.css', 'resources/css/crud.css'])
```

</head>
<body class="dashboard-body">

```
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
                    <h2 class="card-title" style="font-size: 1.25rem;">Edit Course Level</h2>
                    <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Ubah data informasi course level di bawah ini.</p>
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

                <!-- Form Edit Course Level -->
<form action="{{ route('course-level.update', ['course_level' => $course_level->id]) }}" method="POST" style="width: 100%;">
    @csrf
    @method('PUT')
        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="course_id" class="form-label">Course ID</label>
                            <select name="course_id" id="course_id" class="form-control filter-select" required style="width: 100%; padding: 8px 12px;">
                                <option value="" disabled>Pilih Course ID...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $course_level->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->name ?? $course->title ?? 'Level #' . $course->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
        
        <!-- Nama Course Level -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 13px; color: #94a3b8; font-weight: 500px;">Nama Course Level <span style="color: #ef4444;">*</span></label>
            <input type="text" name="name" class="search-input" placeholder="Masukkan nama level..." style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('name', $course_level->name) }}" required>
        </div>

        <!-- Level & Meeting Count -->
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 240px; display: flex; flex-direction: column; gap: 6px;">
                <label style="font-size: 13px; color: #94a3b8; font-weight: 500px;">Level <span style="color: #ef4444;">*</span></label>
                <input type="number" name="level" class="search-input" placeholder="Contoh: 1" min="1" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('level', $course_level->level) }}" required>
            </div>
        </div>

        <!-- Meeting Minutes -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 13px; color: #94a3b8; font-weight: 500px;">Durasi Pertemuan (Menit) <span style="color: #ef4444;">*</span></label>
            <input type="number" name="duration" class="search-input" placeholder="Contoh: 90" min="1" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('duration', $course_level->duration) }}" required>
        </div>

        <!-- Order Index -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 13px; color: #94a3b8; font-weight: 500px;">Urutan <span style="color: #ef4444;">*</span></label>
            <input type="number" name="order_index" class="search-input" placeholder="Contoh: 1" min="1" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('order_index', $course_level->order_index) }}" required>
        </div>

        <!-- Status -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 13px; color: #94a3b8; font-weight: 500px;">Status <span style="color: #ef4444;">*</span></label>
            <select name="is_active" class="filter-select" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" required>
                <option value="1" {{ old('is_active', $course_level->is_active) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active', $course_level->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Tombol Aksi -->
        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; border-top: 1px solid #1f2937; padding-top: 16px;">
            <a href="{{ route('course-level.index') }}" class="btn-action btn-delete" style="padding: 8px 18px; text-decoration: none; font-size: 0.85rem;">Batal</a>
            <button type="submit" class="btn-primary" style="padding: 8px 18px; font-size: 0.85rem;">Update Course Level</button>
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
```

</body>
</html>
