<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class TravelController extends Controller
{
    public function index()
    {
        // Mostra solo i viaggi dell'utente autenticato
        return Travel::where('user_id', Auth::id())->orderBy('start_date')->get();
    }

    public function store(StoreTravelRequest $request)
    {
        $this->authorize('create', Travel::class);

        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title'], '-');

        // Gestione dell'immagine di copertura se presente
        if ($request->hasFile('cover_image')) {
            try {
                $file = $request->file('cover_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->move(public_path('cover_images'), $filename);

                $validated['cover_image'] = 'cover_images/' . $filename;
            } catch (\Exception $e) {
                return response()->json(['message' => 'The cover image failed to upload.'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $travel = Travel::create($validated);

        return response()->json([
            'message' => 'Viaggio creato con successo',
            'data' => $travel
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        // Trova il viaggio per ID, inclusi i passi associati
        $travel = Travel::with('steps')->find($id);

        // Autorizza la visualizzazione
        if ($travel) {
            $this->authorize('view', $travel);

            return response()->json([
                'success' => true,
                'response' => $travel
            ]);
        } else {
            return response()->json([
                'success' => false,
                'response' => '404 Sorry, nothing found'
            ], 404);
        }
    }

    public function update(UpdateTravelRequest $request, Travel $travel)
    {
        $this->authorize('update', $travel);

        $travel->update($request->validated());

        return response()->json([
            'message' => 'Viaggio aggiornato con successo.',
            'data' => $travel
        ]);
    }

    public function destroy(Travel $travel)
    {
        $this->authorize('delete', $travel);

        $travel->delete();

        return response()->json([
            'message' => 'Viaggio eliminato con successo.'
        ], Response::HTTP_NO_CONTENT);
    }
}

