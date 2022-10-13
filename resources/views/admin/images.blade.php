@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.images') }} ({{$data->total()}})</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

              @if (session('info_message'))
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                      <i class="bi bi-check2 me-1"></i>	{{ session('info_message') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                          <i class="bi bi-x-lg"></i>
                        </button>
                        </div>
                      @endif

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

          <div class="d-lg-flex justify-content-lg-between align-items-center mb-2 w-100">

          @if ($data->count() != 0)

						@if (! request()->get('q'))
							<select class="form-select d-inline-block w-auto filter">
	              <option @if ($sort == '') selected="selected" @endif value="{{ url()->current() }}">{{ trans('admin.sort_id') }}</option>
	              <option @if ($sort == 'pending') selected="selected" @endif value="{{ url()->current() }}?sort=pending">{{ trans('admin.pending') }}</option>
	              <option @if ($sort == 'title') selected="selected" @endif value="{{ url()->current() }}?sort=title">{{ trans('admin.sort_title') }}</option>
	              <option @if ($sort == 'likes') selected="selected" @endif value="{{ url()->current() }}?sort=likes">{{ trans('admin.sort_likes') }}</option>
	              <option @if ($sort == 'downloads') selected="selected" @endif value="{{ url()->current() }}?sort=downloads">{{ trans('admin.sort_downloads') }}</option>
	        			</select>
						@endif

						<!-- form -->
            <form class="mt-lg-0 mt-2 position-relative" role="search" autocomplete="off" action="{{ url('panel/admin/images') }}" method="get" class="position-relative">
							<i class="bi bi-search btn-search bar-search"></i>
             <input type="text" name="q" class="form-control ps-5 w-auto" value="{{ request()->get('q') }}" placeholder="{{ __('misc.search') }}">
          </form><!-- form -->
					@endif
          </div>

        <div class="table-responsive p-0">
            <table class="table table-hover">
         <tbody>

          @if ($data->total() !=  0 && $data->count() != 0)
             <tr>
                <th class="active">ID</th>
                <th class="active">{{ trans('misc.thumbnail') }}</th>
                <th class="active">{{ trans('admin.title') }}</th>
                <th class="active">{{ trans('misc.uploaded_by') }}</th>
                <th class="active">{{ trans('admin.type') }}</th>
                <th class="active">{{ trans('misc.likes') }}</th>
                <th class="active">{{ trans('misc.downloads') }}</th>
                <th class="active">{{ trans('admin.date') }}</th>
                <th class="active">{{ trans('admin.status') }}</th>
                <th class="active">{{ trans('admin.actions') }}</th>
              </tr>

            @foreach ($data as $image)
              <tr>
                <td>{{ $image->id }}</td>
                <td><img src="{{Storage::url(config('path.thumbnail').$image->thumbnail)}}" class="rounded" width="50" /></td>
                <td><a href="{{ url('photo', $image->id) }}" title="{{$image->title}}" target="_blank">{{ str_limit($image->title, 10, '...') }} <i class="fa fa-external-link-square"></i></a></td>
                <td>{{ $image->user()->username }}</td>
                <td>{{ $image->item_for_sale == 'sale' ? trans('misc.sale') : trans('misc.free')  }}</td>
                <td>{{ $image->likes()->count() }}</td>
                <td>{{ $image->downloads()->count() }}</td>
                <td>{{ Helper::formatDate($image->date) }}</td>
                <td>
                  <span class="badge rounded-pill bg-{{ $image->status == 'active' ? 'success' : 'warning' }}">
                    {{ $image->status == 'active' ? trans('admin.active') : trans('admin.pending') }}
                </span>
              </td>
                <td>

             <a href="{{ url('panel/admin/images', $image->id) }}" class="text-reset fs-5 me-2">
                    <i class="far fa-edit"></i>
                  </a>

             {!! Form::open([
            'method' => 'POST',
            'url' => 'panel/admin/images/delete',
            'class' => 'd-inline-block align-top'
          ]) !!}

          {!! Form::hidden('id', $image->id); !!}
          {!! Form::button('<i class="bi-trash-fill"></i>', ['data-url' => $image->id, 'class' => 'btn btn-link text-danger e-none fs-5 p-0 actionDelete']) !!}
      {!! Form::close() !!}

          </td>
        </tr><!-- /.TR -->
        @endforeach

              @else
                <h5 class="text-center p-5 text-muted fw-light m-0">
                  {{ trans('misc.no_results_found') }}

                  @if (isset($query) || isset($sort))
                    <div class="d-block w-100 mt-2">
                      <a href="{{url('panel/admin/images')}}"><i class="bi-arrow-left me-1"></i> {{ trans('auth.back') }}</a>
                    </div>
                  @endif
                </h5>

              @endif

            </tbody>
            </table>
          </div><!-- /.table responsive -->
				 </div><!-- card-body -->
 			</div><!-- card  -->

      {{ $data->appends(['q' => $query, 'sort' => $sort])->links() }}
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
