<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Manage Schedule - OSEDU Bright Future</title>

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
            <div class="content-body" style="width: 100%;">
                
                <!-- Notifikasi Success -->
                @if(session('success'))
                    <div class="alert alert-success" style="padding: 12px 16px; background-color: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="dashboard-card">
                    
                    <!-- Header Section & Action Button -->
                    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>

                            @role('Tutor')
                                <h2 class="card-title" style="font-size: 1.25rem;">Jadwal Mengajar Saya</h2>
                                <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Pantau daftar jadwal sesi kelas dan aktivitas pengajaran Anda di OSEDU.</p>
                            @else
                                <h2 class="card-title" style="font-size: 1.25rem;">Daftar Schedule</h2>
                                <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Kelola daftar schedule dan hak akses aplikasi OSEDU.</p>
                            @endrole
                        </div>

                        @role('Admin')
                        <a href="{{ route('schedule.create') }}" class="btn-primary" style="text-decoration: none; font-size: 0.85rem; padding: 8px 16px;">+ Tambah Schedule Baru</a>
                        @endrole
                    </div>

                    <!-- Info Card Khusus Tutor -->
                    @role('Tutor')
                    <div style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; color: #93c5fd;">
                         <strong>Catatan Tutor:</strong> Pastikan hadir tepat waktu sesuai rentang tanggal dan waktu aktivitas yang tertera pada tabel di bawah.
                    </div>
                    @endrole

                    <!-- Table Data -->
                    <div class="table-responsive">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>ACTIVITY</th>
                                    <th>START</th>
                                    <th>END</th>
                                    <th>STATUS</th>
                                    @role('Admin')
                                    <th>ACTIONS</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $index => $schedule)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span style="font-weight: 500; color: #f8fafc;">{{ $schedule->activity->name ?? '-' }}</span>
                                    </td>
                                    <td>{{ $schedule->start_at }}</td>
                                    <td>{{ $schedule->end_at }}</td>
                                    <td>
                                        @if(strtolower($schedule->status) == 'active')
                                            <span class="badge-status badge-active">Active</span>
                                        @else
                                            <span class="badge-status badge-inactive">{{ ucfirst($schedule->status) }}</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Admin -->
                                    @role('Admin')
                                    <td>
                                        <a href="{{ route('schedule.edit', $schedule->id) }}" class="btn-action btn-edit">Edit</a>
                                        <form action="{{ route('schedule.destroy', $schedule->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" style="border:none; cursor:pointer;" onclick="return confirm('Yakin ingin menghapus schedule ini?')">Delete</button>
                                        </form>
                                    </td>
                                    @endrole
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->hasRole('Admin') ? '6' : '5' }}" style="text-align: center; color: #94a3b8; padding: 20px;">
                                        @role('Tutor')
                                            Belum ada jadwal mengajar yang ditugaskan kepada Anda.
                                        @else
                                            Tidak ada data schedule.
                                        @endrole
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination jika menggunakan paginate -->
                    @if(method_exists($schedules, 'links'))
                    <div class="pagination-wrapper" style="margin-top: 20px;">
                        {{ $schedules->links() }}
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