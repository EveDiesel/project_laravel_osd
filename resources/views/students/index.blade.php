<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Manage Students - OSEDU Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Style CSS -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])
    <style>
        /* Memaksa ikon panah pagination jadi kecil */
        .pagination-wrapper svg,
        .pagination-wrapper nav svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            display: inline-block !important;
        }
        .pagination-wrapper nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-active { background-color: #d1fae5; color: #065f46; }
        .badge-inactive { background-color: #f1f5f9; color: #64748b; }

        /* Kartu statistik */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }
        .stat-card {
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 10px;
            padding: 14px 16px;
        }
        .stat-label { font-size: 11px; color: #94a3b8; letter-spacing: 1px; font-weight: 600; }
        .stat-value { font-size: 1.4rem; font-weight: 700; color: #f1f5f9; margin-top: 4px; }
        .stat-value small { font-size: 0.7rem; color: #94a3b8; font-weight: 500; }

        /* Bar progress */
        .progress-track {
            background: #1e293b;
            border-radius: 20px;
            height: 8px;
            width: 110px;
            overflow: hidden;
            display: inline-block;
            vertical-align: middle;
        }
        .progress-fill { background: #38bdf8; height: 100%; border-radius: 20px; }
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
       <main class="main-content" style="flex: 1; min-width: 0;">
            <div class="content-body" style="padding-top: 10px;">
                <div class="dashboard-card" style="padding: 20px;">

                    <!-- Alert Sukses -->
                    @if(session('success'))
                        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Header -->
                    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h2 class="card-title" style="font-size: 1.15rem;">Student Management</h2>
                        <a href="{{ route('students.create') }}" class="btn-primary" style="font-size: 0.8rem; padding: 8px 14px;">+ Tambah Siswa Baru</a>
                    </div>

                    <!-- Kartu Statistik -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">TOTAL SISWA</div>
                            <div class="stat-value">{{ $stats['total_students'] }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">AKTIF</div>
                            <div class="stat-value">{{ $stats['active_students'] }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">COURSE SELESAI</div>
                            <div class="stat-value">{{ $stats['completed_courses'] }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">RATA-RATA PROGRESS</div>
                            <div class="stat-value">{{ $stats['avg_progress'] }}<small> %</small></div>
                        </div>
                    </div>

                    <!-- Filter & Pencarian -->
                    <form method="GET" action="{{ route('students.index') }}">
                        <div class="filter-section" style="display: flex; gap: 10px; margin-bottom: 16px;">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama / Email..." class="search-input" style="font-size: 0.8rem; padding: 6px 12px;">
                            <select name="status" class="filter-select" style="font-size: 0.8rem; padding: 6px 12px;">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <button type="submit" class="btn-primary" style="font-size: 0.8rem; padding: 6px 14px;">Filter</button>
                            <a href="{{ route('students.index') }}" class="btn-action btn-delete" style="text-decoration: none; font-size: 0.8rem; padding: 6px 14px;">Reset</a>
                        </div>
                    </form>

                    <!-- Tabel Siswa -->
                    <div class="table-responsive" style="padding-bottom: 8px; overflow-x: auto;">
                        <table class="compact-table" style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                    <th style="padding: 8px 10px; white-space: nowrap;">AVATAR</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">ID</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">NAME</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">EMAIL</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">PHONE</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">CITY</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">COURSES</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">PROGRESS</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">STATUS</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <img src="{{ $student->entity->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->entity->name ?? 'S') }}" alt="Avatar" class="avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                    </td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <span class="badge" style="background: #e2e8f0; color: #475569; padding: 2px 6px; font-size: 11px; border-radius: 4px;">#{{ $student->id }}</span>
                                    </td>

                                    <td style="padding: 8px 10px; white-space: nowrap;"><strong>{{ $student->entity->name ?? '-' }}</strong></td>

                                    <td style="padding: 8px 10px; white-space: nowrap;" class="text-muted">{{ $student->email }}</td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">{{ $student->entity->phone ?? '-' }}</td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">{{ $student->entity->city ?? '-' }}</td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">{{ $student->course_students_count }}</td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <span class="progress-track"><span class="progress-fill" style="width: {{ min(100, $student->course_students_avg_percentage ?? 0) }}%;"></span></span>
                                        <span style="font-size: 11px; color: #94a3b8; margin-left: 6px;">{{ number_format($student->course_students_avg_percentage ?? 0, 1) }}%</span>
                                    </td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <span class="badge-status {{ $student->is_active ? 'badge-active' : 'badge-inactive' }}">
                                            {{ $student->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <div style="display: flex; gap: 4px;">
                                            <a href="{{ route('students.show', $student->id) }}" class="btn-action btn-edit" style="padding: 3px 8px; font-size: 11px;">View</a>
                                            <a href="{{ route('students.edit', $student->id) }}" class="btn-action btn-edit" style="padding: 3px 8px; font-size: 11px;">Edit</a>
                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" style="border:none; cursor:pointer; padding: 3px 8px; font-size: 11px;" onclick="return confirm('Yakin ingin menghapus siswa ini? Semua data course-nya juga akan terhapus.')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada data siswa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($students, 'links'))
                    <div class="pagination-wrapper" style="margin-top: 16px;">
                        {{ $students->links() }}
                    </div>
                    @endif

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
