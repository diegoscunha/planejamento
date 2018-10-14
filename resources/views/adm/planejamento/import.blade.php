@extends('samples')

@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h1 class="card-title mb-0">Adicionar Planejamento</h1>
          <div class="small text-muted">Importação do arquivo com dados do Planejamento acadêmico</div>
        </div>
      </div>
      <div class="row" style="padding-top: 15px;">
        <div class="col-md-6">
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
        </div>
      </div>
      <div class="row">
          <div class="col-md-12">
            <form method="post" action="{{ route('importar') }}" enctype="multipart/form-data" novalidate>
              @csrf
              <div class="row">
                <div class="form-group col-md-6">
                    <label for="semestre">Ano</label>
                    <select class="form-control form-control-sm" id="ano" name="ano" required>
                        <option value="">:: Selecione ::</option>
                        @foreach($anos as $value)
                        <option value="{{ $value }}"  {{ (old('ano')==$value) ? "selected" : ""}}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                      <label for="semestre">Semestre</label>
                    <select class="form-control form-control-sm" id="semestre" name="semestre" required>
                        <option value="">:: Selecione ::</option>
                        <option value="1" {{ (old('semestre')==1) ? "selected" : ""}}>1</option>
                        <option value="2" {{ (old('semestre')==2) ? "selected" : ""}}>2</option>
                    </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                   <label for="arquivo">Arquivo de importação (.csv)</label>
                   <input type="file" class="form-control-file" id="arquivo" name="arquivo" required/>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <button class="btn btn-primary" type="submit" id="importar">Enviar</button>
                </div>
              </div>
            </form>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
