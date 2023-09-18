<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
<!-- dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" -->
<head>
    <meta charset="UTF-8">
    <title>@lang('lang.title')</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" type="image/png" href="{{url('images/english_logo_inverted.png')}}"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/font-awesome/css/font-awesome.min.css')}}">


    <!-- Ionicons -->
{{--<link href="https://unpkg.com/ionicons@4.1.2/dist/css/ionicons.min.css" rel="stylesheet">--}}
{{--<!-- iCheck -->--}}
{{--<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">--}}
{{--<!-- select2 -->--}}
{{--<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">--}}
<!-- Morris chart -->
{{--<link rel="stylesheet" href="{{asset('plugins/morris/morris.css')}}">--}}
<!-- jvectormap -->
{{--<link rel="stylesheet" href="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">--}}
<!-- Date Picker -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
<!-- Daterange picker -->
{{--<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker-bs3.css')}}">--}}
{{--<!-- bootstrap wysihtml5 - text editor -->--}}

@stack('css_lib')
<!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-sweetalert/sweetalert.css')}}">
    {{--<!-- Bootstrap -->--}}
    {{--<link rel="stylesheet" href="{{asset('plugins/bootstrap/css/bootstrap.min.css')}}">--}}

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('css/'.setting("theme_color","primary").'.css')}}">
<!--
    @if(Auth::user()->language ='ar')
        <link href="{{asset('localization/css/bootstrap1/bootstrap-rtl.min.css')}}" rel="stylesheet">
        <link href="{{asset('localization/css/custom/owl.carousel-rtl.css')}}" rel="stylesheet">
        <link href="{{asset('localization/css/custom/custom-rtl.css')}}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Changa" rel="stylesheet">
       <style>
       .control-label{
        text-align:right !important;
        float:right;
       }

       .main-sidebar {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
       }
       @media (min-width: 992px){
            .content-wrapper, .main-footer, .main-header {
                margin: 0;
                margin-right: 270px;
            }
        }
       @media (min-width: 768px){
            .content-wrapper, .main-footer, .main-header {
            transition: margin-left 0.3s ease-in-out;
            margin-left: 0px;
            }
            }

        @media (min-width: 992px)
        {
            .sidebar-mini.sidebar-collapse .content-wrapper, .sidebar-mini.sidebar-collapse .main-footer, .sidebar-mini.sidebar-collapse .main-header {
                margin-right: 4.6rem !important;
            }
        }

        .ml-auto{
            margin-right:400px;
        }
       </style>

    @endif -->
    @yield('css_custom')

</head>

<body style="height: 100%; background-color: #f9f9f9;" class="hold-transition sidebar-mini {{setting('theme_color')}}">
    @if(auth()->check())
    <div class="wrapper">
        <!-- Main Header -->
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand {{setting('fixed_header','')}} {{setting('nav_color','navbar-light bg-white')}} border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{url('dashboard')}}" class="nav-link">@lang('lang.dashboard')</a>
                </li>
            </ul>


            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
            <!-- lang -->
            <li>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-globe"></i>
                        <span class="caret"></span>
                    </button>
                    <ul id="language" class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <!-- @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <li>
                                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                        {{ $properties['native'] }}
                                    </a>
                                </li>
                            @endforeach -->
                        <li><a rel="alternate" href="{{ route('lang', ['lang' => 'en']) }}" style="margin-left:10px">English</a></li>
                        <li><a rel="alternate" href="{{ route('lang', ['lang' => 'ar']) }}"  style="margin-left:10px">العربية</a></li>
                    </ul>
                </div>
            </li>
                @if(env('APP_CONSTRUCTION',false))
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#"><i class="fa fa-info-circle"></i>
                            {{env('APP_CONSTRUCTION','') }}</a>
                    </li>
                @endif
                
                @can('notifications.index')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('notifications*') ? 'active' : '' }}" href="{{ route('notification.index') }}"><i class="fa fa-bell"></i></a>
                    </li>
                @endcan
                @can('chats.index')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('notifications*') ? 'active' : '' }}" href="{{route('chat')}}"><i class="fa fa-comments"></i></a>
                    </li>
                @endcan
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img src="{{auth()->user()->getFirstMediaUrl('avatar','icon')}}" class="brand-image mx-2 img-circle elevation-2" alt="User Image">
                        <i class="fa fa fa-angle-down"></i> {!! auth()->user()->name !!}

                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('users.profile')}}" class="dropdown-item"> <i class="fa fa-user mr-2"></i> {{trans('lang.user_profile')}} </a>
                        <div class="dropdown-divider"></div>
                        <a href="{!! url('/logout') !!}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-envelope mr-2"></i> {{__('auth.logout')}}
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" >
            @yield('content')
            
        </div>
        
        <!-- Main Footer -->
        <footer class="main-footer {{setting('fixed_footer','')}}">
          
            <div class="float-right d-none d-sm-block">
                <b>Version</b> {{implode('.',str_split(substr(config('installer.currentVersion','v100'),1,3)))}}
            </div>
            <strong>Copyright © {{date('Y')}} <a href="{{url('/')}}">@lang('lang.title')</a>.</strong> All rights reserved.
        </footer>
</div>
@else
    <nav class="nmain-header navbar navbar-expand {{setting('nav_color','navbar-light bg-white')}} border-bottom">
        <div class="container" dir="rtl">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{!! url('/') !!}">{{setting('app_name')}}</a>
                </li>
                @include('layouts.menu',['icons'=>false])
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        {!! Auth::user()->name !!}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('users.profile')}}" class="dropdown-item"> <i class="fa fa-user mr-2"></i> {{trans('lang.user_profile')}} </a>
                        <div class="dropdown-divider"></div>
                        <a href="{!! url('/logout') !!}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-envelope mr-2"></i> {{__('auth.logout')}}
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    
    <div id="page-content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
            <!-- Main Footer -->
            <footer class="{{setting('fixed_footer','')}}">
                <div class="float-right d-none d-sm-block">
                    <b>Version</b> {{implode('.',str_split(substr(config('installer.currentVersion','v100'),1,3)))}}
                </div>
                <strong>Copyright © {{date('Y')}} <a href="{{url('/')}}">@lang('lang.title')</a>.</strong> All rights reserved.
            </footer>
        </div>
    </div>

    @endrole


    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    {{--<script src="{{asset('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js')}}"></script>--}}
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    {{--<script>--}}
    {{--$.widget.bridge('uibutton', $.ui.button)--}}
    {{--</script>--}}
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- The core Firebase JS SDK is always required and must be listed first -->


    @if(LaravelLocalization::getCurrentLocaleDirection() == "rtl")
    <script src="{{asset('js/custom/custom-rtl.js')}}"></script>
    @endif

<!--firebsae config-->

{{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js">--}}
{{--  </script>--}}
{{--<script src="https://www.gstatic.com/firebasejs/8.2.2/firebase-app.js"></script>--}}
{{--<script src="https://www.gstatic.com/firebasejs/8.2.2/firebase-messaging.js"></script>--}}

{{--<script>--}}


{{--   var firebaseConfig = {--}}
{{--       apiKey: "AIzaSyC1GWjZ1Irhj7_OB4Ob--_a_rcP0xnk1Js",--}}
{{--       authDomain: "shs-chat-c425e.firebaseapp.com",--}}
{{--       databaseURL: "https://shs-chat-c425e-default-rtdb.firebaseio.com/",--}}
{{--       projectId: "shs-chat-c425e",--}}
{{--       storageBucket: "shs-chat-c425e.appspot.com",--}}
{{--       messagingSenderId: "963124896977",--}}
{{--       appId: "1:963124896977:web:016e3a562edc51652211f0",--}}
{{--       measurementId: "G-2MVVRHDF8M"--}}
{{--   };--}}

{{--     firebase.initializeApp(firebaseConfig);--}}
{{--     const messaging = firebase.messaging();--}}



{{--    messaging.onMessage(function(payload) {--}}
{{--        const noteTitle = payload.notification.title;--}}
{{--        const noteOptions = {--}}
{{--            body: payload.notification.body,--}}
{{--            icon: payload.notification.icon,--}}
{{--        };--}}
{{--        new Notification(noteTitle, noteOptions);--}}
{{--    });--}}

{{--</script>--}}
    <!-- Sparkline -->
    {{--<script src="{{asset('plugins/sparkline/jquery.sparkline.min.js')}}"></script>--}}
    {{--<!-- iCheck -->--}}
    {{--<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>--}}
    {{--<!-- select2 -->--}}
    {{--<script src="{{asset('plugins/select2/select2.min.js')}}"></script>--}}
    <!-- jQuery Knob Chart -->
    {{--<script src="{{asset('plugins/knob/jquery.knob.js')}}"></script>--}}
    <!-- daterangepicker -->
    {{--<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js')}}"></script>--}}
    {{--<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>--}}
    <!-- datepicker -->
    <script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    {{--<script src="{{asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>--}}
    <!-- Slimscroll -->
    <script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
    <!-- FastClick -->
    {{--<script src="{{asset('plugins/fastclick/fastclick.js')}}"></script>--}}
    @stack('scripts_lib')
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.js')}}"></script>
    {{--<!-- AdminLTE dashboard demo (This is only for demo purposes) -->--}}
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('dist/js/demo.js')}}"></script>

    <script src="{{asset('js/scripts.js')}}"></script>

   
    @stack('scripts')
    @yield('script')
</body>
</html>
