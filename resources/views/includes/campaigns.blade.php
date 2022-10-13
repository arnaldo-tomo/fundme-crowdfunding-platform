<div class="container">
	<div class="row" id="campaigns">
		@foreach ( $data as $key )
    	@include('includes.list-campaigns')
    	  @endforeach
			</div>
		</div>

		<div class="btn-block text-center py-3 ">
			{{ $data->links('vendor.pagination.loadmore') }}
		</div>
