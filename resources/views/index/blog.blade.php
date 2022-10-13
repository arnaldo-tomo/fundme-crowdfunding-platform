@extends('app')

@section('title'){{ trans('misc.blog') }} -@endsection

@section('content')
<div class="py-5 bg-primary bg-sections">
  <div class="btn-block text-center text-white">
    <h1>{{ trans('misc.latest_blog') }}</h1>
    <p>{{ trans('misc.subtitle_blog') }}</p>
  </div>
</div><!-- container -->

<div class="py-5 bg-white">

  <div class="container">
  	<div class="row">
	@if ($blogs->total() != 0)

    @foreach ($blogs as $response)
      <div class="col-md-6">
        <div class="mb-4 shadow-sm h-md-250 position-relative">
          <div class="card-cover w-100" style="height:250px; background: @if ($response->image != '') url({{ url('public/blog', $response->image) }})  @endif #505050 center center;"></div>
          <div class="col p-4 d-flex flex-column position-static">
            <small class="d-inline-block mb-2">{{ trans('misc.by') }} {{ $response->user()->name }}</small>
            <h3 class="mb-0">{{ $response->title }}</h3>
            <div class="mb-1 text-muted">{{ date($settings->date_format, strtotime($response->date) ) }}</div>
            <p class="card-text mb-auto">{{ str_limit(strip_tags($response->content), 120, '...') }}</p>
            <a href="{{ url('blog/post', $response->id).'/'.$response->slug }}" class="stretched-link">{{ trans('misc.continue_reading') }}</a>
          </div>
        </div>
      </div>
    @endforeach

    @if ($blogs->hasPages())
      <div class="w-100 d-block">
        {{ $blogs->links() }}
      </div>
    @endif

    @else
    <div class="btn-block text-center">
            <i class="far fa-times-circle display-4"></i>
          </div>

          <h3 class="btn-block text-center my-3">
          {{ trans('misc.no_results_found') }}
        </h3>
    @endif

  </div>
</div>
</div><!-- container -->
@endsection
