<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Manage Entities - OSEDU Bright Future</title>

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

      

        <div class="user-dropdown-wrapper">
            <button class="dropdown-trigger" id="userMenuBtn">
                <span>{{ Auth::user()->name ?? Auth::user()->email }}</span>
                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

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

    <div class="app-layout" style="display: flex; width: 100%; min-height: calc(100vh - 80px);">
        
        @include('sidebar')

        <main class="main-content">
            <div class="content-body" style="padding-top: 10px;">
                <div class="dashboard-card" style="padding: 20px;">
                    
                    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h2 class="card-title" style="font-size: 1.15rem;">Entity Management</h2>
                        <a href="{{ route('entity.create') }}" class="btn-primary" style="font-size: 0.8rem; padding: 8px 14px;">+ Tambah Entity Baru</a>
                    </div>

                    <div class="filter-section" style="display: flex; gap: 10px; margin-bottom: 16px;">
                        <input type="text" placeholder="Cari Nama..." class="search-input" style="font-size: 0.8rem; padding: 6px 12px;">
                        <select class="filter-select" style="font-size: 0.8rem; padding: 6px 12px;">
                            <option value="">Filter Kota</option>
                        </select>
                        <select class="filter-select" style="font-size: 0.8rem; padding: 6px 12px;">
                            <option value="">Tipe Akun</option>
                        </select>
                    </div>

                    <div class="table-responsive" style="padding-bottom: 8px; overflow-x: auto;">
                        <table class="compact-table" style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                    <th style="padding: 8px 10px; white-space: nowrap;">AVATAR</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">USER ID</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">NAME</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">EMAIL</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">PHONE</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">ADDRESS</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">CITY</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">BIRTHDATE</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">GENDER</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">ACCOUNT TYPE</th>
                                    <th style="padding: 8px 10px; white-space: nowrap;">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entities as $entity)
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <img src="{{ $entity->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100' }}" alt="Avatar" class="avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                    </td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        @if($entity->user_id)
                                            <span class="badge" style="background: #e2e8f0; color: #475569; padding: 2px 6px; font-size: 11px; border-radius: 4px;">#{{ $entity->user_id }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td style="padding: 8px 10px; white-space: nowrap;"><strong>{{ $entity->name }}</strong></td>

                                    <td style="padding: 8px 10px; white-space: nowrap;" class="text-muted">{{ $entity->email ?? '-' }}</td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">{{ $entity->phone ?? '-' }}</td>

                                    <td style="padding: 8px 10px; white-space: nowrap; max-width: 180px; overflow: hidden; text-overflow: ellipsis;">{{ $entity->address ?? '-' }}</td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">{{ $entity->city ?? '-' }}</td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">{{ $entity->birthdate ? \Carbon\Carbon::parse($entity->birthdate)->format('d M Y') : '-' }}</td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        @if($entity->gender == 'male')
                                            Male
                                        @elseif($entity->gender == 'female')
                                            Female
                                        @elseif($entity->gender == 'rather_not_say')
                                            Rather Not Say
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        @if($entity->is_company)
                                            <span class="badge badge-company" style="background-color: #dbeafe; color: #1e40af; padding: 3px 6px; border-radius: 4px; font-weight: 600; font-size: 11px;">Company</span>
                                        @else
                                            <span class="badge badge-personal" style="background-color: #fce7f3; color: #9d174d; padding: 3px 6px; border-radius: 4px; font-weight: 600; font-size: 11px;">Personal</span>
                                        @endif
                                    </td>

                                    <td style="padding: 8px 10px; white-space: nowrap;">
                                        <div style="display: flex; gap: 4px;">
                                            <a href="{{ route('entity.edit', $entity->id) }}" class="btn-action btn-edit" style="padding: 3px 8px; font-size: 11px;">Edit</a>
                                            <form action="{{ route('entity.destroy', $entity->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" style="border:none; cursor:pointer; padding: 3px 8px; font-size: 11px;" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada data entity.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($entities, 'links'))
                    <div class="pagination-wrapper" style="margin-top: 16px;">
                        {{ $entities->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </main>

    </div>

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