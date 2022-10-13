<div class="dropdown-menu dropdown-menu-macos dropdown-menu dropdown-menu-end dd-menu-user" aria-labelledby="dropdown03">

  @if (auth()->user()->role == 'admin')
  <a class="dropdown-item" href="{{ url('panel/admin') }}">{{ trans('admin.admin') }}</a>
  <div class="dropdown-divider"></div>
@endif

<a href="{{ url('dashboard') }}" class="dropdown-item">
  {{ trans('admin.dashboard') }}
  </a>

<a href="{{ url('dashboard/campaigns') }}" class="dropdown-item">
{{ trans('misc.campaigns') }}
  </a>

<a href="{{ url('user/likes') }}" class="dropdown-item">
  {{ trans('misc.likes') }}
  </a>

<a href="{{ url('account') }}" class="dropdown-item">
  {{ trans('users.account_settings') }}
  </a>
  <div class="dropdown-divider"></div>

<a href="{{ url('logout') }}" class="logout dropdown-item">
  {{ trans('users.logout') }}
</a>

</div>
