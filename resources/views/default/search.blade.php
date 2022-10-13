@extends('app')

@section('title'){{ e($title) }}@stop

@section('content')
  <div class="py-5 bg-primary bg-sections">
    <div class="btn-block text-center text-white position-relative">
      <h1>{{ trans('misc.search') }}</h1>
      <p>{{ trans('misc.result_of') }} "{{ $q }}" {{ $total }} {{ trans_choice('misc.campaigns_plural',$total) }}</p>
    </div>
  </div><!-- container -->

  <div class="py-5 bg-white">
  	@if( $data->total() != 0 )

  	     @include('includes.campaigns')

  	  @else
  	  <div class="btn-block text-center">
  	    			<i class="far fa-times-circle display-4"></i>
  	    		</div>

  	    		<h3 class="text-center my-3">
  	    		{{ trans('misc.no_results_found') }}
  	    	</h3>

          <div class="btn-block text-center">
          <a class="btn btn-lg btn-main no-hover btn-primary" href="{{ url('categories') }}" role="button">{{ trans('misc.browse_by_category') }} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small></a>
        </div>
  	  @endif
   </div><!-- container -->
@endsection

@section('javascript')
<script type="text/javascript">
  pagination('{{ url("ajax/search") }}?slug={{$q}}&page=', '{{trans('misc.error')}}');
</script>
@endsection
