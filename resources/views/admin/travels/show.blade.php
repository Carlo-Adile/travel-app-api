@extends('layouts.admin')

@section('content')
  <header class="py-3">
    <div class="container d-flex justify-content-between align-items-center">

      <h4 class="py-4">Viaggio: {{ $travel->title }}</h4>
      <a href="{{ route('admin.steps.create', ['travel' => $travel->id]) }}" class="btn btn-primary">
        Aggiungi una nuova tappa
        <i class="fa-solid fa-pencil"></i>
      </a>

    </div>
  </header>

  <section class="py-4">
    <div class="container">
      <div class="py-4">
        <h5>Tappe del viaggio</h5>
        <p>{{ $travel->steps->count() }} tappe aggiunte</p>
      </div>
      <div class="table-responsive">
        <table class="table table-light">
          <thead>
            <tr>
              <th scope="col">titolo</th>
              <th scope="col">giorno</th>
              <th scope="col">orario</th>
              <th scope="col">descrizione</th>
              <th scope="col">costo</th>
              <th scope="col">completato</th>
              <th scope="col">link Google Maps</th>
              <th scope="col">azioni</th>
            </tr>
          </thead>
          @forelse($travel->steps as $step)
            <tr>
              <td>{{ $step->title }}</td>
              <td>{{ $step->day }}</td>
              <td>{{ $step->time }}</td>
              <td>{{ $step->description }}</td>
              <td>{{ $step->cost }}</td>
              <td>{{ $step->checked ? 'Sì' : 'No' }}</td>
              <td>
                @if ($step->google_maps_link)
                  <a href="{{ $step->google_maps_link }}" target="_blank">Apri in Google Maps</a>
                @endif
              </td>
              <td>
                <a href="{{ route('admin.steps.edit', ['travel' => $travel->id, 'step' => $step->id]) }}" class="btn">
                  <i class="fa-solid fa-pencil"></i>
                </a>
                {{-- bs5-modal-default --}}
                <button type="button" class="btn btn-lg" data-bs-toggle="modal"
                  data-bs-target="#modal-{{ $step->id }}">
                  <i class="fa-solid fa-trash" aria-hidden="true"></i>
                </button>

                <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                <div class="modal fade" id="modal-{{ $step->id }}" tabindex="-1" data-bs-backdrop="static"
                  data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitle-{{ $step->id }}"
                  aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle-{{ $step->id }}">
                          Elimina {{ $step->title }} ?
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          data-bs-target="#modal-{{ $step->id }}"></button>
                      </div>

                      {{-- <div class="modal-body">Destroy</div> --}}
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                          Chiudi
                        </button>
                        <form action="{{ route('admin.steps.destroy', ['travel' => $travel->id, 'step' => $step->id]) }}"
                          method="post">
                          @csrf
                          @method('DELETE')

                          <button type="submit" class="btn btn-danger">Confirm</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8">Nessuna tappa aggiunta per questo viaggio.</td>
            </tr>
          @endforelse
        </table>
      </div>

    </div>
  </section>
@endsection
