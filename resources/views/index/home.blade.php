@extends('app')

@section('content')
  <!-- CAROUSEL -->
  <div id="myCarousel" class="carousel slide carousel-fade mb-0" data-bs-ride="carousel">
    <ol class="carousel-indicators">
      <li data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></li>
      <li data-bs-target="#myCarousel" data-bs-slide-to="1"></li>
      <li data-bs-target="#myCarousel" data-bs-slide-to="2"></li>

    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active carousel-cover">
        <div class="container">
          <div class="carousel-caption text-left">
            <h1 class="vivify driveInLeft delay-500 display-4">{{ trans('slider.slider_1_title') }}</h1>
            <p class="vivify fadeInBottom delay-600">{{ trans('slider.slider_1_subtitle') }}</p>
            <p class="vivify fadeInRight"><a class="btn btn-lg btn-primary" href="{{ url('campaigns/latest') }}" role="button">{{ trans('misc.explore_campaigns') }}</a></p>
          </div>
        </div>
      </div>
      <div class="carousel-item carousel-cover">
        <div class="container">
          <div class="carousel-caption">
            <h1 class="vivify fadeInTop delay-600 display-4">{{ trans('slider.slider_2_title') }}</h1>
            <p class="vivify fadeInBottom delay-600">{{ trans('slider.slider_2_subtitle') }}</p>
            <p class="vivify fadeInLeft"><a class="btn btn-lg btn-primary" href="{{ url('campaigns/latest') }}" role="button">{{ trans('misc.explore_campaigns') }}</a></p>
          </div>
        </div>
      </div>
      <div class="carousel-item carousel-cover">
        <div class="container">
          <div class="carousel-caption text-left">
            <h1 class="vivify fadeInBottom delay-600 display-4">{{ trans('slider.slider_3_title') }}</h1>
            <p class="vivify fadeInTop delay-600">{{ trans('slider.slider_3_subtitle') }}</p>
            <p class="vivify fadeInRight"><a class="btn btn-lg btn-primary" href="{{ url('campaigns/latest') }}" role="button">{{ trans('misc.explore_campaigns') }}</a></p>
          </div>
        </div>
      </div>
    </div>
    <a class="carousel-control-prev" href="#myCarousel" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <!-- ./ CAROUSEL -->

@if($categories->count() != 0)
  <div class="section py-5">
    <div class="btn-block text-center mb-5">
      <h1>{{trans('misc.browse_by_category')}}</h1>
      <p>
        {{trans('misc.categories_subtitle')}}
      </p>
      </div>

      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="owl-carousel owl-theme">
            @foreach(App\Models\Categories::where('mode','on')->orderBy('name')->get() as $category)
                @include('includes.categories-listing')
              @endforeach
            </div>
        </div>
      </div>
    </div>
  </div>
@endif

@if($data->total() != 0)

@if($dataFeatured->total() != 0)
  <!-- Featured Campaigns -->
  <div class="section py-5">
    <div class="btn-block text-center mb-5">
      <span class="display-4 text-warning"><i class="fa fa-award"></i></span>
      <h1>{{ trans('misc.featured_campaign') }}</h1>
      <p>
        {{ trans('misc.featured_campaigns_subtitle') }}
      </p>
    </div>
    <div class="container">
      <div class="row">
        @foreach ($dataFeatured as $key)
         	@include('includes.list-campaigns')
        @endforeach
      </div>
    </div>

    @if ($dataFeatured->total() > 3)
    <div class="btn-block text-center py-3">
      <a href="{{ url('campaigns/featured') }}" class="btn btn-primary btn-main p-2 px-5 btn-lg rounded">
              {{ trans('misc.view_all') }} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small>
            </a>
    </div>
    @endif
  </div>
@endif

    <!-- New Campaigns
    ========================= -->
    <div class="section py-5">
      <div class="btn-block text-center mb-5">
        <h1>{{trans('misc.explore_new_campaign')}}</h1>
        <p>
          {{trans('misc.recent_campaigns')}}
        </p>
      </div>
      <div class="container">
        <div class="row">
          @foreach ($data as $key)
            @include('includes.list-campaigns')
          @endforeach
        </div>
      </div>

      @if ($data->total() > $settings->result_request)
      <div class="btn-block text-center py-3">
      <a href="{{ url('campaigns/latest') }}" class="btn btn-primary btn-main p-2 px-5 btn-lg rounded">
              {{ trans('misc.view_all') }} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small>
            </a>
      </div>
      @endif
    </div>

    <!-- Counter -->
    <div class="py-5 bg-success text-white">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="d-flex py-3 my-3 my-lg-0 justify-content-center">
              <span class="mr-3 display-4"><i class="fa fa-users align-baseline"></i></span>
              <div>
                <h3 class="mb-0">{!! App\Helper::formatNumbersStats(App\Models\User::count()) !!}</h3>
                <h5 class="font-weight-light text-uppercase">{{trans('misc.members')}}</h5>
              </div>
            </div>

          </div>
          <div class="col-md-4">
            <div class="d-flex py-3 my-3 my-lg-0 justify-content-center">
              <span class="mr-3 display-4"><i class="fa fa-bullhorn align-baseline"></i></span>
              <div>
                <h3 class="mb-0">{!! App\Helper::formatNumbersStats(App\Models\Campaigns::where('status','active')->count()) !!}</h3>
                <h5 class="font-weight-light text-uppercase">{{trans('misc.campaigns')}}</h5>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex py-3 my-3 my-lg-0 justify-content-center">
              <span class="mr-3 display-4"><i class="fa fa-hand-holding-usd align-baseline"></i></span>
              <div>
                <h3 class="mb-0">@if($settings->currency_position == 'left') {{ $settings->currency_symbol }}@endif{!! App\Helper::formatNumbersStats(App\Models\Donations::where('approved','1')->sum('donation')) !!}@if($settings->currency_position == 'right'){{ $settings->currency_symbol }} @endif</h3>
                <h5 class="font-weight-light text-uppercase">{{trans('misc.funds_raised')}}</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

	@else
	<div class="py-5 mb-5">
		<div class="text-center">
			<div class="btn-block">
	    			<i class="fa fa-bullhorn display-4"></i>
	    		</div>

	    		<h3 class="my-3">
	    	{{ trans('misc.no_campaigns') }}
	    	</h3>
        <a class="btn btn-primary p-2 px-5 btn-lg" href="{{url('create/campaign')}}" role="button">{{trans('misc.create_campaign')}}</a>
		</div>
	</div>
	@endif

  <div class="jumbotron m-0 text-white text-center position-relative rounded-0">
    <div class="parallax-cover bg-cover"></div>
    <div class="container position-relative">
      <h1>{{trans('misc.title_cover_bottom')}}</h1>
      <p>{{$settings->welcome_subtitle}}</p>
      <p><a class="btn btn-primary p-2 px-5 btn-lg" href="{{url('create/campaign')}}" role="button">{{trans('misc.create_campaign')}}</a></p>
    </div>
  </div>
@endsection

@section('javascript')
		<script type="text/javascript">

    pagination('{{ url("ajax/campaigns") }}?page=', '{{trans('misc.error')}}');

   @if (session('success_verify'))
  		swal({
  			title: "{{ trans('misc.welcome') }}",
  			text: "{{ trans('users.account_validated') }}",
  			type: "success",
  			confirmButtonText: "{{ trans('users.ok') }}"
  			});
  		 @endif

  		 @if (session('error_verify'))
  		swal({
  			title: "{{ trans('misc.error_oops') }}",
  			text: "{{ trans('users.code_not_valid') }}",
  			type: "error",
  			confirmButtonText: "{{ trans('users.ok') }}"
  			});
  		 @endif
</script>
@endsection
