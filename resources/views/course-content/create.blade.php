
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Course Content - OSEDU Bright Future</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

    @vite(['resources/css/style.css', 'resources/css/crud.css'])

    <style>
        #toolbar-container {
            background: #172943;
            border: 1px solid #243954;
            border-radius: 8px 8px 0 0;
        }

        #toolbar-container .ql-stroke {
            stroke: #cbd5e1;
        }

        #toolbar-container .ql-fill {
            fill: #cbd5e1;
        }

        #toolbar-container .ql-picker {
            color: #cbd5e1;
        }

        #toolbar-container .ql-picker-options {
            background: #132239;
            border-color: #243954;
        }

        #toolbar-container .ql-picker-item {
            color: #e2e8f0;
        }

        #editor {
            min-height: 220px;
            background: #132239;
            color: #e2e8f0;
            border: 1px solid #243954;
            border-radius: 0 0 8px 8px;
        }

        #editor .ql-editor {
            min-height: 220px;
            font-size: 15px;
            line-height: 1.7;
        }

        #editor .ql-editor.ql-blank::before {
            color: #94a3b8;
            font-style: normal;
        }

        #editor .ql-editor a {
            color: #60a5fa;
        }

        #editor .ql-editor ol,
        #editor .ql-editor ul {
            padding-left: 1.5em;
        }

        #editor .ql-editor img {
            max-width: 100%;
            height: auto;
            cursor: pointer;
        }

        #editor .ql-editor .ql-align-justify {
            text-align: justify !important;
        }

        /* TAMBAHAN: GARIS PEMILIHAN GAMBAR */
        #editor .ql-editor img.selected-image {
            outline: 2px solid #3b82f6;
            outline-offset: 1px;
        }

        /* TAMBAHAN: TITIK BIRU UNTUK RESIZE GAMBAR */
        .image-resize-handle {
            position: fixed;
            width: 12px;
            height: 12px;
            background: #3b82f6;
            border: 2px solid #ffffff;
            border-radius: 50%;
            box-sizing: border-box;
            z-index: 99999;
            cursor: nwse-resize;
            touch-action: none;
        }

        @media (max-width: 768px) {
            .create-form-grid {
                grid-template-columns: 1fr !important;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="dashboard-body" style="background: #0b1220; color: #e2e8f0;">

    <!-- TOP NAVBAR -->
    <header class="navbar-top" style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 72px; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; background-color: #0d121f; border-bottom: 1px solid #1a2235;">

        <a href="{{ route('dashboard') }}" class="navbar-brand" style="display: flex; align-items: center;">
            <img src="{{ Vite::asset('resources/image/logoosedu.png') }}" alt="OSEDU Logo" style="height: 120px; width: auto;">
        </a>

        <!-- PROFILE & LOGOUT DROPDOWN -->
        <div class="user-dropdown-wrapper">
            <button type="button" class="dropdown-trigger" id="userMenuBtn">
                <span>{{ Auth::user()->name ?? Auth::user()->email }}</span>

                <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div class="dropdown-menu-card" id="userMenuDropdown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7-7h14a7 7 0 00-7 7z" />
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

    <!-- WRAPPER -->
    <div class="app-layout" style="margin-top: 72px; display: flex; width: 100%; min-height: calc(100vh - 72px);">

        <!-- SIDEBAR -->
        @include('sidebar')

        <!-- MAIN CONTENT -->
        <main class="main-content" style="margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; flex: 1;">

            <div class="content-body" style="width: 100%;">

                <!-- HEADER -->
                <div style="margin-bottom: 24px;">
                    <a href="{{ route('course-content.index') }}" style="color: #93c5fd; text-decoration: none;">
                        ← Kembali ke Course Content
                    </a>

                    <h1 style="color: white; font-size: 28px; font-weight: 700; margin-top: 20px;">
                        Tambah Course Content Baru
                    </h1>

                    <p style="color: #94a3b8; margin-top: 8px;">
                        Isi informasi dan materi course content di bawah ini.
                    </p>
                </div>

                <!-- ERROR ALERT -->
                @if ($errors->any())
                    <div class="alert alert-danger" style="background: #3b1720; border: 1px solid #7f1d1d; color: #fecaca; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM -->
                <div class="dashboard-card" style="padding: 28px; width: 100%; max-width: 100%; box-sizing: border-box; background: #0f1b30; border: 1px solid #1e3352; border-radius: 14px;">

                    <form id="createContentForm" action="{{ route('course-content.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- COURSE & COURSE LEVEL -->
                        <div class="create-form-grid" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px;">

                            <!-- COURSE -->
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label for="course_id" class="form-label" style="color: #93c5fd; display: block; margin-bottom: 8px;">
                                    Course
                                </label>

                                <select name="course_id" id="course_id" class="form-control filter-select" required style="width: 100%; padding: 12px; border-radius: 8px; background: #132239; color: white; border: 1px solid #243954;">
                                    <option value="" disabled {{ old('course_id') ? '' : 'selected' }}>Pilih Course...</option>

                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title ?? $course->name ?? 'Course #' . $course->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- COURSE LEVEL -->
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label for="course_level_id" class="form-label" style="color: #93c5fd; display: block; margin-bottom: 8px;">
                                    Course Level
                                </label>

                                <select name="course_level_id" id="course_level_id" class="form-control filter-select" required style="width: 100%; padding: 12px; border-radius: 8px; background: #132239; color: white; border: 1px solid #243954;">
                                    <option value="" disabled {{ old('course_level_id') ? '' : 'selected' }}>Pilih Course Level...</option>

                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ old('course_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name ?? $level->title ?? 'Level #' . $level->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- TITLE -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="title" class="form-label" style="color: #93c5fd; display: block; margin-bottom: 8px;">
                                Judul Content
                            </label>

                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="Masukkan judul content..." required style="width: 100%; padding: 12px; box-sizing: border-box; border-radius: 8px; background: #132239; color: white; border: 1px solid #243954;">
                        </div>

                        <!-- CONTENT TYPE & SESSION -->
                        <div class="create-form-grid" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px;">

                            <!-- CONTENT TYPE -->
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="content_type" class="form-label" style="color: #93c5fd; display: block; margin-bottom: 8px;">
                                    Content Type
                                </label>

                                <select name="content_type" id="content_type" class="form-control filter-select" required style="width: 100%; padding: 12px; border-radius: 8px; background: #132239; color: white; border: 1px solid #243954;">
                                    <option value="" disabled {{ old('content_type') ? '' : 'selected' }}>Pilih Content Type...</option>
                                    <option value="video" {{ old('content_type') == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="pdf" {{ old('content_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="document" {{ old('content_type') == 'document' ? 'selected' : '' }}>Document</option>
                                    <option value="url" {{ old('content_type') == 'url' ? 'selected' : '' }}>URL</option>
                                    <option value="etc" {{ old('content_type') == 'etc' ? 'selected' : '' }}>Etc</option>
                                </select>
                            </div>

                            <!-- SESSION -->
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="session" class="form-label" style="color: #93c5fd; display: block; margin-bottom: 8px;">
                                    Session
                                </label>

                                <input type="number" name="session" id="session" class="form-control" value="{{ old('session') }}" placeholder="Masukkan nomor session..." min="1" required style="width: 100%; padding: 12px; box-sizing: border-box; border-radius: 8px; background: #132239; color: white; border: 1px solid #243954;">
                            </div>
                        </div>

                        <!-- CONTENT QUILL EDITOR -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="editor" class="form-label" style="color: #93c5fd; display: block; margin-bottom: 8px;">
                                Content
                            </label>

                            <!-- EDITOR TOOLBAR -->
                            <div id="toolbar-container">
                                <span class="ql-formats">
                                    <select class="ql-header">
                                        <option selected></option>
                                        <option value="1"></option>
                                        <option value="2"></option>
                                        <option value="3"></option>
                                    </select>

                                    <select class="ql-size">
                                        <option value="small"></option>
                                        <option selected></option>
                                        <option value="large"></option>
                                        <option value="huge"></option>
                                    </select>
                                </span>

                                <span class="ql-formats">
                                    <button type="button" class="ql-bold"></button>
                                    <button type="button" class="ql-italic"></button>
                                    <button type="button" class="ql-underline"></button>
                                    <button type="button" class="ql-strike"></button>
                                </span>

                                <span class="ql-formats">
                                    <select class="ql-color"></select>
                                    <select class="ql-background"></select>
                                </span>

                                <span class="ql-formats">
                                    <button type="button" class="ql-list" value="ordered" title="Numbering"></button>
                                    <button type="button" class="ql-list" value="bullet" title="Bullet List"></button>
                                </span>

                                <span class="ql-formats">
                                    <select class="ql-align" title="Perataan teks">
                                        <option selected></option>
                                        <option value="center"></option>
                                        <option value="right"></option>
                                        <option value="justify"></option>
                                    </select>
                                </span>

                                <span class="ql-formats">
                                    <button type="button" class="ql-blockquote"></button>
                                    <button type="button" class="ql-code-block"></button>
                                    <button type="button" class="ql-link"></button>
                                    <button type="button" class="ql-image"></button>
                                </span>

                                <span class="ql-formats">
                                    <button type="button" class="ql-clean"></button>
                                    <button type="button" id="undoEditor" title="Undo">↶</button>
                                    <button type="button" id="redoEditor" title="Redo">↷</button>
                                </span>
                            </div>

                            <!-- EDITOR AREA -->
                            <div id="editor"></div>

                            <!-- HIDDEN INPUT UNTUK MENYIMPAN ISI EDITOR -->
                            <input type="hidden" name="content" id="content">

                            @error('content')
                                <p style="color: #fca5a5; margin-top: 8px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ACTIONS -->
                        <div class="form-actions" style="display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 12px; border-top: 1px solid #243954; padding-top: 20px; margin-top: 24px;">

                            <a href="{{ route('course-content.index') }}" style="background: #1e3a6d; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 0.9rem;">
                                Batal
                            </a>

                            <button type="submit" class="btn-primary" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem;">
                                Simpan Course Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- QUILL EDITOR SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Masukkan materi course content...',
            modules: {
                toolbar: {
                    container: '#toolbar-container',
                    handlers: {
                        image: imageHandler
                    }
                },
                history: {
                    delay: 1000,
                    maxStack: 100,
                    userOnly: true
                }
            }
        });

        // =====================================================
        // TAMBAHAN: RESIZE GAMBAR DENGAN TITIK BIRU
        // =====================================================

        const editorElement = quill.root;
        let selectedImage = null;
        let resizeHandle = null;
        let isResizingImage = false;

        function removeResizeHandle() {
            if (resizeHandle) {
                resizeHandle.remove();
                resizeHandle = null;
            }

            if (selectedImage) {
                selectedImage.classList.remove('selected-image');
                selectedImage = null;
            }
        }

        function positionResizeHandle() {
            if (!selectedImage || !resizeHandle || !selectedImage.isConnected) {
                return;
            }

            const rect = selectedImage.getBoundingClientRect();

            resizeHandle.style.left = (rect.right - 6) + 'px';
            resizeHandle.style.top = (rect.bottom - 6) + 'px';
        }

        function showResizeHandle(image) {
            removeResizeHandle();

            selectedImage = image;
            selectedImage.classList.add('selected-image');

            resizeHandle = document.createElement('div');
            resizeHandle.className = 'image-resize-handle';
            resizeHandle.setAttribute('title', 'Tarik untuk mengubah ukuran gambar');

            document.body.appendChild(resizeHandle);
            positionResizeHandle();
        }

        function startImageResize(startX, image) {
            if (!image) {
                return;
            }

            isResizingImage = true;

            const startWidth = image.getBoundingClientRect().width;

            function resizeTo(clientX) {
                if (!image.isConnected) {
                    return;
                }

                const editorRect = editorElement.getBoundingClientRect();
                const imageRect = image.getBoundingClientRect();

                const availableWidth = Math.max(
                    50,
                    Math.min(
                        editorRect.right - imageRect.left,
                        editorElement.clientWidth
                    )
                );

                const newWidth = Math.max(
                    50,
                    Math.min(startWidth + clientX - startX, availableWidth)
                );

                image.style.width = newWidth + 'px';
                image.style.height = 'auto';

                positionResizeHandle();
            }

            function mouseMove(event) {
                resizeTo(event.clientX);
            }

            function mouseUp() {
                document.removeEventListener('mousemove', mouseMove);
                document.removeEventListener('mouseup', mouseUp);

                isResizingImage = false;

                if (image.isConnected) {
                    quill.update('user');
                    positionResizeHandle();
                }
            }

            document.addEventListener('mousemove', mouseMove);
            document.addEventListener('mouseup', mouseUp);
        }

        function startImageTouchResize(startX, image) {
            if (!image) {
                return;
            }

            isResizingImage = true;

            const startWidth = image.getBoundingClientRect().width;

            function resizeTo(clientX) {
                if (!image.isConnected) {
                    return;
                }

                const editorRect = editorElement.getBoundingClientRect();
                const imageRect = image.getBoundingClientRect();

                const availableWidth = Math.max(
                    50,
                    Math.min(
                        editorRect.right - imageRect.left,
                        editorElement.clientWidth
                    )
                );

                const newWidth = Math.max(
                    50,
                    Math.min(startWidth + clientX - startX, availableWidth)
                );

                image.style.width = newWidth + 'px';
                image.style.height = 'auto';

                positionResizeHandle();
            }

            function touchMove(event) {
                if (!event.touches.length) {
                    return;
                }

                event.preventDefault();
                resizeTo(event.touches[0].clientX);
            }

            function touchEnd() {
                document.removeEventListener('touchmove', touchMove);
                document.removeEventListener('touchend', touchEnd);

                isResizingImage = false;

                if (image.isConnected) {
                    quill.update('user');
                    positionResizeHandle();
                }
            }

            document.addEventListener('touchmove', touchMove, {
                passive: false
            });

            document.addEventListener('touchend', touchEnd);
        }

        // Klik gambar untuk menampilkan titik biru.
        editorElement.addEventListener('click', function (event) {
            if (isResizingImage) {
                return;
            }

            if (event.target.tagName === 'IMG') {
                showResizeHandle(event.target);
            } else if (
                !resizeHandle ||
                !resizeHandle.contains(event.target)
            ) {
                removeResizeHandle();
            }
        });

        // Tarik titik biru menggunakan mouse.
        document.addEventListener('mousedown', function (event) {
            if (!resizeHandle || !resizeHandle.contains(event.target)) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            const image = selectedImage;

            if (image) {
                startImageResize(event.clientX, image);
            }
        });

        // Tarik titik biru menggunakan layar sentuh.
        document.addEventListener('touchstart', function (event) {
            if (!resizeHandle || !resizeHandle.contains(event.target)) {
                return;
            }

            event.preventDefault();

            const touch = event.touches[0];
            const image = selectedImage;

            if (touch && image) {
                startImageTouchResize(touch.clientX, image);
            }
        }, {
            passive: false
        });

        // Posisi titik mengikuti gambar saat editor digulir.
        editorElement.addEventListener('scroll', positionResizeHandle);
        window.addEventListener('resize', positionResizeHandle);

        // Perbarui posisi ketika halaman digulir.
        window.addEventListener('scroll', positionResizeHandle, true);

        // Sembunyikan titik jika pengguna mengeklik di luar editor.
        document.addEventListener('mousedown', function (event) {
            if (
                selectedImage &&
                !editorElement.contains(event.target) &&
                (!resizeHandle || !resizeHandle.contains(event.target))
            ) {
                removeResizeHandle();
            }
        });

        // =====================================================
        // FITUR ASLI: UPLOAD/PILIH BEBERAPA GAMBAR
        // =====================================================

        function imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.setAttribute('multiple', 'true');
            input.click();

            input.onchange = function () {
                const files = Array.from(input.files || []);

                if (files.length === 0) {
                    return;
                }

                files.forEach(function (file) {
                    if (!file.type.startsWith('image/')) {
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', e.target.result, 'user');
                        quill.setSelection(range.index + 1, 0, 'silent');
                    };

                    reader.readAsDataURL(file);
                });
            };
        }

        // RESTORE CONTENT IF VALIDATION FAILS
        const oldContent = {!! json_encode(old('content', '')) !!};

        if (oldContent) {
            quill.root.innerHTML = oldContent;
        }

        // UNDO
        document.getElementById('undoEditor').addEventListener('click', function () {
            quill.history.undo();
        });

        // REDO
        document.getElementById('redoEditor').addEventListener('click', function () {
            quill.history.redo();
        });

        // SAVE EDITOR CONTENT BEFORE SUBMIT
        document.getElementById('createContentForm').addEventListener('submit', function (e) {
            const content = quill.root.innerHTML.trim();
            const plainText = quill.getText().trim();
            const hasImage = quill.root.querySelector('img');

            if (!plainText && !hasImage) {
                e.preventDefault();
                alert('Content tidak boleh kosong.');
                quill.focus();
                return;
            }

            document.getElementById('content').value = content;
        });

        // USER MENU DROPDOWN
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('userMenuBtn');
            const dropdown = document.getElementById('userMenuDropdown');

            if (btn && dropdown) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('show');
                });

                dropdown.addEventListener('click', function (e) {
                    e.stopPropagation();
                });

                document.addEventListener('click', function () {
                    dropdown.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>
