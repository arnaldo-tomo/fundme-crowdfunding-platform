@extends('app')

@section('title'){{ trans('misc.edit_campaign').' - ' }}@endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
     <div class="container wrap-jumbotron position-relative">
       <h1>{{ trans('misc.edit_campaign') }}</h1>
       <p class="mb-0">
         {{$data->title}}
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
    <form method="POST" action="" enctype="multipart/form-data" id="formUpdate">

    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
    	<input type="hidden" name="id" value="{{ $data->id }}">

		<div class="filer-input-dragDrop position-relative hoverClass" id="draggable">
			<input type="file" accept="image/*" name="photo" id="filePhoto">

			<!-- previewPhoto -->
			<div class="previewPhoto" style='display: block; background-image: url("{{asset('public/campaigns/large/'.$data->large_image)}}");'>

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

                 <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.campaign_title') }}</label>
                        <input type="text" value="{{ $data->title }}" name="title" id="title" class="form-control" placeholder="{{ trans('misc.campaign_title') }}">
                    </div><!-- /.form-group-->

                    <!-- Start Form Group -->
                       <div class="form-group">
                         <label>{{ trans('misc.video') }}</label>
                           <input type="text" value="{{ $data->video }}" name="video" id="video" class="form-control" placeholder="{{ trans('misc.video_description') }} ({{ trans('misc.optional') }})">
                           <small class="text-muted">{{ trans('misc.video_description_2') }}</small>
                       </div><!-- /.form-group-->

                    <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.choose_a_category') }}</label>
                      	<select name="categories_id" class="form-select">
                      		<option value="">{{trans('misc.select_one')}}</option>
                      	@foreach(  App\Models\Categories::where('mode','on')->orderBy('name')->get() as $category )
                            <option @if( $category->id == $data->categories_id ) selected="selected" @endif value="{{$category->id}}">
                              {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
                            </option>
                          @endforeach
                          </select>
                  </div><!-- /.form-group-->

                  <div class="form-group">
				    <label>{{ trans('misc.campaign_goal') }}</label>
				    <div class="input-group">
                <span class="input-group-text">{{$settings->currency_symbol}}</span>
				      <input type="number" min="1" class="form-control" name="goal" id="onlyNumber" value="{{ $data->goal }}" placeholder="10000">
				    </div>
				  </div>

                  <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.location') }}</label>
                        <input type="text" value="{{ $data->location }}" name="location" class="form-control" placeholder="{{ trans('misc.location') }}">
                    </div><!-- /.form-group-->


                  <div class="form-group">
                      <label>{{ trans('misc.campaign_description') }}</label>
                      	<textarea name="description" rows="4" id="description" class="form-control tinymce-txt" placeholder="{{ trans('misc.campaign_description_placeholder') }}">{{ $data->description }}</textarea>
                    </div>

              <div class="form-check">
        					<input class="form-check-input" name="finish_campaign" type="checkbox" value="1" id="customControlInline">
        			    <label class="form-check-label small" for="customControlInline">{{ trans('misc.finish_campaign') }}</label>
        		</div>

                    <!-- Alert -->
            <div class="alert alert-danger d-none-custom mt-2" id="dangerAlert">
							<ul class="list-unstyled mb-0" id="showErrors"></ul>
						</div><!-- Alert -->

						                    <!-- Alert -->
            <div class="alert alert-success d-none-custom mt-2" id="successAlert">
							<ul class="list-unstyled mb-0" id="success_update">
								<li><i class="far fa-check-circle mr-1"></i> {{ trans('misc.success_update') }} <a href="{{url('campaign', $data->id)}}" class="text-white text-underline">{{trans('misc.view_campaign')}}</a></li>
							</ul>
						</div><!-- Alert -->


                  <div class="box-footer">
                  	<hr />
                    <button type="submit" id="buttonFormUpdate" class="btn btn-block btn-lg btn-primary no-hover" data-create="{{ trans('misc.edit_campaign') }}" data-send="{{ trans('misc.send_wait') }}">{{ trans('misc.edit_campaign') }}</button>

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
  <script src="{{ asset('public/js/ckeditor/ckeditor.js')}}"></script>
  <script src="{{ asset('public/js/create-edit-campaign.js')}}"></script>
@endsection
