<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'document' => 'nullable|string|unique:users',
            'document_type' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'lastname' => $validated['lastname'],
            'document' => $validated['document'],
            'document_type' => $validated['document_type'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'document' => 'nullable|string|unique:users,document,' . $user->id,
            'document_type' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|max:2048',
        ]);
    
        // Manejar la foto de perfil
        if ($request->hasFile('profile_photo')) {
            // Usa el trait HasProfilePhoto para actualizar la foto de perfil
            $user->updateProfilePhoto($request->file('profile_photo'));
        }
    
        // Actualizar otros datos del usuario
        $user->update($validated);
    
        // Responder con el URL de la foto de perfil actualizado
        return response()->json([
            'message' => 'Usuario actualizado exitosamente.',
            'profile_photo_url' => $user->profile_photo_url,
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function showProfile(User $user)
    {
        if (auth()->id() !== $user->id) {
            abort(403, 'No tienes permiso para ver este perfil.');
        }
    
        return Inertia::render('Users/Profile', [
            'user' => $user,
        ]);
    }

    public function updateProfilePhoto(Request $request, User $user)
{
    // Validar que se está enviando una imagen
    $request->validate([
        'profile_photo' => 'required|image|max:2048',
    ]);

    // Manejar la foto de perfil
    if ($request->hasFile('profile_photo')) {
        // Eliminar la foto anterior si existe
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Actualizar la foto de perfil usando el trait HasProfilePhoto
        $user->updateProfilePhoto($request->file('profile_photo'));
    }

    return response()->json([
        'message' => 'Foto de perfil actualizada exitosamente.',
        'profile_photo_url' => $user->profile_photo_url,
    ]);
}
}
