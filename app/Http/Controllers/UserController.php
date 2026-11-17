<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'role'       => ['required', 'string', 'exists:roles,name'],
            'is_active'  => ['required', 'boolean'],
            'phone'      => ['required', 'string', 'max:20'],
            'city'       => ['required', 'string', 'max:255'],
            'address'    => ['required', 'string'],
            'birthdate'  => ['nullable', 'date'],
            'gender'     => ['nullable', 'in:male,female,rather_not_say'],
            'avatar'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
        ]);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'is_active' => $request->is_active,
            ]);

            $user->assignRole($request->role);

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $avatarUrl = Storage::url($path);
            } elseif ($request->filled('avatar_url')) {
                $avatarUrl = $request->avatar_url;
            } else {
                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($request->name);
            }

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
                'avatar_url' => $avatarUrl,
            ]);
        });

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
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'  => ['nullable', Rules\Password::defaults()],
            'role'      => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['required', 'boolean'],
        ]);

        $data = [
            'email'     => $request->email,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'User successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User successfully deleted.');
    }
}