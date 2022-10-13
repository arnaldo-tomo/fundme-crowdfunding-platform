<ul class="nav nav-pills nav-stacked">

	<li class="margin-bottom-5">
		<!-- **** list-group-item **** -->
	<a href="{{ url('dashboard') }}" class="list-group-item">
		<i class="icon icon-dashboard myicon-right"></i> {{ trans('admin.dashboard') }}
		</a> <!-- **** ./ list-group-item **** -->
	</li>

			<li class="margin-bottom-5">
				<!-- **** list-group-item **** -->
		  <a href="{{ url('account') }}" class="list-group-item @if(Request::is('account'))active @endif">
		  	<i class="icon icon-pencil myicon-right"></i> {{ trans('users.account_settings') }}
		  	</a> <!-- **** ./ list-group-item **** -->
			</li>

		  	<li class="margin-bottom-5">
		  		<!-- **** list-group-item **** -->
		  <a href="{{ url('account/password') }}" class="list-group-item @if(Request::is('account/password'))active @endif">
		  	<i class="icon icon-lock myicon-right"></i> {{ trans('auth.password') }}
		  	</a> <!-- **** ./ list-group-item **** -->
		  	</li>
		</ul>
