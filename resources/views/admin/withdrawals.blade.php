@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.withdrawals') }} ({{$data->total()}})</span>
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

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($data->total() !=  0 && $data->count() != 0)
                  <tr>
										<th class="active">ID</th>
						<th class="active">{{ trans('misc.campaign') }}</th>
							<th class="active">{{ trans('admin.amount') }}</th>
							<th class="active">{{ trans('misc.method') }}</th>
							<th class="active">{{ trans('admin.status') }}</th>
							<th class="active">{{ trans('admin.date') }}</th>
							<th class="active">{{ trans('admin.actions') }}</th>
                   </tr><!-- /.TR -->

            @foreach ($data as $withdrawal)

                   <tr>
                     <td>{{ $withdrawal->id }}</td>
										 <td>
											 <a title="{{$withdrawal->title}}" href="{{ url('campaign',$withdrawal->campaigns()->id) }}" target="_blank">{{ str_limit($withdrawal->campaigns()->title,20,'...') }} <i class="bi-box-arrow-up-right"></i></a>
											 </td>
                     <td>@if($settings->currency_position == 'left'){{ $settings->currency_symbol.$withdrawal->amount }}@else{{$withdrawal->amount.$settings->currency_symbol}}@endif</td>
                     <td>{{ $withdrawal->gateway == 'Bank' ? trans('misc.bank_transfer') : $withdrawal->gateway }}</td>
                     <td>
                       @if ($withdrawal->status == 'paid')
                       <span class="badge bg-success">{{trans('misc.paid')}}</span>
                       @else
                       <span class="badge bg-warning text-dark">{{trans('misc.pending_to_pay')}}</span>
                       @endif
                     </td>
                     <td>{{ date($settings->date_format, strtotime($withdrawal->date)) }}</td>
                     <td>

                       <a href="#" data-bs-toggle="modal" data-bs-target="#viewDetail{{ $withdrawal->id }}" class="btn btn-success showTooltip rounded-pill btn-sm" title="{{trans('admin.view')}}">
                        <i class="bi-eye"></i>
                       </a>
                       </td>

                   </tr><!-- /.TR -->

									 <!-- Modal -->
									 <div class="modal fade" id="viewDetail{{ $withdrawal->id }}" tabindex="-1" aria-labelledby="viewDetail{{ $withdrawal->id }}" aria-hidden="true">
										 <div class="modal-dialog">
											 <div class="modal-content">
												 <div class="modal-header border-0">
													 <h5 class="modal-title" id="viewDetail{{ $withdrawal->id }}">{{ __('misc.withdrawals') }} #{{$withdrawal->id}}</h5>
													 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												 </div>
												 <div class="modal-body">
													 <dl class="dl-horizontal">

													 <!-- start -->
													 <dt>{{ trans_choice('misc.campaigns_plural', 1) }}</dt>
													 <dd><a href="{{url('campaign',$withdrawal->campaigns()->id)}}" target="_blank">{{ $withdrawal->campaigns()->title }} <i class="fa fa-external-link-square"></i></a></dd>
													 <!-- ./end -->

													 @if( $withdrawal->gateway == 'Paypal' )
													 <!-- start -->
													 <dt>{{ trans('admin.paypal_account') }}</dt>
													 <dd>{{$withdrawal->account}}</dd>
													 <!-- ./end -->

													 @else
													 <!-- start -->
													 <dt>{{ trans('misc.bank_details') }}</dt>
													 <dd>{!!App\Helper::checkText($withdrawal->account)!!}</dd>
													 <!-- ./end -->

													 @endif

													 <!-- start -->
													 <dt>{{ trans('admin.amount') }}</dt>
													 <dd><strong class="text-success">@if($settings->currency_position == 'left'){{ $settings->currency_symbol.$withdrawal->amount }}@else{{$withdrawal->amount.$settings->currency_symbol}}@endif</strong></dd>
													 <!-- ./end -->

													 <!-- start -->
													 <dt>{{ trans('misc.payment_gateway') }}</dt>
													 <dd>{{$withdrawal->gateway}}</dd>
													 <!-- ./end -->


													 <!-- start -->
													 <dt>{{ trans('admin.date') }}</dt>
													 <dd>{{date($settings->date_format, strtotime($withdrawal->date))}}</dd>
													 <!-- ./end -->

													 <!-- start -->
													 <dt>{{ trans('admin.status') }}</dt>
													 <dd>
													 @if( $withdrawal->status == 'paid' )
													       <span class="badge bg-success">{{trans('misc.paid')}}</span>
													       @else
													       <span class="badge bg-warning text-dark">{{trans('misc.pending_to_pay')}}</span>
													       @endif
													 </dd>
													 <!-- ./end -->

													 @if( $withdrawal->status == 'paid' )
													 <!-- start -->
													 <dt>{{ trans('misc.date_paid') }}</dt>
													 <dd>
													 {{date('d M, y', strtotime($withdrawal->date_paid))}}
													 </dd>
													 <!-- ./end -->
													 @endif
													 </dl>

												 </div>

												 <div class="modal-footer @if ($withdrawal->status == 'paid') d-none @endif">

													 @if ($withdrawal->gateway == 'Paypal' && $withdrawal->status == 'pending')

					          @php

					          if ($settings->paypal_sandbox == 'true') {
										// SandBox
										$action = "https://www.sandbox.paypal.com/cgi-bin/webscr";
										} else {
										// Real environment
										$action = "https://www.paypal.com/cgi-bin/webscr";
										}

									@endphp

					                 <form name="_xclick" action="{{$action}}" method="post" class="displayInline">
									        <input type="hidden" name="cmd" value="_xclick">
									        <input type="hidden" name="return" value="{{url('panel/admin/withdrawals')}}">
									        <input type="hidden" name="cancel_return"   value="{{url('panel/admin/withdrawals')}}">
									        <input type="hidden" name="notify_url" value="{{url('paypal/withdrawal/ipn')}}">
									        <input type="hidden" name="currency_code" value="{{$settings->currency_code}}">
									        <input type="hidden" name="amount" id="amount" value="{{$withdrawal->amount}}">
									        <input type="hidden" name="custom" value="{{$withdrawal->id}}">
									        <input type="hidden" name="item_name" value="{{ trans('misc.payment_campaigning').' '.$withdrawal->campaigns()->title }}">
									        <input type="hidden" name="business" value="{{$withdrawal->account}}">
									        <button type="submit" class="btn btn-light border"><i class="fab fa-paypal me-2"></i> {{trans('misc.paid_paypal')}}</button>
									        </form>

						        	@endif

					                  @if ($withdrawal->status == 'pending')

					                {!! Form::open([
								            'method' => 'POST',
								            'url' => "panel/admin/withdrawals/paid/$withdrawal->id",
								            'class' => 'd-inline'
									        ]) !!}

						            	{!! Form::submit(trans('misc.mark_paid'), ['class' => 'btn btn-success float-end']) !!}
						        	{!! Form::close() !!}

						        	@endif
												 </div>

											 </div>
										 </div>
									 </div><!-- End Modal -->

                   @endforeach

									@else
										<h5 class="text-center p-5 text-muted fw-light m-0">{{ trans('misc.no_results_found') }}</h5>
									@endif

								</tbody>
								</table>
							</div><!-- /.box-body -->

				 </div><!-- card-body -->
 			</div><!-- card  -->

			@if ($data->lastPage() > 1)
			{{ $data->onEachSide(0)->links() }}
		@endif
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
