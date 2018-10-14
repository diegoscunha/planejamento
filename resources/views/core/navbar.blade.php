<header class="app-header navbar">
  <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="#"></a>
  <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
    <span class="navbar-toggler-icon"></span>
  </button>

  <ul class="nav navbar-nav ml-auto">
    <li class="nav-item d-md-down-none">
      <a class="nav-link" href="#"><i class="icon-bell"></i><span class="badge badge-pill badge-danger">5</span></a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        @if (Auth::check())
          <img src="https://www.gravatar.com/avatar/{{md5(strtolower(trim(Auth::user()->email)))}}?s=160&d=retro" class="img-avatar" alt="{{ Auth::user()->email }}">
        @else
          <img src="{{ asset('img/avatars/6.jpg') }}" class="img-avatar" alt="admin@bootstrapmaster.com">
        @endif
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-header text-center">
          <strong>{{ auth()->user()->name }}'s Account</strong>
        </div>
        <a class="dropdown-item" href="#"><i class="fa fa-key"></i> Alterar senha</a>
        <div class="dropdown-header text-center">
          <strong>Settings</strong>
        </div>
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-lock"></i>
          Logout
        </a>
      </div>
    </li>
  </ul>
  <button class="navbar-toggler aside-menu-toggler" type="button">
    <span class="navbar-toggler-icon"></span>
  </button>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
  </form>
</header>
