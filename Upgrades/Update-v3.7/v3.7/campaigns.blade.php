@extends('users.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('misc.campaigns') }} ({{$data->total()}})
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

          @if (session('notification'))
    			<div class="alert alert-warning" role="alert">
    				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                		{{ session('notification') }} <a href="{{url('dashboard/withdrawals/configure')}}">{{ trans('misc.configure') }} <i class="fa fa-long-arrow-right"></i></a>
                		</div>
                	@endif


        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h5>
                    @if(  $data->total() !=  0 && $data->count() != 0 )
                    * {{trans('misc.fund_detail_alert')}}
                    @endif
                  </h5>
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if( $data->total() !=  0 && $data->count() != 0 )
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
                    </tr><!-- /.TR -->

                  @foreach( $data as $campaign )

                  <?php

                  $amount = $campaign->donations()->sum('donation');

                  $funds = 0;

                  foreach ($campaign->donations as $key) {

                    foreach (PaymentGateways::all() as $payment) {

                      $paymentGateway = strtolower($payment->name);
                      $paymentGatewayDonation = strtolower($key->payment_gateway);

                      if($paymentGatewayDonation == $paymentGateway) {
                        $fee   = $payment->fee;
                        $cents = $payment->fee_cents;

                        $_amountGlobal = $key->donation - (  $key->donation * $fee/100 ) - $cents;
                        $funds += $_amountGlobal - (  $_amountGlobal * $settings->fee_donation/100  );

                      } // IF

                    }// PaymentGateways()

                  }//<-- foreach

                 	  if($amount == 0) {
                 	  	$funds = 0;
                 	  } else {
                 	  	$funds = $funds;
                 	  }
                   ?>

                    <tr>
                      <td>{{ $campaign->id }}</td>
                      <td><img src="{{asset('public/campaigns/small').'/'.$campaign->small_image}}" width="20" />

                      	@if( $campaign->status == 'active' )
                      	<a title="{{$campaign->title}}" href="{{ url('campaign',$campaign->id) }}" target="_blank">
                      		{{ str_limit($campaign->title,20,'...') }} <i class="fa fa-external-link-square"></i>
                      		</a>
                      		@else
                      		<span title="{{$campaign->title}}" class="text-muted">
                      		{{ str_limit($campaign->title,20,'...') }}
                      		@endif

                      	</td>
                      <td>
                      	@if( isset($campaign->user()->id) )
                      	{{$campaign->user()->name}}
                      	@else
                      	{{ trans('misc.user_not_available') }}
                          @endif
                      	</td>
                      <td>{{ App\Helper::amountFormat($campaign->goal) }}</td>
                      <td>{{\App\Helper::amountFormat($funds, 2)}}</td>
                      <td>
                      	@if( $campaign->status == 'active' && $campaign->finalized == 0 )
                      	<span class="label label-success">{{trans('misc.active')}}</span>
                      	@elseif( $campaign->status == 'pending' && $campaign->finalized == 0 )
                      	<span class="label label-warning">{{trans('admin.pending')}}</span>
                      	@else
                      	<span class="label label-default">{{trans('misc.finalized')}}</span>
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
                        @if( $campaign->finalized == 0 )
                        <a href="{{ url('edit/campaign',$campaign->id) }}" target="_blank" class="btn btn-success btn-xs padding-btn">
                      		{{ trans('admin.edit') }}
                      	</a>
                      @else

                      @if( isset( $campaign->withdrawals()->id ) && $campaign->withdrawals()->status == 'pending'  )
                        <span class="label label-warning">{{trans('misc.pending_to_pay')}}</span>

                        @elseif( isset( $campaign->withdrawals()->id ) && $campaign->withdrawals()->status == 'paid'  )

                        <span class="label label-success">{{trans('misc.paid')}}</span>

                        @else

                   @if( $funds != 0 )

                   {!! Form::open([
                'method' => 'POST',
                'url' => "campaign/withdrawal/$campaign->id",
                'class' => 'displayInline'
              ]) !!}

              {!! Form::submit(trans('misc.make_withdrawal'), ['class' => 'btn btn-success btn-xs padding-btn']) !!}
          {!! Form::close() !!}

                      @else
                      -
                      @endif

                  @endif

              @endif

                      </td>
                    </tr><!-- /.TR -->
                    @endforeach

                    @else
                    <hr />
                    	<h3 class="text-center no-found">{{ trans('misc.no_results_found') }}</h3>

                    @endif

                  </tbody>

                  </table>

                </div><!-- /.box-body -->
              </div><!-- /.box -->
              @if( $data->lastPage() > 1 )
             {{ $data->links() }}
             @endif
            </div>
          </div>

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
