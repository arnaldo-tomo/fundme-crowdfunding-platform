@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.gallery') }} ({{$data->count()}})</span>

			<a href="#" data-bs-toggle="modal" data-bs-target="#addNew" class="btn btn-sm btn-dark float-lg-end mt-1 mt-lg-0">
				<i class="bi-plus-lg"></i> {{ trans('misc.add_new') }}
			</a>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
                </div>
              @endif

							@include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($data->count() !=  0)
                  <tr>
										<th class="active">ID</th>
										<th class="active">{{ trans('misc.image') }}</th>
										<th class="active">{{ trans('admin.actions') }}</th>
                   </tr>

									 @foreach ($data as $gallery)
										 <tr>
											 <td>{{ $gallery->id }}</td>
											 <td><img src="{{ url('public/gallery', 'thumb-'.$gallery->image) }}" width="100" /></td>
											 <td>
										{!! Form::open([
									 'method' => 'post',
									 'url' => ['panel/admin/gallery/delete', $gallery->id],
									 'id' => 'form'.$gallery->id,
									 'class' => 'displayInline'
								 ]) !!}
								 {!! Form::submit(trans('admin.delete'), ['data-url' => $gallery->id, 'class' => 'btn btn-danger btn-sm padding-btn actionDelete']) !!}
						 {!! Form::close() !!}
													 </td>

										 </tr><!-- /.TR -->
										 @endforeach

									@else
										<h5 class="text-center p-5 text-muted fw-light m-0">{{ trans('misc.no_results_found') }}</h5>
									@endif

								</tbody>
								</table>
							</div><!-- /.box-body -->

				 </div><!-- card-body -->
 			</div><!-- card  -->

 		</div><!-- col-lg-12 -->
	</div><!-- end row -->
</div><!-- end content -->

<!-- ***** Modal Create Subcategories ****** -->
  <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-left" id="myModalLabel"><strong>{{{ trans('misc.add_new') }}}</strong></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- form start -->
       <form class="d-block" method="post" action="{{{ url('panel/admin/gallery/add') }}}" id="addSubForm" enctype="multipart/form-data">
        <div class="modal-body">
          @csrf

					<div class="input-group mb-1">
						<input type="file" accept="image/*" name="image" class="form-control custom-file rounded-pill">
					</div>

        <small class="d-block">{{ trans('misc.recommended_size') }} 1280x850 px</small>
        </div><!-- modal-body -->

        <div class="modal-footer">
          <button type="submit" class="btn btn-success pull-right">{{{ trans('auth.send') }}}</button>
        </div><!-- /.box-footer -->

        </form>
      </div>
    </div>
  </div> <!-- ***** Modal ****** -->
@endsection
