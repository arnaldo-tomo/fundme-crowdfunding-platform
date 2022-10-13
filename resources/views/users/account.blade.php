@extends('app')

@section('title') {{ trans('users.account_settings') }} - @endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
      <div class="container wrap-jumbotron position-relative">
        <h1>{{ trans('users.account_settings') }}</h1>
        <p class="mb-0">
          {{ trans('misc.account_desc') }}
        </p>
      </div>
    </div>

<div class="container py-5">

  <div class="row">

    <div class="col-md-3">
      @include('users.navbar-settings')
    </div>

			<!-- Col MD -->
		<div class="col-md-9">

			@if (session('notification'))
			<div class="alert alert-success btn-sm alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            		{{ session('notification') }}
            		</div>
            	@endif

			@include('errors.errors-forms')

		<!-- *********** AVATAR ************* -->

		<form action="{{url('upload/avatar')}}" method="POST" id="formAvatar" accept-charset="UTF-8" enctype="multipart/form-data">
    		@csrf

    		<div class="text-center position-relative avatar-wrap">

          <div class="progress-upload">0%</div>

          @if (auth()->user()->status != 'pending')
          <a href="javascript:;" class="position-absolute button-avatar-upload" id="avatar_file">
            <i class="fa fa-camera"></i>
          </a>
          <input type="file" name="photo" id="uploadAvatar" class="visually-hidden" accept="image/*">
          @endif

    			<img src="{{ asset('public/avatar').'/'.Auth::user()->avatar }}" alt="User" width="125" height="125" class="rounded-circle avatarUser"  />
    		</div>
			</form><!-- *********** AVATAR ************* -->



		<!-- ***** FORM ***** -->
       <form action="{{ url('account') }}" class="mt-4" method="post" name="form">
          	@csrf
            <!-- ***** Form Group ***** -->
            <div class="form-floating mb-3">
             <input type="text" required class="form-control" id="inputname" value="{{auth()->user()->name}}" name="full_name" placeholder="{{ trans('misc.full_name_misc') }}">
             <label for="inputname">{{ trans('misc.full_name_misc') }}</label>
           </div>

           <div class="form-floating mb-3">
            <input type="email" required class="form-control" id="inputemail" value="{{auth()->user()->email}}" name="email" placeholder="{{ trans('auth.email') }}">
            <label for="inputemail">{{ trans('auth.email') }}</label>
          </div>

          <div class="form-floating mb-3">
          <select name="countries_id" class="form-select" id="inputSelectCountry">
            <option value="">{{trans('misc.select_your_country')}}</option>

            @foreach (App\Models\Countries::orderBy('country_name')->get() as $country)
              <option @if( auth()->user()->countries_id == $country->id ) selected="selected" @endif value="{{$country->id}}">{{ $country->country_name }}</option>
              @endforeach
          </select>
          <label for="inputSelectCountry">{{ trans('misc.country') }}</label>
        </div>


           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-primary no-hover">{{ trans('misc.save_changes') }}</button>

       </form><!-- ***** END FORM ***** -->

		</div><!-- /COL MD -->
  </div><!-- / Wrap -->

 </div><!-- container -->

 <!-- container wrap-ui -->
@endsection

@section('javascript')

<script type="text/javascript">

	//<<<<<<<=================== * UPLOAD AVATAR  * ===============>>>>>>>//
    $(document).on('change', '#uploadAvatar', function() {

      $('.progress-upload').show();

   (function() {

     var percent = $('.progress-upload');
 		 var percentVal = '0%';

	 $("#formAvatar").ajaxForm({
	 dataType : 'json',

   beforeSend: function() {
      percent.html(percentVal);
  },
  uploadProgress: function(event, position, total, percentComplete) {
      var percentVal = percentComplete + '%';
      percent.html(percentVal);
  },
	 success:  function(e){
	 if (e) {

     if (e.success == false) {
		$('.progress-upload').hide();

		var error = '';
        for($key in e.errors) {
        	error += '' + e.errors[$key] + '';
        }
		swal({
    			title: "{{ trans('misc.error_oops') }}",
    			text: ""+ error +"",
    			type: "error",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});

			$('#uploadAvatar').val('');
      percent.html(percentVal);

		} else {

			$('#uploadAvatar').val('');
			$('.avatarUser').attr('src',e.avatar);
      $('.progress-upload').hide();
      percent.html(percentVal);
		}

		}//<-- e
			else {
        $('.progress-upload').hide();
        percent.html(percentVal);
				swal({
    			title: "{{ trans('misc.error_oops') }}",
    			text: '{{trans("misc.error")}}',
    			type: "error",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});

				$('#uploadAvatar').val('');
			}
		   }//<----- SUCCESS
		}).submit();
    })(); //<--- FUNCTION %
});//<<<<<<<--- * ON * --->>>>>>>>>>>
//<<<<<<<=================== * UPLOAD AVATAR  * ===============>>>>>>>//
</script>
@endsection
