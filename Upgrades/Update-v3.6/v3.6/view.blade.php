<?php

	$settings = App\Models\AdminSettings::first();

		$percentage = number_format($response->donations()->sum('donation') / $response->goal * 100, 2, '.', ',');

	/*if( $percentage > 100 ) {
		$percentage = 100;
	} else {
		$percentage = $percentage;
	}*/

	// All Donations
	$donations = $response->donations()->orderBy('id','desc')->paginate(10);

	// Updates
	$updates = $response->updates()->orderBy('id','desc')->paginate(1);

	if( str_slug( $response->title ) == '' ) {
		$slug_url  = '';
	} else {
		$slug_url  = '/'.str_slug( $response->title );
	}

	if( Auth::check() ) {
		// LIKE ACTIVE
	   $likeActive = App\Models\Like::where( 'user_id', Auth::user()->id )
	   ->where('campaigns_id',$response->id)
	   ->where('status','1')
	   ->first();

       if( $likeActive ) {
       	  $textLike   = trans('misc.unlike');
		  $icoLike    = 'fa fa-heart';
		  $statusLike = 'active';
       } else {
       		$textLike   = trans('misc.like');
		    $icoLike    = 'fa fa-heart-o';
			$statusLike = '';
       }
	}

	// Deadline
	$timeNow = strtotime(Carbon\Carbon::now());

	if( $response->deadline != '' ) {
	    $deadline = strtotime($response->deadline);

		$date = strtotime($response->deadline);
	    $remaining = $date - $timeNow;

		$days_remaining = floor($remaining / 86400);
	}

?>
@extends('app')

@section('title'){{ $response->title.' - ' }}@endsection

@section('css')

<meta property="og:type" content="website" />
<meta property="og:image:width" content="{{App\Helper::getWidth('public/campaigns/large/'.$response->large_image)}}"/>
<meta property="og:image:height" content="{{App\Helper::getHeight('public/campaigns/large/'.$response->large_image)}}"/>

<!-- Current locale and alternate locales -->
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="es_ES" />

<!-- Og Meta Tags -->
<link rel="canonical" href="{{url("campaign/$response->id").'/'.str_slug($response->title)}}"/>
<meta property="og:site_name" content="{{$settings->title}}"/>
<meta property="og:url" content="{{url("campaign/$response->id").'/'.str_slug($response->title)}}"/>
<meta property="og:image" content="{{url('public/campaigns/large',$response->large_image)}}"/>

<meta property="og:title" content="{{ $response->title }}"/>
<meta property="og:description" content="{{strip_tags($response->description)}}"/>
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:image" content="{{url('public/campaigns/large',$response->large_image)}}" />
<meta name="twitter:title" content="{{ $response->title }}" />
<meta name="twitter:description" content="{{strip_tags($response->description)}}"/>
@endsection

@section('content')

<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
      </div>
    </div>

<div class="container margin-bottom-40 padding-top-40">

	@if (session()->has('donation_cancel'))
	<div class="alert alert-danger text-center btn-block margin-bottom-20  custom-rounded" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
			<i class="fa fa-remove myicon-right"></i> {{ trans('misc.donation_cancel') }}
		</div>
		@endif

			@if (session('donation_success'))
	<div class="alert alert-success text-center btn-block margin-bottom-20  custom-rounded" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
			<i class="fa fa-check myicon-right"></i> {{ trans('misc.donation_success') }}
		</div>
		@endif


<!-- Col MD -->
<div class="col-md-8 margin-bottom-20">

	<div class="text-center margin-bottom-20">
		<img class="img-responsive img-rounded" style="display: inline-block;" src="{{url('public/campaigns/large',$response->large_image)}}" />
</div>

<h1 class="font-default title-image none-overflow margin-bottom-20">

	 		{{ $response->title }}
		</h1>

		<hr />

		<div class="row margin-bottom-30">
			<div class="col-md-3">
				<a class="btn btn-block btn-facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ url('campaign',$response->id).'/'.str_slug($response->title) }}" target="_blank"><i class="fa fa-facebook myicon-right"></i> {{trans('misc.share')}}</a>
			</div>

			<div class="col-md-2">
				<a class="btn btn-twitter btn-block" href="https://twitter.com/intent/tweet?url={{ url('campaign',$response->id) }}&text={{ e( $response->title ) }}" data-url="{{ url('campaign',$response->id) }}" target="_blank"><i class="fa fa-twitter myicon-right"></i> {{trans('misc.tweet')}}</a>
			</div>

			<div class="col-md-3">
			<a class="btn btn-whatsapp btn-block margin-bottom-5" href="whatsapp://send?text={{url()->current()}}" data-action="share/whatsapp/share"><i class="fa fa-whatsapp myicon-right"></i> Whatsapp</a>
			</div>

		<div class="col-md-2">
				<a class="btn btn-default btn-block margin-bottom-5" href="mailto:?subject={{ e( $response->title ) }}&amp;body={{ url('campaign',$response->id) }}" title="{{trans('misc.share')}} {{trans('auth.email')}}"><i class="fa fa-envelope-o"></i> <span class="d-lg-none">{{trans('auth.email')}}</span></a>
			</div>

			<div class="col-md-2">
				<a class="btn btn-default btn-block" data-toggle="modal" data-target="#embedModal" href="#" title="{{trans('misc.embed')}}"><i class="fa fa-code"></i> <span class="d-lg-none">{{trans('misc.embed')}}</span></a>
			</div>

			<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="embedModal">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header headerModal">
				        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				        <h5 class="modal-title">
				        	{{trans('misc.embed_title')}}
				        </h5>
				     </div><!-- Modal header -->

				     <div class="modal-body">
	 								 <div class="form-group">
										 <div class="btn-block text-center" style="position: relative;">
											 <div style="position: absolute; width: 100%; height: 100%;"></div>
											 <div style='max-width:350px; margin: 0 auto;'><script src='{{url('c',$response->id)}}/widget.js' type='text/javascript'></script></div>
										 </div>

										 <input type="text" class="form-control" value="<div style='width:350px;'><script src='{{url('c',$response->id)}}/widget.js' type='text/javascript'></script></div>" readonly="readonly" id="embedCode">
                    </div><!-- /.form-group-->
				     </div>

						 <div class="modal-footer">
							 <button class="btn btn-default" id="btn_copy_code">{{trans('misc.copy_code')}}</button>
						 </div>

			    </div>
			  </div>
			</div>

		</div>

<ul class="nav nav-tabs nav-justified margin-bottom-20">
  <li class="active"><a href="#desc" aria-controls="home" role="tab" data-toggle="tab" class="font-default"><strong>{{ trans('misc.story') }}</strong></a></li>
	<li><a href="#donations" aria-controls="home" role="tab" data-toggle="tab" class="font-default"><strong>{{ trans('misc.donations') }}</strong> <span class="badge update-ico">{{number_format($response->donations()->count())}}</a></span></li>
	 		<li><a href="#updates" aria-controls="home" role="tab" data-toggle="tab" class="font-default"><strong>{{ trans('misc.updates') }}</strong> <span class="badge update-ico">{{number_format($updates->total())}}</span></a></li>
</ul>

<div class="tab-content">
		@if( $response->description != '' )
		<div role="tabpanel" class="tab-pane fade in active description wordBreak"id="desc">
		{!!$response->description!!}
		</div>
		@endif

		<div role="tabpanel" class="tab-pane fade in description wordBreak"id="donations">

			@if( $response->donations()->count() == 0 )
				<span class="btn-block text-center">
		    			<i class="ion ion-cash ico-no-result"></i>
		    		</span>
				<span class="text-center btn-block">{{trans('misc.no_donations')}}</span>

			@else

				<ul class="list-group" id="listDonations">

				    @foreach( $donations as $donation )

				    <?php
				    $letter = str_slug(mb_substr( $donation->fullname, 0, 1,'UTF8'));

					if( $letter == '' ) {
						$letter = 'N/A';
					}

					if( $donation->anonymous == 1 ) {
						$letter = 'N/A';
						$donation->fullname = trans('misc.anonymous');
					}
				    ?>

				     @include('includes.listing-donations')

				       	 @endforeach

				       	 {{ $donations->links('vendor.pagination.loadmore') }}

					</ul>

			@endif

		</div>

		<div role="tabpanel" class="tab-pane fade description wordBreak margin-top-30" id="updates">

		@if( $updates->total() == 0 )
			<span class="btn-block text-center">
	    			<i class="icon-history ico-no-result"></i>
	    		</span>
			<span class="text-center btn-block">{{ trans('misc.no_results_found') }}</span>

			@else

			@foreach( $updates as $update )
				@include('includes.ajax-updates-campaign')
				@endforeach

				 {{ $updates->links('vendor.pagination.loadmore') }}

			@endif

		</div>
</div>

<div class="btn-block margin-top-20">
	<div class="fb-comments"  data-width="100%" data-href="{{ url('campaign',$response->id).'/'.str_slug($response->title) }}" data-numposts="5"></div>
</div>


 </div><!-- /COL MD -->

 <div class="col-md-4">

@if( Auth::check()
&&  isset($response->user()->id)
&& Auth::user()->id == $response->user()->id
&& !isset( $deadline )
&& $response->finalized == 0
)
 	<div class="row margin-bottom-20">
		<div class="col-md-12">
			<a class="btn btn-lg btn-default btn-block margin-bottom-5" href="{{ url('rewards/campaign',$response->id) }}"><i class="icon-gift"></i> {{trans('misc.add_reward')}}</a>
		</div>
			<div class="col-md-12">
				<a class="btn btn-success btn-block margin-bottom-5" href="{{ url('edit/campaign',$response->id) }}">{{trans('misc.edit_campaign')}}</a>
			</div>
			<div class="col-md-12">
				<a class="btn btn-info btn-block margin-bottom-5" href="{{ url('update/campaign',$response->id) }}">{{trans('misc.post_an_update')}}</a>
			</div>
			@if( $response->donations()->count() == 0 )
			<div class="col-md-12">
				<a href="#" class="btn btn-danger btn-block" id="deleteCampaign" data-url="{{ url('delete/campaign',$response->id) }}">{{trans('misc.delete_campaign')}}</a>
			</div>
			@endif
		</div>

@elseif( Auth::check()
&&  isset($response->user()->id)
&& Auth::user()->id == $response->user()->id
&& isset( $deadline )
&& $deadline > $timeNow
&& $response->finalized == 0
)
		<div class="row margin-bottom-20">
			<div class="col-md-12">
				<a class="btn btn-lg btn-default btn-block margin-bottom-5" href="{{ url('rewards/campaign',$response->id) }}"><i class="icon-gift"></i> {{trans('misc.add_reward')}}</a>
			</div>
			<div class="col-md-12">
				<a class="btn btn-success btn-block margin-bottom-5" href="{{ url('edit/campaign',$response->id) }}">{{trans('misc.edit_campaign')}}</a>
			</div>
			<div class="col-md-12">
				<a class="btn btn-info btn-block margin-bottom-5" href="{{ url('update/campaign',$response->id) }}">{{trans('misc.post_an_update')}}</a>
			</div>
			@if( $response->donations()->count() == 0 )
			<div class="col-md-12">
				<a href="#" class="btn btn-danger btn-block" id="deleteCampaign" data-url="{{ url('delete/campaign',$response->id) }}">{{trans('misc.delete_campaign')}}</a>
			</div>
			@endif
		</div>

		@endif

@if( isset($response->user()->id) )
<!-- Start Panel -->
 	<div class="panel panel-default panel-transparent">
	  <div class="panel-body">
	    <div class="media none-overflow">
			  <div class="media-center margin-bottom-5">
			      <img class="img-circle center-block" src="{{url('public/avatar',$response->user()->avatar)}}" width="85" height="85" >
			  </div>
			  <div class="media-body text-center">

			    	<h4 class="media-heading">
			    		{{$response->user()->name}}

			    	@if( Auth::guest() || Auth::check() && Auth::user()->id != $response->user()->id )
			    		<a href="#" title="{{trans('misc.contact_organizer')}}" data-toggle="modal" data-target="#sendEmail">
			    				<i class="fa fa-envelope myicon-right"></i>
			    		</a>
			    		@endif
			    		</h4>

			    <small class="media-heading text-muted btn-block margin-zero">{{trans('misc.created')}} {{ date($settings->date_format, strtotime($response->date) ) }}</small>
			    @if( $response->location != '' )
			    <small class="media-heading text-muted btn-block"><i class="fa fa-map-marker myicon-right"></i> {{$response->location}}</small>
			    @endif
			    			  </div>
			</div>
	  </div>
	</div><!-- End Panel -->

@if( isset( $deadline ) && $deadline > $timeNow && $response->finalized == 0 )
 	<div class="btn-group btn-block margin-bottom-20 @if( Auth::check() && Auth::user()->id == $response->user()->id ) display-none @endif">
		<a href="{{url('donate/'.$response->id.$slug_url)}}" class="btn btn-main btn-donate btn-lg btn-block custom-rounded">
			{{trans('misc.donate_now')}}
			</a>
		</div>

		@elseif( !isset( $deadline ) && $response->finalized == 0 )
		<div class="btn-group btn-block margin-bottom-20 @if( Auth::check() && Auth::user()->id == $response->user()->id ) display-none @endif">
		<a href="{{url('donate/'.$response->id.$slug_url)}}" class="btn btn-main btn-donate btn-lg btn-block custom-rounded">
			{{trans('misc.donate_now')}}
			</a>
		</div>

		@else

		<div class="alert btnDisabled text-center btn-block margin-bottom-20  custom-rounded" role="alert">
			<i class="fa fa-lock myicon-right"></i> {{trans('misc.campaign_ended')}}
		</div>

		@endif

@else
<div class="alert btnDisabled text-center btn-block margin-bottom-20  custom-rounded" role="alert">
			<i class="fa fa-lock myicon-right"></i> {{trans('misc.campaign_ended')}}
		</div>
@endif


	<div class="panel panel-default">
		<div class="panel-body text-center">
	@if( Auth::check() )
		<a href="#" class="btnLike likeButton {{$statusLike}}" data-id="{{$response->id}}" data-like="{{trans('misc.like')}}" data-unlike="{{trans('misc.unlike')}}">
			<h3 class="btn-block text-center margin-zero"><i class="{{$icoLike}}"></i> <span id="countLikes">{{App\Helper::formatNumber($response->likes()->count())}}</span></h3>
		</a>
		@else

		<a href="{{url('login')}}" class="btnLike">
			<h3 class="btn-block text-center margin-zero"><i class="fa fa-heart-o"></i> <span id="countLikes">{{App\Helper::formatNumber($response->likes()->count())}}</span></h3>
		</a>

		@endif
	   </div>
	</div>

	@if( $response->deadline != '' && $response->finalized == 0 )
		<!-- Start Panel -->
	<div class="panel panel-default">
		<div class="panel-body">
			<h4 class="margin-zero text-center" data-date="{{date('M d, Y', strtotime($response->deadline) )}}">

			@if( $days_remaining > 0 )
				<i class="fa fa-clock-o myicon-right"></i>
				<strong>{{$days_remaining}} {{trans('misc.days_left')}}</strong>
				@elseif( $days_remaining == 0 )

				  <i class="fa fa-clock-o myicon-right"></i>
				<strong>{{trans('misc.last_day')}}</strong>

				@else

				 <i class="fa fa-lock myicon-right"></i>
				<strong>{{trans('misc.no_time_anymore')}}</strong>

				@endif

				</h4>
		</div>
	</div><!-- End Panel -->
	@endif

	@if( $response->featured == 1 )
		<!-- Start Panel -->
	<div class="panel panel-default">
		<div class="panel-body text-center">
			<h4 class="margin-zero font-default"><i class="fa fa-trophy myicon-right featured-icon"></i> <strong>{{trans('misc.featured_campaign')}}</strong></h4>
		</div>
	</div><!-- End Panel -->
	@endif

	<!-- Start Panel -->
	<div class="panel panel-default">
		<div class="panel-body">
			<h3 class="btn-block margin-zero" style="line-height: inherit;">
				<strong class="font-default">{{App\Helper::amountFormat($response->donations()->sum('donation'))}}</strong>
				<small>{{trans('misc.of')}} {{App\Helper::amountFormat($response->goal)}} {{strtolower(trans('misc.goal'))}}</small>
				</h3>

				<span class="progress margin-top-10 margin-bottom-10">
					<span class="percentage" style="width: {{$percentage }}%" aria-valuemin="0" aria-valuemax="100" role="progressbar"></span>
				</span>

				<small class="btn-block margin-bottom-10 text-muted">
					{{$percentage }}% {{trans('misc.raised')}} {{trans('misc.by')}} {{number_format($response->donations()->count())}} {{trans_choice('misc.donation_plural',$response->donations()->count())}}
				</small>

				@if( $response->categories_id != '' )
				@if( isset( $response->category->id ) && $response->category->mode == 'on' )
				<small class="btn-block">
					<a href="{{url('category',$response->category->slug)}}" title="{{$response->category->name}}">
						<i class="icon-tag myicon-right"></i> {{str_limit($response->category->name, 18, '...') }}
						</a>
				</small>
				@endif
				@endif
		</div>
	</div><!-- End Panel -->

	<div class="input-group margin-bottom-20">
        <input type="text" readonly="readonly" id="url_campaign" class="form-control" value="{{URL::current()}}">
        <div class="input-group-btn">
            <button class="btn btn-default" id="btn_campaign_url">{{ trans('misc.copy_link') }}</button>
        </div>
    </div>

@if( isset( $deadline ) && $deadline > $timeNow && $response->finalized == 0
|| !isset( $deadline ) && $response->finalized == 0 )

@if( $response->rewards->count() != 0 )
	@foreach ($response->rewards as $reward)
		<?php

				$pledge = '?pledge='.$reward->id;

				if( str_slug( $response->title ) == '' ) {

						$slugUrl  = $pledge;
					} else {
						$slugUrl  = '/'.str_slug( $response->title ).$pledge;
					}

				$pledgeClaimed = $response->donations()->where('rewards_id',$reward->id)->count();

				if( $pledgeClaimed < $reward->quantity ) {
					$url_campaign = url('donate/'.$response->id.$slugUrl);
				} else {
					$url_campaign = 'javascript:void(0);';
				}

		 ?>
	<!-- Start Panel -->
	<a href="@if( Auth::check() && Auth::user()->id == $response->user_id ) {{url('edit/rewards',$reward->id)}} @else {{$url_campaign}} @endif" class="selectReward">
		<span class="cardSelectRewardBox">
			<span class="cardSelectReward">
				<span class="cardSelectRewardText">
					@if( Auth::check() && Auth::user()->id == $response->user_id )
				 <i class="fa fa-pencil"></i>	{{trans('misc.edit_reward')}}
			 @elseif($pledgeClaimed < $reward->quantity)
					{{trans('misc.select_reward')}}
				@else
					{{trans('misc.sold')}}
				@endif
				</span>
			</span>
		</span>


	<div class="panel panel-default">
 		<div class="panel-body">

			<h3 class="btn-block margin-zero" style="line-height: inherit;">
				<small>{{trans('misc.pledge')}}</small>
				<strong class="font-default">{{App\Helper::amountFormat($reward->amount)}}</strong>
				</h3>
				<h4>{{ $reward->title }}</h4>
				<p class="wordBreak">
					{{ $reward->description }}
				</p>
				<small class="btn-block margin-bottom-10 @if( $pledgeClaimed == $reward->quantity )text-danger @else text-muted @endif">
					{{trans('misc.reward_claimed', ['claim' => $pledgeClaimed, 'total' => $reward->quantity])}} @if( $pledgeClaimed == $reward->quantity ) <span class="label label-danger">{{trans('misc.sold')}}</span> @endif
				</small>

				<small class="btn-block text-muted">
					{{trans('misc.delivery')}}:
				</small>
				<strong>{{ date('F, Y', strtotime($reward->delivery)) }}</strong>
		</div><!-- panel-body -->
	</div><!-- End Panel -->
		</a>
	@endforeach
@endif

@endif {{-- End IF Deadline --}}

	@if( Auth::check() &&  isset($response->user()->id) && Auth::user()->id != $response->user()->id  )
	<div class="btn-block text-center">
		<a href="{{url('report/campaign', $response->id)}}/{{$response->user()->id}}"><i class="icon-warning myicon-right"></i> {{ trans('misc.report') }}</a>
	</div>
	@endif

@if(  isset($response->user()->id) )
	<div class="modal fade" id="sendEmail" tabindex="-1" role="dialog" aria-hidden="true">
	     		<div class="modal-dialog modalContactOrganizer">
	     			<div class="modal-content">
	     				<div class="modal-header headerModal headerModalOverlay position-relative" style="background-image: url('{{url('public/campaigns/large',$response->large_image)}}')">
					        <button type="button" class="close closeLight position-relative" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

					        <span class="btn-block margin-top-15 margin-bottom-15 text-center position-relative">
								      <img class="img-circle" src="{{url('public/avatar/',$response->user()->avatar)}}" width="80" height="80" >
								    </span>

							<h5 class="modal-title text-center font-default position-relative" id="myModalLabel">
					        	{{ $response->user()->name }}
					        	</h5>

					        <h4 class="modal-title text-center font-default position-relative" id="myModalLabel">
					        	{{ trans('misc.contact_organizer') }}
					        	</h4>
					     </div><!-- Modal header -->

					      <div class="modal-body listWrap text-center center-block modalForm">

					    <!-- form start -->
				    <form method="POST" class="margin-bottom-15" action="{{ url('contact/organizer') }}" enctype="multipart/form-data" id="formContactOrganizer">
				    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
				    	<input type="hidden" name="id" value="{{ $response->user()->id }}">

					    <!-- Start Form Group -->
	                    <div class="form-group">
	                    	<input type="text" required="" name="name" class="form-control" placeholder="{{ trans('users.name') }}">
	                    </div><!-- /.form-group-->

	                    <!-- Start Form Group -->
	                    <div class="form-group">
	                    	<input type="text" required="" name="email" class="form-control" placeholder="{{ trans('auth.email') }}">
	                    </div><!-- /.form-group-->

	                    <!-- Start Form Group -->
	                    <div class="form-group">
	                    	<textarea name="message" rows="4" class="form-control" placeholder="{{ trans('misc.message') }}"></textarea>
	                    </div><!-- /.form-group-->

	                    <!-- Alert -->
	                    <div class="alert alert-danger display-none" id="dangerAlert">
								<ul class="list-unstyled text-left" id="showErrors"></ul>
							</div><!-- Alert -->

	              <button type="submit" class="btn btn-lg btn-main custom-rounded" id="buttonFormSubmit">{{ trans('misc.send_message') }}</button>
	             </form>

	             <!-- Alert -->
	             <div class="alert alert-success display-none" id="successAlert">
								<ul class="list-unstyled" id="showSuccess"></ul>
							</div><!-- Alert -->

					      </div><!-- Modal body -->
	     				</div><!-- Modal content -->
	     			</div><!-- Modal dialog -->
	     		</div><!-- Modal -->
     		@endif

 </div><!-- /COL MD -->

 </div><!-- container wrap-ui -->
@endsection

@section('javascript')
<script type="text/javascript">

$("#embedCode,#url_campaign").click(function() {
	var $this = $(this);
    $this.select();
		});

textTruncate('#desc', ' {{trans("misc.view_more")}}');

$(document).on('click','#updates .loadPaginator', function(r){
	r.preventDefault();
	 $(this).remove();
			$('<a class="list-group-item text-center loadMoreSpin"><i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i></a>').appendTo( "#updates" );

			var page = $(this).attr('href').split('page=')[1];
			$.ajax({
				url: '{{ url("ajax/campaign/updates") }}?id={{$response->id}}&page=' + page
			}).done(function(data){
				if( data ) {
					$('.loadMoreSpin').remove();

					$( data ).appendTo( "#updates" );
					jQuery(".timeAgo").timeago();
					Holder.run({images:".holderImage"})
				} else {
					bootbox.alert( "{{trans('misc.error')}}" );
				}
				//<**** - Tooltip
			});
	});

$(document).on('click','#listDonations .loadPaginator', function(e){
			e.preventDefault();
			$(this).remove();
			//$('<li class="list-group-item position-relative spinner spinnerList loadMoreSpin"></li>').appendTo( "#listDonations" )
			$('<a class="list-group-item text-center loadMoreSpin"><i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i></a>').appendTo( "#listDonations" );

			var page = $(this).attr('href').split('page=')[1];
			$.ajax({
				url: '{{ url("ajax/donations") }}?id={{$response->id}}&page=' + page
			}).done(function(data){
				if( data ) {
					$('.loadMoreSpin').remove();

					$( data ).appendTo( "#listDonations" );
					jQuery(".timeAgo").timeago();
					Holder.run({images:".holderImage"})
				} else {
					bootbox.alert( "{{trans('misc.error')}}" );
				}
				//<**** - Tooltip
			});
		});

		@if( Auth::check() )

$("#deleteCampaign").click(function(e) {
   	e.preventDefault();

   	var element = $(this);
	var url     = element.attr('data-url');

	element.blur();

	swal(
		{   title: "{{trans('misc.delete_confirm')}}",
		 text: "{{trans('misc.confirm_delete_campaign')}}",
		  type: "warning",
		  showLoaderOnConfirm: true,
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		   confirmButtonText: "{{trans('misc.yes_confirm')}}",
		   cancelButtonText: "{{trans('misc.cancel_confirm')}}",
		    closeOnConfirm: false,
		    },
		    function(isConfirm){
		    	 if (isConfirm) {
		    	 	window.location.href = url;
		    	 	}
		    	 });

		 });

		 @if (session('noty_error'))
    		swal({
    			title: "{{ trans('misc.error_oops') }}",
    			text: "{{ trans('misc.already_sent_report') }}",
    			type: "error",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});
   		 @endif

   		 @if (session('noty_success'))
    		swal({
    			title: "{{ trans('misc.thanks') }}",
    			text: "{{ trans('misc.send_success') }}",
    			type: "success",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});
   		 @endif

		@endif

		// Copy Code Embed
		$(document).on('click','#btn_copy_code', function(){
						copyToClipboard('#embedCode',this);
				});
				// Copy Link Campaign
				$('#btn_campaign_url').click(function(){
                copyToClipboard('#url_campaign',this);
            });



				function copyToClipboard(element,btn) {
            var $temp = $('<input>');
            $("body").append($temp);
            $temp.val($(element).val()).select();
						$(element).select().focus();
            document.execCommand("copy");
						$(btn).html('<i class="fa fa-check"></i> {{ trans('misc.copied') }}').addClass('btn btn-success');
						//$('.popout').addClass('popout').html("Campaign URL has been copied.").fadeIn(500).delay(5000).fadeOut();
            $temp.remove();
		        }

		$('.selectReward').hover(

	   function () {
	      $(this).find('.cardSelectRewardBox').fadeIn();
	   },

	   function () {
	      $(this).find('.cardSelectRewardBox').fadeOut();
	   }
	);

</script>

@endsection
@php session()->forget('donation_cancel') @endphp
@php session()->forget('donation_success') @endphp
