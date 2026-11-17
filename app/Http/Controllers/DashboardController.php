<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tutorCourses = collect();
        $handledStudents = collect();

        if ($user->hasRole('Tutor')) {
            $tutorCourses = $user->courses()->get();
            
            if ($tutorCourses->isNotEmpty()) {
                $handledStudents = User::role('student')
                    ->whereHas('courseStudents', function($q) use ($tutorCourses) {
                        $q->whereIn('course_id', $tutorCourses->pluck('id'));
                    })
                    ->with('entity', 'courseStudents')
                    ->get();
            }
        }

        return view('dashboard', compact('tutorCourses', 'handledStudents'));
    }
}
