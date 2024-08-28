<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStepRequest;
use App\Http\Requests\UpdateStepRequest;
use App\Models\Step;
use App\Models\Travel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Travel  $travel
     * @return JsonResponse
     */
    public function index(Travel $travel): JsonResponse
    {
        // Verifica che il viaggio appartenga all'utente autenticato
        $this->authorize('view', $travel);

        // Recupera tutte le tappe del viaggio e ordina per day e poi per time
        $steps = $travel->steps()
            ->orderBy('day', 'asc')
            ->orderBy('time')
            ->get();

        return response()->json([
            'data' => $steps
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreStepRequest  $request
     * @param  Travel  $travel
     * @return JsonResponse
     */
    public function store(StoreStepRequest $request, Travel $travel): JsonResponse
    {
        // Verifica che il viaggio appartenga all'utente autenticato
        $this->authorize('create', [Step::class, $travel]);

        // Genera lo slug
        $slug = Str::slug($request->input('title'), '-');

        // Crea una nuova tappa
        $step = $travel->steps()->create(array_merge($request->validated(), ['slug' => $slug]));

        return response()->json([
            'message' => 'Tappa creata con successo.',
            'data' => $step
        ], Response::HTTP_CREATED);
    }
    /**
     * Display the specified resource.
     *
     * @param  Travel  $travel
     * @param  Step  $step
     * @return JsonResponse
     */
    public function show(Travel $travel, Step $step): JsonResponse
    {
        // Verifica che il viaggio e la tappa appartengano all'utente autenticato
        $this->authorize('view', [$step, $travel]);

        return response()->json([
            'data' => $step
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateStepRequest  $request
     * @param  Travel  $travel
     * @param  Step  $step
     * @return JsonResponse
     */
    public function update(UpdateStepRequest $request, Travel $travel, Step $step): JsonResponse
    {
        // Verifica che il viaggio e la tappa appartengano all'utente autenticato
        $this->authorize('update', [$step, $travel]);

        // Prepara i dati da aggiornare
        $data = $request->only(['title', 'day', 'time', 'description', 'tag', 'lat', 'lng']);

        // Aggiorna solo i campi presenti nei dati validati
        $step->update($data);

        return response()->json([
            'message' => 'Tappa aggiornata con successo.',
            'data' => $step
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  Travel  $travel
     * @param  Step  $step
     * @return JsonResponse
     */
    public function destroy(Travel $travel, Step $step): JsonResponse
    {
        // Verifica che il viaggio e la tappa appartengano all'utente autenticato
        $this->authorize('delete', [$step, $travel]);

        // Elimina la tappa
        $step->delete();

        return response()->json([
            'message' => 'Tappa eliminata con successo.'
        ], Response::HTTP_NO_CONTENT);
    }
}
