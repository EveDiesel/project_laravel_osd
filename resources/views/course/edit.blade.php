<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Course - OSEDU Bright Future</title>
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
                        <h2 class="card-title" style="font-size: 1.25rem;">Edit Course</h2>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Ubah data course di bawah ini.</p>
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

                    <!-- Form Edit Data Course -->
                        <form action="{{ route('course.update', $course) }}" method="POST" enctype="multipart/form-data" style="width: 100%;">
                        @csrf
                        @method('PUT')

                        <div style="display: flex; flex-direction: column; gap: 18px;">
                            <!-- Course Title -->
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">
                                    Course Title <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" name="title" class="search-input" placeholder="Masukkan judul course..." style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('title', $course->title) }}" required>
                            </div>

                            <!-- Slug -->
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">
                                    Slug <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" name="slug" class="search-input" placeholder="Masukkan slug course..." style="width: 100%; padding: 8px 12px; box-sizing: border-box;" value="{{ old('slug', $course->slug) }}" required>
                            </div>

                            <!-- Description -->
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">
                                    Description <span style="color: #ef4444;">*</span>
                                </label>
                                <textarea name="description" rows="4" class="search-input" placeholder="Masukkan deskripsi course..." style="width: 100%; padding: 8px 12px; box-sizing: border-box; resize: vertical;" required>{{ old('description', $course->description) }}</textarea>
                            </div>

                            <!-- Thumbnail URL -->
                           <div style="display: flex; flex-direction: column; gap: 6px;">
                            <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">
                            Thumbnail Image <span style="color: #ef4444;">*</span>
                              </label>
                             <input type="file" name="thumbnail_url" class="search-input" accept="image/png, image/jpeg, image/jpg, image/webp" style="width: 100%; padding: 8px 12px; box-sizing: border-box;">
                             @if($course->thumbnail_url)
                            <small style="color: #94a3b8; margin-top: 4px;">Thumbnail saat ini: {{ basename($course->thumbnail_url) }}</small>
                        @endif
                    </div>

                            <!-- Created By (Entity) -->
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">
                                    Created By (Entity) <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="created_by" id="created_by" class="filter-select" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" required>
                                    <option value="" disabled>-- Pilih Entity --</option>
                                    @foreach($entities as $entity)
                                        <option value="{{ $entity->id }}" {{ old('created_by', $course->created_by) == $entity->id ? 'selected' : '' }}>
                                            {{ $entity->name ?? $entity->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Course Type -->
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-size: 13px; color: #94a3b8; font-weight: 500;">
                                    Course Type <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="course_type" id="course_type" class="filter-select" style="width: 100%; padding: 8px 12px; box-sizing: border-box;" required>
                                    <option value="" disabled>-- Pilih Course Type --</option>
                                    <option value="regular" {{ old('course_type', $course->course_type) == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="private" {{ old('course_type', $course->course_type) == 'private' ? 'selected' : '' }}>Private</option>
                                    <option value="bootcamp" {{ old('course_type', $course->course_type) == 'bootcamp' ? 'selected' : '' }}>Bootcamp</option>
                                </select>
                            </div>

                            <!-- Is Active -->
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }} style="width: 16px; height: 16px;">
                                <label for="is_active" style="font-size: 13px; color: #cbd5e1; font-weight: 500; cursor: pointer;">Is Active</label>
                            </div>

                            <!-- Is Displayed -->
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="hidden" name="is_displayed" value="0">
                                <input type="checkbox" name="is_displayed" id="is_displayed" value="1" {{ old('is_displayed', $course->is_displayed) ? 'checked' : '' }} style="width: 16px; height: 16px;">
                                <label for="is_displayed" style="font-size: 13px; color: #cbd5e1; font-weight: 500; cursor: pointer;">Is Displayed</label>
                            </div>

                            <!-- Tombol Aksi -->
                            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; border-top: 1px solid #1f2937; padding-top: 16px;">
                                <a href="{{ route('course.index') }}" class="btn-action btn-delete" style="padding: 8px 18px; text-decoration: none; font-size: 0.85rem;">
                                    Batal
                                </a>
                                <button type="submit" class="btn-primary" style="padding: 8px 18px; font-size: 0.85rem;">
                                    Update Course
                                </button>
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