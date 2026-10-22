<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard - OSEDU Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Style CSS -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])
</head>
<body class="dashboard-body">

    <!-- TOP NAVBAR -->
    <header class="navbar-top">
        <a href="{{ route('dashboard') }}" class="navbar-brand" style="display: flex; align-items: center;">
            <img src="{{ Vite::asset('resources/image/logoosedu.png') }}" alt="OSEDU Logo" style="height: 120px; width: auto;">
        </a>

        <!-- User Dropdown Wrapper -->
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

    <!-- Wrapper Dashboard Layout -->
    <div class="app-layout" style="display: flex; width: 100%; min-height: calc(100vh - 80px);">
        
        <!-- SIDEBAR -->
            @include('sidebar')  
            
        <!-- MAIN CONTENT AREA -->
        <main class="main-content">
            <div class="content-body" style="padding-top: 10px;">
                <div class="dashboard-card" style="padding: 20px;">
                    <h2 class="card-title" style="font-size: 1.15rem; margin-bottom: 8px;">Welcome!</h2>
                    <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">You have successfully logged into the OSEDU Bright Future Portal system.</p>
                    <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">You can manage your courses, view your progress, and access various features based on your role. Use the sidebar to navigate through different sections of the portal.</p>

                    @role('Admin')
                        <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">As an admin, you have full access to manage users, courses, and other administrative tasks.</p>
                    <div class="quick-stats">
                        <a href="{{ route('entity.index') }}" class="stat-box">
                            <h3>Manage Entities</h3>
                            <p>Manage entity data</p>
                        </a>
                        <a href="{{ route('users.index') }}" class="stat-box">
                            <h3>Manage Users</h3>
                            <p>Manage users & role assignments</p>
                        </a>
                    </div>
                    @endrole
                </div>
            </div>
        </main>

    </div>

    <!-- Script Dropdown Profile -->
    <script>
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
    </script>

</body>
</html>