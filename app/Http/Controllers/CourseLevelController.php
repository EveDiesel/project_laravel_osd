<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Course_Level;
use Illuminate\Http\Request;

class CourseLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Course_Level::with('course')->get();
        return view('course-level.index', compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        return view('course-level.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'order_index' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        Course_Level::create($request->all());
        return redirect()->route('course-level.index')->with('success', 'Level berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course_Level $course_level)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course_Level $course_level)
    {
        $courses = Course::all();
        return view('course-level.edit', compact('course_level', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course_Level $course_level)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'order_index' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $course_level->update($request->all());
        return redirect()->route('course-level.index')->with('success', 'Level berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course_level = Course_Level::findOrFail($id);
        $course_level->delete();
        return redirect()
            ->route('course-level.index')
            ->with('success', 'Level berhasil dihapus.');
    }
}