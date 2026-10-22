<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Course_Content;
use App\Models\Course_Level;
use Illuminate\Http\Request;

class CourseContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contents = Course_Content::with(['course', 'courseLevel'])->latest()->paginate(10);
        return view('course-content.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        $levels = Course_Level::all(); // Pastikan model CourseLevel terpanggil dengan benar

        return view('course-content.create', compact('courses', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'       => 'required|exists:courses,id',
            'course_level_id' => 'required|exists:course_levels,id',
            'session'         => 'required|integer',
            'content_type'    => 'required|string|max:255',
            'title'           => 'required|string|max:255',
            'content'         => 'required|string', // Bisa berupa URL, teks, atau path file
        ]);

        Course_Content::create($validated);

        return redirect()->route('course-content.index')
                 ->with('success', 'Konten kursus berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contents = Course_Content::with(['course', 'courseLevel'])->findOrFail($id);

        return view('course-content.show', compact('contents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course_Content $course_content) 
    {
        $courses = Course::all();
        $levels = Course_Level::all();
        return view('course-content.edit', compact('course_content', 'courses', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course_Content $course_content)
    {
        $validated = $request->validate([
            'course_id'       => 'required|exists:courses,id',
            'course_level_id' => 'required|exists:course_levels,id',
            'session'         => 'required|integer',
            'content_type'    => 'required|string|max:255',
            'title'           => 'required|string|max:255',
            'content'         => 'required|string',
        ]);

        $course_content->update($validated);

        return redirect()->route('course-content.index')
                         ->with('success', 'Konten kursus berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course_Content $course_content)
    {
        $course_content->delete();

        return redirect()->route('course-content.index')
                         ->with('success', 'Konten kursus berhasil dihapus.');
    }
}