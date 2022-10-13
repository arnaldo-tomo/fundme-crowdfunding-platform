@extends('app')

@section('title'){{ $title.' - ' }}@endsection

@section('content')
<div class="py-5 bg-primary bg-sections">
  <div class="btn-block text-center text-white">

    @if (request()->path() == "campaigns/featured")
      <span class="display-4"><i class="fa fa-award"></i></span>
    @endif

    @if (request()->path() == "campaigns/popular")
      <span class="display-4"><i class="fa fa-heart"></i></span>
    @endif

    <h1>{{ $title }}</h1>
    <p>
      {{ $subtitle }}
    </p>
  </div>
</div><!-- container -->

<div class="py-5 bg-white">

	@if ($data->total() != 0)

	     @include('includes.campaigns')

	  @else
	  <div class="btn-block text-center">
	    			<i class="far fa-times-circle display-4"></i>
	    		</div>

	    		<h3 class="text-center my-3">
	    		{{ trans('misc.no_results_found') }}
	    	</h3>
	  @endif
 </div><!-- container -->
@endsection

@section('javascript')
<script type="text/javascript">
pagination('{{ url()->current() }}?page=', '{{trans('misc.error')}}');
		</script>
@endsection
