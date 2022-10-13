<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@include('includes.css_general')

	@if($settings->color_default <> '')
	<style>
	.btn-primary:not(:disabled):not(.disabled).active,
	.btn-primary:not(:disabled):not(.disabled):active,
	.show>.btn-primary.dropdown-toggle,
	.btn-primary:hover,
	.btn-primary:focus,
	.btn-primary:active,
	.btn-primary,
	.btn-primary.disabled,
	.btn-primary:disabled {
	    background-color: {{$settings->color_default}};
	    border-color: {{$settings->color_default}};
	}
	</style>
@endif
</head>
<body>
	<div class="container">
	<div class="row">

      <div class="col-xs-12 col-sm-6 col-md-3 col-thumb">
 <?php

	if( str_slug( $response->title ) == '' ) {
		$slugUrl  = '';
	} else {
		$slugUrl  = '/'.str_slug( $response->title );
	}

	$url = url('campaign',$response->id).$slugUrl;
	$percentage = number_format($response->donations()->sum('donation') / $response->goal * 100, 2, '.', '');

	// Deadline
	$timeNow = strtotime(Carbon\Carbon::now());

	if( $response->deadline != '' ) {
	    $deadline = strtotime($response->deadline);

		$date = strtotime($response->deadline);
	    $remaining = $date - $timeNow;

		$days_remaining = floor($remaining / 86400);
	}

?>
<div class="card mb-4 shadow-sm">
	<a href="{{ $url }}" class="p-relative">
		@if ($response->featured == 1)
		<div class="ribbon-1" title="{{ trans('misc.featured_campaign') }}"><i class="fa fa-award"></i></div>
	@endif
		<img class="card-img-top" src="{{ asset('public/campaigns/small').'/'.$response->small_image }}" alt="{{ $response->title }}">
	</a>
	<div class="card-body">
		<h5 class="card-title text-truncate">
			<a href="{{ $url }}" class="text-dark">
				{{ $response->title }}
			</a>
		</h5>
		<div class="progress progress-xs mb-4">
			<div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%">
			</div>
		</div>
		<p class="card-text text-truncate">{{ str_limit(strip_tags($response->description), 80, '...') }}</p>
		<div class="d-flex justify-content-between align-items-center">
			<strong>{{ App\Helper::amountFormat($response->donations()->sum('donation')) }}</strong>
			<small class="font-weight-bold">{{ $percentage }}%</small>
		</div>
		<small class="text-muted">{{ trans('misc.raised_of') }} {{App\Helper::amountFormat($response->goal)}}</small>

		<div class="btn-block">
		@if (isset($deadline) && $deadline > $timeNow && $response->finalized == 0)
			<a href="{{url('donate/'.$response->id)}}" target="_blank" class="btn btn-main btn-primary no-hover btn-lg btn-block custom-rounded">
				{{trans('misc.donate_now')}}
				</a>

				@elseif (! isset($deadline) && $response->finalized == 0)

					<a href="{{url('donate/'.$response->id)}}" target="_blank" class="btn btn-main btn-primary no-hover btn-lg btn-block custom-rounded">
						{{trans('misc.donate_now')}}
						</a>
				@else

			<div class="padding-15 btnDisabled text-center custom-rounded" role="alert">
				<i class="fa fa-lock mr-1"></i> {{trans('misc.campaign_ended')}}
			</div>

			@endif
	</div>

		<hr>
		<div class="d-flex justify-content-between align-items-center">
			<span class="text-truncate">
				<img src="{{ asset('public/avatar').'/'.$response->user()->avatar }}" width="25" height="25" class="rounded-circle avatar-campaign">
					<small>{{ trans('misc.by') }} <strong>{{ $response->user()->name }}</strong></small>
				</span>
			</span>

			@if (isset( $deadline ) && $response->finalized == 0)

				@if ($days_remaining > 0 )
					<small class="text-truncate"><i class="far fa-clock text-success"></i> {{ $days_remaining }} {{ trans('misc.days_left') }}</small>
				@elseif ($days_remaining == 0 )
					<small class="text-truncate"><i class="far fa-clock text-warning"></i> {{trans('misc.last_day')}}</small>
				@else
					<small class="text-truncate"><i class="far fa-clock text-danger"></i> {{ trans('misc.no_time_anymore').$days_remaining }}</small>
				@endif

			@endif

			@if ($response->finalized == 1)
				<small class="text-truncate"><i class="far fa-clock text-danger"></i> {{ trans('misc.campaign_ended') }}</small>
				@endif

				@if (!isset( $deadline) && $response->finalized == 0)
					<small class="text-truncate"><i class="fa fa-infinity text-success"></i> {{ trans('misc.no_deadline') }}</small>
					@endif
		</div>
	</div>
</div>

    	   </div><!-- /col-sm-6 col-md-4 -->

    	 </div><!-- /row -->
   </div><!-- /container -->

		@include('includes.javascript_general')
</body>
</html>
