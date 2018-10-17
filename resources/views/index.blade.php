<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Icons -->
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/simple-line-icons.css') }}" rel="stylesheet">

	<link href="{{ asset('css/jquery.loading.min.css') }}" rel="stylesheet">

	<!-- Scripts -->
	<script src="{{ asset('js/app_old.js') }}" defer></script>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body onload="init_consultar(null);">
	<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
      <div class="container">
	      <a class="navbar-brand" href="{{ url('/') }}">
	          {{ config('app.name', 'Laravel') }}
	      </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
	        <!-- Left Side Of Navbar -->
	        <ul class="navbar-nav mr-auto">

	        </ul>

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
          	<!-- Authentication Links -->
            @guest
            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
            <!-- <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li> -->
            @else
            <li class="nav-item dropdown">
	            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
	                {{ Auth::user()->name }} <span class="caret"></span>
	            </a>

              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              </div>
            </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>
      <main class="py-4">
				<div id="loading-calendar" class="container" style='width:1000px; height:800px; padding:0px;'>
    			<form id="form-pesquisar" action="#" class="needs-validation" novalidate>
						<div class="form-group row">
							<label for="semestre" class="col-sm-1 col-form-label">Semestre</label>
							<div class="col-sm-4">
								<select id="semestre" name="semestre" class="form-control form-control-sm filtro" required>
										<option value="">:: Selecione ::</option>
										@foreach ($semestres as $value)
												<option value="{{ $value->ano }}.{{ $value->semestre }}">{{ $value->ano }}.{{ $value->semestre }}</option>
										@endforeach
								</select>
								<div class="invalid-feedback">
	      						Por favor, selecione um semestre.
	    					</div>
							</div>
						</div>
		        <div class="form-group row">
	            <label for="unidade" class="col-sm-1 col-form-label">Unidade</label>
	            <div class="col-sm-4">
                <select id="unidade" name="unidade" class="form-control form-control-sm filtro" required>
                    <option value="">:: Selecione ::</option>
                </select>
								<div class="invalid-feedback">
        						Por favor, selecione uma unidade.
      					</div>
	            </div>
		        </div>
		        <div class="form-group row">
	            <label for="sala" class="col-sm-1 col-form-label">Sala</label>
	            <div class="col-sm-4">
                <select id="sala" name="sala" class="form-control form-control-sm filtro" required>
                    <option value="">:: Selecione ::</option>
                </select>
								<div class="invalid-feedback">
										Por favor, selecione uma sala.
								</div>
	            </div>
		        </div>
		        <div class="form-group">
		          <button id="pesquisar" class="btn btn-primary">Pesquisar</button>
		        </div>
    			</form>

    			<div class='controls'>
        		<button type="button" class="btn btn-sm btn-danger" onclick='exports("pdf")'>
							<i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
						</button>
        		<button type="button" class="btn btn-sm btn-primary" onclick='exports("png")'>
							<i class="fa fa-picture-o" aria-hidden="true"></i> PNG
						</button>
        		<button type="button" class="btn btn-sm btn-success" onclick='exports("excel")'>
							<i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel
						</button>
    			</div>
			    <div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
			        <div class="dhx_cal_navline">
			    			<div class="dhx_cal_prev_button">&nbsp;</div>
			    			<div class="dhx_cal_next_button">&nbsp;</div>
			    			<div class="dhx_cal_today_button"></div>
			    			<div class="dhx_cal_date"></div>
			    			<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
			    			<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
			    			<!--<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div> -->
			    		</div>
			    		<div class="dhx_cal_header"></div>
			    		<div class="dhx_cal_data"></div>
			    </div>
				</div>
			</main>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
		<script src="{{ asset('dhtmlxScheduler_v5.0.0/codebase/dhtmlxscheduler.js') }}" type="text/javascript" charset="utf-8"></script>
		<script src="{{ asset('dhtmlxScheduler_v5.0.0/codebase/ext/dhtmlxscheduler_active_links.js') }}" type="text/javascript" charset="utf-8"></script>
		<script src="{{ asset('dhtmlxScheduler_v5.0.0/codebase/ext/dhtmlxscheduler_agenda_view.js') }}"></script>
		<script src="{{ asset('dhtmlxScheduler_v5.0.0/codebase/ext/dhtmlxscheduler_readonly.js') }}"></script>
		<script src="https://export.dhtmlx.com/scheduler/api.js" charset="utf-8"></script>
		<link rel="stylesheet" href="{{ asset('dhtmlxScheduler_v5.0.0/codebase/dhtmlxscheduler_material.css') }}" type="text/css" charset="utf-8">
		<script src="{{ asset('dhtmlxScheduler_v5.0.0/codebase/locale/locale_pt.js') }}" type="text/javascript" charset="utf-8"></script>
		<script src="{{ asset('js/jquery.loading.js') }}" type="text/javascript" ></script>
		<script src="{{ asset('js/script.js') }}" type="text/javascript" charset="utf-8"></script>
		<script src="{{ asset('js/functions.js') }}" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			// Exportação
			function exports(type) {
					exportScheduler(type, $('#unidade option:selected').text(), $('#sala option:selected').text());
			}
		</script>
	</body>
</html>
