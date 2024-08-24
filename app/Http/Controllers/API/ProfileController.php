<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show()
    {
        $user = Auth::user(); // Ottieni l'utente autenticato

        return response()->json([
            'data' => $user
        ]);
    }
    /**
     * Registrazione dell'utente.
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'cover_image' => 'nullable|image|max:2048' // Assicurati che la validazione sia corretta
        ]);

        // Gestione dell'immagine di copertura se presente
        if ($request->hasFile('cover_image')) {
            try {
                $path = $request->file('cover_image')->store('cover_images');
                $validatedData['cover_image'] = $path;
            } catch (\Exception $e) {
                return response()->json(['message' => 'The cover image failed to upload.'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], Response::HTTP_CREATED);
    }
    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user(); // Ottieni l'utente autenticato

        // Validazione dei dati
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'cover_image' => 'nullable|image|max:2048' // Validazione per l'immagine
        ]);

        // Gestione del file della cover image
        if ($request->hasFile('cover_image')) {
            // Elimina la vecchia cover image se esiste
            if ($user->cover_image) {
                Storage::delete($user->cover_image);
            }

            // Salva la nuova cover image
            $path = $request->file('cover_image')->store('cover_images');
            $validated['cover_image'] = $path;
        }

        // Aggiornamento dei dati dell'utente
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']); // Cripta la password
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profilo aggiornato con successo.',
            'data' => $user
        ]);
    }
}
