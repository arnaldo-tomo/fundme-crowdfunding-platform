@extends('app')

@section('title'){{ trans('misc.edit_update').' - ' }}@endsection

@section('css')@endsection

@section('content')

 <div class="jumbotron mb-0 bg-sections text-center">
      <div class="container wrap-jumbotron position-relative">
      	<h1>{{ trans('misc.edit_update') }}</h1>
        <p class="mb-0">
          {{$data->campaigns()->title}}
        </p>
      </div>
    </div>

<div class="container py-5">
	<div class="row">

    <div class="wrap-container-lg">
	<!-- col-md-8 -->
	<div class="col-md-12">

			@include('errors.errors-forms')

    <!-- form start -->
    <form method="POST" action="" enctype="multipart/form-data" id="formUpdateCampaign">

    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
    	<input type="hidden" name="id" value="{{ $data->id }}">
		<div class="filer-input-dragDrop position-relative" id="draggable">
			<input type="file" accept="image/*" name="photo" id="filePhoto">

			<!-- previewPhoto -->
			<div class="previewPhoto" @if($data->image != '')style='display: block; background-image: url("{{asset('public/campaigns/updates/'.$data->image)}}");' @endif>

				<div class="btn btn-danger btn-sm btn-remove-photo" id="removePhoto" title="{{trans('misc.delete')}}">
					<i class="fa fa-trash myicon-right"></i>
					</div>

			</div><!-- previewPhoto -->

			<div class="filer-input-inner">
				<div class="filer-input-icon">
					<i class="fa fa-cloud-upload-alt"></i>
					</div>
					<div class="filer-input-text">
						<h3 class="fw-light">{{ trans('misc.click_select_image') }}</h3>
						<h3 class="fw-light">{{ trans('misc.max_size') }}: {{ $settings->min_width_height_image.' - '.App\Helper::formatBytes($settings->file_size_allowed * 1024) }} </h3>
					</div>
				</div>
			</div>

                  <div class="form-group">
                      <label>{{ trans('misc.update_details') }}</label>
                      	<textarea name="description" rows="4" id="description" class="form-control" placeholder="{{ trans('misc.update_details_desc') }}">{{$data->description}}</textarea>
                    </div>

                    <!-- Alert -->
                    <div class="alert alert-danger d-none-custom mt-2" id="dangerAlert">
                      <ul class="list-unstyled mb-0" id="showErrors"></ul>
                    </div><!-- Alert -->

                  <div class="box-footer">
                  	<hr />
                    <button type="submit" id="buttonUpdateForm" class="btn btn-block btn-lg btn-primary no-hover" data-create="{{ trans('auth.send') }}" data-send="{{ trans('misc.send_wait') }}">{{ trans('auth.send') }}</button>
                    <div class="btn-block text-center mt-2">
			           		<a href="{{url('campaign',$data->id)}}" class="text-muted">
			           		<i class="fa fa-long-arrow-alt-left"></i>	{{trans('auth.back')}}</a>
			           </div>
                  </div><!-- /.box-footer -->

                </form>

               </div><!-- wrap-center -->
		</div><!-- col-md-12-->

	</div><!-- row -->
</div><!-- container -->
@endsection

@section('javascript')
<script src="{{ asset('public/js/create-edit-campaign.js')}}"></script>
@endsection
