<?php

	//$settings = App\Models\AdminSettings::first();

	// Total
	$total_campaigns = App\Models\Campaigns::count();
	$campaigns       = App\Models\Campaigns::orderBy('id','DESC')->take(3)->get();
	$users        = App\Models\User::orderBy('id','DESC')->take(4)->get();
	$total_raised_funds = App\Models\Donations::where('approved','1')->sum('donation');
	$total_donations = App\Models\Donations::count();

?>
@extends('admin.layout')

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
            {{ trans('admin.dashboard') }} v{{$settings->version}}
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('panel/admin') }}"><i class="fa fa-dashboard"></i> {{ trans('admin.home') }}</a></li>
            <li class="active">{{ trans('admin.dashboard') }}</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">

        <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ $total_donations }}</h3>
                  <p>{{ trans_choice('misc.donation_plural', $total_donations) }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-cash"></i>
                </div>
								<a href="{{url('panel/admin/donations')}}" class="small-box-footer">{{trans('misc.view_more')}} <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->

        <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{ \App\Helper::amountFormat($total_raised_funds) }}</h3>
                  <p>{{ trans('misc.funds_raised') }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-social-usd"></i>
                </div>
								<span class="small-box-footer">{{trans('misc.funds_raised')}}</span>
              </div>
            </div><!-- ./col -->

            <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>{{ number_format( \App\Models\User::count()) }}</h3>
                  <p>{{ trans('misc.members') }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-people"></i>
                </div>
								<a href="{{url('panel/admin/members')}}" class="small-box-footer">{{trans('misc.view_more')}} <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->

            <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{ \App\Helper::formatNumber( $total_campaigns ) }}</h3>
                  <p>{{ trans_choice('misc.campaigns_plural', $total_campaigns) }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-speakerphone"></i>
                </div>
								<a href="{{url('panel/admin/campaigns')}}" class="small-box-footer">{{trans('misc.view_more')}} <i class="fa fa-arrow-circle-right"></i></a>
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
		  	<!-- Map box -->
              <div class="box box-solid bg-purple-gradient">
                <div class="box-header">

                  <i class="fa fa-map-marker"></i>
                  <h3 class="box-title">
                    {{ trans('admin.user_countries') }}
                  </h3>
                </div>
                <div class="box-body">
                  <div id="world-map" style="height: 350px; width: 100%;"></div>
                </div><!-- /.box-body-->
              </div>
              <!-- /.box -->
		  </section>

        </div><!-- ./row -->

        <div class="row">
        	<div class="col-md-6">

                  <!-- USERS LIST -->
                  <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title">{{ trans('admin.latest_members') }}</h3>
                      <div class="box-tools pull-right">
                      </div>
                    </div><!-- /.box-header -->

                    <div class="box-body no-padding">
                      <ul class="users-list clearfix">
                        @foreach( $users as $user )
                        <li>
                          <img src="{{ asset('public/avatar').'/'.$user->avatar }}" alt="User Image">
                          <span class="users-list-name">{{ $user->name }}</span>

                          <span class="users-list-date">{{ date($settings->date_format, strtotime($user->date)) }}</span>
                        </li>
                        @endforeach

                      </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->

                    <div class="box-footer text-center">
                      <a href="{{ url('panel/admin/members') }}" class="uppercase">{{ trans('admin.view_all_members') }}</a>
                    </div><!-- /.box-footer -->

                  </div><!--/.box -->
                </div>



           <div class="col-md-6">
             <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">{{ trans('misc.recent_campaigns') }}</h3>
                  <div class="box-tools pull-right">
                  </div>
                </div><!-- /.box-header -->

                @if( $total_campaigns != 0 )
                <div class="box-body">

                  <ul class="products-list product-list-in-box">

                 @foreach( $campaigns as $campaign )
                 <?php
                      	switch (  $campaign->status ) {
							  case 'active':
								  $color_status = 'success';
								  $txt_status = trans('misc.active');
								  break;

							case 'pending':
								  $color_status = 'warning';
								  $txt_status = trans('admin.pending');
								  break;

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
                        	@if( isset($campaign->user()->id) )
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
                  <a href="{{ url('panel/admin/campaigns') }}" class="uppercase">{{ trans('misc.view_all') }}</a>
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
	<script src="{{ asset('public/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}" type="text/javascript"></script>
	<script src="{{ asset('public/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}" type="text/javascript"></script>
	<script src="{{ asset('public/plugins/knob/jquery.knob.js')}}" type="text/javascript"></script>

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
    for ( $i=0; $i < 30; ++$i) {

		$date = date('Y-m-d', strtotime('today - '.$i.' days'));
		$_donations = App\Models\Donations::whereRaw("DATE(date) = '".$date."'")->where('approved','1')->count();
		//print_r(DB::getQueryLog());
    ?>

    { days: '<?php echo $date; ?>', value: <?php echo $_donations ?> },

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

/* jQueryKnob */
  $(".knob").knob();

  //jvectormap data
  var visitorsData = {
  <?php

  $users_countries = App\Models\User::where('countries_id', '<>', '')->groupBy('countries_id')->get();

   foreach ( $users_countries as $key ) {

	$total = App\Models\Countries::find($key->countries_id);
   	?>

 	"{{ $key->country()->country_code }}": {{ $total->users()->count() }}, <?php } ?>
  };

  //World map by jvectormap
  $('#world-map').vectorMap({
    map: 'world_mill_en',
    backgroundColor: "transparent",
    regionStyle: {
      initial: {
        fill: '#e4e4e4',
        "fill-opacity": 1,
        stroke: 'none',
        "stroke-width": 0,
        "stroke-opacity": 1
      }
    },
    series: {
      regions: [{
          values: visitorsData,
          scale: ["#92c1dc", "#00a65a"],
          normalizeFunction: 'polynomial'
        }]
    },
    onRegionLabelShow: function (e, el, code) {
      if (typeof visitorsData[code] != "undefined")
        el.html(el.html() + ': ' + visitorsData[code] + ' {{ trans("admin.registered_members") }}');
    }
  });
  </script>
@endsection
