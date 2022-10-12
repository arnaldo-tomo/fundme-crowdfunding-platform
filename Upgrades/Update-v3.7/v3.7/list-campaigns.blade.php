<div class="col-xs-12 col-sm-6 col-md-3 col-thumb">
 <?php

   $settings = App\Models\AdminSettings::first();

	if( str_slug( $key->title ) == '' ) {
		$slugUrl  = '';
	} else {
		$slugUrl  = '/'.str_slug( $key->title );
	}

	$url = url('campaign',$key->id).$slugUrl;
	$percentage = number_format($key->donations()->sum('donation') / $key->goal * 100, 2, '.', '');

  // Deadline
	$timeNow = strtotime(Carbon\Carbon::now());

	if( $key->deadline != '' ) {
	    $deadline = strtotime($key->deadline);

		$date = strtotime($key->deadline);
	    $remaining = $date - $timeNow;

		$days_remaining = floor($remaining / 86400);
	}

?>
<div class="thumbnail padding-top-zero">

				<a class="position-relative btn-block img-grid" href="{{$url}}">

				@if( $key->featured == 1 )
					<span class="box-featured" title="{{trans('misc.featured_campaign')}}"><i class="fa fa-trophy"></i></span>
					@endif

					<img title="{{ e($key->title) }}" src="{{ asset('public/campaigns/small').'/'.$key->small_image }}" class="image-url img-responsive btn-block radius-image" />
				</a>

    			<div class="caption">
    				<h1 class="title-campaigns font-default">
    					<a title="{{ e($key->title) }}" class="item-link" href="{{$url}}">
    					 {{ e($key->title) }}
    					</a>
    				</h1>

    				<p class="desc-campaigns">
    					@if( isset($key->user()->id) )
    					<img src="{{ asset('public/avatar').'/'.$key->user()->avatar }}" width="20" height="20" class="img-circle avatar-campaign" /> {{ $key->user()->name}}
    					@else
    					<img src="{{ asset('public/avatar/default.jpg') }}" width="20" height="20" class="img-circle avatar-campaign" /> {{ trans('misc.user_not_available') }}
    					@endif
    				</p>

    				<p class="desc-campaigns text-overflow">
    					 {{ str_limit(strip_tags($key->description),80,'...') }}
    				</p>

    				<p class="desc-campaigns">
              <span class="stats-campaigns margin-bottom-zero">

                <em>
                  @if( isset( $deadline ) && $key->finalized == 0 )

                    @if( $days_remaining > 0 )
                    <strong>{{$days_remaining}}</strong> {{trans('misc.days_left')}}
                    @elseif( $days_remaining == 0 )
                      <strong>{{trans('misc.last_day')}}</strong>
                    @else
                      <strong class="text-danger">{{trans('misc.no_time_anymore').$days_remaining}}</strong>
                    @endif

                  @endif

                  @if( $key->finalized == 1 )
                    <strong class="text-danger">{{trans('misc.campaign_ended')}}</strong>
                    @endif

                    @if( !isset( $deadline ) && $key->finalized == 0 )
                      <strong>{{trans('misc.no_deadline')}}</strong>
                      @endif
                </em>

              </span>

    						<span class="stats-campaigns">
    							<span class="pull-left">
    								<strong>{{App\Helper::amountFormat($key->donations()->sum('donation'))}}</strong>
    								{{trans('misc.raised')}}
    								</span>
    							<span class="pull-right"><strong>{{$percentage }}%</strong></span>
    							</span>

	    					<span class="progress">
	    						<span class="percentage" style="width: {{$percentage }}%" aria-valuemin="0" aria-valuemax="100" role="progressbar"></span>
	    					</span>
    				</p>

            <h6 class="margin-bottom-zero">
    					<em><strong>{{ trans('misc.goal') }} {{App\Helper::amountFormat($key->goal)}}</strong></em>
    				</h6>

    			</div><!-- /caption -->
    		  </div><!-- /thumbnail -->
    	   </div><!-- /col-sm-6 col-md-4 -->
