<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Manage Course Quizzes - OSEDU Bright Future</title>

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
                            <h2 class="card-title" style="font-size: 1.25rem;">Daftar Course Quizzes</h2>
                            <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Kelola daftar quiz dan soal latihan OSEDU.</p>
                        </div>
                        @hasanyrole('Admin|Tutor')
                            <a href="{{ route('course-quiz.create') }}" class="btn-primary" style="text-decoration: none; font-size: 0.85rem; padding: 8px 16px;">+ Tambah Quiz Baru</a>
                        @endhasanyrole
                    </div>

                    <!-- Table Data -->
                    <div class="table-responsive">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>COURSE CONTENT</th>
                                    <th>NAME</th>
                                    <th>QUESTIONS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courseQuizzes as $index => $courseQuiz)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $courseQuiz->courseContent->title ?? '-' }}</td>
                                    <td>{{ $courseQuiz->name ?? '-' }}</td>
                                    <td>
                                        {{-- Menghitung jumlah soal dari kolom JSON --}}
                                        <span class="badge" style="background: #1e293b; color: #34d399; border: 1px solid #334155; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 500;">
                                            {{ is_array($courseQuiz->questions) ? count($courseQuiz->questions) : 0 }} Soal
                                        </span>
                                    </td>
                                    <td>
                                        @hasanyrole('Admin|Tutor')
                                            <!-- Tombol untuk Tutor/Admin mengelola/membuat soal -->
                                            <a href="{{ route('course-quiz.show', $courseQuiz->id) }}" class="btn-action" style="background-color: #3b82f6; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; display: inline-block; margin-right: 4px; font-size: 0.85rem;">Kelola Soal</a>
                                            
                                            <a href="{{ route('course-quiz.edit', $courseQuiz->id) }}" class="btn-action btn-edit">Edit</a>
                                            
                                            <form action="{{ route('course-quiz.destroy', $courseQuiz->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" style="border:none; cursor:pointer;" onclick="return confirm('Yakin ingin menghapus quiz ini?')">Delete</button>
                                            </form>
                                        @else
                                            <!-- Tombol untuk Student mengerjakan quiz -->
                                            <a href="{{ route('course-quiz.show', $courseQuiz->id) }}" class="btn-action" style="background-color: #10b981; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; display: inline-block; font-size: 0.85rem;">Kerjakan Quiz</a>
                                        @endhasanyrole
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada data course quiz.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($courseQuizzes, 'links'))
                    <div class="pagination-wrapper" style="margin-top: 20px;">
                        {{ $courseQuizzes->links() }}
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