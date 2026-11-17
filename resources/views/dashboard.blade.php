@extends('layouts.app')

@section('title', 'Dashboard')

<style>
    .student-info-box {
        display: flex;
        gap: 20px;
        align-items: center;
        border: 1px solid #1e293b;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .student-avatar {
        width: 72px; 
        height: 72px; 
        border-radius: 50%; 
        object-fit: cover;
    }
    .student-details {
        flex: 1;
        min-width: 250px;
    }
    .student-name {
        font-size: 1.1rem; 
        font-weight: 700;
        margin-bottom: 4px;
    }
    .student-meta {
        font-size: 0.85rem;
        color: #94a3b8;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 4px;
    }
    .badge-status {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-active { background-color: #d1fae5; color: #065f46; }
    .badge-inactive { background-color: #f1f5f9; color: #64748b; }

    .progress-track {
        background: #1e293b;
        border-radius: 20px;
        height: 6px;
        width: 100px;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
    }
    .progress-fill { background: #38bdf8; height: 100%; border-radius: 20px; }
</style>

@section('content')
<div class="dashboard-card" style="padding: 20px;">
    <h2 class="card-title" style="font-size: 1.15rem; margin-bottom: 8px;">Welcome!</h2>
    <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">You have successfully logged into the OSEDU Bright Future Portal system.</p>
    <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">You can manage your courses, view your progress, and access various features based on your role. Use the sidebar to navigate through different sections of the portal.</p>

    @role('Admin')
        <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">As an admin, you have full access to manage users, courses, and other administrative tasks.</p>
        <div class="quick-stats">
        </div>
    @endrole

    @role('Tutor')
        @php
            $tutorUser = Auth::user();
            $tutorProfile = optional($tutorUser)->entity; 
            // Fallback aman kalau variabel belum terkirim dari controller
            $tutorCourses = $tutorCourses ?? collect();
            $handledStudents = $handledStudents ?? collect();
        @endphp

        <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">As a tutor, you can manage your courses and track the students assigned to you.</p>
        <div class="quick-stats">
        </div>

        @if($tutorUser)
        <div class="student-info-box" style="display: flex; gap: 20px; align-items: center; background: #0f172a; border: 1px solid #1e293b; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
            <img src="{{ optional($tutorProfile)->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(optional($tutorProfile)->name ?? $tutorUser->name) . '&size=72' }}" alt="Avatar" style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover;">
            
            <div class="student-details">
                <h4 style="font-size: 1.1rem; color: #f1f5f9; font-weight: 700; margin-bottom: 6px;">{{ optional($tutorProfile)->name ?? $tutorUser->name }}</h4>
                <div style="display: flex; gap: 20px; font-size: 0.85rem; color: #94a3b8; flex-wrap: wrap;">
                    <span>Email: <strong style="color: #f1f5f9;">{{ $tutorUser->email }}</strong></span>
                    <span>No. HP: <strong style="color: #f1f5f9;">{{ optional($tutorProfile)->phone ?? '-' }}</strong></span>
                    <span>Total Course: <strong style="color: #f1f5f9;">{{ $tutorCourses->count() }}</strong></span>
                </div>
            </div>
        </div>
        @endif

        <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 12px; color: #f1f5f9;">Course yang Diampu</h3>
        <div class="table-responsive" style="padding-bottom: 8px; overflow-x: auto; margin-bottom: 28px;">
            <table class="compact-table" style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color, #cbd5e1); text-align: left;">
                        <th style="padding: 8px 10px; color: #94a3b8;">JUDUL COURSE</th>
                        <th style="padding: 8px 10px; color: #94a3b8;">TIPE</th>
                        <th style="padding: 8px 10px; color: #94a3b8;">STATUS</th>
                        <th style="padding: 8px 10px; color: #94a3b8;">DIBUAT PADA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tutorCourses as $course)
                    <tr style="border-bottom: 1px solid var(--border-color, #cbd5e1);">
                        <td style="padding: 8px 10px; color: #f1f5f9;"><strong>{{ $course->title }}</strong></td>
                        <td style="padding: 8px 10px; color: #f1f5f9;">
                            <span class="badge" style="background: #e2e8f0; color: #475569; padding: 2px 6px; font-size: 11px; border-radius: 4px;">{{ ucfirst($course->course_type ?? '-') }}</span>
                        </td>
                        <td style="padding: 8px 10px;">
                            <span class="badge-status {{ $course->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td style="padding: 8px 10px; color: #94a3b8;">
                            {{ $course->created_at ? $course->created_at->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">Kamu belum mengampu course apa pun.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 12px; color: #f1f5f9;">Siswa yang Ditangani</h3>
        <div class="table-responsive" style="padding-bottom: 8px; overflow-x: auto; margin-bottom: 28px;">
            <table class="compact-table" style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color, #cbd5e1); text-align: left;">
                        <th style="padding: 8px 10px; color: #94a3b8;">AVATAR</th>
                        <th style="padding: 8px 10px; color: #94a3b8;">NAMA SISWA</th>
                        <th style="padding: 8px 10px; color: #94a3b8;">EMAIL</th>
                        <th style="padding: 8px 10px; color: #94a3b8;">STATUS AKUN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($handledStudents as $student)
                    <tr style="border-bottom: 1px solid var(--border-color, #cbd5e1);">
                        <td style="padding: 8px 10px;">
                            <img src="{{ optional($student->entity)->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(optional($student->entity)->name ?? 'S') }}" alt="Avatar" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                        </td>
                        <td style="padding: 8px 10px; color: #f1f5f9;"><strong>{{ optional($student->entity)->name ?? '-' }}</strong></td>
                        <td style="padding: 8px 10px; color: #94a3b8;">{{ $student->email }}</td>
                        <td style="padding: 8px 10px;">
                            <span class="badge-status {{ $student->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $student->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada siswa yang terdaftar di course kamu.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endrole

    @role('Student')
        <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">As a student, you can access your enrolled courses, complete quizzes, and track your learning progress.</p>
        <div class="quick-stats">
        </div>
        @php
            $student = Auth::user();
        @endphp

        <!-- 1. Ringkasan Profil Singkat Student -->
        @if($student)
        <div class="student-info-box">
            <img src="{{ $student->entity->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->entity->name ?? $student->name) . '&size=72' }}" alt="Avatar" class="student-avatar">
            
            <div class="student-details">
                <h4 class="student-name">{{ $student->entity->name ?? $student->name }}</h4>
                <div class="student-meta">
                    <span>Email: <strong>{{ $student->email }}</strong></span>
                    <span>No. HP: <strong>{{ $student->entity->phone ?? '-' }}</strong></span>
                </div>
            </div>
        </div>
        @endif

        <!-- 2. Ringkasan Course yang Diikuti -->
        <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 12px;">Course & Progres Saya</h3>
        <div class="table-responsive" style="padding-bottom: 8px; overflow-x: auto; margin-bottom: 28px;">
            <table class="compact-table" style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color, #cbd5e1); text-align: left;">
                        <th style="padding: 8px 10px;">COURSE</th>
                        <th style="padding: 8px 10px;">TUTOR PEMBIMBING</th>
                        <th style="padding: 8px 10px;">PROGRESS MURID</th>
                        <th style="padding: 8px 10px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $enrolledCourses = optional($student)->courseStudents ?? collect();
                    @endphp
                    @forelse($enrolledCourses as $enrollment)
                    <tr style="border-bottom: 1px solid var(--border-color, #cbd5e1);">
                        <td style="padding: 8px 10px;"><strong>{{ $enrollment->course->title ?? '-' }}</strong></td>
                        <td style="padding: 8px 10px;">{{ optional($enrollment->course->creator)->name ?? '-' }}</td>
                        <td style="padding: 8px 10px;">
                            <span class="progress-track"><span class="progress-fill" style="width: {{ min(100, $enrollment->percentage ?? 0) }}%;"></span></span>
                            <span style="font-size: 11px; margin-left: 6px;">{{ number_format($enrollment->percentage ?? 0, 1) }}%</span>
                        </td>
                        <td style="padding: 8px 10px;">
                            <span class="badge-status {{ ($enrollment->is_complete ?? false) ? 'badge-active' : 'badge-inactive' }}">
                                {{ ($enrollment->is_complete ?? false) ? 'Completed' : 'On Progress' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; opacity: 0.7; padding: 20px;">Kamu belum terdaftar di course manapun.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endrole
</div>
@endsection