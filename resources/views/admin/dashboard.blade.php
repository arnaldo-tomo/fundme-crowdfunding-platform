@extends('admin.layout')

@section('css')
<script type="text/javascript">
var labelChart = [{!!$label!!}];
var datalastDonations = [{!!$datalastDonations!!}];
</script>
@endsection

@section('content')
	<h4 class="mb-4 fw-light">{{ __('admin.dashboard') }} <small class="fs-6">v{{$settings->version}}</small></h4>

<div class="content">
	<div class="row">

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3>
						<i class="bi-cash-stack me-2 icon-dashboard"></i>
						<span>{{ number_format($total_donations) }}</span>
					</h3>
					<small>{{ trans_choice('misc.donation_plural', $total_donations) }}</small>

					<span class="icon-wrap icon--admin"><i class="bi-cash-stack"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3><i class="bi-currency-dollar me-2 icon-dashboard"></i> {{ App\Helper::earningsNet('admin') }}</h3>
					<small>{{ trans('misc.earnings_net') }}  ({{trans('users.admin') }})</small>

					<span class="icon-wrap icon--admin"><i class="bi-currency-dollar"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3><i class="bi bi-people me-2 icon-dashboard"></i> {{ number_format(App\Models\User::count())}}</h3>
					<small>{{ trans('admin.members') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-people"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3><i class="bi-megaphone me-2 icon-dashboard"></i> {{ number_format($total_campaigns) }}</h3>
					<small>{{ trans_choice('misc.campaigns_plural', $total_campaigns) }}</small>
					<span class="icon-wrap icon--admin"><i class="bi-megaphone"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-12 mt-0 mt-lg-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4 fw-light">{{ trans('misc.donations_last_30_days') }}</h6>
					 <div style="height: 350px">
						<canvas id="chartDonations"></canvas>
					</div>
				 </div>
			 </div>
		</div>

		<div class="col-lg-6 mt-0 mt-lg-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4 fw-light">{{ trans('admin.latest_members') }}</h6>

					 @foreach ($users as $user)
						 <div class="d-flex mb-3">
							  <div class="flex-shrink-0">
							    <img src="{{ asset('public/avatar').'/'.$user->avatar }}" width="50" class="rounded-circle" />
							  </div>
							  <div class="flex-grow-1 ms-3">
							    <h6 class="m-0 text-break">
											{{ $user->name }}
											<small class="float-end badge rounded-pill bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'pending' ? 'info' : 'warning') }}">
												{{ $user->status == 'active' ? trans('misc.active') : ($user->status == 'pending' ? trans('misc.pending') : trans('admin.suspended')) }}
											</small>
									</h6>
									<div class="w-100 small">
										{{ date($settings->date_format, strtotime($user->date)) }}
									</div>
							  </div>
							</div>
					 @endforeach
				 </div>

				 @if ($users->count() != 0)
				 <div class="card-footer bg-light border-0 p-3">
					   <a href="{{ url('panel/admin/members') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
							 {{ trans('admin.view_all_members') }} <i class="bi-arrow-right ml-2"></i>
						 </a>
					 </div>
				 @endif

			 </div>
		</div>

		<div class="col-lg-6 mt-0 mt-lg-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4 fw-light">{{ trans('misc.recent_campaigns') }}</h6>

					 @foreach ($campaigns as $campaign)
						 <div class="d-flex mb-3">
							  <div class="flex-shrink-0">
							    <img src="{{ asset('public/campaigns/small/').'/'.$campaign->small_image }}" width="50" class="rounded" />
							  </div>
							  <div class="flex-grow-1 ms-3">
							    <h6 class="m-0 fw-light text-break">
										<a href="{{ url('campaign', $campaign->id) }}" target="_blank">
											{{ $campaign->title }}
											</a>
											<small class="float-end badge rounded-pill bg-{{ $campaign->status == 'active' ? 'success' : 'warning' }}">
												{{ $campaign->status == 'active' ? trans('misc.active') : trans('misc.pending') }}
											</small>
									</h6>
									<div class="w-100 small">
										{{ trans('misc.by') }} {{ $campaign->user()->name }} / {{ date($settings->date_format, strtotime($campaign->date)) }}
									</div>
							  </div>
							</div>
					 @endforeach

					 @if ($total_campaigns == 0)
						 <small>{{ trans('admin.no_result') }}</small>
					 @endif
				 </div>

				 @if ($total_campaigns != 0)
				 <div class="card-footer bg-light border-0 p-3">
					   <a href="{{ url('panel/admin/campaigns') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
							 {{ trans('misc.view_all') }} <i class="bi-arrow-right ml-2"></i>
						 </a>
					 </div>
					  @endif
			 </div>
		</div>

	</div><!-- end row -->
</div><!-- end content -->
@endsection

@section('javascript')
  <script src="{{ asset('public/js/Chart.min.js') }}"></script>
	<script src="{{ asset('public/js/chart-admin.js') }}"></script>
  @endsection
