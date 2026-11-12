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

        /* Styling Grouped Cards yang ikut warna system (tanpa background kaku) */
        .tutor-group-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        .course-group-card {
            border: 1px solid var(--border-color, #cbd5e1); /* Mengikuti variabel system jika ada, fallback ke abu-abu */
            border-radius: 12px;
            padding: 20px;
        }
        .course-group-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color, #2563eb);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--border-color, #cbd5e1);
            padding-bottom: 8px;
        }
        .quiz-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }
        .quiz-item-card {
            border: 1px solid var(--border-color, #cbd5e1);
            border-radius: 8px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }
        .quiz-item-card:hover {
            border-color: var(--primary-color, #2563eb);
            transform: translateY(-2px);
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
                                <h2 class="card-title" style="font-size: 1.25rem;">Kelola Quiz Berdasarkan Materi</h2>
                                <p style="font-size: 0.85rem; opacity: 0.8; margin-top: 4px;">Daftar quiz untuk pengajaran.</p>
                            @elserole('Student')
                                <h2 class="card-title" style="font-size: 1.25rem;">Daftar Quiz Belajar Saya</h2>
                                <p style="font-size: 0.85rem; opacity: 0.8; margin-top: 4px;">Pilih materi dan kerjakan quiz untuk meningkatkan progress belajarmu.</p>
                            @else
                                <h2 class="card-title" style="font-size: 1.25rem;">Daftar Course Quizzes</h2>
                                <p style="font-size: 0.85rem; opacity: 0.8; margin-top: 4px;">Kelola daftar quiz dan soal latihan OSEDU.</p>
                            @endrole
                        </div>

                        {{-- Tombol Tambah Quiz HANYA untuk Admin/Tutor --}}
                        @hasanyrole('Admin|Tutor')
                            <a href="{{ route('course-quiz.create') }}" class="btn-primary" style="text-decoration: none; font-size: 0.85rem; padding: 8px 16px;">+ Tambah Quiz Baru</a>
                        @endhasanyrole
                    </div>


                    {{-- ================= 1. TAMPILAN KHUSUS TUTOR ================= --}}
                    @role('Tutor')
                        <div class="tutor-group-container">
                            @php
                                $groupedQuizzes = $courseQuizzes->groupBy(function($item) {
                                    return $item->courseContent->title ?? 'Materi Lainnya';
                                });
                            @endphp

                            @forelse($groupedQuizzes as $contentTitle => $quizzes)
                                <div class="course-group-card">
                                    <div class="course-group-title">
                                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <span>{{ $contentTitle }}</span>
                                    </div>

                                    <div class="quiz-cards-grid">
                                        @foreach($quizzes as $quiz)
                                            <div class="quiz-item-card">
                                                <div>
                                                    <h4 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 6px;">{{ $quiz->name }}</h4>
                                                    <span style="display: inline-block; border: 1px solid currentColor; opacity: 0.8; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500; margin-bottom: 12px;">
                                                        {{ is_array($quiz->questions) ? count($quiz->questions) : 0 }} Soal
                                                    </span>
                                                </div>
                                                
                                                <div>
                                                    <a href="{{ route('course-quiz.show', $quiz->id) }}" class="btn-action" style="background-color: #3b82f6; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; display: block; text-align: center; font-size: 0.8rem; font-weight: 500;">
                                                        Kelola Soal
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div style="text-align: center; opacity: 0.7; padding: 40px;">
                                    Belum ada data quiz yang tersedia untuk dikelompokkan.
                                </div>
                            @endforelse
                        </div>


                    {{-- ================= 2. TAMPILAN KHUSUS STUDENT ================= --}}
                    @elserole('Student')
                        <div class="tutor-group-container">
                            @php
                                $groupedQuizzes = $courseQuizzes->groupBy(function($item) {
                                    return $item->courseContent->title ?? 'Materi Lainnya';
                                });
                            @endphp

                            @forelse($groupedQuizzes as $contentTitle => $quizzes)
                                <div class="course-group-card">
                                    <div class="course-group-title">
                                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <span>{{ $contentTitle }}</span>
                                    </div>

                                    <div class="quiz-cards-grid">
                                        @foreach($quizzes as $quiz)
                                            <div class="quiz-item-card">
                                                <div>
                                                    <h4 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 6px;">{{ $quiz->name }}</h4>
                                                    <span style="display: inline-block; border: 1px solid currentColor; opacity: 0.8; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500; margin-bottom: 12px;">
                                                        {{ is_array($quiz->questions) ? count($quiz->questions) : 0 }} Soal
                                                    </span>
                                                </div>
                                                
                                                <div>
                                                    <a href="{{ route('course-quiz.show', $quiz->id) }}" class="btn-action" style="background-color: #10b981; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; display: block; text-align: center; font-size: 0.8rem; font-weight: 500;">
                                                        Kerjakan Quiz
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div style="text-align: center; opacity: 0.7; padding: 40px;">
                                    Belum ada quiz yang tersedia untuk dikerjakan.
                                </div>
                            @endforelse
                        </div>


                    {{-- ================= 3. TAMPILAN UNTUK ADMIN ================= --}}
                    @else
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
                                            <span class="badge" style="border: 1px solid currentColor; opacity: 0.9; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 500;">
                                                {{ is_array($courseQuiz->questions) ? count($courseQuiz->questions) : 0 }} Soal
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('course-quiz.show', $courseQuiz->id) }}" class="btn-action" style="background-color: #3b82f6; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; display: inline-block; margin-right: 4px; font-size: 0.85rem;">Kelola Soal</a>
                                            
                                            <a href="{{ route('course-quiz.edit', $courseQuiz->id) }}" class="btn-action btn-edit">Edit</a>
                                            
                                            <form action="{{ route('course-quiz.destroy', $courseQuiz->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" style="border:none; cursor:pointer;" onclick="return confirm('Yakin ingin menghapus quiz ini?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; opacity: 0.7; padding: 20px;">Tidak ada data course quiz.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endrole

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