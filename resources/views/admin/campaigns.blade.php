@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.campaigns') }}</span>
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

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

                  @if ($data->count() !=  0)
                     <tr>
											 <th class="active">ID</th>
                       <th class="active">{{ trans('misc.title') }}</th>
                       <th class="active">{{ trans('admin.user') }}</th>
                       <th class="active">{{ trans('misc.goal') }}</th>
                       <th class="active">{{ trans('misc.funds_raised') }}</th>
                       <th class="active">{{ trans('admin.status') }}</th>
                       <th class="active">{{ trans('admin.date') }}</th>
                       <th class="active">{{ trans('misc.date_deadline') }}</th>
                       <th class="active">{{ trans('admin.actions') }}</th>
                      </tr>

                    @foreach ($data as $campaign)
											<tr>
	                      <td>{{ $campaign->id }}</td>
	                      <td>
	                      	@if( $campaign->status == 'active' )
	                      	<a title="{{$campaign->title}}" href="{{ url('campaign',$campaign->id) }}" target="_blank">
	                      		{{ str_limit($campaign->title,20,'...') }} <i class="bi-box-arrow-up-right"></i>
	                      		</a>
	                      		@else
	                      		<span title="{{$campaign->title}}" class="text-muted">
	                      		{{ str_limit($campaign->title,20,'...') }}
	                      		@endif

	                      	</td>
	                      <td>
	                      	{{$campaign->user()->name ?? trans('misc.user_not_available')}}
	                      	</td>
	                      <td>{{ App\Helper::amountFormat($campaign->goal) }}</td>
	                      <td>{{ App\Helper::amountFormat($campaign->donations()->sum('donation')) }}</td>
	                      <td>
	                      	@if( $campaign->status == 'active' && $campaign->finalized == 0 )
	                      	<span class="badge bg-success">{{trans('misc.active')}}</span>
	                      	@elseif( $campaign->status == 'pending' && $campaign->finalized == 0 )
	                      	<span class="badge bg--warning">{{trans('admin.pending')}}</span>
	                      	@else
	                      	<span class="badge bg-secondary">{{trans('misc.finalized')}}</span>
	                      	@endif
	                      </td>
	                      <td>{{ date($settings->date_format, strtotime($campaign->date)) }}</td>
	                      <td>
	                      	@if( $campaign->deadline != '' )
	                      	{{ date($settings->date_format, strtotime($campaign->deadline)) }}
	                      	@else
	                      	 -
	                      	@endif
	                      	</td>
	                      <td>
													<div class="d-flex">
													<a href="{{ url('panel/admin/campaigns/edit', $campaign->id) }}" class="btn btn-success rounded-pill btn-sm me-2">
	                      		<i class="bi-pencil"></i>
	                      	</a>

													@if ($campaign->donations()->count() == 0)
														<form method="POST" action="{{ url('panel/admin/campaign/delete') }}" accept-charset="UTF-8" class="d-inline-block">
															@csrf
															<input name="id" type="hidden" value="{{ $campaign->id }}">
							               <button data-url="208" class="btn btn-danger rounded-pill btn-sm e-none actionDelete" type="button">
															 <i class="bi-trash3"></i>
														 </button>
													 </form>
													  @endif
													</div>

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
			{{ $data->onEachSide(0)->links() }}
		@endif

 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
