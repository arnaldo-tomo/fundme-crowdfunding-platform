@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/members') }}">{{ __('admin.members') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.edit') }}</span>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ $data->name }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

    @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form class="form-horizontal" method="POST" action="{{ url('panel/admin/members/'.$data->id) }}" enctype="multipart/form-data">
             @csrf
						 <input type="hidden" name="_method" value="PUT">

             <div class="row mb-3">
 		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.avatar') }}</label>
 		          <div class="col-sm-10">
 		            <img src="{{asset('public/avatar').'/'.$data->avatar}}" width="80" height="80" class="rounded-circle" />
 		          </div>
 		        </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.name') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->name }}" name="name" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('auth.email') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->email }}" name="email" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.role') }}</label>
              <div class="col-sm-10">
                <select name="role" class="form-select">
									<option @if($data->role == 'normal') selected="selected" @endif value="normal">{{trans('admin.normal')}} {{trans('admin.normal_user')}}</option>
									<option @if($data->role == 'admin') selected="selected" @endif value="admin">{{trans('misc.admin')}}</option>
               </select>
              </div>
            </div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-label text-lg-end">{{ trans('auth.password') }}</label>
							<div class="col-sm-10">
								<input name="password" type="password" class="form-control" placeholder="{{ trans('admin.password_no_change') }}">
							</div>
						</div>

						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5 me-2">{{ __('admin.save') }}</button>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
