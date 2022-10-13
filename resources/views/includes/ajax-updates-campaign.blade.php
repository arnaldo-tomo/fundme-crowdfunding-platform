<li>
	<p class="timeAgo text-muted" data="{{ date('c', strtotime( $update->date )) }}"></p>
	<p>{!! App\Helper::checkText($update->description) !!}</p>
	@if( Auth::check() && Auth::user()->id == $update->campaigns()->user_id )
		<a href="{{url('edit/update',$update->id)}}" class="btn btn-success btn-sm px-3"><i class="fa fa-pencil-alt mr-2"></i> {{trans('users.edit')}}</a>
	@endif
	@if( $update->image !== '' )
	<span class="text-center btn-block">
		<img class="img-fluid rounded mt-2" style="display: inline-block; max-width:400px" src="{{url('public/campaigns/updates',$update->image)}}" />
	</span>
		@endif
</li>
