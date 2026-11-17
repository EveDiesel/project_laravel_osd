<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
Use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Student')) {
            $enrolledCourseIds = $user->courseStudents()->pluck('course_id');

            $schedules = Schedule::whereHas('activity', function ($query) use ($enrolledCourseIds) {
                    $query->whereIn('course_id', $enrolledCourseIds);
                })
                ->with('activity.course')
                ->latest()
                ->paginate(10);

        } elseif ($user->hasRole('Tutor')) {
            // Ambil ID course milik tutor lewat relasi courses() di model User
            $tutorCourseIds = $user->courses()->pluck('courses.id');

            $schedules = Schedule::whereHas('activity', function ($query) use ($tutorCourseIds) {
                    $query->whereIn('course_id', $tutorCourseIds);
                })
                ->with('activity.course')
                ->latest()
                ->paginate(10);

        } else {
            $schedules = Schedule::with('activity.course')
                ->latest()
                ->paginate(10);
        }

        return view('schedule.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activities = Activity::all();
        return view('schedule.create', compact('activities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'status' => 'required|in:active,inactive',
        ]);

        Schedule::create($request->all());

        return redirect()->route('schedule.index')->with('success', 'Schedule created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $activities = Activity::all();
        return view('schedule.edit', compact('schedule', 'activities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'status' => 'required|in:active,inactive',
        ]);

        $schedule->update($request->all());

        return redirect()->route('schedule.index')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedule.index')->with('success', 'Schedule deleted successfully.');
    }
}
