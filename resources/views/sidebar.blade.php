<aside class="sidebar">
    <!-- Kontainer atas yang bisa di-scroll ke atas/bawah -->
    <div class="sidebar-scroll-container">
        <div class="sidebar-brand">
            <div style="color: #38bdf8; font-size: 11px; font-weight: 700; letter-spacing: 1px; margin-bottom: 12px;">MANAJEMEN DATA</div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            @role('Admin')
            <a href="{{ route('entity.index') }}" class="menu-item {{ request()->routeIs('entity.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m0 0v-5a2 2 0 012-2h2a2 2 0 012 2v5m-4 0h4"/>
                </svg>
                <span>Entities</span>
            </a>

            <a href="{{ route('users.index') }}" class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>User</span>
            </a>

            <a href="{{ route('roles.index') }}" class="menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Role</span>
            </a>

            <a href="{{ route('permissions.index') }}" class="menu-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span>Permission</span>
            </a>

            <a href="{{ route('course.index') }}" class="menu-item {{ request()->routeIs('course.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                </svg>
                <span>Courses</span>
            </a>

            <a href="{{ route('course-level.index') }}" class="menu-item {{ request()->routeIs('course-level.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Course Levels</span>
            </a>

            <a href="{{ route('activity.index') }}" class="menu-item {{ request()->routeIs('activity.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span>Activity</span>
            </a>
            @endrole

            <a href="{{ route('schedule.index') }}" class="menu-item {{ request()->routeIs('schedule.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Schedule</span>
            </a>

            <a href="{{ route('course-student.index') }}" class="menu-item {{ request()->routeIs('course-student.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0.01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"/>
                </svg>
                @hasanyrole('Admin|Tutor')
                    <span>Course Students</span>
                @endhasanyrole
                @role('Student')
                    <span>My Courses</span>
                @endrole
            </a>

            <a href="{{ route('course-quiz.index') }}" class="menu-item {{ request()->routeIs('course-quiz.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                @hasanyrole('Admin|Tutor')
                    <span>Course Quizzes</span>
                @endhasanyrole
                @role('Student')
                    <span>My Quizzes</span>
                @endrole

            <a href="{{ route('course-content.index') }}" class="menu-item {{ request()->routeIs('course-content.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4V7a1 1 0 011-1h4V3a1 1 0 011-1h4a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1v-11z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-4M21 14h-4M21 18h-4"/>
                </svg>
                @hasanyrole('Admin|Tutor')
                    <span>Course Contents</span>
                @endhasanyrole
                @role('Student')
                    <span>Content</span>
                @endrole
            </a>
        </nav>
    </div>

    <!-- Bagian Footer Sidebar (Profil User di bawah dan diam tidak ikut terpotong) -->
    <div class="sidebar-footer">
        <div class="user-info">
            <span class="user-name">{{ Auth::user()->name ?? Auth::user()->email }}</span>
            <span class="user-role">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</span>
        </div>
    </div>
</aside>