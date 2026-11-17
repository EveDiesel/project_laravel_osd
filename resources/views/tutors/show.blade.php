<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Detail Tutor - OSEDU Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Style CSS -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])
    <style>
        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-active { background-color: #d1fae5; color: #065f46; }
        .badge-inactive { background-color: #f1f5f9; color: #64748b; }

        .profile-row { display: flex; gap: 8px; font-size: 0.85rem; padding: 6px 0; }
        .profile-label { color: #94a3b8; width: 140px; flex-shrink: 0; }
        .profile-value { color: #f1f5f9; font-weight: 500; }
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
            <div class="content-body">
                <div class="dashboard-card" style="padding: 20px;">

                    <!-- Header -->
                    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <div>
                            <h2 class="card-title" style="font-size: 1.25rem;">Detail Tutor</h2>
                            <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Profil, daftar course, dan siswa yang ditangani oleh tutor.</p>
                        </div>
                        <a href="{{ route('tutors.index') }}" class="btn-action btn-delete" style="text-decoration: none; padding: 8px 16px; font-size: 0.8rem;">← Kembali</a>
                    </div>

                    <!-- Profil Tutor -->
                    <div style="display: flex; gap: 20px; align-items: flex-start; background: #0f172a; border: 1px solid #1e293b; border-radius: 10px; padding: 20px; margin-bottom: 24px; flex-wrap: wrap;">
                        <img src="{{ optional($tutor->entity)->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(optional($tutor->entity)->name ?? 'T') . '&size=96' }}" alt="Avatar" style="width: 96px; height: 96px; border-radius: 50%; object-fit: cover;">

                        <div style="flex: 1; min-width: 280px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <h3 style="font-size: 1.1rem; color: #f1f5f9; font-weight: 700;">{{ optional($tutor->entity)->name ?? '-' }}</h3>
                                <span class="badge-status {{ $tutor->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $tutor->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="profile-row"><span class="profile-label">Email</span><span class="profile-value">{{ $tutor->email }}</span></div>
                            <div class="profile-row"><span class="profile-label">No. Telepon</span><span class="profile-value">{{ optional($tutor->entity)->phone ?? '-' }}</span></div>
                            <div class="profile-row"><span class="profile-label">Kota</span><span class="profile-value">{{ optional($tutor->entity)->city ?? '-' }}</span></div>
                            <div class="profile-row"><span class="profile-label">Alamat</span><span class="profile-value">{{ optional($tutor->entity)->address ?? '-' }}</span></div>
                            <div class="profile-row"><span class="profile-label">Tanggal Lahir</span><span class="profile-value">{{ optional($tutor->entity)->birthdate ? \Carbon\Carbon::parse(optional($tutor->entity)->birthdate)->format('d M Y') : '-' }}</span></div>
                            <div class="profile-row"><span class="profile-label">Jenis Kelamin</span><span class="profile-value">{{ ucfirst(str_replace('_', ' ', optional($tutor->entity)->gender ?? '-')) }}</span></div>
                            <div class="profile-row"><span class="profile-label">Terdaftar Sejak</span><span class="profile-value">{{ $tutor->created_at->format('d M Y') }}</span></div>
                        </div>
                    </div>

                    <!-- 1. Daftar Course yang Diampu -->
                    <h3 style="font-size: 1rem; color: #f1f5f9; font-weight: 700; margin-bottom: 12px;">Course yang Diampu ({{ $tutor->courses->count() }})</h3>

                    <div class="table-responsive" style="padding-bottom: 16px; overflow-x: auto; margin-bottom: 24px;">
                        <table class="compact-table" style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                    <th style="padding: 8px 10px; white-space: nowrap;">JUDUL COURSE</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">TIPE</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">STATUS COURSE</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">DIBUAT PADA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tutor->courses as $course)
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 8px 10px;"><strong>{{ $course->title }}</strong></td>
                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <span class="badge" style="background: #e2e8f0; color: #475569; padding: 2px 6px; font-size: 11px; border-radius: 4px;">{{ ucfirst($course->course_type ?? '-') }}</span>
                                    </td>
                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <span class="badge-status {{ $course->is_active ? 'badge-active' : 'badge-inactive' }}">
                                            {{ $course->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td style="padding: 8px 10px; white-space: nowrap;" class="text-muted">
                                        {{ $course->created_at ? $course->created_at->format('d M Y') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 15px;">Tutor ini belum mengampu course apa pun.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- 2. Daftar Murid / Siswa yang Ditangani (Mengambil dari siswa yang enroll ke course tutor ini) -->
                    @php
                        // Mengambil siswa unik yang terdaftar pada course-course yang diampu tutor ini
                        $students = \App\Models\User::role('student')
                            ->whereHas('courseStudents', function($q) use ($tutor) {
                                $q->whereIn('course_id', $tutor->courses->pluck('id'));
                            })
                            ->with('entity')
                            ->get();
                    @endphp

                    <h3 style="font-size: 1rem; color: #f1f5f9; font-weight: 700; margin-bottom: 12px;">Siswa yang Ditangani ({{ $students->count() }})</h3>

                    <div class="table-responsive" style="padding-bottom: 8px; overflow-x: auto;">
                        <table class="compact-table" style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                    <th style="padding: 8px 10px; white-space: nowrap;">AVATAR</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">NAMA SISWA</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">EMAIL</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">STATUS AKUN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <img src="{{ optional($student->entity)->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(optional($student->entity)->name ?? 'S') }}" alt="Avatar" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                                    </td>
                                    <td style="padding: 8px 10px; white-space: nowrap;"><strong>{{ optional($student->entity)->name ?? '-' }}</strong></td>
                                    <td style="padding: 8px 10px; white-space: nowrap;" class="text-muted">{{ $student->email }}</td>
                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <span class="badge-status {{ $student->is_active ? 'badge-active' : 'badge-inactive' }}">
                                            {{ $student->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada siswa yang terdaftar di course tutor ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

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