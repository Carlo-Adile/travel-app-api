<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], Response::HTTP_OK);
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // Validazione dei dati di input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        // Gestione dell'immagine di copertura
        if ($request->hasFile('cover_image')) {
            try {
                $path = $request->file('cover_image')->store('cover_images', 'public');
                $validatedData['cover_image'] = $path;
            } catch (\Exception $e) {
                \Log::error('Cover image upload failed: ' . $e->getMessage());
                return response()->json(['message' => 'The cover image failed to upload.'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        // Hash della password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Creazione dell'utente
        $user = User::create($validatedData);

        // Creazione del token
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Risposta
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out.',
        ], Response::HTTP_OK);
    }
}

