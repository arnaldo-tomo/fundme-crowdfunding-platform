<!-- FOOTER -->
<div class="py-5 bg-dark-2 text-light">
<footer class="container">
  <div class="row">
    <div class="col-md-3">
      <h5>{{trans('misc.about')}}</h5>
      <ul class="list-unstyled">
        @foreach (App\Models\Pages::all() as $page)
        <li><a class="link-footer" href="{{ url('/page',$page->slug) }}">{{ $page->title }}</a></li>
        @endforeach
        <li><a class="link-footer" href="{{ url('blog') }}">{{ trans('misc.blog') }}</a></li><li>
      </ul>
    </div>

    @if (App\Models\Categories::count() != 0)
    <div class="col-md-3">
      <h5>{{trans('misc.categories')}}</h5>
      <ul class="list-unstyled">
        @foreach (App\Models\Categories::where('mode','on')->orderBy('name')->take(5)->get() as $category)
        <li><a class="link-footer" href="{{ url('category', $category->slug) }}">{{ $category->name }}</a></li>
        @endforeach

        @if (App\Models\Categories::count() > 5)
        <li><a class="link-footer" href="{{ url('categories') }}">{{ trans('misc.view_all') }} <i class="fa fa-long-arrow-alt-right"></i></a></li>
      @endif
      </ul>
    </div>
  @endif

    <div class="col-md-3">
      <h5>{{trans('misc.links')}}</h5>
      <ul class="list-unstyled">
        <li><a class="link-footer" href="{{ url('campaigns/latest') }}">{{ trans('misc.explore') }}</a></li><li>
          @if (App\Models\Gallery::count() != 0)
          <li><a class="link-footer" href="{{ url('gallery') }}">{{ trans('misc.gallery') }}</a></li><li>
          @endif
        <li><a class="link-footer" href="{{ url('create/campaign') }}">{{ trans('misc.create_campaign') }}</a></li><li>
          <li><a class="link-footer" href="{{ url('campaigns/featured') }}">{{ trans('misc.featured_campaign') }}</a></li><li>
            @guest
              <li><a class="link-footer" href="{{ url('login') }}">{{ trans('auth.login') }}</a></li><li>
              <li><a class="link-footer" href="{{ url('register') }}">{{ trans('auth.sign_up') }}</a></li><li>
              @else
                <li><a class="link-footer" href="{{ url('account') }}">{{ trans('users.account_settings') }}</a></li><li>
                <li><a class="link-footer" href="{{ url('logout') }}">{{ trans('users.logout') }}</a></li><li>
            @endguest

      </ul>
    </div>

    <div class="col-md-3">
      <a href="{{ url('/') }}">
        <img src="{{ asset('public/img/watermark.png') }}">
      </a>
      <p class="text-muted">{{ $settings->description }}</p>
    </div>
  </div>
</footer>
</div>

<footer class="py-2 bg-dark-3 text-muted">
  <div class="container">
    <div class="row">
      <div class="col-md-6 copyright">
        &copy; <?php echo date('Y'); ?> {{ $settings->title }}
      </div>
      <div class="col-md-6 text-right social-links">
        <span class="mr-2">{{ trans('misc.follow_us') }}</span>
        <ul class="list-inline float-right list-social">

          @if( $settings->twitter != '' )
            <li class="list-inline-item"><a href="{{$settings->twitter}}" class="ico-social"><i class="fab fa-twitter"></i></a></li>
          @endif

        @if( $settings->facebook != '' )
          <li class="list-inline-item"><a href="{{$settings->facebook}}" class="ico-social"><i class="fab fa-facebook"></i></a></li>
        @endif

        @if( $settings->instagram != '' )
          <li class="list-inline-item"><a href="{{$settings->instagram}}" class="ico-social"><i class="fab fa-instagram"></i></a></li>
        @endif
        </ul>
      </div>
    </div>
  </div>
</footer>
