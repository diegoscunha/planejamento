@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">Alterar Senha</h1>
          <div class="small text-muted">Alterar senha do usuário</div>
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
          <form class="" method="POST" action="{{ route('alterar-senha') }}">
              {{ csrf_field() }}
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
