<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Manage Course - OSEDU Bright Future</title>

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

        <div class="user-dropdown-wrapper" style="position: relative;">
            <button class="dropdown-trigger" id="userMenuBtn">
                <span>{{ Auth::user()->name ?? Auth::user()->email }}</span>
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div class="dropdown-menu-card" id="userMenuDropdown" style="display: none; position: absolute; right: 0; top: 100%;">
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

    <div class="app-layout" style="display: flex; width: 100%; min-height: calc(100vh - 80px);">

        @include('sidebar')

        <!-- MAIN CONTENT -->
        <main class="main-content" style="flex: 1;">
            <div class="content-body" style="padding-top: 10px;">
                <div class="dashboard-card" style="padding: 20px;">

                    <!-- HEADER -->
                    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h2 class="card-title" style="font-size: 1.15rem;">Course Management</h2>
                        <a href="{{ route('course.create') }}" class="btn-primary" style="font-size: 0.8rem; padding: 8px 14px;">
                            + Tambah Course Baru
                        </a>
                    </div>

                    <!-- FILTER -->
                    <div class="filter-section" style="display: flex; gap: 10px; margin-bottom: 16px;">
                        <input type="text" id="searchCourse" placeholder="Cari Course..." class="search-input" style="font-size: 0.8rem; padding: 6px 12px;">

                        <select id="courseTypeFilter" class="filter-select" style="font-size: 0.8rem; padding: 6px 12px;">
                            <option value="">Course Type</option>
                            <option value="regular">Regular</option>
                            <option value="private">Private</option>
                            <option value="bootcamp">Bootcamp</option>
                        </select>

                        <select id="statusFilter" class="filter-select" style="font-size: 0.8rem; padding: 6px 12px;">
                            <option value="">Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive" style="padding-bottom: 8px; overflow-x: auto;">
                        <table class="compact-table" style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                    <th style="padding: 8px 10px;">THUMBNAIL</th>
                                    <th style="padding: 8px 10px;">TITLE</th>
                                    <th style="padding: 8px 10px;">SLUG</th>
                                    <th style="padding: 8px 10px;">DESCRIPTION</th>
                                    <th style="padding: 8px 10px;">COURSE TYPE</th>
                                    <th style="padding: 8px 10px;">CREATED BY</th>
                                    <th style="padding: 8px 10px;">ACTIVE</th>
                                    <th style="padding: 8px 10px;">DISPLAYED</th>
                                    <th style="padding: 8px 10px;">ACTIONS</th>
                                </tr>
                            </thead>

                            <tbody id="courseTableBody">
                                @forelse($courses as $course)
                                    <tr class="course-row" 
                                        data-title="{{ strtolower($course->title) }}" 
                                        data-type="{{ strtolower($course->course_type) }}" 
                                        data-active="{{ $course->is_active ? 'active' : 'inactive' }}"
                                        style="border-bottom: 1px solid var(--border-color);">

                                        <!-- THUMBNAIL -->
                                        <td style="padding: 8px 10px; white-space: nowrap;">
                                            @if($course->thumbnail_url)
                                                <img src="{{ asset('storage/' . $course->thumbnail_url) }}" 
                                                     alt="{{ $course->title }}" 
                                                     class="avatar" 
                                                     style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                                <span style="display: none;">No Image</span>
                                            @else
                                                <span>No Image</span>
                                            @endif
                                        </td>

                                        <!-- TITLE -->
                                        <td style="padding: 8px 10px; white-space: nowrap;">
                                            <strong>{{ $course->title }}</strong>
                                        </td>

                                        <td style="padding: 8px 10px; white-space: nowrap;">
                                            <strong>{{ $course->slug }}</strong>
                                        </td>

                                        <!-- DESCRIPTION -->
                                        <td style="padding: 8px 10px; max-width: 250px;">
                                            <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ $course->description }}
                                            </div>
                                        </td>

                                        <!-- COURSE TYPE -->
                                        <td style="padding: 8px 10px; white-space: nowrap;">
                                            @if($course->course_type == 'regular')
                                                <span class="badge" style="background: #e2e8f0; color: #475569; padding: 3px 6px; font-size: 11px; border-radius: 4px;">Regular</span>
                                            @elseif($course->course_type == 'private')
                                                <span class="badge" style="background: #fce7f3; color: #9d174d; padding: 3px 6px; font-size: 11px; border-radius: 4px;">Private</span>
                                            @else
                                                <span class="badge" style="background: #dbeafe; color: #1e40af; padding: 3px 6px; font-size: 11px; border-radius: 4px;">Bootcamp</span>
                                            @endif
                                        </td>

                                        <!-- CREATED BY -->
                                        <td style="padding: 8px 10px; white-space: nowrap;">
                                            {{ $course->creator->name ?? '-' }}
                                        </td>

                                        <!-- ACTIVE -->
                                        <td style="padding: 8px 10px; white-space: nowrap;">
                                            @if($course->is_active)
                                                <span class="badge" style="background: #dcfce7; color: #166534; padding: 3px 6px; font-size: 11px; border-radius: 4px;">Active</span>
                                            @else
                                                <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 3px 6px; font-size: 11px; border-radius: 4px;">Inactive</span>
                                            @endif
                                        </td>

                                        <!-- DISPLAYED -->
                                        <td style="padding: 8px 10px; white-space: nowrap;">
                                            @if($course->is_displayed)
                                                <span class="badge" style="background: #dcfce7; color: #166534; padding: 3px 6px; font-size: 11px; border-radius: 4px;">Yes</span>
                                            @else
                                                <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 3px 6px; font-size: 11px; border-radius: 4px;">No</span>
                                            @endif
                                        </td>

                                        <!-- ACTIONS -->
                                        <td style="padding: 8px 10px; white-space: nowrap;">
                                            <div style="display: flex; gap: 4px;">
                                                <a href="{{ route('course.edit', $course->id) }}" class="btn-action btn-edit" style="padding: 3px 8px; font-size: 11px;">
                                                    Edit
                                                </a>

                                                <form action="{{ route('course.destroy', $course->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-delete" style="border:none; cursor:pointer; padding:3px 8px; font-size:11px;" onclick="return confirm('Yakin ingin menghapus course ini?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 16px; color: #64748b;">
                                            Belum ada data course.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <!-- SCRIPT INTERAKTIF (SEARCH, FILTER & DROPDOWN) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dropdown Profile Toggle
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userMenuDropdown = document.getElementById('userMenuDropdown');

            if (userMenuBtn && userMenuDropdown) {
                userMenuBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    userMenuDropdown.style.display = userMenuDropdown.style.display === 'none' ? 'block' : 'none';
                });

                document.addEventListener('click', function () {
                    userMenuDropdown.style.display = 'none';
                });
            }

            // Client-side Filtering
            const searchInput = document.getElementById('searchCourse');
            const typeFilter = document.getElementById('courseTypeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const rows = document.querySelectorAll('.course-row');

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();
                const selectedType = typeFilter.value.toLowerCase();
                const selectedStatus = statusFilter.value.toLowerCase();

                rows.forEach(row => {
                    const title = row.getAttribute('data-title');
                    const type = row.getAttribute('data-type');
                    const active = row.getAttribute('data-active');

                    const matchesSearch = title.includes(searchValue);
                    const matchesType = selectedType === '' || type === selectedType;
                    const matchesStatus = selectedStatus === '' || active === selectedStatus;

                    if (matchesSearch && matchesType && matchesStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterTable);
            typeFilter.addEventListener('change', filterTable);
            statusFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>