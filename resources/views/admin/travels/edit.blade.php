@extends('layouts.admin')

@section('content')
  <header class="py-3">
    <div class="container d-flex justify-content-between align-items-center">
      <h1>Modifica il viaggio {{ $travel->title }}</h1>

    </div>
  </header>

  <div class="container">
    @include('layouts.partials.validation-messages')

    <form action="{{ route('admin.travels.update', $travel) }}" method="post" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="" class="form-label">Title*</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title"
          aria-describedby="helpId" placeholder="" value="{{ old('title', $travel->title) }}" />
        <small id="helpId" class="form-text text-muted">modifica il titolo del viaggio</small>
        @error('title')
          <div class="text-danger py-2">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="start_date" class="form-label">Data di inizio*</label>
        <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date"
          id="start_date" value="{{ old('start_date', $travel->start_date ?? '') }}" />
        @error('start_date')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="end_date" class="form-label">Data di fine*</label>
        <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" id="end_date"
          value="{{ old('end_date', $travel->end_date ?? '') }}" />
        @error('end_date')
          <div class="text-danger py-2">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">
        Save
      </button>

    </form>
  </div>
@endsection
