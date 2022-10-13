@extends('app')

@section('title') {{ trans('misc.donations') }} - @endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
      <div class="container position-relative">
        <h1>{{ trans('misc.donations') }} ({{$data->total()}})</h1>
      </div>
    </div>

<div class="container py-5">

  <div class="row">

    <div class="col-md-3">
      @include('users.navbar-settings')
    </div>

		<!-- Col MD -->
		<div class="col-md-9">

  @if ($data->total() !=  0 && $data->count() != 0)
  <div class="card shadow-sm">
  <div class="table-responsive">
   <table class="table m-0">

        <thead>
         <tr>
            <th class="active">ID</th>
            <th class="active">{{ trans('auth.full_name') }}</th>
            <th class="active">{{ trans_choice('misc.campaigns_plural', 1) }}</th>
            <th class="active">{{ trans('misc.donation') }}</th>
            <th class="active">{{ trans('misc.earnings_net') }}</th>
            <th class="active">{{ trans('admin.date') }}</th>
            <th class="active">{{ trans('admin.actions') }}</th>
          </tr><!-- /.TR -->
          </thead>

        <tbody>
        @foreach ($data as $donation)
          <tr>
            <td>{{ $donation->id }}</td>
            <td>{{ $donation->fullname }}</td>
            <td><a href="{{url('campaign',$donation->campaigns_id)}}" target="_blank">{{ str_limit($donation->campaigns()->title, 10, '...') }} <i class="fa fa-external-link-square"></i></a></td>
            <td>{{ App\Helper::amountFormat($donation->donation) }}</td>
            <td>{{ App\Helper::earningsNetDonation($donation->id, 'user') }}</td>
            <td>{{ date($settings->date_format, strtotime($donation->date)) }}</td>
            <td>
              <button type="button" data-bs-toggle="modal" data-bs-target="#viewDetail{{ $donation->id }}" class="btn btn-success showTooltip rounded-pill btn-sm padding-btn" title="{{ trans('admin.view') }}">
                <i class="bi-eye"></i>
              </button>
            </td>
          </tr><!-- /.TR -->

          <!-- Modal -->
          <div class="modal fade" id="viewDetail{{ $donation->id }}" tabindex="-1" aria-labelledby="viewDetail{{ $donation->id }}" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header border-0">
                  <h5 class="modal-title" id="viewDetail{{ $donation->id }}">{{ __('misc.donation') }} #{{$donation->id}}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <dl class="dl-horizontal">
                  <!-- start -->
                  <dt>{{ trans('auth.full_name') }}</dt>
                  <dd>{{$donation->fullname}}</dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans_choice('misc.campaigns_plural', 1) }}</dt>
                  <dd><a href="{{url('campaign',$donation->campaigns()->id)}}" target="_blank">{{ $donation->campaigns()->title }} <i class="bi-box-arrow-up-right ms-1"></i></a></dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans('auth.email') }}</dt>
                  <dd>{{$donation->email}}</dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans('misc.donation') }}</dt>
                  <dd><strong class="text-success">{{App\Helper::amountFormat($donation->donation)}}</strong></dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans('misc.country')  }}</dt>
                  <dd>{{$donation->country}}</dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans('misc.postal_code') }}</dt>
                  <dd>{{$donation->postal_code}}</dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans('misc.payment_gateway') }}</dt>
                  <dd>{{$donation->payment_gateway}}</dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans('misc.comment') }}</dt>
                  <dd>
                  @if( $donation->comment != '' )
                  {{$donation->comment}}
                  @else
                  -------------------------------------
                  @endif
                  </dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans('admin.date') }}</dt>
                  <dd>{{date($settings->date_format, strtotime($donation->date))}}</dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans('misc.anonymous') }}</dt>
                  <dd>
                  @if( $donation->anonymous == '1' )
                  {{trans('misc.yes')}}
                  @else
                  {{trans('misc.no')}}
                  @endif
                  </dd>
                  <!-- ./end -->

                  <!-- start -->
                  <dt>{{ trans('misc.reward') }}</dt>
                  <dd>
                  @if( $donation->rewards_id )
                  <strong>ID</strong>: {{$donation->rewards()->id}} <br />
                  <strong>{{trans('misc.title')}}</strong>: {{$donation->rewards()->title}} <br />
                  <strong>{{trans('misc.amount')}}</strong>: {{$settings->currency_symbol.$donation->rewards()->amount}} <br />
                  <strong>{{trans('misc.delivery')}}</strong>: {{ date('F, Y', strtotime($donation->rewards()->delivery)) }} <br />
                  <strong>{{trans('misc.description')}}</strong>:{{$donation->rewards()->description}}
                  @else
                  {{trans('misc.no')}}
                  @endif
                  </dd>
                  <!-- ./end -->

                  </dl>
                </div>
              </div>
            </div>
          </div><!-- End Modal -->
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
