@extends('layouts.admin')

@section('content')
  <header class="py-3">
    <div class="container d-flex justify-content-between align-items-center">

      <h1>Back-office di Carlo Adile - Travel App</h1>
      <a href="{{ route('admin.travels.create') }}" class="btn btn-primary">
        Aggiungi un nuovo viaggio
        <i class="fa-solid fa-pencil"></i>
      </a>

    </div>
  </header>

  <section class="py-4">
    <div class="container">
      <h4 class="p-3">Tutti i viaggi</h4>

      {{-- messaggio operazione compiuta --}}
      @include('layouts.partials.session-messages')

      <div class="table-responsive">
        <table class="table table-light">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Titolo</th>
              <th scope="col">Data di inizio</th>
              <th scope="col">Data di fine</th>
              <th scope="col">Azioni</th>
            </tr>
          </thead>

          <tbody>
            {{-- @dd($travels) --}}
            @forelse($travels as $travel)
              <tr>

                <td scope="row">{{ $travel->id }}</td>
                <td>{{ $travel->title }}</td>
                <td>{{ $travel->start_date }}</td>
                <td>{{ $travel->end_date }}</td>
                <td>
                  <a href="{{ route('admin.travels.edit', $travel) }}" class="btn">
                    <i class="fa-solid fa-pencil"></i>
                  </a>
                  <a href="{{ route('admin.travels.show', $travel) }}">
                    vedi
                  </a>
                  {{-- bs5-modal-default --}}
                  <button type="button" class="btn btn-lg" data-bs-toggle="modal"
                    data-bs-target="#modal-{{ $travel->id }}">
                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                  </button>

                  <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                  <div class="modal fade" id="modal-{{ $travel->id }}" tabindex="-1" data-bs-backdrop="static"
                    data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitle-{{ $travel->id }}"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalTitle-{{ $travel->id }}">
                            Elimina {{ $travel->title }} ?
                          </h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            data-bs-target="#modal-{{ $travel->id }}"></button>
                        </div>

                        {{-- <div class="modal-body">Destroy</div> --}}
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Chiudi
                          </button>
                          <form action="{{ route('admin.travels.destroy', $travel) }}" method="post">
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
              <tr class="">
                <td scope="row" colspan="5">Nessun viaggio aggiunto!</td>
              </tr>
            @endforelse

          </tbody>
        </table>
      </div>
      {{ $travels->links('pagination::bootstrap-5') }}
    </div>
  </section>
@endsection
