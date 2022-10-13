<?php $settings = App\Models\AdminSettings::first(); ?>
@extends('app')

@section('title'){{ trans('misc.campaigns_available') }}@endsection

@section('content') 
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h2 class="title-site">{{ trans('misc.campaigns_available') }}</h2>
       
       
       @if( $data->total() != 0 )
        	<p class="subtitle-site"><strong>({{number_format($data->total())}}) {{trans_choice('misc.campaigns_plural',$data->total() )}}</strong></p>
        @else
        	<p class="subtitle-site"><strong>{{$settings->title}}</strong></p>
        @endif
      </div>
    </div>

<div class="container margin-bottom-40">
	
<!-- Col MD -->
<div class="col-md-12 margin-top-20 margin-bottom-20">	

	@if( $data->total() != 0 )	

	     @include('includes.campaigns')
	     			    		 
	  @else
	  <div class="btn-block text-center">
	    			<i class="icon-search ico-no-result"></i>
	    		</div>
	    		
	    		<h3 class="margin-top-none text-center no-result no-result-mg">
	    		{{ trans('misc.no_results_found') }}
	    	</h3>
	  @endif
	    	
     </div><!-- /COL MD -->
 </div><!-- container wrap-ui -->
@endsection