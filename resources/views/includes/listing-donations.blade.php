<li class="media py-2">
    <img src="{{ url('public/img/donor.jpg') }}" width="60" class="rounded-circle mr-3" alt="{{ $donation->fullname }}" />
    <div class="media-body">
      <h6 class="mt-0 mb-1">{{ $donation->fullname }} <span class="fw-light">{{ trans('misc.donated') }}</span> <span class="text-success">{{App\Helper::amountFormat($donation->donation)}}</span> </h6>
			@if( $donation->comment != '' )
			<p class="mb-0">{{ $donation->comment }}</p>
			@endif
			<small class="btn-block timeAgo text-muted" data="{{ date('c', strtotime( $donation->date )) }}"></small>
    </div>
  </li>
