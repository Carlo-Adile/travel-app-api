@extends('layouts.admin')

@section('content')
  <header class="py-3">
    <div class="container d-flex justify-content-between align-items-center">
      <h2 class="px-2">Aggiungi una nuova tappa!</h2>
    </div>
  </header>

  <div class="container py-4">

    {{-- segnala errori --}}
    @include('layouts.partials.validation-messages')

    <form action="{{ route('admin.travels.steps.store', $travel->id) }}" method="post" enctype="multipart/form-data">
      @csrf

      <!-- Titolo -->
      <div class="mb-3">
        <label for="title" class="form-label">Titolo*</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title"
          value="{{ old('title') }}" />
        <small id="helpId" class="form-text text-muted">Inserisci il titolo della tappa</small>
        @error('title')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <!-- Giorno -->
      <div class="mb-3">
        <label for="day" class="form-label">Giorno*</label>
        <input type="date" class="form-control @error('day') is-invalid @enderror" name="day" id="day"
          value="{{ old('day') }}" />
        @error('day')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <!-- Orario -->
      <div class="mb-3">
        <label for="time" class="form-label">Orario*</label>
        <input type="time" class="form-control @error('time') is-invalid @enderror" name="time" id="time"
          value="{{ old('time') }}" />
        @error('time')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <!-- Descrizione -->
      <div class="mb-3">
        <label for="description" class="form-label">Descrizione</label>
        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
          rows="3">{{ old('description') }}</textarea>
        <small id="helpId" class="form-text text-muted">Inserisci una descrizione della tappa</small>
        @error('description')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <!-- Costo -->
      <div class="mb-3">
        <label for="cost" class="form-label">Costo</label>
        <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" name="cost"
          id="cost" value="{{ old('cost') }}" />
        @error('cost')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <!-- Google Maps Link -->
      <div class="mb-3">
        <label for="google_maps_link" class="form-label">Link Google Maps</label>
        <input type="url" class="form-control @error('google_maps_link') is-invalid @enderror" name="google_maps_link"
          id="google_maps_link" value="{{ old('google_maps_link') }}" />
        @error('google_maps_link')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <!-- Immagini -->
      <div class="mb-3">
        <label for="images" class="form-label">Immagini</label>
        <input type="file" class="form-control @error('images.*') is-invalid @enderror" name="images[]" id="images"
          multiple />
        @error('images.*')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <!-- Check -->
      <div class="mb-3">
        <label for="checked" class="form-label">Completato?</label>
        <input type="checkbox" class="form-check-input @error('checked') is-invalid @enderror" name="checked"
          id="checked" {{ old('checked') ? 'checked' : '' }} />
        @error('checked')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <input type="hidden" name="travel_id" value="{{ $travel->id }}">

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Create</button>
    </form>
  </div>
@endsection
