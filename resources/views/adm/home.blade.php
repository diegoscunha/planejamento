@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
                <ul>
                    <li><a href="{{ route('listar-planejamento') }}">Planejamento</a></li>
                    <li><a href="{{ route('importar-planejamento') }}">Adicionar Planejamento</a></li>
                    <!-- <li><a href="route('detalhes-planejamento') ">Detalhes Planejamento</a></li>
                    <li><a href=" route('estatisticas-planejamento') ">Estatísticas Planejamento</a></li>
                    <li><a href=" route('editar-registros-planejamento') ">Editar Registros Planejamento</a></li> -->

                    <!-- <li><a href=" route('listar-usuarios') ">Usuários</a></li>
                    <li><a href=" route('adicionar-usuarios') ">Adicionar Usuários</a></li>
                    <li><a href=" route('editar-usuarios') ">Editar Usuários</a></li>
                    <li><a href=" route('excluir-usuarios') ">Excluir Usuários</a></li> -->
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
