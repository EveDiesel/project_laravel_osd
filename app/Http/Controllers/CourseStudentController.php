<?php

namespace App\Http\Controllers;

use App\Models\Course_Student;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseStudents = Course_Student::with(['course', 'user'])->paginate(10);
        return view('course-student.index', compact('courseStudents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        $users = User::all();
        return view('course-student.create', compact('courses', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'is_complete' => 'nullable|boolean',
            'joined_at' => 'date',
            'completed_at' => 'nullable|date|after_or_equal:joined_at',
        ]);

        Course_Student::create($validatedData);

        return redirect()->route('course-student.index')->with('success', 'Course student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course_Student $courseStudent)
    {
        return view('course-student.show', compact('courseStudent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course_Student $courseStudent)
    {
        $courses = Course::all();
        $users = User::all();

        return view('course-student.edit', compact('courseStudent', 'courses', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course_Student $courseStudent)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'is_complete' => 'nullable|boolean',
            'joined_at' => 'date',
            'completed_at' => 'nullable|date|after_or_equal:joined_at',
        ]);

        $courseStudent->update($validatedData); 

        return redirect()->route('course-student.index')->with('success', 'Course student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course_Student $courseStudent) 
    {
        $courseStudent->delete();

        return redirect()->route('course-student.index')->with('success', 'Course student deleted successfully.');
    }
}