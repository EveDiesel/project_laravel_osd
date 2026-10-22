<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Menampilkan semua course.
     */
    public function index()
    {
        $courses = Course::with('creator')->latest()->get();

        return view('course.index', compact('courses'));
    }

    /**
     * Menampilkan form tambah course.
     */
    public function create()
    {
        $entities = Entity::all();

        return view('course.create', compact('entities'));
    }

    /**
     * Menyimpan course baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail_url' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'course_type' => 'required|in:regular,private,bootcamp',
            'is_displayed' => 'nullable|boolean',
            'created_by' => 'required|exists:entities,id',
        ]);

        $thumbnailPath = $request->file('thumbnail_url')
            ->store('courses', 'public');

        Course::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'thumbnail_url' => $thumbnailPath,
            'is_active' => $request->boolean('is_active'),
            'course_type' => $request->course_type,
            'is_displayed' => $request->boolean('is_displayed'),
            'created_by' => $request->created_by,
        ]);

        return redirect()
            ->route('course.index')
            ->with('success', 'Course berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail course.
     */
    public function show(Course $course)
    {
        return view('course.show', compact('course'));
    }

    /**
     * Menampilkan form edit course.
     */
    public function edit(Course $course)
{
    $entities = Entity::all();
    $users = \App\Models\User::all();

    return view('course.edit', compact('course', 'entities', 'users'));
}

    /**
     * Mengupdate course.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'course_type' => 'required|in:regular,private,bootcamp',
            'is_displayed' => 'nullable|boolean',
            'created_by' => 'required|exists:entities,id',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
            'course_type' => $request->course_type,
            'is_displayed' => $request->boolean('is_displayed'),
            'created_by' => $request->created_by,
        ];

        // Jika user memilih gambar baru, upload gambar baru.
        if ($request->hasFile('thumbnail_url')) {
            $data['thumbnail_url'] = $request->file('thumbnail_url')
                ->store('courses', 'public');
        }

        $course->update($data);

        return redirect()
            ->route('course.index')
            ->with('success', 'Course berhasil diperbarui.');
    }

    /**
     * Menghapus course.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()
            ->route('course.index')
            ->with('success', 'Course berhasil dihapus.');
    }
}