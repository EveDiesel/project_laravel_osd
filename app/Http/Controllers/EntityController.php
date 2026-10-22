<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entity;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entities = Entity::all();
        return view('entity.index', compact('entities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::doesntHave('entity')->get();

        return view('entity.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
        'is_company' => 'required|boolean',
        'name'       => 'required|string|max:255',
        'email'      => 'nullable|email',
        'phone'      => 'required|string|max:20',
        'address'    => 'required|string|max:255',
        'city'       => 'nullable|string|max:255',
        'avatar_url' => 'nullable|url',
    ];

    if (!$request->boolean('is_company')) {
            // Tambahkan unique:entities,user_id agar 1 User max 1 Entity
            $rules['user_id']   = 'required|exists:users,id|unique:entities,user_id';
            $rules['birthdate'] = 'required|date';
            $rules['gender']    = 'required|in:male,female,rather_not_say';
        } else {
            $rules['user_id']   = 'nullable';
            $rules['birthdate'] = 'nullable|date';
            $rules['gender']    = 'nullable|in:male,female,rather_not_say';
        }

        $validated = $request->validate($rules, [
            'user_id.unique' => 'User ini sudah terikat dengan Entity lain.',
        ]);

        if (empty($validated['avatar_url'])) {
            $validated['avatar_url'] = 'https://ui-avatars.com/api/?name=' . urlencode($validated['name']);
        }

        if ($request->boolean('is_company')) {
            $validated['user_id']   = null;
            $validated['birthdate'] = null;
            $validated['gender']    = null;
        }

        $validated['user_id'] = auth()->id();
        
        // Proses Simpan miaw hihi 
        Entity::create($validated);

        return redirect()->route('entity.index')->with('success', 'Entity successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Entity $entity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entity $entity)
    {
        $users = User::all(); 
        return view('entity.edit', compact('entity', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entity $entity)
    {
        $rules = [
            'is_company' => 'required|boolean',
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:255',
            'city'       => 'nullable|string|max:255',
            'avatar_url' => 'nullable|url',
        ];

        if (!$request->boolean('is_company')) {
            // Validasi unik dengan mengabaikan ID entity yang sedang diedit
            $rules['user_id']   = [
                'required',
                'exists:users,id',
                Rule::unique('entities', 'user_id')->ignore($entity->id),
            ];
            $rules['birthdate'] = 'required|date';
            $rules['gender']    = 'required|in:male,female,rather_not_say';
        } else {
            $rules['user_id']   = 'nullable';
            $rules['birthdate'] = 'nullable|date';
            $rules['gender']    = 'nullable|in:male,female,rather_not_say';
        }

        $validated = $request->validate($rules, [
            'user_id.unique' => 'User ini sudah terikat dengan Entity lain.',
        ]);

        // Pasang avatar otomatis jika dikosongkan
        if (empty($validated['avatar_url'])) {
            $validated['avatar_url'] = 'https://ui-avatars.com/api/?name=' . urlencode($validated['name']);
        }

        // Set null jika tipe diubah menjadi Company
        if ($request->boolean('is_company')) {
            $validated['user_id']   = null;
            $validated['birthdate'] = null;
            $validated['gender']    = null;
        }

        // Proses Update
        $entity->update($validated);

        return redirect()->route('entity.index')->with('success', 'Entity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entity $entity)
    {
        $entity->delete();

        return redirect()->route('entity.index')->with('success', 'Entity deleted successfully.');   
    }
}
