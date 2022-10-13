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

<script type="text/javascript">
    var campaignId = "{{ $response->id }}";
 </script>
@endsection

@section('content')
<div class="container py-5">

<div class="row">
	@if (session()->has('donation_cancel'))
	<div class="alert alert-danger text-center btn-block mb-3 alert-dismissible fade show" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
								</button>
			<i class="far fa-times-circle mr-1"></i> {{ trans('misc.donation_cancel') }}
		</div>
		@endif

			@if (session('donation_success'))
	<div class="alert alert-success text-center btn-block mb-3 alert-dismissible fade show" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
								</button>
			<i class="fa fa-check mr-1"></i> {{ trans('misc.donation_success') }}
		</div>
		@endif

		@if (session('donation_pending'))
<div class="alert alert-info text-center btn-block mb-3 alert-dismissible fade show" role="alert">
	<button type="button" class="close text-dark" data-bs-dismiss="alert" aria-label="Close">
							</button>
		<i class="fa fa-info-circle mr-1"></i> {{ trans('misc.donation_pending') }}
	</div>
	@endif


<!-- Col MD -->
<div class="col-md-7 margin-bottom-20">

	<div class="text-center mb-2 position-relative">
		@if ($response->featured == 1)
		<div class="ribbon-1" title="{{ trans('misc.featured_campaign') }}"><i class="fa fa-award"></i></div>
	@endif

	@if ($response->video == '')
		<img class="img-fluid rounded-lg" style="display: inline-block;" src="{{ url('public/campaigns/large',$response->large_image) }}" />
	@else

		@if (in_array(App\Helper::videoUrl($response->video), array('youtube.com','www.youtube.com','youtu.be','www.youtu.be')))

		<div class="embed-responsive embed-responsive-16by9 mb-2">
			<iframe class="embed-responsive-item" height="360" src="https://www.youtube.com/embed/{{App\Helper::getYoutubeId($response->video)}}" allowfullscreen></iframe>
		</div>
		@endif

		@if (in_array(App\Helper::videoUrl($response->video), array('vimeo.com','player.vimeo.com')))

		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/{{App\Helper::getVimeoId($response->video)}}" allowfullscreen></iframe>
		</div>
		@endif

	@endif

</div>

@if( Auth::check()
&&  isset($response->user()->id)
&& Auth::user()->id == $response->user()->id
&& !isset( $deadline )
&& $response->finalized == 0
)
 	<div class="row margin-bottom-20">
		<div class="col-md-3">
			<a class="btn btn-block btn-primary mb-2 no-hover" href="{{ url('rewards/campaign',$response->id) }}">{{trans('misc.add_reward')}}</a>
		</div>
			<div class="col-md-3">
				<a class="btn btn-success btn-block mb-2 no-hover" href="{{ url('edit/campaign',$response->id) }}">{{trans('users.edit')}}</a>
			</div>
			<div class="col-md-3">
				<a class="btn btn-info btn-block mb-2 no-hover text-white" href="{{ url('update/campaign',$response->id) }}">{{trans('misc.add_update')}}</a>
			</div>
			@if( $response->donations()->count() == 0 )
			<div class="col-md-3">
				<a href="#" class="btn btn-danger btn-block mb-2 no-hover" id="deleteCampaign" data-url="{{ url('delete/campaign',$response->id) }}">{{trans('misc.delete')}}</a>
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
			<div class="col-md-3">
				<a class="btn btn-block btn-primary mb-2 no-hover" href="{{ url('rewards/campaign',$response->id) }}">{{trans('misc.add_reward')}}</a>
			</div>
			<div class="col-md-3">
				<a class="btn btn-success btn-block mb-2 no-hover" href="{{ url('edit/campaign',$response->id) }}">{{trans('users.edit')}}</a>
			</div>
			<div class="col-md-3">
				<a class="btn btn-info btn-block mb-2 no-hover" href="{{ url('update/campaign',$response->id) }}">{{trans('misc.post_an_update')}}</a>
			</div>
			@if( $response->donations()->count() == 0 )
			<div class="col-md-3">
				<a href="#" class="btn btn-danger btn-block mb-2 no-hover" id="deleteCampaign" data-url="{{ url('delete/campaign',$response->id) }}">{{trans('misc.delete')}}</a>
			</div>
			@endif
		</div>

		@endif

 </div><!-- /COL MD -->

<!-- Second Panel
==================================== -->
 <div class="col-md-5">

	 @if( $response->categories_id != '' )
	 	@if (isset($response->category->id ) && $response->category->mode == 'on')
	 <small class="btn-block mb-1">
		 <a href="{{ url('category', $response->category->slug) }}" class="text-muted">
		 <i class="far fa-folder-open"></i> {{ Lang::has('categories.' . $response->category->slug) ? __('categories.' . $response->category->slug) : $response->category->name }}
	 </a>
	 </small>
 		@endif
 	@endif
	 <h2 class="mb-2 font-weight-bold text-break text-dark">{{ $response->title }}</h2>

@if (isset($response->user()->id))
<!-- Start Panel -->
 	<div class="panel panel-default panel-transparent mb-4">
	  <div class="panel-body">
	    <div class="media none-overflow">
			  <div class="d-flex my-2 align-items-center">
			      <img class="rounded-circle mr-2" src="{{url('public/avatar',$response->user()->avatar)}}" width="60" height="60">

						<div class="d-block">
						{{ trans('misc.by') }} <strong class="text-dark">{{ $response->user()->name }}</strong>

						@if (Auth::guest() || Auth::check() && Auth::user()->id != $response->user()->id)
			    		<a href="#" title="{{trans('misc.contact_organizer')}}" class="text-muted" data-bs-toggle="modal" data-bs-target="#sendEmail">
			    				<i class="fa fa-envelope"></i>
			    		</a>
						@endif

							<div class="d-block">
								<small class="media-heading text-muted btn-block margin-zero">{{trans('misc.created')}} {{ date($settings->date_format, strtotime($response->date) ) }} <span class="align-middle mx-1" style="font-size:8px;">|</span>
								@if( $response->location != '' )
							 <i class="fa fa-map-marker-alt mr-1"></i> {{$response->location}}
							 @endif
							 </small>
							</div>
						</div>
			  </div>
			</div>
	  </div>
	</div><!-- End Panel -->

	<span class="progress progress-xs mb-3">
		<span class="percentage bg-success" style="width: {{$percentage }}%" aria-valuemin="0" aria-valuemax="100" role="progressbar"></span>
	</span>

	<small class="btn-block margin-bottom-10 text-muted">
		<strong class="text-strong-small">{{ App\Helper::amountFormat($response->donations()->sum('donation')) }}</strong> {{trans('misc.raised_of')}} {{ App\Helper::amountFormat($response->goal)}} {{strtolower(trans('misc.goal')) }}
		<strong class="text-percentage">{{$percentage }}%</strong>
	</small>

	<ul class="list-inline my-4 border-top border-bottom py-3 text-center">
		<li class="list-inline-item border-right" style="width:31%;">
			<i class="fa fa-donate align-baseline text-success"></i> {{ App\Helper::formatNumber($response->donations()->count()) }} {{trans_choice('misc.donation_plural',$response->donations()->count())}}
		</li>
		<li class="list-inline-item border-right" style="width:31%;">
			@if (isset( $deadline ) && $response->finalized == 0)

				@if ($days_remaining > 0 )
					<i class="far fa-clock text-success"></i> {{ $days_remaining }} {{ trans('misc.days_left') }}
				@elseif ($days_remaining == 0 )
					<i class="far fa-clock text-warning"></i> {{trans('misc.last_day')}}
				@else
					<i class="far fa-clock text-danger"></i> {{ trans('misc.no_time_anymore') }}
				@endif

			@endif

			@if ($response->finalized == 1)
				<i class="far fa-clock text-danger"></i> {{ trans('misc.finalized') }}
				@endif

				@if (!isset( $deadline) && $response->finalized == 0)
					<i class="fa fa-infinity text-success"></i> {{ trans('misc.no_deadline') }}
					@endif
		</li>

		<li class="list-inline-item" style="width:31%;">
			@auth
				<span class="likeButton {{$statusLike}}" data-id="{{$response->id}}" data-like="{{trans('misc.like')}}" data-unlike="{{trans('misc.unlike')}}">
					<i class="{{$icoLike}} align-baseline text-success"></i>
				</span>
			@else
				<i class="far fa-heart align-baseline text-success"></i>
			@endauth
			<span id="countLikes">{{ App\Helper::formatNumber($response->likes()->count()) }}</span> {{trans_choice('misc.likes_plural', $response->likes()->count())}}
		</li>
	</ul>

<!-- Button Donate
============================== -->
@if (isset( $deadline ) && $deadline > $timeNow && $response->finalized == 0)
 	<div class="btn-group btn-block margin-bottom-20 @if( Auth::check() && Auth::user()->id == $response->user()->id ) d-none @endif">
		<a href="{{url('donate/'.$response->id.$slug_url)}}" class="btn btn-main btn-primary no-hover btn-lg btn-block custom-rounded">
			{{trans('misc.donate_now')}}
			</a>
		</div>

		@elseif (! isset( $deadline ) && $response->finalized == 0)
		<div class="btn-group btn-block margin-bottom-20 @if( Auth::check() && Auth::user()->id == $response->user()->id ) d-none @endif">
		<a href="{{url('donate/'.$response->id.$slug_url)}}" class="btn btn-main btn-primary no-hover btn-lg btn-block custom-rounded">
			{{trans('misc.donate_now')}}
			</a>
		</div>

		@else

		<div class="alert btnDisabled text-center btn-block" role="alert">
			<i class="fa fa-lock mr-1"></i> {{trans('misc.campaign_ended')}}
		</div>

		@endif

@else
<div class="alert btnDisabled text-center btn-block" role="alert">
			<i class="fa fa-lock mr-1"></i> {{trans('misc.campaign_ended')}}
		</div>
@endif
<!-- End Button Donate
============================== -->

@if ($response->finalized != 1)
<div class="btn-group btn-block">
<a href="javascript:;" class="btn btn-main btn-outline-primary no-hover btn-lg w-100" data-bs-toggle="modal" data-bs-target=".share-modal">
	{{trans('misc.share')}}
	</a>
</div>


<!-- Social Share
============================== -->

	<!-- Share modal -->
<div class="modal fade share-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
		<div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="mySmallModalLabel">{{trans('misc.share_on')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3 col-6 mb-3">
							<a href="https://www.facebook.com/sharer/sharer.php?u={{ url('campaign',$response->id).'/'.str_slug($response->title) }}" title="Facebook" target="_blank" class="social-share text-muted d-block text-center h6">
								<i class="fab fa-facebook-square facebook-btn"></i>
								<span class="btn-block mt-3">Facebook</span>
							</a>
						</div>
						<div class="col-md-3 col-6 mb-3">
							<a href="https://twitter.com/intent/tweet?url={{ url('campaign',$response->id) }}&text={{ e( $response->title ) }}" data-url="{{ url('campaign',$response->id) }}" class="social-share text-muted d-block text-center h6" target="_blank" title="Twitter">
								<i class="fab fa-twitter twitter-btn"></i> <span class="btn-block mt-3">Twitter</span>
							</a>
						</div>
						<div class="col-md-3 col-6 mb-3">
							<a href="fb-messenger://share/?link={{url()->current()}}&app_id={{config('fb_app.id')}}" class="social-share text-muted d-block text-center h6" title="Facebook Messenger">
							<i class="fab fa-facebook-messenger fb-messenger"></i> <span class="btn-block mt-3">Messenger</span>
						</a>
					</div>
						<div class="col-md-3 col-6 mb-3">
							<a href="whatsapp://send?text={{url()->current()}}" data-bs-action="share/whatsapp/share" class="social-share text-muted d-block text-center h6" title="WhatsApp">
								<i class="fab fa-whatsapp btn-whatsapp"></i> <span class="btn-block mt-3">WhatsApp</span>
							</a>
						</div>
						<div class="col-md-3 col-6 mb-3">
							<a href="https://telegram.me/share/url?url={{url()->current()}}&text={{ e( $response->title ) }}" target="_blank"  class="social-share text-muted d-block text-center h6" title="Telegram">
								<i class="fab fa-telegram-plane btn-telegram"></i> <span class="btn-block mt-3">Telegram</span></a>
							</div>
						<div class="col-md-3 col-6 mb-3">
							<a href="mailto:?subject={{ e( $response->title ) }}&amp;body={{ url('campaign',$response->id) }}" class="social-share text-muted d-block text-center h6" title="{{trans('auth.email')}}">
								<i class="far fa-envelope"></i> <span class="btn-block mt-3">{{trans('auth.email')}}</span>
							</a>
						</div>
						<div class="col-md-3 col-6 mb-3">
							<a href="sms://?body={{ trans('misc.check_this') }} {{url()->current()}}" class="social-share text-muted d-block text-center h6" title="{{ trans('misc.sms') }}">
								<i class="fa fa-sms"></i> <span class="btn-block mt-3">{{ trans('misc.sms') }}</span>
							</a>
						</div>
						<div class="col-md-3 col-6 mb-3">
							<a data-bs-toggle="modal" data-bs-target="#embedModal" href="#" class="social-share text-muted d-block text-center h6" title="{{trans('misc.embed')}}">
							<i class="fa fa-code"></i> <span class="btn-block mt-3">{{trans('misc.embed')}}</span>
						</a>
					</div>
					</div>

				</div>

				<div class="form-inline">
			        <input type="text" readonly="readonly" id="url_campaign" class="form-control w-100 bg-white" value="{{ url()->current() }}">
								<button class="btn btn-primary no-hover ml-1 btn-absolute" id="btn_campaign_url">{{ trans('misc.copy_link') }}</button>
			    </div>
      </div>
    </div>
  </div>
</div>
@endif

<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="embedModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white headerModal">
					<h6 class="modal-title">
						{{trans('misc.embed_title')}}
					</h6>
					<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
			 </div><!-- Modal header -->

			 <div class="modal-body">
						 <div class="form-group">
							 <div class="btn-block text-center" style="position: relative;">
								 <div style="position: absolute; width: 100%; height: 100%;"></div>
								 <div style='max-width:350px; margin: 0 auto;'><script src='{{url('c',$response->id)}}/widget.js' type='text/javascript'></script></div>
							 </div>

							 <div class="form-inline mt-1">
							 <input type="text" class="form-control w-100 bg-white" value="<div style='width:350px;'><script src='{{url('c',$response->id)}}/widget.js' type='text/javascript'></script></div>" readonly="readonly" id="embedCode">
							  <button class="btn btn-primary no-hover ml-1 btn-absolute" id="btn_copy_code">{{trans('misc.copy_code')}}</button>
							 </div>
						 </div><!-- /.form-group-->
			 </div>
		</div>
	</div>
</div><!-- Modal -->



	@if( Auth::check() &&  isset($response->user()->id) && Auth::user()->id != $response->user()->id  )
	<div class="btn-block text-center mt-1">
		<a href="{{ url('report/campaign', $response->id)}}/{{$response->user()->id }}" class="text-small"><i class="far fa-flag"></i> {{ trans('misc.report') }}</a>
	</div>
	@endif

@if (isset($response->user()->id))
	@include('includes.contact_organizer')
@endif

 		</div><!-- /COL MD 5 -->
	</div><!-- ./ Row -->
 </div><!-- container -->

 <!-- Third Panel
 ======================================== -->
 <div class="container pb-5">
	 <div class="row">
		 <div class="col-md-8">

			 <!-- Tab Content
			 ====================================== -->
			 <ul class="nav nav-tabs nav-fill">
			   <li class="nav-item active"><a href="#desc" aria-controls="home" role="tab" data-bs-toggle="tab" class="nav-link active text-dark py-3"><strong>{{ trans('misc.story') }}</strong></a></li>
				 <li class="nav-item text-muted"><a href="#donations" aria-controls="home" role="tab" data-bs-toggle="tab" class="nav-link text-dark py-3"><strong>{{ trans('misc.donations') }}</strong></a></li>
			 	 <li class="nav-item text-muted"><a href="#updates" aria-controls="home" role="tab" data-bs-toggle="tab" class="nav-link text-dark py-3"><strong>{{ trans('misc.updates') }}</strong></a></li>
				 <li class="nav-item text-muted"><a href="#comments" aria-controls="home" role="tab" data-bs-toggle="tab" class="nav-link text-dark py-3"><strong>{{ trans('misc.comments') }}</strong></a></li>
			 </ul>

			 <div class="tab-content py-3">

			 		@if( $response->description != '' )
			 		<div role="tabpanel" class="tab-pane fade show active description text-break"id="desc">
			 		{!!$response->description!!}
			 		</div>
			 		@endif

			 		<div role="tabpanel" class="tab-pane fade in description text-break"id="donations">

			 			@if( $response->donations()->count() == 0 )
							<div class="btn-block text-center py-3 text-muted icon-display">
			    			<i class="fas fa-donate"></i>
			    		</div>
			 				<span class="text-center btn-block">{{trans('misc.no_donations')}}</span>

			 			@else

							  <ul class="list-unstyled" id="listDonations">

			 				    @foreach ($donations as $donation)

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
										 </ul>

										 <div class="text-center py-2 wrap-paginator">
			 				       	 {{ $donations->links('vendor.pagination.loadmore') }}
											 </div>
										 @endif

									 </div>

			 		<div role="tabpanel" class="tab-pane fade description" id="updates">

			 		@if( $updates->total() == 0 )
						<div class="btn-block text-center py-3 text-muted icon-display">
							<i class="fas fa-history"></i>
						</div>
			 			<span class="text-center btn-block">{{ trans('misc.no_results_found') }}</span>

			 			@else

							<ul id="listUpdates">
			 			@foreach ($updates as $update)
			 				@include('includes.ajax-updates-campaign')
			 				@endforeach
							</ul>

							<div class="text-center py-2">
								{{ $updates->links('vendor.pagination.loadmore') }}
							</div>

			 			@endif
			 		</div>

					<div role="tabpanel" class="tab-pane fade description text-break" id="comments">
						<div class="btn-block margin-top-20">
							<div class="fb-comments"  data-bs-width="100%" data-bs-href="{{ url('campaign',$response->id).'/'.str_slug($response->title) }}" data-bs-numposts="5"></div>
						</div>
					</div>
			 </div><!-- ./End Tab Content -->
		 </div><!-- col 8 -->

		 <!-- Rewards
		======================================= -->
		 @if( isset( $deadline ) && $deadline > $timeNow && $response->finalized == 0
		 || !isset( $deadline ) && $response->finalized == 0 )

		 @if ($response->rewards->count() != 0)

		 <div class="col-md-4">

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
			 				 <i class="fa fa-pencil-alt"></i>	{{trans('misc.edit_reward')}}
			 			 @elseif($pledgeClaimed < $reward->quantity)
			 					{{trans('misc.select_reward')}}
			 				@else
			 					{{trans('misc.sold')}}
			 				@endif
			 				</span>
			 			</span>
			 		</span>

					<div class="card mb-2" style="position: initial;">
					  <div class="card-body">

							<h6 class="card-title" style="line-height: inherit;">
			          <i class="fa fa-gift"></i> {{trans('misc.reward')}}
			        </h6>

			        <hr />

					    <h5 class="card-title">
								<small>{{trans('misc.pledge')}}</small> <strong class="font-default">{{App\Helper::amountFormat($reward->amount)}}</strong>
						</h5>
					    <h6 class="card-subtitle mb-2 text-muted">{{ $reward->title }}</h6>
					    <p class="card-text">{{ $reward->description }}</p>

							<small class="btn-block margin-bottom-10 @if( $pledgeClaimed == $reward->quantity )text-danger @else text-muted @endif">
			 					<i class="far fa-user"></i> {{trans('misc.reward_claimed', ['claim' => $pledgeClaimed, 'total' => $reward->quantity])}} @if( $pledgeClaimed == $reward->quantity ) <span class="label label-danger">{{trans('misc.sold')}}</span> @endif
			 				</small>

							<small class="btn-block text-muted">
			 					<i class="far fa-clock"></i> {{trans('misc.delivery')}}:
			 				</small>
			 				<strong>{{ date('F, Y', strtotime($reward->delivery)) }}</strong>
					  </div>
					</div>


			 	<div class="panel panel-default d-none">
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

		 </div><!-- col 4 -->
	 		@endif
	 @endif {{-- End IF Deadline --}}
	 </div><!-- row -->
 </div><!-- container -->

@if ($data->total() != 0)
 <!-- New Campaigns
 ========================= -->
 <div class="py-5 bg-light">
	 <div class="btn-block text-center mb-5">
		 <h1>{{trans('misc.related_campaigns')}}</h1>
		 <p>
			 {{trans('misc.related_campaigns_subtitle')}}
		 </p>
	 </div>
	 <div class="container">
		 <div class="row">
			 @foreach ($data as $key)
				 @include('includes.list-campaigns')
			 @endforeach
		 </div>
	 </div>
 </div>
 @endif

@endsection

@section('javascript')
	<script src="{{ asset('public/js/pagination-updates-donations.js') }}?v={{$settings->version}}"></script>

@if (session('noty_error') || session('noty_success'))
<script type="text/javascript">
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
</script>
@endif

@endsection
@php session()->forget('donation_cancel') @endphp
@php session()->forget('donation_success') @endphp
@php session()->forget('donation_pending') @endphp
