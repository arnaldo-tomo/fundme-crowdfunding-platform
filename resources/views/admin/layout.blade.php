<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ url('public/img/favicon.png') }}" />

    <title>{{ __('admin.admin') }}</title>

    <link href="{{ asset('public/css/core.css') }}?v={{$settings->version}}" rel="stylesheet">
    <link href="{{ asset('public/css/bootstrap/bootstrap.min.css') }}?v={{$settings->version}}" rel="stylesheet">
    <link href="{{ asset('public/css/bootstrap-icons.css') }}?v={{$settings->version}}" rel="stylesheet">
    <link href="{{ asset('public/css/admin-styles.css') }}?v={{$settings->version}}" rel="stylesheet">
    <link href="{{ asset('public/css/styles.css') }}?v={{$settings->version}}" rel="stylesheet">

    <script type="text/javascript">
        // URL BASE
        var URL_BASE = "{{ url('/') }}";
        var url_file_upload = "{{route('upload.image', ['_token' => csrf_token()])}}";
        var delete_confirm = "{{trans('misc.delete_confirm')}}";
        var yes_confirm = "{{trans('misc.yes_confirm')}}";
        var cancel_confirm = "{{trans('misc.cancel_confirm')}}";
        var formats_available = "{{ trans('misc.formats_available') }}";
        var file_size_allowed = {{$settings->file_size_allowed * 1024}};
        var amount = '{{trans('misc.amount')}}';
        var donations = '{{trans('misc.donations')}}';
        var decimalFormat = '{{ $settings->decimal_format }}';
        var currencyPosition = '{{ $settings->currency_position }}';
        var currencySymbol = '{{ $settings->currency_symbol }}';
      </script>
    <style>
     :root {
       --color-default: {{ $settings->color_default }} !important;
     }
     </style>

    @yield('css')
  </head>
  <body>
  <div class="overlay" data-bs-toggle="offcanvas" data-bs-target="#sidebar-nav"></div>
  <div class="popout font-default"></div>

    <main>

      <div class="offcanvas offcanvas-start sidebar bg-dark text-white" tabindex="-1" id="sidebar-nav" data-bs-keyboard="false" data-bs-backdrop="false">
      <div class="offcanvas-header">
          <h5 class="offcanvas-title"><img src="{{ url('public/img/logo.png') }}" width="100" /></h5>
          <button type="button" class="btn-close btn-close-custom text-white toggle-menu d-lg-none" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="bi bi-x-lg"></i>
          </button>
      </div>
      <div class="offcanvas-body px-0 scrollbar">
          <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-start list-sidebar" id="menu">

              <li class="nav-item">
                  <a href="{{ url('panel/admin') }}" class="nav-link text-truncate @if (request()->is('panel/admin')) active @endif">
                      <i class="bi-speedometer2 me-2"></i> {{ __('admin.dashboard') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="#settings" data-bs-toggle="collapse" class="nav-link text-truncate dropdown-toggle @if (request()->is('panel/admin/settings') ||request()->is('panel/admin/settings/limits')) active @endif" @if (request()->is('panel/admin/settings') ||request()->is('panel/admin/settings/limits')) aria-expanded="true" @endif>
                      <i class="bi-gear me-2"></i> {{ __('admin.general_settings') }}
                  </a>
              </li><!-- /end list -->

              <div class="collapse w-100 @if (request()->is('panel/admin/settings') || request()->is('panel/admin/settings/limits')) show @endif ps-3" id="settings">
                <li>
                <a class="nav-link text-truncate w-100 @if (request()->is('panel/admin/settings')) text-white @endif" href="{{ url('panel/admin/settings') }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ trans('admin.general') }}
                  </a>
                </li>
                <li>
                <a class="nav-link text-truncate @if (request()->is('panel/admin/settings/limits')) text-white @endif" href="{{ url('panel/admin/settings/limits') }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ trans('admin.limits') }}
                  </a>
                </li>
              </div><!-- /end collapse settings -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/theme') }}" class="nav-link text-truncate @if (request()->is('panel/admin/theme')) active @endif">
                      <i class="bi-brush me-2"></i> {{ __('misc.theme') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/gallery') }}" class="nav-link text-truncate @if (request()->is('panel/admin/gallery')) active @endif">
                      <i class="bi-image me-2"></i>
                       {{ __('misc.gallery') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/blog') }}" class="nav-link text-truncate @if (request()->is('panel/admin/blog')) active @endif">
                      <i class="bi-pencil me-2"></i>
                       {{ __('misc.blog') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/campaigns') }}" class="nav-link text-truncate @if (request()->is('panel/admin/campaigns')) active @endif">
                      <i class="bi-megaphone me-2"></i>
                       {{ __('misc.campaigns') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/donations') }}" class="nav-link text-truncate @if (request()->is('panel/admin/donations')) active @endif">
                      <i class="bi-cash-stack me-2"></i>

                      @if (App\Models\Donations::whereApproved('0')->count() <> 0)
                        <i class="bi-circle-fill small text-warning alert-admin"></i>
                      @endif
                       {{ __('misc.donations') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/languages') }}" class="nav-link text-truncate @if (request()->is('panel/admin/languages')) active @endif">
                      <i class="bi-translate me-2"></i> {{ __('admin.languages') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/withdrawals') }}" class="nav-link text-truncate @if (request()->is('panel/admin/withdrawals')) active @endif">
                      <i class="bi-bank me-2"></i>

                      @if (App\Models\Withdrawals::whereStatus('pending')->count() <> 0)
                        <i class="bi-circle-fill small text-warning alert-admin"></i>
                      @endif

                      {{ __('misc.withdrawals') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/categories') }}" class="nav-link text-truncate @if (request()->is('panel/admin/categories')) active @endif">
                      <i class="bi-list-stars me-2"></i> {{ __('admin.categories') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/members') }}" class="nav-link text-truncate @if (request()->is('panel/admin/members')) active @endif">
                      <i class="bi-people me-2"></i> {{ __('admin.members') }}
                  </a>
              </li><!-- /end list -->


              <li class="nav-item">
                  <a href="{{ url('panel/admin/campaigns/reported') }}" class="nav-link text-truncate @if (request()->is('panel/admin/campaigns/reported')) active @endif">
                      <i class="bi-exclamation-triangle me-2"></i>

                      @if (App\Models\CampaignsReported::count() <> 0)
                        <i class="bi-circle-fill small text-warning alert-admin"></i>
                      @endif

                      {{ __('misc.campaigns_reported') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/pages') }}" class="nav-link text-truncate @if (request()->is('panel/admin/pages')) active @endif">
                      <i class="bi-file-earmark-text me-2"></i> {{ __('admin.pages') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="#payments" data-bs-toggle="collapse" class="nav-link text-truncate dropdown-toggle @if (request()->is('panel/admin/payments') || request()->is('panel/admin/payments/*')) active @endif" @if (request()->is('panel/admin/payments') || request()->is('panel/admin/payments/*')) aria-expanded="true" @endif>
                      <i class="bi-credit-card me-2"></i> {{ __('misc.payment_settings') }}
                  </a>
              </li><!-- /end list -->

              <div class="collapse w-100 ps-3 @if (request()->is('panel/admin/payments') || request()->is('panel/admin/payments/*') || request()->is('panel/admin/payment/add')) show @endif" id="payments">
                <li>
                <a class="nav-link text-truncate @if (request()->is('panel/admin/payment/add')) text-white @endif" href="{{ url('panel/admin/payment/add') }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ trans('misc.add_payment') }}
                  </a>
                </li>

                <li>
                <a class="nav-link text-truncate @if (request()->is('panel/admin/payments')) text-white @endif" href="{{ url('panel/admin/payments') }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ trans('admin.general') }}
                  </a>
                </li>

                @foreach (App\Models\PaymentGateways::all() as $key)
                <li>
                <a class="nav-link text-truncate @if (request()->is('panel/admin/payments/'.$key->id.'')) text-white @endif" href="{{ url('panel/admin/payments', $key->id) }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ $key->name }}
                  </a>
                </li>
              @endforeach
              </div><!-- /end collapse settings -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/profiles-social') }}" class="nav-link text-truncate @if (request()->is('panel/admin/profiles-social')) active @endif">
                      <i class="bi-share me-2"></i> {{ __('admin.profiles_social') }}
                  </a>
              </li><!-- /end list -->

              <li class="nav-item">
                  <a href="{{ url('panel/admin/pwa') }}" class="nav-link text-truncate @if (request()->is('panel/admin/pwa')) active @endif">
                      <i class="bi-phone me-2"></i> PWA
                  </a>
              </li><!-- /end list -->

          </ul>
      </div>
  </div>

  <header class="py-3 mb-3 shadow-custom bg-white">

    <div class="container-fluid d-grid gap-3 px-4 justify-content-end position-relative">

      <div class="d-flex align-items-center">

        <a class="text-dark ms-2 animate-up-2 me-4" href="{{ url('/') }}">
        {{ trans('admin.view_site') }} <i class="bi-arrow-up-right"></i>
        </a>

        <div class="flex-shrink-0 dropdown">
          <a href="#" class="d-block link-dark text-decoration-none" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
           <img src="{{ asset('public/avatar').'/'.auth()->user()->avatar }}" width="32" height="32" class="rounded-circle">
          </a>
            @include('includes.dropdown-user')
        </div>

        <a class="ms-4 toggle-menu d-block d-lg-none text-dark fs-3 position-absolute start-0" data-bs-toggle="offcanvas" data-bs-target="#sidebar-nav" href="#">
            <i class="bi-list"></i>
            </a>
      </div>
    </div>
  </header>

  <div class="container-fluid">
      <div class="row">
          <div class="col min-vh-100 admin-container p-4">
              @yield('content')
          </div>
      </div>
  </div>

  <footer class="admin-footer px-4 py-3 bg-white shadow-custom">
    &copy; {{ $settings->title }} v{{$settings->version}} - {{ date('Y') }}
  </footer>

</main>

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ asset('public/js/core.min.js') }}?v={{$settings->version}}"></script>
    <script src="{{ asset('public/css/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('public/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/js/admin-functions.js') }}?v={{$settings->version}}"></script>

    @yield('javascript')

     </body>
</html>
