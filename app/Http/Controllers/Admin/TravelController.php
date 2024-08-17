<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Models\Travel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $travels = Travel::where('user_id', auth()->id())->get();

        return view('admin.travels.index', ['travels' => Travel::orderByDesc('id')->paginate(8)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.travels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTravelRequest $request)
    {
        /* validate */
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $slug = Str::slug($request->title, '-');
        $validated['slug'] = $slug;

        $travel = Travel::create($validated);

        /* redirect */
        return to_route('admin.travels.index')->with('message', "Travel $request->title created correctly");
    }

    /**
     * Display the specified resource.
     */
    public function show(Travel $travel)
    {
        if ($travel->user_id !== auth()->id()) {
            abort(403, 'Non autorizzato');
        }
        return view('admin.travels.show', compact('travel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Travel $travel)
    {
        return view('admin.travels.edit', compact('travel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTravelRequest $request, Travel $travel)
    {
        // Valida i dati
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Aggiorna il viaggio
        $travel->update($validated);

        /* redirect */
        return to_route('admin.travels.show', $travel)->with('message', "Viaggio $request->title aggiornato con successo");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Travel $travel)
    {
        $travel->delete();

        return to_route('admin.travels.index')->with('message', "Travel $travel->title deleted correctly");
    }
}
