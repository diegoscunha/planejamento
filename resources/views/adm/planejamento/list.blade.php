@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    
                    <ul>
                        <li><a href="{{ route('importar') }}">Importar Planejamento</a></li>
                        <li><a href="{{ route('importar') }}">Ajuster de Planejamento</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
