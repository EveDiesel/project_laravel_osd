<aside class="sidebar">
    <div>
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

            <a href="{{ route('roles.index') }}" class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Role</span>
            </a>

            <a href="{{ route('course.index') }}" class="menu-item {{ request()->routeIs('course.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253" />
                </svg>
                <span>Courses</span>
            </a>

            <a href="{{ route('course-level.index') }}" class="menu-item {{ request()->routeIs('course-level.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Course Levels</span>
            </a>
            @endrole

            <a href="{{ route('course-content.index') }}" class="menu-item {{ request()->routeIs('course-content.*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4V7a1 1 0 011-1h4V3a1 1 0 011-1h4a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1v-11z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-4M21 14h-4M21 18h-4" />
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

    <div class="sidebar-footer">
        <div class="user-info">
            <span class="user-name">{{ Auth::user()->name ?? Auth::user()->email }}</span>
            <span class="user-role">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</span>
        </div>
    </div>
</aside>