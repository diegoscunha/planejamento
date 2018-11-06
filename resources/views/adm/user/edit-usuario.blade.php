@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">Editar Usuário</h1>
          <div class="small text-muted">Editar registro de usuário</div>
        </div>
      </div>
      @if ($errors->any())
      <br>
      <div class="row">
        <div class="col-md-6">
          <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
            </ul>
          </div>
        </div>
      </div>
      @endif
      <br>
      <div class="row">
        <div class="col-md-6">
          <form class="" method="POST" action="{{ route('salvar-usuario') }}">
              {{ csrf_field() }}
              <input type="hidden" id="id" name="id" value="{{ old('id', $usuario->id) }}" />
              <div class="form-group">
                <label for="name">Nome</label>
                <input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $usuario->name) }}" required autofocus />
              </div>
              <div class="form-group">
                <label for="email">E-mail</label>
                <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $usuario->email) }}" required />
              </div>
              <button type="submit" class="btn btn-primary">
                    Salvar
              </button>
              <a href="{{ route('listar-usuarios') }}" class="btn btn-secondary">Cancelar</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
