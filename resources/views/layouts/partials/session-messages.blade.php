{{-- messaggio dell'operazione compiuta --}}
@if (session('message'))
  {{-- bs5-alert-closable --}}
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    {{ session('message') }}
  </div>
@endif
