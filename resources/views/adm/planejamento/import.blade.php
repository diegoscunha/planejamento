@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">Importar Planejamento</div>

              <div class="card-body">
    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif
    <form method="post" action="{{ route('importar') }}" enctype="multipart/form-data" novalidate>
      <div class="form-group">
          <select class="custom-select" id="semestre" name="semestre" required>
              <option value="">Semestre</option>
              <option value="20181">2018.1</option>
              <option value="20182">2018.2</option>
          </select>
          <div class="invalid-feedback">Example invalid custom select feedback</div>
      </div>
      <div class="custom-file">
          <input type="file" class="custom-file-input" id="arquivo" name="arquivo" required>
          <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
          <div class="invalid-feedback">Example invalid custom file feedback</div>
      </div>
      <button class="btn btn-primary" type="submit">Enviar</button>
      @csrf
    </form>
  </div>
</div>
</div>
</div>
</div>
@endsection
