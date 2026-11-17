<?php

namespace App\Http\Controllers;

use App\Models\Course_Student;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * Daftar siswa = semua user ber-role Student, digabung dengan profil entity.
     */
    public function index(Request $request)
    {
        // 1. Statistik ringkasan untuk kartu di atas tabel
        $studentIds = User::role('Student')->pluck('id');

        $enrollments = Course_Student::whereIn('user_id', $studentIds);

        $stats = [
            'total_students'     => $studentIds->count(),
            'active_students'    => User::role('Student')->where('is_active', true)->count(),
            'completed_courses'  => (clone $enrollments)->where('is_complete', true)->count(),
            'avg_progress'       => round((clone $enrollments)->avg('percentage') ?? 0, 1),
        ];

        // 2. Query daftar siswa dengan pencarian & filter status
        $students = User::role('Student')
            ->with('entity')
            ->withCount('courseStudents')
            ->withAvg('courseStudents', 'percentage')
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->where('email', 'like', "%{$q}%")
                        ->orWhereHas('entity', fn ($e) => $e->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->status === 'active'))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', compact('students', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     * Membuat User ber-role Student + Entity sekaligus dalam satu transaksi.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'  => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'phone'     => ['required', 'string', 'max:20'],
            'city'      => ['required', 'string', 'max:255'],
            'address'   => ['required', 'string'],
            'birthdate' => ['nullable', 'date'],
            'gender'    => ['nullable', 'in:male,female,rather_not_say'],
            'is_active' => ['required', 'boolean'],
        ]);

        // 2. Simpan User + Role + Entity secara atomik
        DB::transaction(function () use ($request) {
            $user = User::create([
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'is_active' => $request->is_active,
            ]);

            $user->assignRole('Student');

            Entity::create([
                'user_id'    => $user->id,
                'is_company' => 0,
                'name'       => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'address'    => $request->address,
                'city'       => $request->city,
                'birthdate'  => $request->birthdate,
                'gender'     => $request->gender,
                'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($request->name),
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Student successfully created.');
    }

    /**
     * Display the specified resource.
     * Profil siswa + daftar course yang diikuti beserta progressnya.
     */
    public function show(User $student)
    {
        $student->load(['entity', 'courseStudents.course']);

        return view('students.show', ['student' => $student]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $student)
    {
        $student->load('entity');

        return view('students.edit', ['student' => $student]);
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui profil entity + status akun (+ password opsional).
     */
    public function update(Request $request, User $student)
    {
        // 1. Validasi input
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $student->id],
            'password'  => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'phone'     => ['required', 'string', 'max:20'],
            'city'      => ['required', 'string', 'max:255'],
            'address'   => ['required', 'string'],
            'birthdate' => ['nullable', 'date'],
            'gender'    => ['nullable', 'in:male,female,rather_not_say'],
            'is_active' => ['required', 'boolean'],
        ]);

        // 2. Update User + Entity secara atomik
        DB::transaction(function () use ($request, $student) {
            $userData = [
                'email'     => $request->email,
                'is_active' => $request->is_active,
            ];

            // Password hanya di-update jika diisi
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $student->update($userData);

            Entity::where('user_id', $student->id)->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'city'      => $request->city,
                'birthdate' => $request->birthdate,
                'gender'    => $request->gender,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Student successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus user: course_students ikut terhapus (cascade), entity menjadi yatim (nullOnDelete).
     */
    public function destroy(User $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student successfully deleted.');
    }
}
