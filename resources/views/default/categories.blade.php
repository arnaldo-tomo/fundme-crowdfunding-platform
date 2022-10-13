@extends('app')

@section('title'){{ trans('misc.categories').' - ' }}@endsection

@section('content')
<div class="py-5 bg-primary bg-sections">
  <div class="btn-block text-center text-white position-relative">
    <h1>{{trans('misc.browse_by_category')}}</h1>
    <p>
      {{trans('misc.categories_subtitle')}}
    </p>
  </div>
</div><!-- container -->

<div class="py-5 bg-white">
  <div class="container">
    <div class="row">
      @foreach ($data as $category)
        <div class="col-md-4 mb-3">
          @include('includes.categories-listing')
        </div>
      @endforeach
    </div>
  </div>

 </div><!-- container wrap-ui -->
@endsection
