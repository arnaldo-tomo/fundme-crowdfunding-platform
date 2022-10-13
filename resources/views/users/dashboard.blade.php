@extends('app')

@section('title') {{ trans('admin.dashboard') }} - @endsection

  @section('css')
  <script type="text/javascript">
  var labelChart = [{!!$label!!}];
  var dataFundsChart = [{!!$data!!}];
  var datalastDonations = [{!!$datalastDonations!!}];
  </script>
  @endsection

@section('content')
  <div class="jumbotron mb-0 bg-sections text-center">
        <div class="container position-relative">
          <h1>{{ trans('admin.dashboard') }}</h1>
        </div>
      </div>

<div class="container py-5">
  <div class="row">

    <div class="col-md-3">
      @include('users.navbar-settings')
    </div>

		<!-- Col MD -->
		<div class="col-md-9">

			<div class="content">
				<div class="row">
					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm overflow-hidden">
							<div class="card-body">
								<h5><i class="bi-cash-stack me-2 icon-dashboard"></i> {{ $donations->count() }}</h5>
								<small>{{ trans_choice('misc.donation_plural', $donations->count()) }}</small>
                <span class="icon-wrap opacity-25">
                  <i class="bi-cash-stack"></i>
                </span>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm overflow-hidden">
							<div class="card-body">
								<h5><i class="bi-currency-dollar me-2 icon-dashboard"></i> {{ App\Helper::earningsNet('user') }}</h5>
								<small>{{ trans('misc.earnings_net') }}
								</small>
                <span class="icon-wrap opacity-25">
                  <i class="bi-currency-dollar"></i>
                </span>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm overflow-hidden">
							<div class="card-body">
								<h5>
									<i class="bi-megaphone me-2 icon-dashboard"></i>
									<span>{{ number_format($total_campaigns) }}</span>
								</h5>
								<small>{{ trans_choice('misc.campaigns_plural', $total_campaigns) }}</small>
                <span class="icon-wrap opacity-25">
                  <i class="bi-megaphone"></i>
                </span>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-12 mt-3 py-4">
						 <div class="card shadow-sm">
							 <div class="card-body">
								 <h5 class="mb-4">{{ trans('misc.funds_raised_last') }}</h5>
								 <div style="height: 350px">
									<canvas id="Chart"></canvas>
								</div>
							 </div>
						 </div>
					</div>

					<div class="col-lg-12 mt-3 py-4">
						 <div class="card shadow-sm">
							 <div class="card-body">
								 <h5 class="mb-4">{{ trans('misc.donations_last_30_days') }}</h5>
								 <div style="height: 350px">
									<canvas id="ChartSales"></canvas>
								</div>
							 </div>
						 </div>
					</div>

				</div><!-- end row -->
			</div><!-- end content -->

		</div><!-- /COL MD -->
  </div><!-- row -->
 </div><!-- container -->
 <!-- container wrap-ui -->
@endsection

@section('javascript')
  <script src="{{ asset('public/js/Chart.min.js') }}"></script>
  <script src="{{ asset('public/js/chart-user.js') }}"></script>
  @endsection
