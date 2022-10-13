@extends('app')

@section('title') {{ $response->title }} | {{ trans('misc.blog') }} @endsection
  @section('description_custom'){{strip_tags($response->content)}}@endsection
    @section('keywords_custom'){{$response->tags ? $response->tags.',' : null}}@endsection

  @section('css')
    <meta property="og:type" content="website" />
    <meta property="og:image:width" content="650"/>
    <meta property="og:image:height" content="430"/>

    <!-- Current locale and alternate locales -->
    <meta property="og:locale" content="en_US" />
    <meta property="og:locale:alternate" content="es_ES" />

    <!-- Og Meta Tags -->
    <link rel="canonical" href="{{url()->current()}}"/>
    <meta property="og:site_name" content="{{ $response->title }}"/>
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta property="og:image" content="{{url('public/blog', $response->image)}}"/>

    <meta property="og:title" content="{{ $response->title }}"/>
    <meta property="og:description" content="{{strip_tags($response->content)}}"/>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:image" content="{{url('public/blog', $response->image)}}" />
    <meta name="twitter:title" content="{{ $response->title  }}" />
    <meta name="twitter:description" content="{{strip_tags($response->content)}}"/>
    @endsection

@section('content')

<div class="jumbotron mb-0 bg-sections text-center" style="background-image: url('{{ url('public/blog', $response->image) }}')">
      <div class="container wrap-jumbotron position-relative">
      	<h1 class="text-break">{{ $response->title }}</h1>
      	<p class="mb-0">{{ trans('misc.by') }} {{ $response->user()->name }} / {{ date($settings->date_format, strtotime($response->date) ) }}</p>
      </div>
    </div>

<!-- Container
============================= -->
<div class="container py-5">

  <div class="row">

    <!-- Col MD -->
    <div class="col-md-12 blog-post">
      {!! $response->content !!}

      <div class="mt-4 justify-content-middle text-center">
        <hr>
        <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}" class="mr-2 text-muted">
          <i class="fab fa-facebook mr-1"></i> {{trans('misc.share')}}
        </a>

        <a target="_blank" href="https://twitter.com/intent/tweet?url={{url()->current()}}&text={{ $response->title }}" class="text-muted">
          <i class="fab fa-twitter mr-1"></i> Tweet
        </a>
      </div>
    </div><!-- /COL MD -->

  </div><!-- Row -->
 </div><!-- container wrap-ui -->
@endsection
