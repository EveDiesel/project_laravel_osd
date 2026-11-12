<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $contents->title }} - OSEDU Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Style CSS -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])
   
    <style>
        .content-display img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 15px 0;
        }
        
        .content-display img.ql-align-center {
            margin-left: auto;
            margin-right: auto;
        }
        .content-display img.ql-align-right {
            margin-left: auto;
            margin-right: 0;
        }
        .content-display img.ql-align-left {
            margin-left: 0;
            margin-right: auto;
        }
      
        .content-display .ql-align-center {
            text-align: center;
        }
        .content-display .ql-align-right {
            text-align: right;
        }
        .content-display .ql-align-justify {
            text-align: justify;
        }
      
        .content-display a {
            color: #60a5fa;
            text-decoration: underline;
        }
    </style>
</head>
<body class="dashboard-body">

    @php
        $stu = auth()->user()->hasRole('Student');
    @endphp

    <!-- TOP NAVBAR (Tanpa Background Hardcode) -->
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
        <main class="main-content" style="flex: 1; padding: 24px;">
            <div class="content-body" style="width: 100%;">

                @if(session('success'))
                    <div class="alert alert-success" style="padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- CARD UTAMA (Menggunakan dashboard-card standar mengikuti sistem tema) -->
                <div class="dashboard-card" style="padding: 28px; margin-top: 4px; border-radius: 14px; border: 1px solid var(--border-color);">

                    <!-- KEMBALI & NAVIGASI -->
                    @hasanyrole('Admin|Tutor')
                        <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                            <a href="{{ route('course-content.index') }}" class="btn-action" style="display: inline-block; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-size: 14px;">
                                ← Kembali ke Daftar
                            </a>
                        </div>
                    @endhasanyrole

                    @if($stu)
                    <!-- Tampilan untuk Student (Bergaya Buku / Modul) -->
                        <div style="max-width: 800px; margin: 0 auto; border: 1px solid var(--border-color); border-radius: 16px; padding: 40px;">
                            
                            <!-- Header Buku / Modul -->
                            <div style="text-align: center; border-bottom: 1px solid var(--border-color); padding-bottom: 24px; margin-bottom: 30px;">
                                <span style="display: inline-block; background: var(--primary-color, #2563eb); color: white; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; margin-bottom: 12px;">
                                    {{ $contents->course->title ?? 'Materi Belajar' }} | Level: {{ $contents->courseLevel->name ?? '-' }}
                                </span>
                                <h1 style="font-size: 32px; font-weight: 800; line-height: 1.3;">
                                    {{ $contents->title }}
                                </h1>
                                <p style="color: #94a3b8; font-size: 14px; margin-top: 8px;">
                                    Sesi: {{ $contents->session ?? '1' }} | Tipe: {{ ucfirst($contents->content_type) }}
                                </p>
                            </div>

                            <!-- Isi Konten Bergaya Buku -->
                            <div style="font-size: 16px; line-height: 1.9; word-break: break-word;">
                                @if ($contents->content)
                                    <div class="content-display">
                                        {!! $contents->content !!}
                                    </div>
                                @else
                                    <p style="color: #94a3b8; text-align: center; font-style: italic;">Belum ada konten yang diunggah untuk materi ini.</p>
                                @endif
                            </div>

                            <!-- Footer Navigasi / Selesai Membaca -->
                            <div style="border-top: 1px solid var(--border-color); margin-top: 40px; padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                                <a href="{{ route('course-content.index') }}" style="color: #94a3b8; text-decoration: none; font-size: 14px;">
                                    ← Kembali
                                </a>
                                <span style="color: #64748b; font-size: 13px;">OSEDU Bright Future</span>
                            </div>

                        </div>

                    @else
                        <!-- Tampilan untuk Admin / Tutor -->
                        <div style="margin-bottom: 24px;">
                            <h1 style="font-size: 28px; font-weight: 700; margin-top: 20px;">
                                Detail Course Content
                            </h1>
                            <p style="color: #94a3b8; margin-top: 8px;">
                                Berikut adalah detail informasi dari Course Content.
                            </p>
                        </div>

                        <!-- KOTAK INFORMASI -->
                        <div style="border: 1px solid var(--border-color); border-radius: 12px; margin-top: 24px; overflow: hidden;">
                            <div style="padding: 24px;">
                                <h2 style="font-size: 24px; font-weight: 700;">
                                    {{ $contents->title }}
                                </h2>
                                <span style="display: inline-block; background: #3730a3; color: white; padding: 6px 14px; border-radius: 20px; margin-top: 12px;">
                                    {{ $contents->content_type }}
                                </span>
                            </div>

                            <hr style="border-color: var(--border-color);">

                            <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 20px; padding: 24px;">
                                <div>
                                    <p style="color: #7da5d8;">Course</p>
                                    <p style="margin-top: 8px; font-weight: 500;">
                                        {{ $contents->course->title ?? '-' }}
                                    </p>
                                </div>
                                <div style="border-left: 1px solid var(--border-color); padding-left: 20px;">
                                    <p style="color: #7da5d8;">Course Level</p>
                                    <p style="margin-top: 8px; font-weight: 500;">
                                        {{ $contents->courseLevel->name ?? '-' }}
                                    </p>
                                </div>
                                <div style="border-left: 1px solid var(--border-color); padding-left: 20px;">
                                    <p style="color: #7da5d8;">Session</p>
                                    <p style="margin-top: 8px; font-weight: 500;">
                                        {{ $contents->session ?? '-' }}
                                    </p>
                                </div>
                                <div style="border-left: 1px solid var(--border-color); padding-left: 20px;">
                                    <p style="color: #7da5d8;">Content Type</p>
                                    <p style="margin-top: 8px; font-weight: 500;">
                                        {{ $contents->content_type }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- KOTAK ISI CONTENT -->
                        <div style="border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; margin-top: 24px;">
                            <h3 style="font-size: 22px; font-weight: 700; margin-bottom: 16px;">
                                Isi Content
                            </h3>
                            <div class="content-display" style="border: 1px solid var(--border-color); border-radius: 10px; padding: 16px; overflow-wrap: anywhere; min-height: 80px; line-height: 1.7;">
                                @if ($contents->content)
                                    {!! $contents->content !!}
                                @endif
                            </div>
                        </div>

                        <!-- TOMBOL EDIT DAN DELETE (Hanya Admin/Tutor) -->
                        @role('Admin|Tutor')

                        <div class="quiz-info-section" style="margin-top: 15px; padding: 10px; border-radius: 8px;">
                                <p style="color: #cbd5e1; font-size: 0.9rem;">
                                    Total Quiz untuk materi ini: 
                                    <strong style="color: #38bdf8;">{{ $contents->quizzes->count() }} Quiz</strong>
                                </p>
                                <a href="{{ route('course-quiz.index', ['courseContentId' => $contents->id]) }}" class="btn-action" style="display: inline-block; background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-top: 8px; font-size: 0.85rem;">
                                    Lihat Daftar Quiz
                                </a>
                                <a href="{{ route('quiz.create', ['courseContentId' => $contents->id]) }}" class="btn-primary" style="display: inline-block; background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-top: 8px; font-size: 0.85rem;">
                                    + Buat Quiz Baru
                                </a>
                                
                            </div>

                        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                            <a href="{{ route('course-content.edit', $contents->id) }}" style="display: inline-block; background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                                ✎ Edit
                            </a>
                            <form action="{{ route('course-content.destroy', $contents->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #dc2626; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;">
                                    Delete
                                </button>
                            </form>
                        </div>
                        @endrole

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