<?php
	 $data = App\Models\Donations::leftJoin('campaigns', function($join) {
      $join->on('donations.campaigns_id', '=', 'campaigns.id');
    })
    ->where('campaigns.user_id',Auth::user()->id)
		->where('donations.approved','1')
	->select('donations.*')
	->addSelect('campaigns.title')
	->orderBy('donations.id','DESC')
    ->paginate(20);

	  ?>
@extends('users.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('misc.donations') }} ({{$data->total()}})
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                  		{{ trans('misc.donations') }}
                  	</h3>
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if( $data->total() !=  0 && $data->count() != 0 )
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('auth.full_name') }}</th>
                      <th class="active">{{ trans_choice('misc.campaigns_plural', 1) }}</th>
                      <th class="active">{{ trans('auth.email') }}</th>
                      <th class="active">{{ trans('misc.donation') }}</th>
											<th class="active">{{ trans('misc.earnings_net') }}</th>
											<th class="active">{{ trans('misc.reward') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
											<th class="active">{{ trans('admin.actions') }}</th>
                    </tr><!-- /.TR -->

                  @foreach( $data as $donation )
                    <tr>
                      <td>{{ $donation->id }}</td>
                      <td>{{ $donation->fullname }}</td>
                      <td><a href="{{url('campaign',$donation->campaigns_id)}}" target="_blank">{{ str_limit($donation->campaigns()->title, 10, '...') }} <i class="fa fa-external-link-square"></i></a></td>
                      <td>{{ $donation->email }}</td>
                      <td>{{ App\Helper::amountFormat($donation->donation) }}</td>
											<td>{{ App\Helper::earningsNetDonation($donation->id, 'user') }}</td>
											<td>@if( $donation->rewards_id )<i class="icon-gift"></i>@else - @endif</td>
                      <td>{{ date($settings->date_format, strtotime($donation->date)) }}</td>
											<td> <a href="{{ url('dashboard/donations',$donation->id) }}" class="btn btn-success btn-xs padding-btn">
                      		{{ trans('admin.view') }}
                      	</a>
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
