<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Course Quiz - OSEDU Bright Future</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
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

            <div class="dropdown-menu-card" id="userMenuDropdown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <span>Profile</span>
                </a>
                <hr class="dropdown-divider">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item logout-danger"><span>Log Out</span></button>
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
                
                @if(session('success'))
                    <div class="alert alert-success" style="padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- CARD UTAMA (Mengikuti tema sistem secara otomatis) -->
                <div class="dashboard-card" style="width: 100%;">
                    
                    <!-- HEADER SECTION -->
                    <div style="margin-bottom: 20px;">
                        <h2 class="card-title" style="font-size: 1.25rem;">Quiz: {{ $courseQuiz->name }}</h2>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">
                            Materi: {{ $courseQuiz->courseContent->title ?? '-' }}
                        </p>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 24px;">

                    {{-- =================================================== --}}
                    {{-- TAMPILAN UNTUK TUTOR / ADMIN (KELOLA & TAMBAH SOAL) --}}
                    {{-- =================================================== --}}
                    @hasanyrole('Admin|Tutor')
                        <h3 style="font-size: 1.1rem; margin-bottom: 15px;">+ Tambah Pertanyaan Baru</h3>
                        
                        <form action="{{ route('course-quiz.questions.store', $courseQuiz->id) }}" method="POST" style="padding: 24px; border-radius: 10px; margin-bottom: 30px; border: 1px solid var(--border-color);">
                            @csrf
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label" style="font-weight: 500; margin-bottom: 6px; display: block;">Pertanyaan</label>
                                <textarea name="question" class="form-control" rows="3" required style="width: 100%; padding: 12px; border-radius: 8px;"></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                <div>
                                    <label class="form-label" style="font-weight: 500; margin-bottom: 6px; display: block;">Pilihan A</label>
                                    <input type="text" name="option_a" class="form-control" required style="width: 100%; padding: 10px 12px; border-radius: 8px;">
                                </div>
                                <div>
                                    <label class="form-label" style="font-weight: 500; margin-bottom: 6px; display: block;">Pilihan B</label>
                                    <input type="text" name="option_b" class="form-control" required style="width: 100%; padding: 10px 12px; border-radius: 8px;">
                                </div>
                                <div>
                                    <label class="form-label" style="font-weight: 500; margin-bottom: 6px; display: block;">Pilihan C</label>
                                    <input type="text" name="option_c" class="form-control" required style="width: 100%; padding: 10px 12px; border-radius: 8px;">
                                </div>
                                <div>
                                    <label class="form-label" style="font-weight: 500; margin-bottom: 6px; display: block;">Pilihan D</label>
                                    <input type="text" name="option_d" class="form-control" required style="width: 100%; padding: 10px 12px; border-radius: 8px;">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label class="form-label" style="font-weight: 500; margin-bottom: 6px; display: block;">Kunci Jawaban Benar</label>
                                <select name="correct_answer" class="form-control" required style="width: 200px; padding: 10px 12px; border-radius: 8px;">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>

                            <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 0.9rem;">Simpan Soal</button>
                        </form>

                        <h3 style="font-size: 1.1rem; margin-bottom: 15px;">Daftar Soal Saat Ini</h3>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            @forelse($courseQuiz->questions ?? [] as $index => $q)
                                <div style="padding: 16px; border-radius: 8px; border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: flex-start;">
                                    <div>
                                        <p style="font-weight: 600; margin-bottom: 8px;">{{ $index + 1 }}. {{ $q['question'] }}</p>
                                        <ul style="list-style: none; padding: 0; font-size: 0.9rem; color: #94a3b8; display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                                            <li>A. {{ $q['option_a'] }}</li>
                                            <li>B. {{ $q['option_b'] }}</li>
                                            <li>C. {{ $q['option_c'] }}</li>
                                            <li>D. {{ $q['option_d'] }}</li>
                                        </ul>
                                        <p style="margin-top: 8px; font-size: 0.85rem; color: #34d399;"><strong>Kunci Jawaban: {{ $q['correct_answer'] }}</strong></p>
                                    </div>
                                    <form action="{{ route('course-quiz.questions.destroy', [$courseQuiz->id, $q['id']]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" style="padding: 6px 12px; font-size: 0.8rem;" onclick="return confirm('Hapus soal ini?')">Hapus</button>
                                    </form>
                                </div>
                            @empty
                                <p style="color: #94a3b8; font-style: italic;">Belum ada soal yang ditambahkan pada quiz ini.</p>
                            @endforelse
                        </div>

                        <div style="margin-top: 25px;">
                            <a href="{{ route('course-quiz.index') }}" class="btn-action" style="background: #334155; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 0.85rem;">Kembali ke Daftar Quiz</a>
                        </div>
                    @endhasanyrole

                   <!-- student -->
                   @role('Student')
                        @php
                            $user = Auth::user();
                            $courseId = optional($courseQuiz->courseContent)->course_id;
                            $courseStudent = $user->courseStudents()->where('course_id', $courseId)->first();
                            $currentPercentage = $courseStudent->percentage ?? 0;

                            $fixedTotalQuizzes = 8;
                            $incrementPerQuiz = 100 / $fixedTotalQuizzes;
                            $quizzesCompletedCount = ($incrementPerQuiz > 0) ? floor($currentPercentage / $incrementPerQuiz) : 0;

                            $allQuizzes = \App\Models\Course_Quiz::whereHas('courseContent', function($q) use ($courseId) {
                                $q->where('course_id', $courseId);
                            })->get();

                            $quizIndex = $allQuizzes->search(function ($item) use ($courseQuiz) {
                                return $item->id === $courseQuiz->id;
                            });

                            $isLocked = ($quizIndex !== false && $quizIndex < $quizzesCompletedCount);
                        @endphp

                        @if(empty($courseQuiz->questions))
                            <div style="text-align: center; padding: 40px; color: #94a3b8;">
                                <p>Quiz untuk sesi ini belum memiliki soal.</p>
                            </div>
                        @elseif($isLocked)
                            <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: 10px; padding: 30px; margin-bottom: 20px; text-align: center;">
                                <span style="font-size: 2rem;">!!</span>
                                <h3 style="font-size: 1.1rem; font-weight: 600; color: #0f172a; margin-top: 8px;">Quiz Sesi Ini Sudah Selesai</h3>
                                <p style="font-size: 0.9rem; color: #64748b; margin-top: 4px;">Anda sudah menyelesaikan quiz ini. Sesi telah terkunci.</p>
                            </div>

                            <div style="margin-top: 25px;">
                                <a href="{{ route('course-quiz.index') }}" class="btn-action" style="background: #334155; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 0.9rem; display: inline-block;">Kembali ke Daftar Quiz</a>
                            </div>
                        @else
                            <form action="{{ route('course-quiz.submit', $courseQuiz->id) }}" method="POST">
                                @csrf
                                <div style="display: flex; flex-direction: column; gap: 20px;">
                                    @foreach($courseQuiz->questions as $index => $q)
                                        <div style="padding: 16px; border-radius: 8px; border: 1px solid var(--border-color); background: #ffffff;">
                                            <p style="font-weight: 600; margin-bottom: 12px; color: #0f172a;">{{ $index + 1 }}. {{ $q['question'] }}</p>
                                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                                <label style="cursor: pointer;"><input type="radio" name="answers[{{ $q['id'] }}]" value="A" required> A. {{ $q['option_a'] }}</label>
                                                <label style="cursor: pointer;"><input type="radio" name="answers[{{ $q['id'] }}]" value="B"> B. {{ $q['option_b'] }}</label>
                                                <label style="cursor: pointer;"><input type="radio" name="answers[{{ $q['id'] }}]" value="C"> C. {{ $q['option_c'] }}</label>
                                                <label style="cursor: pointer;"><input type="radio" name="answers[{{ $q['id'] }}]" value="D"> D. {{ $q['option_d'] }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div style="margin-top: 25px; display: flex; justify-content: space-between; align-items: center;">
                                    <a href="{{ route('course-quiz.index') }}" class="btn-action" style="background: #334155; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 0.85rem;">Kembali</a>
                                    <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 0.9rem;">Submit Quiz</button>
                                </div>
                            </form>
                        @endif
                    @endrole
                </div>
            </div>
        </main>
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