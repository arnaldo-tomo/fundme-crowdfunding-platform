<button class="btn btn-dark mb-4 w-100 d-lg-none no-hover" type="button" data-bs-toggle="collapse" data-bs-target="#navSettings" aria-expanded="false" aria-controls="collapseExample">
    <i class="bi bi-menu-down me-2"></i> {{ trans('admin.main_menu') }}
  </button>

  <div class="card shadow-sm mb-3 collapse d-lg-block card-settings" id="navSettings">
  <div class="list-group list-group-flush">

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('dashboard'))active @endif" href="{{ url('dashboard') }}">
			<div>
				<i class="bi bi-speedometer2 me-2"></i>
				<span>{{ trans('users.dashboard') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('account'))active @endif" href="{{ url('account') }}">
			<div>
				<i class="bi bi-person me-2"></i>
				<span>{{ trans('users.account_settings') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('dashboard/donations'))active @endif" href="{{ url('dashboard/donations') }}">
			<div>
				<i class="bi-cash-stack me-2"></i>
				<span>{{ trans('misc.donations') }}</span>
			</div>

			<div>
				<i class="bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('dashboard/campaigns'))active @endif" href="{{ url('dashboard/campaigns') }}">
			<div>
				<i class="bi-megaphone me-2"></i>
				<span>{{ trans('misc.campaigns') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('account/password'))active @endif" href="{{ url('account/password') }}">
			<div>
				<i class="bi bi-key me-2"></i>
				<span>{{ trans('auth.password') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('dashboard/withdrawals'))active @endif" href="{{ url('dashboard/withdrawals') }}">
			<div>
				<i class="bi bi-arrow-left-right me-2"></i>
				<span>{{ trans('misc.withdrawals') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('dashboard/withdrawals/configure'))active @endif" href="{{ url('dashboard/withdrawals/configure') }}">
			<div>
				<i class="bi bi-credit-card me-2"></i>
				<span>{{ trans('misc.withdrawals') }} {{ trans('misc.configure') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

  </div>
</div>
