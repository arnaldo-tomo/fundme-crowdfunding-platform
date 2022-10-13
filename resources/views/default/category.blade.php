@extends('app')

@section('title'){{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name.' - ' }}@endsection

@section('content')
<div class="py-5 bg-primary bg-sections" style="background-image: url('{{asset('public/img-category')}}/{{ $category->image == '' ? 'default.jpg' : $category->image }}')">
  <div class="btn-block text-center text-white position-relative">
    <h1>{{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}</h1>
    @if( $data->total() != 0 )
     	<p>({{number_format($data->total())}}) {{trans_choice('misc.campaign_available_category',$data->total() )}}</p>
     @else
     	<p>{{$settings->title}}</p>
     @endif
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
	  @endif
 </div><!-- container -->
@endsection

@section('javascript')
<script type="text/javascript">
pagination('{{ url("ajax/category") }}?slug={{$category->slug}}&page=', '{{trans('misc.error')}}');
</script>
@endsection
