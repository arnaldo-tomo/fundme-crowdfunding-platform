<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@include('includes.css_general')

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Raleway:100,600' rel='stylesheet' type='text/css'>

	@if($settings->color_default <> '')
	<style>
	.btn-main,
	.btn-main:hover,
	.btn-main:active,
	.btn-main:focus {
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
	$percentage = number_format($response->donations()->sum('donation') / $response->goal * 100, 2, '.', ',');

	// Deadline
	$timeNow = strtotime(Carbon\Carbon::now());

	if( $response->deadline != '' ) {
	    $deadline = strtotime($response->deadline);

		$date = strtotime($response->deadline);
	    $remaining = $date - $timeNow;

		$days_remaining = floor($remaining / 86400);
	}

?>
<div class="thumbnail padding-top-zero">

				<a class="position-relative btn-block img-grid" href="{{$url}}" target="_blank">

					@if( $response->featured == 1 )
					<span class="box-featured" title="{{trans('misc.featured_campaign')}}"><i class="fa fa-trophy"></i></span>
					@endif

					<img title="{{ e($response->title) }}" src="{{ asset('public/campaigns/small').'/'.$response->small_image }}" class="image-url img-responsive btn-block radius-image" />
				</a>

    			<div class="caption">
    				<h1 class="title-campaigns font-default">
    					<a title="{{ e($response->title) }}" class="item-link" href="{{$url}}" target="_blank">
    					 {{ e($response->title) }}
    					</a>
    				</h1>
    				<p class="desc-campaigns text-overflow">
    					 {{ str_limit(strip_tags($response->description),80,'...') }}
    				</p>

   <div class="btn-group btn-block">

	@if( isset( $deadline ) && $deadline > $timeNow && $response->finalized == 0 )
		<a href="{{url('donate/'.$response->id)}}" target="_blank" class="btn btn-main btn-lg btn-block btn-donate-embed custom-rounded">
			{{trans('misc.donate_now')}}
			</a>

			@elseif( !isset( $deadline ) && $response->finalized == 0 )

				<a href="{{url('donate/'.$response->id)}}" target="_blank" class="btn btn-main btn-lg btn-block btn-donate-embed custom-rounded">
					{{trans('misc.donate_now')}}
					</a>

			@else

			<div class="padding-15 btnDisabled text-center custom-rounded" role="alert">
			<i class="fa fa-lock myicon-right"></i> {{trans('misc.campaign_ended')}}
		</div>

		@endif

		</div>

    				<p class="desc-campaigns">
    						<span class="stats-campaigns">
    							<span class="pull-left">
    								<strong>{{App\Helper::amountFormat($response->donations()->sum('donation'))}}</strong>
    								{{trans('misc.raised')}}
    								</span>
    							<span class="pull-right"><strong>{{$percentage }}%</strong></span>
    							</span>

	    					<span class="progress">
	    						<span class="percentage" style="width: {{$percentage }}%" aria-valuemin="0" aria-valuemax="100" role="progressbar"></span>
	    					</span>
    				</p>

    				<h6 class="margin-bottom-zero">
    					<em><strong>{{ trans('misc.goal') }} {{App\Helper::amountFormat($response->goal)}}</strong></em>
    				</h6>
    				<hr />
    				<div class="btn-group btn-block text-center">
    					<img src="{{ asset('public/img/watermark.png') }}" />
    				</div>

    			</div><!-- /caption -->
    		  </div><!-- /thumbnail -->
    	   </div><!-- /col-sm-6 col-md-4 -->

    	 </div><!-- /row -->
   </div><!-- /container -->

		@include('includes.javascript_general')
</body>
</html>
