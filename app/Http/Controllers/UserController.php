<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entity; // [TAMBAHAN] Memanggil Model Entity agar controller bisa mengakses tabel entities
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // [TAMBAHAN] Memanggil DB facade untuk menggunakan fitur Database Transaction
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * Fungsi: Menampilkan daftar seluruh user beserta relasi rolenya ke halaman index.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * Fungsi: Menampilkan form halaman tambah user baru dengan membawa data pilihan role.
     */
    public function create()
    {
        $roles = Role::all(); 

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     * Fungsi: Menyimpan data user baru ke database, memberikan role, dan otomatis membuat entitas terkait.
     */
    public function store(Request $request)
    {
        // 1. Validasi input yang dikirimkan dari form Create User
        $request->validate([
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'  => ['required', Rules\Password::defaults()],
            'role'      => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['required', 'boolean'],
        ]);

        // 2. Menggunakan DB::transaction
        // Fungsi: Memastikan proses simpan User dan Entity berjalan bersamaan. 
        // Jika salah satu gagal, maka data yang sudah masuk akan dibatalkan (rollback) agar tidak terjadi data separuh jadi.
        DB::transaction(function () use ($request) {
            
            // A. Membuat data user baru ke dalam tabel 'users'
            $user = User::create([
                'email'     => $request->email,
                'password'  => Hash::make($request->password), // Enkripsi password sebelum disimpan
                'is_active' => $request->is_active,
            ]);

            // B. Memberikan role terpilih ke user menggunakan package Spatie Permission
            $user->assignRole($request->role);

            // C. Membuat nama sementara untuk entity dengan mengambil teks sebelum karakter '@' dari email
            $defaultName = explode('@', $request->email)[0];
            
            // D. Otomatis membuat entitas terkait di tabel 'entities'
            Entity::create([
                'user_id'    => $user->id,                    // Menghubungkan id user yang baru dibuat dengan tabel entities
                'is_company' => 0,                            // Diset 0 karena ini adalah akun personal/perorangan
                'name'       => ucfirst($defaultName),        // Mengisi nama awal otomatis dari email
                'email'      => $request->email,              // Menyamakan email dengan akun user
                'phone'      => '-',                          // Placeholder sementara agar lolos validasi 'required' di EntityController
                'address'    => '-',   
                'city'       => '-',                           // Placeholder sementara agar lolos validasi 'required' di EntityController
                'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($defaultName), // Membuat avatar otomatis berdasarkan nama
            ]);
        });

        // 3. Mengembalikan pengguna ke halaman index user dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User and entity successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Fungsi: Menampilkan form edit user berdasarkan data user yang dipilih.
     */
    public function edit(User $user)
    {
        $roles = Role::all(); 

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     * Fungsi: Memperbarui data user yang sudah ada di database.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi input saat update data user
        $request->validate([
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password'  => ['nullable', Rules\Password::defaults()],
            'role'      => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['required', 'boolean'],
        ]);

        // 2. Menyiapkan array data dasar yang pasti akan di-update
        $data = [
            'email'     => $request->email,
            'is_active' => $request->is_active,
        ];

        // 3. Mengecek apakah kolom password diisi atau tidak. Jika diisi, update password baru.
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 4. Melakukan proses update data user ke database
        $user->update($data);

        // 5. Menyinkronkan ulang role user
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'User successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     * Fungsi: Menghapus data user dari database.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User successfully deleted.');
    }
}