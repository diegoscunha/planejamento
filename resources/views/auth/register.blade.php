@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">Novo Usuário</h1>
          <div class="small text-muted">Novo registro de usuário</div>
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
          <form class="" method="POST" action="{{ route('register') }}">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="name">Nome</label>
                <input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus />
              </div>
              <div class="form-group">
                <label for="email">E-mail</label>
                <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required />
              </div>

              <div class="form-group">
                <label for="password">Senha</label>
                <input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required />
              </div>

              <div class="form-group">
                <label for="password-confirm">Confirmar Senha</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required />
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
