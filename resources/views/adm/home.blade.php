@extends('samples')

@section('content')
<div class="container">
  @if(session()->has('message'))
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-success">
        {{ session('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection
