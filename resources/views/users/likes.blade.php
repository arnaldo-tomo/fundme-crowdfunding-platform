@extends('app')

@section('title'){{ trans('misc.likes').' - ' }}@endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
      <div class="container position-relative">
        <span class="display-4"><i class="fa fa-heart"></i></span>
        <h1>{{ trans('misc.likes') }}</h1>
        <p class="mb-0">
          {{ trans('misc.likes_users_desc') }}
        </p>
        </div>
    </div>

<div class="container py-5">
<!-- Col MD -->
<div class="col-md-12 margin-top-20 margin-bottom-20">

	@if ($data->total() !== 0)

			<div class="row" id="campaigns">
		   @foreach ($data as $key)
		    	@include('includes.list-campaigns')
		    	  @endforeach

		 @if ($data->lastPage() > 1)
		    <div class="col-md-12">
		    	<hr />
   		  		 {{ $data->links() }}
		    </div>
		    @endif


		    	 </div><!-- /row -->
	  @else
    <div class="btn-block text-center">
	    			<i class="far fa-times-circle display-4"></i>
	    		</div>

	    		<h3 class="text-center my-3">
	    		{{ trans('misc.no_results_found') }}
	    	</h3>
	  @endif

     </div><!-- /COL MD -->
 </div><!-- container wrap-ui -->
@endsection
