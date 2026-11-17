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
            border: 1px solid var(--border-color, #cbd5e1);
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

                    @hasanyrole('Tutor|Admin')
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
                                                
                                                <div style="display: flex; gap: 8px; margin-top: 12px;">
                                                    <a href="{{ route('course-quiz.show', $quiz->id) }}" class="btn-action" style="background-color: #3b82f6; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; flex: 1; text-align: center; font-size: 0.8rem; font-weight: 500;">
                                                        Kelola Soal
                                                    </a>

                                                    <!-- Form Hapus Quiz -->
                                                    <form action="{{ route('course-quiz.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus quiz &quot;{{ $quiz->name }}&quot; ini?')" style="margin: 0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="background-color: #ef4444; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8rem; font-weight: 500; display: flex; align-items: center; justify-content: center;" title="Hapus Quiz">
                                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
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
                        
                        @elserole('Student|Tutor')
                            <div class="tutor-group-container">
                                @php
                                    $groupedQuizzes = $courseQuizzes->groupBy(function($item) {
                                        return $item->courseContent->title ?? 'Materi Pembelajaran';
                                    });
                                @endphp

                                @forelse($groupedQuizzes as $contentTitle => $quizzes)
                                    <div class="course-group-card" style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                        
                                        <!-- Judul Materi Course Content -->
                                        <div class="course-group-title" style="display: flex; align-items: center; gap: 8px; font-size: 1.05rem; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                                            <svg style="width: 20px; height: 20px; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <span>{{ $contentTitle }}</span>
                                        </div>

                                        <!-- Grid Kartu Quiz -->
                                        <div class="quiz-cards-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px;">
                                            @foreach($quizzes as $quiz)
                                                @php
                                                    $isSubmitted = isset($quiz->is_submitted) ? $quiz->is_submitted : false; 
                                                @endphp

                                                <div class="quiz-item-card" style="border: 1px solid {{ $isSubmitted ? '#bbf7d0' : '#e2e8f0' }}; background: {{ $isSubmitted ? '#f0fdf4' : '#ffffff' }}; border-radius: 10px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;">
                                                    
                                                    <div>
                                                        <h4 style="font-size: 0.95rem; font-weight: 600; color: #0f172a; margin-bottom: 6px;">
                                                            {{ $quiz->name ?? 'Quiz Tanpa Nama' }}
                                                        </h4>

                                                        <!-- Jumlah Soal & Badge Status Pengerjaan -->
                                                        <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
                                                            <span style="display: inline-block; background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 500;">
                                                                {{ is_array($quiz->questions) ? count($quiz->questions) : 0 }} Soal
                                                            </span>

                                                            @if($isSubmitted)
                                                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                                                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                    Sudah Dikerjakan
                                                                </span>
                                                            @else
                                                                <span style="display: inline-block; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 500;">
                                                                    Belum Dikerjakan
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Tombol Aksi (Kondisional: Kerjakan vs Lihat Hasil / Terkunci) -->
                                                    <div>
                                                        @if($isSubmitted)
                                                            <!-- Jika sudah dikerjakan, tombol berubah jadi Lihat Hasil & Terkunci -->
                                                            <a href="{{ route('course-quiz.show', $quiz->id) }}" class="btn-action" style="background-color: #059669; color: white; text-decoration: none; padding: 8px 12px; border-radius: 6px; display: block; text-align: center; font-size: 0.8rem; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                                                Quiz Selesai
                                                            </a>
                                                        @else
                                                            <!-- Jika belum, tombol untuk mulai mengerjakan -->
                                                            <a href="{{ route('course-quiz.show', $quiz->id) }}" class="btn-action" style="background-color: #2563eb; color: white; text-decoration: none; padding: 8px 12px; border-radius: 6px; display: block; text-align: center; font-size: 0.8rem; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                                                Kerjakan Quiz
                                                            </a>
                                                        @endif
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                @empty
                                    <div style="text-align: center; background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 40px; color: #64748b;">
                                        <p style="font-size: 0.95rem; font-weight: 500; margin-bottom: 4px;">Belum ada quiz yang tersedia</p>
                                        <p style="font-size: 0.8rem; opacity: 0.8;">Quiz akan muncul di sini setelah Anda terdaftar ke dalam course yang sesuai.</p>
                                    </div>
                                @endforelse
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