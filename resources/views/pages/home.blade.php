@extends('app')

 @section('title') {{ $response->title }} -@endsection

 @section('content')
 <div class="py-5 bg-primary bg-sections">
   <div class="btn-block text-center text-white position-relative">
     <h1>{{ $response->title }}</h1>
     <p>{{$settings->title}}</p>
   </div>
 </div><!-- container -->

 <div class="py-5 bg-white">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
         <dl>
           <dd>
         		{!! $response->content !!}
          </dd>
         </dl>
       </div>
      </div>
   </div>

  </div><!-- container wrap-ui -->
 @endsection
