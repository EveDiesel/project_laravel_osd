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
<body class="dashboard-body" style="background-color: #070a13;">

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
                    <div class="alert alert-success" style="padding: 12px 16px; background-color: #065f46; color: #d1fae5; border-radius: 8px; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- CARD UTAMA (Diberi background gelap agar menyatu) -->
                <div class="dashboard-card" style="width: 100%; background: #0d121f !important; border: 1px solid #1a2235 !important; color: #ffffff !important; box-shadow: none !important;">
                    
                    <!-- HEADER SECTION -->
                    <div style="margin-bottom: 20px;">
                        <h2 class="card-title" style="font-size: 1.25rem; color: #ffffff !important;">Quiz: {{ $courseQuiz->name }}</h2>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">
                            Materi: {{ $courseQuiz->courseContent->title ?? '-' }}
                        </p>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #1a2235; margin-bottom: 24px;">

                    {{-- =================================================== --}}
                    {{-- TAMPILAN UNTUK TUTOR / ADMIN (KELOLA & TAMBAH SOAL) --}}
                    {{-- =================================================== --}}
                    @hasanyrole('Admin|Tutor')
                        <h3 style="font-size: 1.1rem; margin-bottom: 15px; color: #ffffff;">+ Tambah Pertanyaan Baru</h3>
                        
                        <form action="{{ route('course-quiz.questions.store', $courseQuiz->id) }}" method="POST" style="background: #131b2e; border: 1px solid #1f2d45; padding: 24px; border-radius: 10px; margin-bottom: 30px;">
                            @csrf
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label" style="color: #cbd5e1; font-weight: 500; margin-bottom: 6px; display: block;">Pertanyaan</label>
                                <textarea name="question" class="form-control" rows="3" required style="width: 100%; padding: 12px; background: #070a13; border: 1px solid #2a3649; color: #ffffff; border-radius: 8px;"></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                <div>
                                    <label class="form-label" style="color: #cbd5e1; font-weight: 500; margin-bottom: 6px; display: block;">Pilihan A</label>
                                    <input type="text" name="option_a" class="form-control" required style="width: 100%; padding: 10px 12px; background: #070a13; border: 1px solid #2a3649; color: #ffffff; border-radius: 8px;">
                                </div>
                                <div>
                                    <label class="form-label" style="color: #cbd5e1; font-weight: 500; margin-bottom: 6px; display: block;">Pilihan B</label>
                                    <input type="text" name="option_b" class="form-control" required style="width: 100%; padding: 10px 12px; background: #070a13; border: 1px solid #2a3649; color: #ffffff; border-radius: 8px;">
                                </div>
                                <div>
                                    <label class="form-label" style="color: #cbd5e1; font-weight: 500; margin-bottom: 6px; display: block;">Pilihan C</label>
                                    <input type="text" name="option_c" class="form-control" required style="width: 100%; padding: 10px 12px; background: #070a13; border: 1px solid #2a3649; color: #ffffff; border-radius: 8px;">
                                </div>
                                <div>
                                    <label class="form-label" style="color: #cbd5e1; font-weight: 500; margin-bottom: 6px; display: block;">Pilihan D</label>
                                    <input type="text" name="option_d" class="form-control" required style="width: 100%; padding: 10px 12px; background: #070a13; border: 1px solid #2a3649; color: #ffffff; border-radius: 8px;">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label class="form-label" style="color: #cbd5e1; font-weight: 500; margin-bottom: 6px; display: block;">Kunci Jawaban Benar</label>
                                <select name="correct_answer" class="form-control" required style="width: 200px; padding: 10px 12px; background: #070a13; border: 1px solid #2a3649; color: #ffffff; border-radius: 8px;">
                                    <option value="A" style="background: #070a13; color: #fff;">A</option>
                                    <option value="B" style="background: #070a13; color: #fff;">B</option>
                                    <option value="C" style="background: #070a13; color: #fff;">C</option>
                                    <option value="D" style="background: #070a13; color: #fff;">D</option>
                                </select>
                            </div>

                            <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 0.9rem;">Simpan Soal</button>
                        </form>

                        <h3 style="font-size: 1.1rem; margin-bottom: 15px; color: #ffffff;">Daftar Soal Saat Ini</h3>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            @forelse($courseQuiz->questions ?? [] as $index => $q)
                                <div style="background: #131b2e; border: 1px solid #1f2d45; padding: 16px; border-radius: 8px; display: flex; justify-content: space-between; align-items: flex-start;">
                                    <div>
                                        <p style="font-weight: 600; margin-bottom: 8px; color: #fff;">{{ $index + 1 }}. {{ $q['question'] }}</p>
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

                    {{-- =================================================== --}}
                    {{-- TAMPILAN UNTUK STUDENT (MENGERJAKAN & SUBMIT QUIZ) --}}
                    {{-- =================================================== --}}
                    @role('Student')
                        @if(empty($courseQuiz->questions))
                            <p style="color: #94a3b8; text-align: center; padding: 30px;">Quiz ini belum memiliki soal.</p>
                        @else
                            <form action="{{ route('course-quiz.submit', $courseQuiz->id) }}" method="POST">
                                @csrf
                                <div style="display: flex; flex-direction: column; gap: 20px;">
                                    @foreach($courseQuiz->questions as $index => $q)
                                        <div style="background: #131b2e; border: 1px solid #1f2d45; padding: 16px; border-radius: 8px;">
                                            <p style="font-weight: 600; margin-bottom: 12px; color: #fff;">{{ $index + 1 }}. {{ $q['question'] }}</p>
                                            
                                            <div style="display: flex; flex-direction: column; gap: 8px; color: #cbd5e1;">
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
                                    <button type="submit" class="btn-primary" style="padding: 10px 24px; font-size: 0.9rem;" onclick="return confirm('Kumpul jawaban sekarang? Progress Anda akan diperbarui.')">Kumpul Jawaban Quiz</button>
                                </div>
                            </form>
                        @endif
                    @endrole

                </div>
            </div>
        </main>
    </div>

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