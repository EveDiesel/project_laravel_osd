<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Course - OSEDU Bright Future</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- CSS External via Vite -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])
</head>

<body class="dashboard-body">

    <!-- TOP NAVBAR -->
    <header class="navbar-top" style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 72px; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; background-color: #0d121f; border-bottom: 1px solid #1a2235;">

        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="navbar-brand" style="display: flex; align-items: center;">
            <img src="{{ Vite::asset('resources/image/logoosedu.png') }}" alt="OSEDU Logo" style="height: 120px; width: auto;">
        </a>

        <!-- User Profile Dropdown -->
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

    <!-- MAIN APP WRAPPER -->
    <div class="app-layout" style="margin-top: 72px; display: flex; width: 100%;">

        <!-- FIXED SIDEBAR -->
        @include('sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="main-content" style="margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; flex: 1;">
            <div class="content-body" style="width: 100%;">
                <div class="dashboard-card" style="padding: 24px; width: 100% !important; max-width: 100% !important; box-sizing: border-box;">

                    <!-- Card Header -->
                    <div class="card-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h2 class="card-title" style="font-size: 1.25rem;">Tambah Course Baru</h2>
                            <p class="card-desc" style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">
                                Isi formulir di bawah ini untuk menambahkan course baru.
                            </p>
                        </div>
                    </div>

                    <!-- Display Error Validasi -->
                    @if ($errors->any())
                        <div class="alert alert-danger" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Tambah Course -->
                    <form action="{{ route('course.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title Input -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="title" class="form-label">Judul Course</label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control"
                                   value="{{ old('title') }}"
                                   placeholder="Masukkan judul course..."
                                   required
                                   style="width: 100%; padding: 8px 12px;">
                        </div>

                        <!-- Description Input -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description"
                                      id="description"
                                      class="form-control"
                                      rows="5"
                                      placeholder="Masukkan deskripsi course..."
                                      required
                                      style="width: 100%; padding: 8px 12px; resize: vertical;">{{ old('description') }}</textarea>
                        </div>

                        <!-- Thumbnail Image Input File -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="thumbnail_url" class="form-label">Thumbnail Image</label>
                            <input type="file"
                                   name="thumbnail_url"
                                   accept="image/*"
                                   id="thumbnail_url"
                                   class="form-control"
                                   required
                                   style="width: 100%; padding: 8px 12px;">
                        </div>

                        <!-- Course Type Select -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="course_type" class="form-label">Tipe Course</label>
                            <select name="course_type"
                                    id="course_type"
                                    class="form-control filter-select"
                                    required
                                    style="width: 100%; padding: 8px 12px;">
                                <option value="" disabled {{ old('course_type') ? '' : 'selected' }}>Pilih Tipe Course...</option>
                                <option value="regular" {{ old('course_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="private" {{ old('course_type') == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="bootcamp" {{ old('course_type') == 'bootcamp' ? 'selected' : '' }}>Bootcamp</option>
                            </select>
                        </div>

                        <!-- Created By Select (Entity) -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="created_by" class="form-label">Created By</label>
                            <select name="created_by"
                                    id="created_by"
                                    class="form-control filter-select"
                                    required
                                    style="width: 100%; padding: 8px 12px;">
                                <option value="" disabled {{ old('created_by') ? '' : 'selected' }}>Pilih Entity...</option>
                                @foreach($entities as $entity)
                                    <option value="{{ $entity->id }}" {{ old('created_by') == $entity->id ? 'selected' : '' }}>
                                        {{ $entity->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status & Display Controls Row -->
                        <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                            <div class="form-group flex-1" style="flex: 1;">
                                <label for="is_active" class="form-label">Status Course</label>
                                <select name="is_active" id="is_active" class="form-control filter-select" style="width: 100%; padding: 8px 12px;">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="form-group flex-1" style="flex: 1;">
                                <label for="is_displayed" class="form-label">Tampilkan Course</label>
                                <select name="is_displayed" id="is_displayed" class="form-control filter-select" style="width: 100%; padding: 8px 12px;">
                                    <option value="1" {{ old('is_displayed', '1') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_displayed') === '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 16px;">
                            <a href="{{ route('course.index') }}" class="btn-action btn-delete" style="padding: 8px 18px; text-decoration: none; font-size: 0.85rem;">
                                Batal
                            </a>
                            <button type="submit" class="btn-primary" style="padding: 8px 18px; font-size: 0.85rem;">
                                Simpan Course
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </main>
    </div>

    <!-- Dropdown Menu JS -->
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