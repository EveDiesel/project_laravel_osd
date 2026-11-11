<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Course Content - OSEDU Bright Future</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

    @vite(['resources/css/style.css', 'resources/css/crud.css'])

    <style>
        #toolbar-container {
            background: var();
            border: 1px solid var(--border-color);
            border-radius: 8px 8px 0 0;
        }

        #toolbar-container .ql-stroke {
            stroke: currentColor;
        }

        #toolbar-container .ql-fill {
            fill: currentColor;
        }

        #toolbar-container .ql-picker {
            color: inherit;
        }

        #toolbar-container .ql-picker-options {
            background: var();
            border-color: var(--border-color);
        }

        #toolbar-container .ql-picker-item {
            color: inherit;
        }

        #editor {
            min-height: 220px;
            background: var();
            color: inherit;
            border: 1px solid var(--border-color);
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
            text-decoration: underline;
            cursor: pointer;
        }

        #editor .ql-editor ol,
        #editor .ql-editor ul {
            padding-left: 1.5em;
        }

        #editor .ql-editor .ql-align-justify {
            text-align: justify !important;
        }

        #editor .ql-editor img {
            max-width: 100%;
            height: auto;
            cursor: pointer;
            display: block;
            margin: 10px 0;
        }

        #editor .ql-editor img.ql-align-center {
            margin-left: auto;
            margin-right: auto;
        }

        #editor .ql-editor img.ql-align-right {
            margin-left: auto;
            margin-right: 0;
        }

        #editor .ql-editor img.ql-align-left {
            margin-left: 0;
            margin-right: auto;
        }

        #editor .ql-editor img.image-selected {
            outline: 2px solid #60a5fa;
        }

        #imageResizeHandle {
            position: fixed;
            width: 14px;
            height: 14px;
            background: #2563eb;
            border: 2px solid white;
            border-radius: 3px;
            box-shadow: 0 0 0 1px #2563eb;
            cursor: nwse-resize;
            z-index: 999999;
            display: none;
            touch-action: none;
            box-sizing: border-box;
        }

        /* Styling Tombol Simbol Align Gambar di Toolbar */
        .ql-image-align {
            display: inline-flex;
            gap: 2px;
            margin-left: 4px;
            vertical-align: middle;
        }
        .ql-image-align button {
            background: transparent;
            border: 1px solid var(--border-color);
            color: inherit;
            width: 28px;
            height: 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .ql-image-align button:hover {
            background: #1e3a6d;
            color: white;
        }

        @media (max-width: 768px) {
            .edit-form-grid {
                grid-template-columns: 1fr !important;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="dashboard-body">

    <!-- TOP NAVBAR -->
    <header class="navbar-top">
        <a href="{{ route('dashboard') }}" class="navbar-brand" style="display: flex; align-items: center;">
            <img src="{{ Vite::asset('resources/image/logoosedu.png') }}" alt="OSEDU Logo" style="height: 120px; width: auto;">
        </a>

        <div class="user-dropdown-wrapper">
            <button class="dropdown-trigger" id="userMenuBtn" type="button">
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

    <!-- WRAPPER -->
    <div class="app-layout" style="display: flex; width: 100%; min-height: calc(100vh - 72px);">

        <!-- SIDEBAR -->
        @include('sidebar')

        <!-- MAIN CONTENT -->
        <main class="main-content" style="flex: 1; padding: 24px;">
            <div class="content-body" style="width: 100%;">

                @if ($errors->any())
                    <div class="alert alert-danger" style="background: #3b1720; border: 1px solid #7f1d1d; color: #fecaca; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div style="margin-bottom: 24px;">
                    <a href="{{ route('course-content.show', $course_content->id) }}" style="color: #60a5fa; text-decoration: none;">
                        ← Kembali ke Detail
                    </a>

                    <h1 style="font-size: 28px; font-weight: 700; margin-top: 20px;">
                        Edit Course Content
                    </h1>

                    <p style="color: #94a3b8; margin-top: 8px;">
                        Ubah informasi konten kursus di bawah ini.
                    </p>
                </div>

                <div class="dashboard-card" style="padding: 28px; width: 100%; max-width: 100%; box-sizing: border-box; border-radius: 14px; border: 1px solid var(--border-color);">

                    <form id="editContentForm" action="{{ route('course-content.update', $course_content->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="edit-form-grid" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px;">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label for="course_id" class="form-label" style="display: block; margin-bottom: 8px;">Course</label>
                                <select name="course_id" id="course_id" class="form-control filter-select" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color);">
                                    <option value="" disabled>Pilih Course...</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', $course_content->course_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->title ?? $course->name ?? 'Course #' . $course->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 16px;">
                                <label for="course_level_id" class="form-label" style="display: block; margin-bottom: 8px;">Course Level</label>
                                <select name="course_level_id" id="course_level_id" class="form-control filter-select" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color);">
                                    <option value="" disabled>Pilih Course Level...</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ old('course_level_id', $course_content->course_level_id) == $level->id ? 'selected' : '' }}>
                                            {{ $level->name ?? $level->title ?? 'Level #' . $level->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="title" class="form-label" style="display: block; margin-bottom: 8px;">Judul Content</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $course_content->title) }}" placeholder="Masukkan judul content..." required style="width: 100%; padding: 12px; box-sizing: border-box; border-radius: 8px; border: 1px solid var(--border-color);">
                        </div>

                        <div class="edit-form-grid" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px;">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="content_type" class="form-label" style="display: block; margin-bottom: 8px;">Content Type</label>
                                <select name="content_type" id="content_type" class="form-control filter-select" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color);">
                                    <option value="" disabled>Pilih Content Type...</option>
                                    <option value="video" {{ old('content_type', $course_content->content_type) == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="pdf" {{ old('content_type', $course_content->content_type) == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="document" {{ old('content_type', $course_content->content_type) == 'document' ? 'selected' : '' }}>Document</option>
                                    <option value="url" {{ old('content_type', $course_content->content_type) == 'url' ? 'selected' : '' }}>URL</option>
                                    <option value="etc" {{ old('content_type', $course_content->content_type) == 'etc' ? 'selected' : '' }}>Etc</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="session" class="form-label" style="display: block; margin-bottom: 8px;">Session</label>
                                <input type="number" name="session" id="session" class="form-control" value="{{ old('session', $course_content->session) }}" placeholder="Masukkan nomor session..." min="1" required style="width: 100%; padding: 12px; box-sizing: border-box; border-radius: 8px; border: 1px solid var(--border-color);">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="editor" class="form-label" style="display: block; margin-bottom: 8px;">Content</label>

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

                                <!-- Tombol Simbol Perataan Gambar -->
                                <span class="ql-formats">
                                    <span class="ql-image-align" title="Atur Posisi Gambar">
                                        <button type="button" id="imgAlignLeft" title="Rata Kiri">⇠</button>
                                        <button type="button" id="imgAlignCenter" title="Rata Tengah">≡</button>
                                        <button type="button" id="imgAlignRight" title="Rata Kanan">⇢</button>
                                    </span>
                                </span>

                                <span class="ql-formats">
                                    <button type="button" class="ql-clean"></button>
                                    <button type="button" id="undoEditor" title="Undo">↶</button>
                                    <button type="button" id="redoEditor" title="Redo">↷</button>
                                </span>
                            </div>

                            <div id="editor"></div>
                            <input type="hidden" name="content" id="content">

                            @error('content')
                                <p style="color: #fca5a5; margin-top: 8px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions" style="display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 24px;">
                            <a href="{{ route('course-content.show', $course_content->id) }}" class="btn-action" style="background: #334155; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 0.9rem;">
                                Batal
                            </a>
                            <button type="submit" class="btn-primary" style="padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem;">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        function imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.setAttribute('multiple', 'true');
            input.click();

            input.onchange = () => {
                const files = input.files;
                if (files) {
                    Array.from(files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const range = quill.getSelection(true);
                            const index = range ? range.index : quill.getLength();
                            quill.insertEmbed(index, 'image', e.target.result, 'user');
                            quill.setSelection(index + 1, 0, 'user');
                        };
                        reader.readAsDataURL(file);
                    });
                }
            };
        }

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

        const existingContent = {!! json_encode(old('content', $course_content->content ?? '')) !!};
        if (existingContent) {
            quill.root.innerHTML = existingContent;
        }

        quill.root.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                const url = e.target.getAttribute('href');
                if (url) {
                    window.open(url, '_blank');
                }
            }
        });

        document.getElementById('undoEditor').addEventListener('click', function () {
            quill.history.undo();
        });

        document.getElementById('redoEditor').addEventListener('click', function () {
            quill.history.redo();
        });

        document.getElementById('editContentForm').addEventListener('submit', function () {
            document.getElementById('content').value = quill.root.innerHTML;
        });

        document.addEventListener('DOMContentLoaded', function () {
            const editor = document.querySelector('#editor .ql-editor');
            if (!editor) return;

            const handle = document.createElement('div');
            handle.id = 'imageResizeHandle';
            document.body.appendChild(handle);

            let selectedImage = null;
            let startX = 0;
            let startWidth = 0;
            let isResizing = false;

            function positionHandle() {
                if (!selectedImage || !selectedImage.isConnected) {
                    handle.style.display = 'none';
                    return;
                }
                const rect = selectedImage.getBoundingClientRect();
                handle.style.left = (rect.right - 7) + 'px';
                handle.style.top = (rect.bottom - 7) + 'px';
                handle.style.display = 'block';
            }

            function selectImage(img) {
                if (selectedImage) {
                    selectedImage.classList.remove('image-selected');
                }
                selectedImage = img;
                selectedImage.classList.add('image-selected');
                positionHandle();
            }

            editor.addEventListener('click', function (event) {
                if (isResizing) return;
                if (event.target.tagName === 'IMG') {
                    selectImage(event.target);
                } else {
                    if (selectedImage) {
                        selectedImage.classList.remove('image-selected');
                    }
                    selectedImage = null;
                    handle.style.display = 'none';
                }
            });

            document.getElementById('imgAlignLeft').addEventListener('click', function() {
                if (!selectedImage) return;
                selectedImage.classList.remove('ql-align-center', 'ql-align-right');
                selectedImage.classList.add('ql-align-left');
                positionHandle();
            });

            document.getElementById('imgAlignCenter').addEventListener('click', function() {
                if (!selectedImage) return;
                selectedImage.classList.remove('ql-align-left', 'ql-align-right');
                selectedImage.classList.add('ql-align-center');
                positionHandle();
            });

            document.getElementById('imgAlignRight').addEventListener('click', function() {
                if (!selectedImage) return;
                selectedImage.classList.remove('ql-align-left', 'ql-align-center');
                selectedImage.classList.add('ql-align-right');
                positionHandle();
            });

            handle.addEventListener('pointerdown', function (event) {
                if (!selectedImage) return;
                event.preventDefault();
                event.stopPropagation();
                isResizing = true;
                startX = event.clientX;
                startWidth = selectedImage.getBoundingClientRect().width;
                handle.setPointerCapture(event.pointerId);
            });

            handle.addEventListener('pointermove', function (event) {
                if (!isResizing || !selectedImage) return;
                const editorWidth = editor.clientWidth;
                const deltaX = event.clientX - startX;
                const newWidth = Math.max(40, Math.min(startWidth + deltaX, editorWidth));
                selectedImage.style.width = newWidth + 'px';
                selectedImage.style.height = 'auto';
                selectedImage.style.maxWidth = '100%';
                positionHandle();
            });

            function stopResizing() {
                if (!isResizing) return;
                isResizing = false;
                if (selectedImage) {
                    selectedImage.setAttribute('width', Math.round(selectedImage.getBoundingClientRect().width));
                }
                positionHandle();
                quill.update('user');
            }

            handle.addEventListener('pointerup', stopResizing);
            handle.addEventListener('pointercancel', stopResizing);

            editor.addEventListener('scroll', positionHandle);
            window.addEventListener('scroll', positionHandle, true);
            window.addEventListener('resize', positionHandle);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('userMenuBtn');
            const dropdown = document.getElementById('userMenuDropdown');

            if (btn && dropdown) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('show');
                });

                document.addEventListener('click', function () {
                    dropdown.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>