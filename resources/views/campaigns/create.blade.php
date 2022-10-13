@extends('app')

@section('title'){{ trans('misc.create_campaign').' - ' }}@endsection

@section('content')
 <div class="jumbotron mb-0 bg-sections text-center">
      <div class="container wrap-jumbotron position-relative">
      	<h1 class="title-site">{{ trans('misc.create_campaign') }}</h1>
        <p class="mb-0">
          {{ trans('misc.create_campaign_desc') }}
        </p>
      </div>
    </div>

<div class="container py-5">
	<div class="row">

    <div class="wrap-container-lg">
	<!-- col-md-8 -->
	<div class="col-md-12">

    @if (auth()->user()->status == 'active')
    <!-- form start -->
    <form method="POST" action="{{ url('create/campaign') }}" enctype="multipart/form-data" id="formUpload">

    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="filer-input-dragDrop position-relative" id="draggable">
			<input type="file" accept="image/*" name="photo" id="filePhoto">

			<!-- previewPhoto -->
			<div class="previewPhoto">

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
                <input type="text" value="{{ old('title') }}" name="title" id="title" class="form-control" placeholder="{{ trans('misc.campaign_title') }}">
            </div><!-- /.form-group-->

            <!-- Start Form Group -->
               <div class="form-group">
                 <label>{{ trans('misc.video') }}</label>
                   <input type="text" value="{{ old('video') }}" name="video" id="video" class="form-control" placeholder="{{ trans('misc.video_description') }} ({{ trans('misc.optional') }})">
                   <small class="text-muted">{{ trans('misc.video_description_2') }}</small>
               </div><!-- /.form-group-->

            <!-- Start Form Group -->
            <div class="form-group">
              <label>{{ trans('misc.choose_a_category') }}</label>
              	<select name="categories_id" class="form-select">
              		<option value="">{{trans('misc.select_one')}}</option>
              	@foreach (App\Models\Categories::where('mode','on')->orderBy('name')->get() as $category)
                    <option value="{{$category->id}}">
                      {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
                    </option>
                  @endforeach
                  </select>
                </div><!-- /.form-group-->

                  <div class="form-group">
      				    <label>{{ trans('misc.campaign_goal') }}</label>
      				    <div class="input-group">
                      <span class="input-group-text">{{$settings->currency_symbol}}</span>
      				      <input type="number" min="1" class="form-control" name="goal" id="onlyNumber" value="{{ old('goal') }}" placeholder="10000">
      				    </div>
                  <div class="alert alert-warning mt-2 small" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ trans('misc.info_campaign_goal', ['percentage' => $settings->fee_donation]) }}
                  </div>
      				  </div>

                <!-- Start Form Group -->
                  <div class="form-group">
                    <label>{{ trans('misc.location') }}</label>
                      <input type="text" value="{{ old('location') }}" name="location" class="form-control" placeholder="{{ trans('misc.location') }}">
                  </div><!-- /.form-group-->

                  <!-- Start Form Group -->
                  <div class="form-group">
                    <label>{{ trans('misc.deadline') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      <input type="text" value="{{ old('deadline') }}" id="datepicker" name="deadline" class="form-control" placeholder="{{ trans('misc.deadline') }}" autocomplete="off">
                    </div>
                    <small class="text-muted">{{ trans('misc.deadline_desc') }}</small>
                  </div><!-- /.form-group-->


                  <div class="form-group">
                      <label>{{ trans('misc.campaign_description') }}</label>
                      	<textarea name="description" rows="4" id="description" class="form-control" placeholder="{{ trans('misc.campaign_description_placeholder') }}">{{ old('description') }}</textarea>
                        <small class="text-muted">{{ trans('misc.campaign_description_placeholder') }}</small>
                    </div>

                    <!-- Alert -->
            <div class="alert alert-danger d-none-custom" id="dangerAlert">
							<ul class="list-unstyled m-0" id="showErrors"></ul>
						</div><!-- Alert -->

                  <div class="box-footer">
                  	<hr />
                    <button type="submit" id="buttonFormSubmit" class="btn btn-block btn-lg btn-primary no-hover" data-create="{{ trans('misc.create_campaign') }}" data-send="{{ trans('misc.send_wait') }}" data-error="{{ trans('misc.error') }}">
                      {{ trans('misc.create_campaign') }}
                    </button>
                  </div><!-- /.box-footer -->
                </form>

                @else

	<div class="btn-block text-center mb-2">
	    			<i class="fa fa-exclamation-triangle display-4"></i>
	    		</div>

	   <h3 class="margin-top-none text-center no-result no-result-mg">
	    	{{trans('misc.confirm_email')}} <strong>{{Auth::user()->email}}</strong>
	    	</h3>

                @endif

               </div><!-- wrap-center -->
		</div><!-- col-md-12-->

	</div><!-- row -->
</div><!-- container -->

@endsection

@section('javascript')
	<script src="{{ asset('public/js/ckeditor/ckeditor.js')}}"></script>
  <script src="{{ asset('public/js/create-edit-campaign.js')}}"></script>
@endsection
