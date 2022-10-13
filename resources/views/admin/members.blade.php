@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.members') }} ({{$data->total()}})</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

      @if (session('info_message'))
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <i class="bi-exclamation-triangle me-1"></i>	{{ session('info_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
                </div>
              @endif

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

          <div class="d-inline-block mb-2 w-100">

          @if ($data->count() != 0)
            <!-- form -->
            <form role="search" autocomplete="off" action="{{ url('panel/admin/members') }}" method="get" class="position-relative">
							<i class="bi bi-search btn-search-2 bar-search"></i>
             <input type="text" name="q" class="form-control ps-5" placeholder="{{ __('misc.search') }}">
          </form><!-- form -->
            @endif
          </div>

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($data->total() !=  0 && $data->count() != 0)
                  <tr>
										<th class="active">ID</th>
										<th class="active">{{ trans('auth.full_name') }}</th>
										<th class="active">{{ trans_choice('misc.campaigns_plural', 0) }}</th>
										<th class="active">{{ trans('admin.date') }}</th>
										<th class="active">{{ trans('admin.actions') }}</th>
                   </tr>

                 @foreach ($data as $user)
									 <tr>
										 <td>{{ $user->id }}</td>
										 <td><img src="{{asset('public/avatar').'/'.$user->avatar}}" width="40" height="40" class="rounded-circle me-1" /> {{ $user->name }}</td>
										 <td>{{ $user->campaigns()->count() }}</td>
										 <td>{{ date($settings->date_format, strtotime($user->date)) }}</td>
										 <td>
										@if( $user->id <> Auth::user()->id && $user->id <> 1 )

									<div class="d-flex">
									<a href="{{ route('user.edit', $user->id) }}" class="btn btn-success rounded-pill btn-sm me-2">
												 <i class="bi-pencil"></i>
											 </a>

									{!! Form::open([
								 'method' => 'DELETE',
								 'route' => ['user.destroy', $user->id],
								 'id' => 'form'.$user->id,
								 'class' => 'd-inline'
							 ]) !!}
							 {!! Form::button('<i class="bi-trash3"></i>', ['data-url' => $user->id,  'class' => 'btn btn-danger rounded-pill btn-sm e-none actionDelete']) !!}
					 {!! Form::close() !!}
				 </div>

				@else
				 ------------
												 @endif

												 </td>

									 </tr><!-- /.TR -->
                   @endforeach

									@else
										<h5 class="text-center p-5 text-muted fw-light m-0">{{ trans('misc.no_results_found') }}

                      @if (isset($query))
                        <div class="d-block w-100 mt-2">
                          <a href="{{url('panel/admin/members')}}"><i class="bi-arrow-left me-1"></i> {{ trans('auth.back') }}</a>
                        </div>
                      @endif
                    </h5>
									@endif

								</tbody>
								</table>
							</div><!-- /.box-body -->

				 </div><!-- card-body -->
 			</div><!-- card  -->

		@if( $data->lastPage() > 1 )
			{{ $data->appends(['q' => $query])->onEachSide(0)->links() }}
		@endif
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
