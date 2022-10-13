@extends('app')

 @section('title') {{ trans('misc.gallery') }} - @endsection

   @section('css')
   <link href="{{ asset('public/css/smartphoto.min.css') }}" rel="stylesheet" type="text/css" />
   @endsection

 @section('content')
 <div class="py-5 bg-primary bg-sections">
   <div class="btn-block text-center text-white position-relative">
     <h1>{{ trans('misc.gallery') }}</h1>
     <p>{{ $settings->title }}</p>
   </div>
 </div><!-- container -->

 <div class="py-5 bg-white">
   <div class="container">
     <div class="row">
       @if ($data->count() != 0)
       @foreach ($data as $gallery)
         <div class="col-md-4">
           <div class="gallery mb-3">
             <a href="{{ url('public/gallery', $gallery->image ) }}" data-group="gallery" class="js-smartPhoto">
               <img src="{{ url('public/gallery', 'thumb-'.$gallery->image ) }}" alt="{{ $settings->title }}">
             </a>
           </div>

         </div>
       @endforeach
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

  </div><!-- container wrap-ui -->
 @endsection

 @section('javascript')
<script src="{{ asset('public/js/smartphoto.min.js') }}" type="text/javascript"></script>

 <script type="text/javascript">
 document.addEventListener('DOMContentLoaded',function(){
  new SmartPhoto(".js-smartPhoto",{
    resizeStyle: 'fit',
    showAnimation: false
  });
});
 </script>
 @endsection
