@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.campaigns_reported') }} ({{$data->count()}})</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($data->total() !=  0 && $data->count() !=  0)
                  <tr>
										<th class="active">ID</th>
										<th class="active">{{ trans('admin.user') }}</th>
										<th class="active">{{ trans_choice('misc.campaigns_plural', 1) }}</th>
										<th class="active">{{ trans('admin.date') }}</th>
										<th class="active">{{ trans('admin.actions') }}</th>
                   </tr>

                 @foreach ($data as $report)
									 <tr>
										 <td>{{ $report->id }}</td>
										 <td>{{ $report->user()->name }}</td>
										 <td><a href="{{url('campaign',$report->campaigns_id)}}" target="_blank">{{ str_limit($report->campaigns()->title, 20, '...') }} <i class="fa fa-external-link-square"></i></a></td>
										 <td>{{ date($settings->date_format, strtotime($report->created_at)) }}</td>
										 <td>

								{!! Form::open([
								 'method' => 'POST',
								 'url' => 'panel/admin/campaigns/reported/delete',
								 'class' => 'd-inline'
							 ]) !!}
							 {!! Form::hidden('id',$report->id ); !!}
							 {!! Form::button('<i class="bi-trash3"></i>', ['class' => 'btn btn-danger rounded-pill btn-sm e-none actionDelete']) !!}
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

			@if( $data->lastPage() > 1 )
				{{ $data->appends(['q' => $query])->onEachSide(0)->links() }}
			@endif

 		</div><!-- col-lg-12 -->
	</div><!-- end row -->
</div><!-- end content -->
@endsection
