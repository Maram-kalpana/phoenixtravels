<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') - {{ config('app.name', 'Go Go Grocery101') }}</title>
  @if (!empty(asset(getFav())))
  <link rel="icon" href="{{asset(getFav())}}" type="image/png" />
  @endif
  <link href="{{asset('backend/assets/css/pace.min.css')}}" rel="stylesheet" />
  <script src="{{asset('backend/assets/js/pace.min.js')}}"></script>
  <!--plugins-->
  <link href="{{asset('backend/assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
  <link href="{{asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
  <link href="{{asset('backend/assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="{{asset('backend/assets/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('backend/assets/css/bootstrap-extended.css')}}" rel="stylesheet">
  <link href="{{asset('backend/assets/css/style.css')}}" rel="stylesheet">
  <link href="{{asset('backend/assets/css/icons.css')}}" rel="stylesheet">
  <link href="http://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <!--Theme Styles-->
  <link href="{{asset('backend/assets/css/dark-theme.css')}}" rel="stylesheet" />
  <link href="{{asset('backend/assets/css/semi-dark.css')}}" rel="stylesheet" />
  <link href="{{asset('backend/assets/css/header-colors.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="{{asset('backend/sweetalerts/sweetalert.css')}}" />
  <link rel="stylesheet" href="http://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="http://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <style>
  :root{
    --primary-color: {{  getColorCode(); }};
  }
  </style>
  @yield('css')
</head>
<body>
  <!--start wrapper-->
  <div class="wrapper">

    <!--start sidebar -->
    @include('includes.sidebar')
    <!--end sidebar -->

    <!--start top header-->
    <header class="top-header">
        @include('includes.header')
    </header>
    <!--end top header-->


    <!-- start page content wrapper-->
    <div class="page-content-wrapper">
      <!-- start page content-->
      @yield('content')
      <!-- end page content-->
    </div>
    <!--end page content wrapper-->


    @include('includes.footer')
  </div>
  <!--end wrapper-->
  <!-- JS Files-->
  <script src="{{asset('backend/assets/js/jquery.min.js')}}"></script>
  <script src="{{asset('backend/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
  <script src="{{asset('backend/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
  <script src="{{asset('backend/assets/js/bootstrap.bundle.min.js')}}"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <!--plugins-->
  <script src="{{asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
  <script src="{{asset('backend/assets/plugins/apexcharts-bundle/js/apexcharts.min.js')}}"></script>
  <script src="{{asset('backend/assets/plugins/easyPieChart/jquery.easypiechart.js')}}"></script>
  <script src="{{asset('backend/assets/plugins/chartjs/chart.min.js')}}"></script>
  <script src="{{asset('backend/assets/js/index.js')}}"></script>
  <!-- Main JS-->
  <script src="{{asset('backend/assets/js/main.js')}}"></script>

  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="{{asset('backend/js/notify.min.js')}}"></script>
  <script src="{{asset('backend/sweetalerts/sweetalert.min.js')}}"></script>
  <script src="{{asset('backend/plugins/notify/js/notifIt.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('backend/js/select2-custom.js')}}"></script>
		<script>
			function notify(id=null,message='Something went wrong',position='top right',type='error',willReload=false,url=null,timeout=3000){
            if(id!=null && id != 'input[name=error]' && id!='input[name=error]') $(id).notify(
                             message,
                             {
                                 position:position,
                                 className:type
                             }
                         );
        else  $.notify(
                message,
                {
                    position:'top right',
                    className:type
                }
            );
            if (willReload) setTimeout(function () {
                  if(url==null)window.location.reload();
                  else window.location.href = url;
                }, timeout)
        }
		</script>
        @yield('script')
</body>
</html>
