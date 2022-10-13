@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/pages') }}">{{ __('admin.pages') }}</a>
			<i class="bi-chevron-right me-1 fs-6"></i>
			<span class="text-muted">{{ __('misc.add_new') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

		@include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="post" action="{{ url('panel/admin/pages') }}">
             @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.title') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ old('title') }}" name="title" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.slug') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ old('slug') }}" name="slug"  type="text" class="form-control">
                <small class="d-block"><strong>{{ trans('misc.important') }}: {{ trans('misc.slug_lang_info') }}</strong></small>
		          </div>
		        </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.language') }}</label>
		          <div class="col-sm-10">
		            <select name="lang" class="form-select">
                  @foreach (App\Models\Languages::orderBy('name')->get() as $language)
                    <option @if ($language->abbreviation == session('locale')) selected="selected" @endif value="{{$language->abbreviation}}">{{ $language->name }}</option>
                  @endforeach
		           </select>
               <small class="d-block">{{ trans('misc.page_lang') }}</small>
		          </div>
		        </div>

						<fieldset class="row mb-3">
              <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('admin.show_navbar') }}</legend>
              <div class="col-sm-10">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="show_navbar" value="1" id="flexRadioDefault1">
									<label class="form-check-label" for="flexRadioDefault1">
										{{ trans('misc.yes') }}
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="show_navbar" id="flexRadioDefault2" value="0" checked>
									<label class="form-check-label" for="flexRadioDefault2">
										{{ trans('misc.no') }}
									</label>
								</div>
              </div>
            </fieldset><!-- end row -->

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.content') }}</label>
		          <div class="col-sm-10">
                <textarea class="form-control" name="content" rows="4" id="content">{{ old('content') }}</textarea>
		          </div>
		        </div>

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
