<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TutorController extends Controller
{
    /**
     * Menampilkan daftar tutor dengan fitur pencarian, filter status, dan statistik.
     */
    public function index(Request $request)
    {
        $query = User::role('tutor')->with('entity')->withCount('courses'); 
        
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('email', 'like', "%{$keyword}%")
                ->orWhereHas('entity', function($subQ) use ($keyword) {
                    $subQ->where('name', 'like', "%{$keyword}%");
                });
            });
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }

        $tutors = $query->paginate(10)->withQueryString();

        $stats = [
            'total_tutors'  => User::role('tutor')->count(),
            'active_tutors' => User::role('tutor')->where('is_active', 1)->count(),
            'total_courses' => \App\Models\Course::whereHas('creator.user', function($q) {
                $q->role('tutor');
            })->count(),
        ];

        return view('tutors.index', compact('tutors', 'stats'));
    }

    /**
     * Menampilkan form tambah tutor baru.
     */
    public function create()
    {
        return view('tutors.create');
    }

    /**
     * Menyimpan data tutor baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'phone'     => 'required|string|max:20',
            'city'      => 'required|string|max:100',
            'address'   => 'required|string',
            'birthdate' => 'nullable|date',
            'gender'    => 'nullable|string',
            'is_active' => 'required|boolean',
            'courses'   => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);

        // 1. Buat data user utama
        $user = User::create([
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => $request->is_active,
        ]);

        // Berikan role tutor
        $user->assignRole('tutor');

        // 2. Simpan data detail ke relasi entity
        $entity = $user->entity()->create([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'city'      => $request->city,
            'address'   => $request->address,
            'birthdate' => $request->birthdate,
            'gender'    => $request->gender,
        ]);

        // 3. Ikat course menggunakan created_by (mengarah ke id entity)
        if ($request->filled('courses')) {
            \App\Models\Course::whereIn('id', $request->courses)->update([
                'created_by' => $entity->id
            ]);
        }

        return redirect()->route('tutors.index')->with('success', 'Tutor baru berhasil ditambahkan dan course berhasil diikat!');
    }

    /**
     * Menampilkan detail tutor.
     */
    public function show($id)
    {
        $tutor = User::with('entity', 'courses')->findOrFail($id);
        return view('tutors.show', compact('tutor'));
    }

    /**
     * Menampilkan form edit tutor.
     */
    public function edit($id)
    {
        $tutor = User::with('entity')->findOrFail($id);
        return view('tutors.edit', compact('tutor'));
    }

    /**
     * Mengupdate data tutor di database.
     */
    public function update(Request $request, $id)
    {
        $tutor = User::with('entity', 'courses')->findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $tutor->id,
            'password'  => 'nullable|min:6',
            'phone'     => 'required|string|max:20',
            'city'      => 'required|string|max:100',
            'address'   => 'required|string',
            'birthdate' => 'nullable|date',
            'gender'    => 'nullable|string',
            'is_active' => 'required|boolean',
            'courses'   => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);

        // 1. Update akun user utama
        $tutor->email     = $request->email;
        $tutor->is_active = $request->is_active;

        if ($request->filled('password')) {
            $tutor->password = Hash::make($request->password);
        }

        $tutor->save();

        // 2. Update atau buat entity terkait
        $entity = $tutor->entity()->updateOrCreate(
            ['user_id' => $tutor->id],
            [
                'name'      => $request->name,
                'phone'     => $request->phone,
                'city'      => $request->city,
                'address'   => $request->address,
                'birthdate' => $request->birthdate,
                'gender'    => $request->gender,
            ]
        );

        // 3. Sinkronisasi Course menggunakan created_by
        if ($entity) {
            // Kosongkan dulu created_by pada course yang sebelumnya diampu entity ini
            \App\Models\Course::where('created_by', $entity->id)->update(['created_by' => null]);

            // Jika ada course yang dicentang, ikat kembali ke entity tutor ini
            if ($request->filled('courses')) {
                \App\Models\Course::whereIn('id', $request->courses)->update([
                    'created_by' => $entity->id
                ]);
            }
        }

        return redirect()->route('tutors.index')->with('success', 'Data tutor dan course berhasil diperbarui!');
    }

    /**
     * Menghapus data tutor.
     */
    public function destroy($id)
    {
        $tutor = User::findOrFail($id);

        // Hapus entity jika ada relasinya
        if ($tutor->entity) {
            $tutor->entity()->delete();
        }

        $tutor->delete();

        return redirect()->route('tutors.index')->with('success', 'Tutor berhasil dihapus!');
    }
}