<?php
$settings = App\Models\AdminSettings::first();
$campaignsReported = App\Models\CampaignsReported::count();
$donationsPending = App\Models\Donations::where('approved','0')->count();
$paymentsGateways = PaymentGateways::all();
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <title>{{ trans('admin.admin') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link href="{{ asset('public/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('public/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{ asset('public/fonts/ionicons/css/ionicons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('public/admin/css/app.css')}}" rel="stylesheet" type="text/css" />
    <!-- IcoMoon CSS -->
    <link href="{{ asset('public/css/icomoon.css') }}" rel="stylesheet">

     <!-- Theme style -->
    <link href="{{ asset('public/admin/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link href="{{ asset('public/admin/css/skins/skin-red.min.css')}}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ URL::asset('public/img/favicon.png') }}" />

    <link href='//fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>

    <link href="{{ asset('public/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
    .alert-danger.fade {
      opacity: 9 !important;
    }
    </style>

    @yield('css')

    <script type="text/javascript">

    // URL BASE
    var URL_BASE = "{{ url('/') }}";
    var url_file_upload = "{{route('upload.image', ['_token' => csrf_token()])}}";
    var delete_confirm = "{{trans('misc.delete_confirm')}}";
    var yes_confirm = "{{trans('misc.yes_confirm')}}";
    var cancel_confirm = "{{trans('misc.cancel_confirm')}}";
 </script>

  </head>
  <!--
  BODY TAG OPTIONS:
  =================
  Apply one or more of the following classes to get the
  desired effect
  |---------------------------------------------------------|
  | SKINS         | skin-blue                               |
  |               | skin-black                              |
  |               | skin-purple                             |
  |               | skin-yellow                             |
  |               | skin-red                                |
  |               | skin-green                              |
  |---------------------------------------------------------|
  |LAYOUT OPTIONS | fixed                                   |
  |               | layout-boxed                            |
  |               | layout-top-nav                          |
  |               | sidebar-collapse                        |
  |               | sidebar-mini                            |
  |---------------------------------------------------------|
  -->
  <body class="skin-red sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="{{ url('panel/admin') }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b><i class="ion ion-ios-bolt"></i></b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><i class="ion ion-ios-bolt"></i> {{ trans('admin.admin') }}</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

            	<li>
            		<a href="{{ url('/') }}"><i class="glyphicon glyphicon-home myicon-right"></i> {{ trans('admin.view_site') }}</a>
            	</li>

              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <img src="{{ asset('public/avatar').'/'.Auth::user()->avatar }}" class="user-image" alt="User Image" />
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
                    <img src="{{ asset('public/avatar').'/'.Auth::user()->avatar }}" class="img-circle" alt="User Image" />
                    <p>
                      <small>{{ Auth::user()->name }}</small>
                    </p>
                  </li>

                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="{{ url('account') }}" class="btn btn-default btn-flat">{{ trans('users.account_settings') }}</a>
                    </div>
                    <div class="pull-right">
                      <a href="{{ url('logout') }}" class="btn btn-default btn-flat">{{ trans('users.logout') }}</a>
                    </div>
                  </li>
                </ul>
              </li>

            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="{{ asset('public/avatar').'/'.Auth::user()->avatar }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p class="text-overflow">{{ Auth::user()->name }}</p>
              <small class="btn-block text-overflow"><a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> {{ trans('misc.online') }}</a></small>
            </div>
          </div>


          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">

            <li class="header">{{ trans('admin.main_menu') }}</li>

            <!-- Links -->
            <li @if(Request::is('panel/admin')) class="active" @endif>
            	<a href="{{ url('panel/admin') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('admin.dashboard') }}</span></a>
            </li><!-- ./Links -->

           <!-- Links -->
            <li class="treeview @if( Request::is('panel/admin/settings') || Request::is('panel/admin/settings/limits') ) active @endif">
            	<a href="{{ url('panel/admin/settings') }}"><i class="fa fa-cogs"></i> <span>{{ trans('admin.general_settings') }}</span> <i class="fa fa-angle-left pull-right"></i></a>

           		<ul class="treeview-menu">
                <li @if(Request::is('panel/admin/settings')) class="active" @endif><a href="{{ url('panel/admin/settings') }}"><i class="fa fa-circle-o"></i> {{ trans('admin.general') }}</a></li>
                <li @if(Request::is('panel/admin/settings/limits')) class="active" @endif><a href="{{ url('panel/admin/settings/limits') }}"><i class="fa fa-circle-o"></i> {{ trans('admin.limits') }}</a></li>
              </ul>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/theme')) class="active" @endif>
            	<a href="{{ url('panel/admin/theme') }}"><i class="fa fa-paint-brush"></i> <span>{{ trans('misc.theme') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/gallery')) class="active" @endif>
            	<a href="{{ url('panel/admin/gallery') }}"><i class="fa fa-picture-o"></i> <span>{{ trans('misc.gallery') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/blog')) class="active" @endif>
            	<a href="{{ url('panel/admin/blog') }}"><i class="fa fa-pencil"></i> <span>{{ trans('misc.blog') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/categories')) class="active" @endif>
            	<a href="{{ url('panel/admin/categories') }}"><i class="fa fa-list-ul"></i> <span>{{ trans('admin.categories') }}</span></a>
            </li><!-- ./Links -->

             <!-- Links -->
            <li @if(Request::is('panel/admin/campaigns')) class="active" @endif>
            	<a href="{{ url('panel/admin/campaigns') }}"><i class="ion ion-speakerphone"></i> <span>{{ trans_choice('misc.campaigns_plural',0) }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/campaigns/reported')) class="active" @endif>
            	<a href="{{ url('panel/admin/campaigns/reported') }}"><i class="ion ion-alert-circled"></i> <span>{{ trans('misc.campaigns_reported') }}</span>
            	@if( $campaignsReported != 0 )
            		<span class="pull-right-container">
              <span class="label label-danger pull-right">{{$campaignsReported}}</span>
            </span>
            @endif
            </a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/withdrawals')) class="active" @endif>
            	<a href="{{ url('panel/admin/withdrawals') }}"><i class="fa fa-university"></i> <span>{{ trans('misc.withdrawals') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li class="@if( Request::is('panel/admin/donations') ) active @endif">
            	<a href="{{ url('panel/admin/donations') }}"><i class="ion ion-cash"></i> <span>{{ trans('misc.donations') }}</span>

                @if($donationsPending != 0)
                  <span class="pull-right-container">
                    <span class="label label-warning pull-right">{{$donationsPending}}</span>
                  </span>
                @endif
              </a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/members')) class="active" @endif>
            	<a href="{{ url('panel/admin/members') }}"><i class="glyphicon glyphicon-user"></i> <span>{{ trans('admin.members') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/pages')) class="active" @endif>
            	<a href="{{ url('panel/admin/pages') }}"><i class="glyphicon glyphicon-file"></i> <span>{{ trans('admin.pages') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
            <li class="treeview @if(Request::is('panel/admin/payments') || Request::is('panel/admin/payments/*') || Request::is('panel/admin/payment/add')) active @endif">
            	<a href="{{ url('panel/admin/payments') }}"><i class="fa fa-credit-card"></i> <span>{{ trans('misc.payment_settings') }}</span> <i class="fa fa-angle-left pull-right"></i></a>

           		<ul class="treeview-menu">
                <li @if(Request::is('panel/admin/payment/add')) class="active" @endif><a href="{{ url('panel/admin/payment/add') }}"><i class="fa fa-circle-o"></i> {{ trans('misc.add_payment') }}</a></li>
                <li @if(Request::is('panel/admin/payments')) class="active" @endif><a href="{{ url('panel/admin/payments') }}"><i class="fa fa-circle-o"></i> {{ trans('admin.general') }}</a></li>

                  <?php
                  foreach ($paymentsGateways as $key) {
                    ?>
                    <li @if(Request::is('panel/admin/payments/'.$key->id)) class="active" @endif><a href="{{ url('panel/admin/payments/'.$key->id) }}"><i class="fa fa-circle-o"></i> {{ $key->name }}</a></li>
                <?php
                  }
                ?>
              </ul>
            </li><!-- ./Links -->

            <!-- Links -->
            <li @if(Request::is('panel/admin/profiles-social')) class="active" @endif>
            	<a href="{{ url('panel/admin/profiles-social') }}"><i class="fa fa-share-alt"></i> <span>{{ trans('admin.profiles_social') }}</span></a>
            </li><!-- ./Links -->

            <!-- Links -->
           <!-- <li @if(Request::is('panel/admin/messages')) class="active" @endif>
            	<a href="{{ url('panel/admin/messages') }}"><i class="fa fa-envelope"></i> <span>{{ trans('users.messages') }}</span></a>
            </li><!-- ./Links -->

          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>

      @yield('content')

      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- Default to the left -->
       &copy; <strong>{{ $settings->title }}</strong> - <?php echo date('Y'); ?>
      </footer>

    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

   <!-- jQuery 2.1.4 -->
    <script src="{{ asset('public/plugins/jQuery/jQuery-2.1.4.min.js')}}" type="text/javascript"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ asset('public/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="{{ asset('public/plugins/fastclick/fastclick.min.js')}}" type="text/javascript"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('public/admin/js/app.min.js')}}" type="text/javascript"></script>

    <script src="{{ asset('public/js/ckeditor/ckeditor.js')}}"></script>

    <script src="{{ asset('public/plugins/sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>

    <script src="{{ asset('public/admin/js/functions.js')}}" type="text/javascript"></script>

    @yield('javascript')

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
          Both of these plugins are recommended to enhance the
          user experience. Slimscroll is required when using the
          fixed layout. -->
  </body>
</html>
