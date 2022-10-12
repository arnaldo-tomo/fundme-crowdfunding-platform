@extends('users.layout')

@section('css')
<link href="{{ asset('public/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            {{ trans('admin.dashboard') }}
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('admin.home') }}</a></li>
            <li class="active">{{ trans('admin.dashboard') }}</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">

        <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ $donations->count() }}</h3>
                  <p>{{ trans_choice('misc.donation_plural', $donations->count()) }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-cash"></i>
                </div>
								<a href="{{url('dashboard/donations')}}" class="small-box-footer">{{trans('misc.view_more')}} <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->

        <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{ App\Helper::earningsNet('user') }}</h3>
                  <p>{{ trans('misc.earnings_net') }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-social-usd"></i>
                </div>
								<span class="small-box-footer">{{trans('misc.earnings_net')}}</span>
              </div>
            </div><!-- ./col -->

            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{ \App\Helper::formatNumber($total_campaigns) }}</h3>
                  <p>{{ trans_choice('misc.campaigns_plural', $total_campaigns) }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-speakerphone"></i>
                </div>
								<a href="{{url('dashboard/campaigns')}}" class="small-box-footer">{{trans('misc.view_more')}} <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
          </div>

        <div class="row">

			<section class="col-md-7">
			  <div class="nav-tabs-custom">
			    <ul class="nav nav-tabs pull-right ui-sortable-handle">
			        <li class="pull-left header"><i class="ion ion-cash"></i> {{ trans('misc.donations_last_30_days') }}</li>
			    </ul>
			    <div class="tab-content">
			        <div class="tab-pane active">
			          <div class="chart" id="chart1"></div>
			        </div>
			    </div>
			</div>
		  </section>

			<section class="col-md-5">

          <div class="box box-solid bg-teal-gradient">
            <div class="box-header">
              <i class="fa fa-th"></i>

              <h3 class="box-title">{{ trans('misc.funds_raised_last') }}</h3>
            </div>
            <div class="box-body border-radius-none">
              <div class="chart" id="line-chart"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
		  </section>

        </div><!-- ./row -->

        <div class="row">


					<div class="col-md-6">
						<div class="box box-primary">
							 <div class="box-header with-border">
								 <h3 class="box-title">{{ trans('misc.recent_donations') }}</h3>
								 <div class="box-tools pull-right">
								 </div>
							 </div><!-- /.box-header -->

							 @if ($donations->count() != 0)
							 <div class="box-body">

								 <ul class="products-list product-list-in-box">

								@foreach ($donations->take(1)->get() as $donation)

									 <li class="item">
										 <div class="product-img">
											 <img src="{{ asset('public/avatar/default.jpg') }}" style="height: auto !important;" />
										 </div>
										 <div class="product-info">
											 <a href="{{ url('campaign',$donation->campaigns_id) }}" target="_blank" class="product-title">{{ $donation->title }}
												 <span class="label label-success pull-right">{{App\Helper::amountFormat($donation->donation)}}</span>
												 </a>
											 <span class="product-description">
												 {{ trans('misc.by') }} {{ $donation->fullname }} / {{ date($settings->date_format, strtotime($donation->date)) }}
											 </span>
										 </div>
									 </li><!-- /.item -->
									 @endforeach
								 </ul>
							 </div><!-- /.box-body -->

							 <div class="box-footer text-center">
								 <a href="{{ url('dashboard/donations') }}" class="uppercase">{{ trans('misc.view_all') }}</a>
							 </div><!-- /.box-footer -->

							 @else
								<div class="box-body">
								 <h5>{{ trans('admin.no_result') }}</h5>
									</div><!-- /.box-body -->

							 @endif

						 </div>
					 </div>



           <div class="col-md-6">
             <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">{{ trans('misc.recent_campaigns') }}</h3>
                  <div class="box-tools pull-right">
                  </div>
                </div><!-- /.box-header -->

                @if ($total_campaigns != 0)
                <div class="box-body">

                  <ul class="products-list product-list-in-box">

                 @foreach($campaigns as $campaign)
                 <?php
                switch ($campaign->status) {
							  case 'active':
								  $color_status = 'success';
								  $txt_status = trans('misc.active');
								  break;

							case 'pending':
								  $color_status = 'warning';
								  $txt_status = trans('admin.pending');
								  break;
						  }

							if($campaign->finalized == 1) {
								$color_status = 'default';
								$txt_status = trans('misc.finalized');
							}
                       ?>
                    <li class="item">
                      <div class="product-img">
                        <img src="{{ asset('public/campaigns/small/').'/'.$campaign->small_image }}" style="height: auto !important;" />
                      </div>
                      <div class="product-info">
                        <a href="{{ url('campaign',$campaign->id) }}" target="_blank" class="product-title">{{ $campaign->title }}
                        	<span class="label label-{{ $color_status }} pull-right">{{ $txt_status }}</span>
                        	</a>
                        <span class="product-description">
                        	@if (isset($campaign->user()->id))
                          {{ trans('misc.by') }} {{ $campaign->user()->name }} / {{ date($settings->date_format, strtotime($campaign->date)) }}
                          @else
                          {{ trans('misc.user_not_available') }}
                          @endif
                        </span>
                      </div>
                    </li><!-- /.item -->
                    @endforeach
                  </ul>
                </div><!-- /.box-body -->

                <div class="box-footer text-center">
                  <a href="{{ url('dashboard/campaigns') }}" class="uppercase">{{ trans('misc.view_all') }}</a>
                </div><!-- /.box-footer -->

                @else
                 <div class="box-body">
                	<h5>{{ trans('admin.no_result') }}</h5>
                	 </div><!-- /.box-body -->

                @endif

              </div>
            </div>


        </div><!-- ./row -->

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')

	<!-- Morris -->
	<script src="{{ asset('public/plugins/morris/raphael-min.js')}}" type="text/javascript"></script>
	<script src="{{ asset('public/plugins/morris/morris.min.js')}}" type="text/javascript"></script>

	<!-- knob -->

	<script type="text/javascript">

var IndexToMonth = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];

//** Charts
new Morris.Area({
  // ID of the element in which to draw the chart.
  element: 'chart1',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    <?php
    for ($i=0; $i < 30; ++$i) {

		$date = date('Y-m-d', strtotime('today - '.$i.' days'));

		$__donations = App\Models\Donations::leftJoin('campaigns', function($join) {
			 $join->on('donations.campaigns_id', '=', 'campaigns.id');
		 })
		 ->where('campaigns.user_id',Auth::user()->id)
		 ->where('donations.approved','1')
		 ->whereRaw("DATE(donations.date) = '".$date."'")->count();

		//print_r(DB::getQueryLog());
    ?>

    { days: '<?php echo $date; ?>', value: <?php echo $__donations ?> },

    <?php } ?>
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'days',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['{{ trans("misc.donations") }}'],
  pointFillColors: ['#FF5500'],
  lineColors: ['#DDD'],
  hideHover: 'auto',
  gridIntegers: true,
  resize: true,
  xLabelFormat: function (x) {
                  var month = IndexToMonth[ x.getMonth() ];
                  var year = x.getFullYear();
                  var day = x.getDate();
                  return  day +' ' + month;
                  //return  year + ' '+ day +' ' + month;
              },
          dateFormat: function (x) {
                  var month = IndexToMonth[ new Date(x).getMonth() ];
                  var year = new Date(x).getFullYear();
                  var day = new Date(x).getDate();
                  return day +' ' + month;
                  //return year + ' '+ day +' ' + month;
              },
});// <------------ MORRIS

var line = new Morris.Line({
    element          : 'line-chart',
    resize           : true,
		xLabelFormat: function (x) {
	                  var month = IndexToMonth[ x.getMonth() ];
	                  var year = x.getFullYear();
	                  var day = x.getDate();
	                  return  day +' ' + month;
	                  //return  year + ' '+ day +' ' + month;
	              },
	          dateFormat: function (x) {
	                  var month = IndexToMonth[ new Date(x).getMonth() ];
	                  var year = new Date(x).getFullYear();
	                  var day = new Date(x).getDate();
	                  return day +' ' + month;
	                  //return year + ' '+ day +' ' + month;
	              },
    data             : [
			<?php
	    for ($i=0; $i < 30; ++$i) {

			$date = date('Y-m-d', strtotime('today - '.$i.' days'));

			$__donations = App\Models\Donations::leftJoin('campaigns', function($join) {
				 $join->on('donations.campaigns_id', '=', 'campaigns.id');
			 })
			 ->where('campaigns.user_id',Auth::user()->id)
			 ->where('donations.approved','1')
			 ->whereRaw("DATE(donations.date) = '".$date."'")->sum('donation');

			//print_r(DB::getQueryLog());
	    ?>

	    { days: '<?php echo $date; ?>', item1: <?php echo $__donations ?> },

	    <?php } ?>

    ],
    xkey             : 'days',
    ykeys            : ['item1'],
    labels           : ['{{ trans("admin.amount") }}'],
    lineColors       : ['#efefef'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    pointStrokeColors: ['#efefef'],
    gridLineColor    : '#efefef',
    gridTextFamily   : 'Open Sans',
    gridTextSize     : 10
  });
  </script>
@endsection
