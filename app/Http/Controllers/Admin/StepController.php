<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Models\Step;
use App\Http\Requests\StoreStepRequest;
use App\Http\Requests\UpdateStepRequest;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Travel;

class StepController extends Controller
{
    public function index()
    {
        return Step::whereHas('travel', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Travel $travel)
    {
        // Verifica che il viaggio appartenga all'utente
        $this->authorize('create', [Step::class, $travel]);

        return view('admin.steps.create', compact('travel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStepRequest $request, Travel $travel)
    {
        // Verifica che il viaggio appartenga all'utente
        $this->authorize('create', [Step::class, $travel]);

        // Valida i dati
        $validated = $request->validated();

        // Genera lo slug dal titolo
        $slug = Str::slug($validated['title'], '-');
        $validated['slug'] = $slug;

        // Gestisci l'upload delle immagini
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('uploads', 'public');
            }
        }
        $validated['images'] = json_encode($images);

        // Assegna il travel_id
        $validated['travel_id'] = $travel->id;

        // Crea lo step
        Step::create($validated);

        return redirect()->route('admin.travels.show', $travel->id)->with('success', 'Tappa creata con successo!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Travel $travel, Step $step)
    {
        // Verifica che la tappa appartenga all'utente
        $this->authorize('update', $step);

        return view('admin.steps.edit', compact('step'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStepRequest $request, Travel $travel, Step $step)
    {
        // Verifica che la tappa appartenga all'utente
        $this->authorize('update', $step);

        // Valida i dati
        $validated = $request->validated();

        // Aggiorna lo slug se necessario
        if ($validated['title'] !== $step->title) {
            $slug = Str::slug($validated['title'], '-');
            $validated['slug'] = $slug;
        }

        // Aggiungi nuove immagini e mantieni le immagini esistenti
        $images = json_decode($step->images) ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('uploads', 'public');
            }
        }
        $validated['images'] = json_encode($images);

        // Aggiorna lo stato 'checked'
        $validated['checked'] = $request->has('checked');

        // Aggiorna la risorsa
        $step->update($validated);

        return redirect()->route('admin.travels.show', $step->travel_id)->with('success', 'Tappa aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Travel $travel, Step $step)
    {
        // Verifica che la tappa appartenga all'utente
        $this->authorize('delete', $step);

        if ($step->images) {
            $images = json_decode($step->images);
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $step->delete();

        return redirect()->route('admin.travels.show', $travel->id)->with('success', 'Tappa eliminata con successo!');
    }
}
