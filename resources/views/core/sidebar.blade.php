<div class="sidebar">
  <nav class="sidebar-nav">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('listar-planejamento') }}"><i class="icon-speedometer"></i> Planejamentos </a>
      </li>

      <li class="nav-title">
        Cadastros
      </li>
      <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-database"></i> Cadastro</a>
        <ul class="nav-dropdown-items">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('listar-disciplinas') }}"><i class="fa fa-book"></i> Disciplinas</a>
          </li>
          {{--<li class="nav-item">
            <a class="nav-link" href="{{ route('listar-salas') }}"><i class="fa fa-book"></i> Salas</a>
          </li>--}}
          <li class="nav-item">
            <a class="nav-link" href="{{ route('listar-unidades') }}"><i class="fa fa-institution"></i> Unidades</a>
          </li>
          {{--<li class="nav-item">
              <a class="nav-link" href="{{ route('listar-usuarios') }}"><i class="fa fa-group"></i> Usuários</a>
            </li>--}}
        </ul>
      </li>
      <li class="nav-title">
        Registro de modificações
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/sample/charts"><i class="fa fa-file-text-o "></i> Logs</a>
      </li>
    </ul>
  </nav>
  <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
