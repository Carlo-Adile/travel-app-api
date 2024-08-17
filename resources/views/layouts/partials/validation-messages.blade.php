{{-- messaggio errore di validazione --}}
@if ($errors->any())
  <div class="alert alert-primary" role="alert">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
