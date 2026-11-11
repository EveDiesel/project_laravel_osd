@php
    $stu = auth()->user()->hasRole('Student');
    $tut = auth()->user()->hasRole('Tutor');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Manage Course Content - OSEDU Bright Future</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Style CSS -->
    @vite(['resources/css/style.css', 'resources/css/crud.css'])

    <!-- Tambahan Styling Inline Khusus Pill Card Content agar Super Rapi -->
    <style>
        .content-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 6px 10px;
            border-radius: 50px; /* Bentuk pill melengkung */
            max-width: 300px;
        }
        .content-pill-thumb {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            background: #334155;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #94a3b8;
        }
        .content-pill-text {
            font-size: 0.8rem;
            color: #cbd5e1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
                    
                @hasanyrole('Admin|Tutor')
                    <!-- Header Section & Action Button -->
                    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h2 class="card-title" style="font-size: 1.25rem;">Course Content</h2>
                            <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Kelola konten course dalam aplikasi OSEDU.</p>
                        </div>
                        <a href="{{ route('course-content.create') }}" class="btn-primary" style="text-decoration: none; font-size: 0.85rem; padding: 8px 16px;">+ Tambah Content Baru</a>
                    </div>
                @endhasanyrole

                @role('Student')
                    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h2 class="card-title" style="font-size: 1.25rem;">Content</h2>
                            <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 4px;">Lihat konten course yang tersedia untuk Anda.</p>
                        </div>
                    </div>
                @endrole

                    <!-- Table Data -->
                    <div class="table-responsive">
                        <table style="width: 100%; border-collapse: collapse; vertical-align: middle;">
                            <thead>
                                <tr>
                                    @if($stu)
                                        <th>TITLE</th>
                                        <th>CONTENT</th>
                                        <th>DETAILS</th>
                                    @else
                                        <th>NO</th>
                                        <th>COURSE</th>
                                        <th>LEVEL</th>
                                        <th>TITLE</th>
                                        @if(!$tut)<th>CONTENT TYPE</th>@endif
                                        <th>SESSION</th>
                                        <th>CONTENT</th>
                                        @if(!$tut)<th>DETAILS</th>@endif
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contents as $index => $cont)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    @if($stu)
                                        <!-- Isi Baris khusus Student -->
                                        <td><strong>{{ $cont->title }}</strong></td>
                                        <td>
                                            <div class="content-pill">
                                                <div class="content-pill-thumb">📄</div>
                                                <div class="content-pill-text">{{ Str::limit(strip_tags($cont->content), 40) }}</div>
                                            </div>
                                        </td>
                                        <td> 
                                            <a href="{{ route('course-content.show', $cont->id) }}" class="btn-action btn-edit" style="text-decoration: none">Lihat</a>
                                        </td>
                                    @else
                                        <!-- Isi Baris untuk Admin & Tutor -->
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $cont->course->title ?? '-' }}</td>
                                        <td><strong>{{ $cont->courseLevel->name ?? '-' }}</strong></td>
                                        <td>{{ $cont->title }}</td>
                                        
                                        @if(!$tut)
                                            <td>{{ $cont->content_type }}</td>
                                        @endif
                                        
                                        <td>{{ $cont->session }}</td>
                                        <td>
                                            <!-- Pill Card Compact untuk menghindari gambar melar -->
                                            <div class="content-pill">
                                                @php
                                                    // Ambil gambar pertama dari konten HTML jika ada, atau tampilkan icon default
                                                    preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $cont->content, $matches);
                                                    $firstImg = $matches[1] ?? null;
                                                @endphp

                                                @if($firstImg)
                                                    <img src="{{ $firstImg }}" alt="Thumb" class="content-pill-thumb">
                                                @else
                                                    <div class="content-pill-thumb">-</div>
                                                @endif

                                                <div class="content-pill-text">
                                                    {{ Str::limit(strip_tags($cont->content), 35) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td> 
                                            <a href="{{ route('course-content.show', $cont->id) }}" class="btn-action btn-edit" style="text-decoration: none">Lihat</a>
                                        </td>
                                    @endif

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $stu ? 3 : 9 }}" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada data course content.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination jika menggunakan paginate -->
                    @if(method_exists($contents, 'links'))
                    <div class="pagination-wrapper" style="margin-top: 20px;">
                        {{ $contents->links() }}
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