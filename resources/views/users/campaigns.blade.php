@extends('app')

@section('title') {{ trans('misc.campaigns') }} - @endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
      <div class="container position-relative">
        <h1>{{ trans('misc.campaigns') }} ({{$data->total()}})</h1>
        @if(  $data->total() !=  0 && $data->count() != 0 )
        <p>
          * {{trans('misc.fund_detail_alert')}}
        </p>
        @endif
      </div>
    </div>

<div class="container py-5">

  <div class="row">

    <div class="col-md-3">
      @include('users.navbar-settings')
    </div>

		<!-- Col MD -->
		<div class="col-md-9">

      @if (session('notification'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi-exclamation-triangle mr-2"></i> {{ session('notification') }} <a href="{{url('dashboard/withdrawals/configure')}}">{{ trans('misc.configure') }} <i class="bi-arrow-right"></i></a>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

  @if ($data->total() !=  0 && $data->count() != 0)
  <div class="card shadow-sm">
  <div class="table-responsive">
   <table class="table m-0">

        <thead>
         <tr>
           <th class="active">ID</th>
           <th class="active">{{ trans('misc.title') }}</th>
           <th class="active">{{ trans('misc.goal') }}</th>
           <th class="active">{{ trans('misc.funds_raised') }}</th>
           <th class="active">{{ trans('admin.status') }}</th>
           <th class="active">{{ trans('admin.date') }}</th>
           <th class="active">{{ trans('misc.date_deadline') }}</th>
           <th class="active">{{ trans('admin.actions') }}</th>
          </tr><!-- /.TR -->
          </thead>

        <tbody>
        @foreach($data as $campaign )

          @php

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

            if ($amount == 0) {
              $funds = 0;
            } else {
              $funds = $funds;
            }

           @endphp

           <tr>
             <td>{{ $campaign->id }}</td>
             <td>
               @if( $campaign->status == 'active' )
               <a title="{{$campaign->title}}" href="{{ url('campaign',$campaign->id) }}" target="_blank">
                 {{ str_limit($campaign->title,20,'...') }} <i class="fa fa-external-link-square"></i>
                 </a>
                 @else
                 <span title="{{$campaign->title}}" class="text-muted">
                 {{ str_limit($campaign->title,20,'...') }}
                 @endif

               </td>
             <td>{{ App\Helper::amountFormat($campaign->goal) }}</td>
             <td>{{ $settings->currency_code == 'JPY' ? $settings->currency_symbol.round($funds) : \App\Helper::amountFormat($funds, 2)}}</td>
             <td>
               @if( $campaign->status == 'active' && $campaign->finalized == 0 )
               <span class="badge bg-success">{{trans('misc.active')}}</span>
               @elseif( $campaign->status == 'pending' && $campaign->finalized == 0 )
               <span class="badge bg-warning text-dark">{{trans('admin.pending')}}</span>
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
               @if( $campaign->finalized == 0 )
               <a href="{{ url('edit/campaign',$campaign->id) }}" class="btn btn-success btn-sm rounded-pill padding-btn showTooltip" title="{{ trans('admin.edit') }}">
                 <i class="bi-pencil"></i>
               </a>
             @else

             @if( isset( $campaign->withdrawals()->id ) && $campaign->withdrawals()->status == 'pending'  )
               <span class="badge bg-warning text-dark">{{trans('misc.pending_to_pay')}}</span>

               @elseif (isset($campaign->withdrawals()->id) && $campaign->withdrawals()->status == 'paid'  )

               <span class="badge bg-success">{{trans('misc.paid')}}</span>

               @else

          @if ($funds != 0)

          {!! Form::open([
       'method' => 'POST',
       'url' => "campaign/withdrawal/$campaign->id",
       'class' => 'displayInline'
     ]) !!}

     {!! Form::submit(trans('misc.make_withdrawal'), ['class' => 'btn btn-success btn-sm padding-btn no-hover']) !!}
 {!! Form::close() !!}

             @else
             -
             @endif

         @endif

     @endif

             </td>
           </tr><!-- /.TR -->
          @endforeach
        </tbody>
      </table>

      </div><!-- /.table-responsive -->
    </div><!-- /.card -->

    @if ($data->lastPage() > 1)
      {{ $data->onEachSide(0)->links() }}
   @endif

 @else
   <h4 class="mt-0 fw-light">
     {{ trans('misc.no_results_found') }}
   </h4>
 @endif

		</div><!-- /COL MD -->
    </div><!-- / Wrap -->
 </div><!-- container -->

 <!-- container wrap-ui -->
@endsection
