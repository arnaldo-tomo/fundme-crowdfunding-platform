@if( $data->count() != 0 )

@foreach ( $data as $update )

	@include('includes.ajax-updates-campaign')

@endforeach

<div class="text-center py-2">
	{{ $data->links('vendor.pagination.loadmore') }}
</div>
@endif
