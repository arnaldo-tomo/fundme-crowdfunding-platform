@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/campaigns') }}">{{ __('misc.campaigns') }}</a>
			<i class="bi-chevron-right me-1 fs-6"></i>
			<span class="text-muted">{{ __('admin.edit') }}</span>
			<i class="bi-chevron-right me-1 fs-6"></i>
			<span class="text-muted">{{ $data->title }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

		@include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="post" action="{{ url('panel/admin/campaigns/edit/'.$data->id) }}" enctype="multipart/form-data">
						 <input type="hidden" name="id" value="{{ $data->id }}">
             @csrf

						 <div class="row mb-3">
 		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.thumbnail') }}</label>
 		          <div class="col-sm-10">
 		            <img src="{{asset('public/campaigns/small').'/'.$data->small_image}}" width="180" class="rounded">
 		          </div>
 		        </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.campaign_title') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->title }}" name="title" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.choose_a_category') }}</label>
		          <div class="col-sm-10">
								<select class="form-select" name="categories_id">
									<option value="">{{trans('misc.select_one')}}</option>
								@foreach (App\Models\Categories::where('mode','on')->orderBy('name')->get() as $category)
										<option @if( $category->id == $data->categories_id ) selected="selected" @endif value="{{$category->id}}">{{ $category->name }}</option>
								@endforeach
								</select>
		          </div>
		        </div>

						<div class="row mb-3">
 						 <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.campaign_goal') }}</label>
 						 <div class="col-sm-10">
 							 <input value="{{ $data->goal }}" name="goal" type="number" min="1" autocomplete="off" class="form-control">
 						 </div>
 					 </div><!-- end row -->

					 <div class="row mb-3">
						 <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.location') }}</label>
						 <div class="col-sm-10">
							 <input value="{{ $data->location }}" name="location" type="text" class="form-control">
						 </div>
					 </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.campaign_description') }}</label>
		          <div class="col-sm-10">
                <textarea class="form-control" name="description" rows="4" id="content">{{ $data->description }}</textarea>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.status') }}</label>
		          <div class="col-sm-10">
								<select class="form-select" name="finalized">
									<option @if($data->finalized == '0' && $data->status == 'pending') selected="selected" @endif value="pending">{{trans('admin.pending')}}</option>
									<option @if($data->finalized == '0'  && $data->status == 'active') selected="selected" @endif value="0">{{trans('admin.active')}}</option>
									<option @if($data->finalized == '1'  && $data->status == 'active') selected="selected" @endif value="1">{{trans('misc.finalized')}}</option>
								</select>
		          </div>
		        </div>

						<fieldset class="row mb-3">
		          <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.featured') }}</legend>
		          <div class="col-sm-10">
		            <div class="form-check form-switch form-switch-md">
		             <input class="form-check-input" type="checkbox" name="featured" @if ($data->featured == '1') checked="checked" @endif value="1" role="switch">
		           </div>
		          </div>
		        </fieldset><!-- end row -->

						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection

@section('javascript')
<script src="{{ asset('public/js/ckeditor-init.js') }}?v={{$settings->version}}" type="text/javascript"></script>
@endsection
