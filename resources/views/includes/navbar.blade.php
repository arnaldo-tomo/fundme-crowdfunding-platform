<div class="btn-block text-center showBanner py-2" style="display:none;">
  <i class="fa fa-cookie-bite"></i> {{trans('misc.cookies_text')}}
  @if($settings->link_privacy != '')
    <a href="{{$settings->link_privacy}}" class="me-2" target="_blank"><strong>{{ trans('misc.learn_more') }}</strong></a>
  @endif
  <button class="btn btn-sm btn-primary" id="close-banner">{{trans('misc.got_it')}}</button>
</div>

@if (auth()->check() && auth()->user()->status != 'active' )
<div class="btn-block mt-0 py-2 position-fixed text-center bg-danger" style="bottom:0; z-index:99">{{trans('misc.confirm_email')}} <em>{{auth()->user()->email}}</em></div>
@endif

<div id="search">
<a href="javascript:;" class="close text-decoration-none">×</a>
<form autocomplete="off" action="{{ url('search') }}" method="get">
    <input type="text" value="" name="q" id="btnItems" placeholder="{{trans('misc.search_query')}}" />
    <button type="submit" class="btn btn-lg no-shadow btn-trans custom-rounded d-none btn_search"  id="btnSearch">{{trans('misc.search')}}</button>
</form>
</div>

<header>
  <nav class="navbar navbar-expand-lg navbar-inverse fixed-top py-3 @if(request()->path() == '/') scroll @else shadow-sm bg-dark @endif">
    <div class="container d-flex fw-bold">
      <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('public/img/logo.png') }}" style="max-width: 100px;"class="align-baseline" alt="{{$settings->title}}" />
      </a>
      <ul class="navbar-nav ms-auto d-lg-none">
        <li class="nav-item">
          <a class="nav-link search" href="#search"><i class="fa fa-search"></i></a>
        </li>
      </ul>

      <button class="navbar-toggler text-white" type="button"data-bs-toggle="collapse"data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">

      <div class="d-lg-none text-right">
        <a href="javascript:;" class="close-menu"data-bs-toggle="collapse"data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"><i class="fa fa-times"></i></a>
      </div>

        <ul class="navbar-nav me-auto">

        @foreach (App\Helper::pages('show_navbar') as $_page)
          <li class="nav-item @if(Request::path() == "page/$_page->slug")active @endif">
            <a class="nav-link" href="{{ url('p',$_page->slug) }}">{{ $_page->title }}</a>
          </li>
          @endforeach

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" role="button" href="#" id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
              {{ trans('misc.explore') }}
            </a>
            <div class="dropdown-menu dropdown-menu-macos dd-menu" aria-labelledby="dropdownMenu1">
              <a class="dropdown-item @if(Request::path() == "campaigns/latest")active @endif" href="{{ url('campaigns/latest') }}">{{ trans('misc.latest') }}</a>
              <a class="dropdown-item @if(Request::path() == "campaigns/featured")active @endif" href="{{ url('campaigns/featured') }}">{{ trans('misc.featured') }}</a>
              <a class="dropdown-item @if(Request::path() == "campaigns/popular")active @endif" href="{{ url('campaigns/popular') }}">{{ trans('misc.popular') }}</a>
                <a class="dropdown-item @if(Request::path() == "campaigns/ended")active @endif" href="{{ url('campaigns/ended') }}">{{ trans('misc.ended') }}</a>
                @if (App\Models\Gallery::count() != 0)
                <a class="dropdown-item @if(Request::path() == "gallery")active @endif" href="{{ url('gallery') }}">{{ trans('misc.gallery') }}</a>
                @endif
            </div>
          </li>
          @if (App\Models\Categories::count() != 0)
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdownCategories"data-bs-toggle="dropdown" aria-expanded="false">
              {{trans('misc.categories')}}
            </a>
            <div class="dropdown-menu dropdown-menu-macos dd-menu" aria-labelledby="dropdownCategories">
              @foreach (App\Models\Categories::where('mode','on')->orderBy('name')->take(6)->get() as $category)
              <a class="dropdown-item @if(Request::path() == "category/$category->slug")active @endif" href="{{ url('category', $category->slug) }}">
                {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
              </a>
              @endforeach

              @if (App\Models\Categories::count() > 6)
                <a class="dropdown-item" href="{{ url('categories') }}">{{ trans('misc.view_all') }} <i class="bi-arrow-right"></i></a>
              @endif
            </div>
          </li>
          @endif
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link search" href="#search"><i class="fa fa-search"></i></a>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">

          @auth
            <li class="nav-item me-1 dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="dropdown03"data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('public/avatar').'/'.auth()->user()->avatar }}" alt="User" class="rounded-circle avatarUser" width="25" height="25" />
                @php $name = explode(' ', auth()->user()->name); @endphp
                <span class="d-lg-none">{{ trans('users.my_profile') }}</span>
              </a>
              @include('includes.dropdown-user')
            </li>
          @else

          <li class="nav-item me-1">
            <a class="nav-link" href="{{ url('login') }}">{{ trans('auth.login') }}</a>
          </li>
          @endauth

          <li class="nav-item">
            <a class="nav-link btn btn-primary pr-3 pl-3 btn-create no-hover" href="{{ url('create/campaign') }}">{{ trans('misc.create_campaign') }}</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
