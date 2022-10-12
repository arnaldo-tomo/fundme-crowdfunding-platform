<?php
$categoriesMenu = App\Models\Categories::where('mode','on')->orderBy('name')->take(6)->get();
$categoriesTotal = App\Models\Categories::count();
?>

<div class="btn-block text-center showBanner padding-top-10 padding-bottom-10" style="display:none;">{{trans('misc.cookies_text')}} <button class="btn btn-sm btn-success" id="close-banner">{{trans('misc.agree')}}</button></div>

@if( Auth::check() && Auth::user()->status == 'pending' )
<div class="btn-block margin-top-zero text-center confirmEmail">{{trans('misc.confirm_email')}} <strong>{{Auth::user()->email}}</strong></div>
@endif
<div class="navbar navbar-inverse navbar-px padding-top-10 padding-bottom-10">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">

          	 <?php if( isset( $totalNotify ) ) : ?>
        	<span class="notify"><?php echo $totalNotify; ?></span>
        	<?php endif; ?>

            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('/') }}">
          	<img src="{{ asset('public/img/logo.png') }}" style="max-width: 150px;" class="logo" />
          	</a>
        </div><!-- navbar-header -->

        <div class="navbar-collapse collapse">

        	<ul class="nav navbar-nav navbar-right">

          		<li>
          			<a href="#search"  class="text-uppercase font-default">
        				<i class="glyphicon glyphicon-search"></i> <span class="title-dropdown font-default"><strong>{{ trans('misc.search') }}</strong></span>
        				</a>
          		</li>

          		<li class="dropdown">
        				<a class="text-uppercase font-default" data-toggle="dropdown" href="javascript:void(0);">{{ trans('misc.campaigns') }} <i class="ion-chevron-down margin-lft5"></i></a>

                <!-- DROPDOWN MENU -->
        				<ul class="dropdown-menu arrow-up" role="menu" aria-labelledby="dropdownMenu2">
                  <li><a class="text-overflow" href="{{ url('campaigns/featured') }}">{{ trans('misc.featured') }}</a></li>
        					<li><a class="text-overflow" href="{{ url('/') }}">{{ trans('misc.latest') }}</a></li>
        				</ul><!-- DROPDOWN MENU -->
        			</li>

        		@if( $categoriesTotal != 0 )
        		<li class="dropdown">
        			<a href="javascript:void(0);" data-toggle="dropdown" class="text-uppercase font-default">{{trans('misc.categories')}}
        				<i class="ion-chevron-down margin-lft5"></i>
        				</a>

        				<!-- DROPDOWN MENU -->
        				<ul class="dropdown-menu arrow-up" role="menu" aria-labelledby="dropdownMenu2">
        				@foreach(  $categoriesMenu as $category )
        					<li @if(Request::path() == "category/$category->slug") class="active" @endif>
        						<a href="{{ url('category') }}/{{ $category->slug }}" class="text-overflow">
        						{{ $category->name }}
        							</a>
        					</li>
        					@endforeach

        					@if( $categoriesTotal > 6 )
			        		<li><a href="{{ url('categories') }}">
			        			<strong>{{ trans('misc.view_all') }} <i class="fa fa-long-arrow-right"></i></strong>
			        		</a></li>
			        		@endif
        				</ul><!-- DROPDOWN MENU -->

        		</li><!-- Categories -->
        		@endif

        			@foreach( \App\Models\Pages::where('show_navbar', '1')->get() as $_page )
					 	<li @if(Request::is("page/$_page->slug")) class="active-navbar" @endif>
					 		<a class="text-uppercase font-default" href="{{ url('page',$_page->slug) }}">{{ $_page->title }}</a>
					 		</li>
					 	@endforeach

        		@if( Auth::check() )

        			<li class="dropdown">
			          <a href="javascript:void(0);" data-toggle="dropdown" class="userAvatar myprofile dropdown-toggle">
			          		<img src="{{ asset('public/avatar').'/'.Auth::user()->avatar }}" alt="User" class="img-circle avatarUser" width="21" height="21" />
			          		<span class="title-dropdown font-default"><strong>{{ trans('users.my_profile') }}</strong></span>
			          		<i class="ion-chevron-down margin-lft5"></i>
			          	</a>

			          <!-- DROPDOWN MENU -->
			          <ul class="dropdown-menu arrow-up nav-session" role="menu" aria-labelledby="dropdownMenu4">
	          		 @if( Auth::user()->role == 'admin' )
	          		 	<li>
	          		 		<a href="{{ url('panel/admin') }}" class="text-overflow">
	          		 			<i class="icon-cogs myicon-right"></i> {{ trans('admin.admin') }}</a>
	          		 			</li>
                      <li role="separator" class="divider"></li>
	          		 			@endif

	          		 			<li>
	          		 			<a href="{{ url('dashboard') }}" class="text-overflow">
	          		 				<i class="icon icon-dashboard myicon-right"></i> {{ trans('admin.dashboard') }}
	          		 				</a>
	          		 			</li>

                      <li>
	          		 			<a href="{{ url('dashboard/campaigns') }}" class="text-overflow">
	          		 			<i class="ion ion-speakerphone myicon-right"></i> {{ trans('misc.campaigns') }}
	          		 				</a>
	          		 			</li>

	          		 			<li>
	          		 			<a href="{{ url('user/likes') }}" class="text-overflow">
	          		 				<i class="fa fa-heart myicon-right"></i> {{ trans('misc.likes') }}
	          		 				</a>
	          		 			</li>

	          		 			<li>
	          		 			<a href="{{ url('account') }}" class="text-overflow">
	          		 				<i class="glyphicon glyphicon-cog myicon-right"></i> {{ trans('users.account_settings') }}
	          		 				</a>
	          		 			</li>

	          		 		<li>
	          		 			<a href="{{ url('logout') }}" class="logout text-overflow">
	          		 				<i class="glyphicon glyphicon-log-out myicon-right"></i> {{ trans('users.logout') }}
	          		 			</a>
	          		 		</li>
	          		 	</ul><!-- DROPDOWN MENU -->
	          		</li>

	       <li><a class="log-in custom-rounded" href="{{url('create/campaign')}}" title="{{trans('misc.create_campaign')}}">
					<i class="glyphicon glyphicon-edit"></i> <strong>{{trans('misc.create_campaign')}}</strong></a>
					</li>

					@else

					<li><a class="text-uppercase font-default" href="{{url('login')}}">{{trans('auth.login')}}</a></li>

          @if($settings->registration_active == 'on')
					<li>
						<a class="log-in custom-rounded text-uppercase font-default" href="{{url('register')}}">
						<i class="glyphicon glyphicon-user"></i> {{trans('auth.sign_up')}}
						</a>
						</li>
          @endif

        	  @endif
          </ul>

         </div><!--/.navbar-collapse -->
     </div>
 </div>

<div id="search">
    <button type="button" class="close">×</button>
    <form autocomplete="off" action="{{ url('search') }}" method="get">
        <input type="search" value="" name="q" id="btnItems" placeholder="{{trans('misc.search_query')}}" />
        <button type="submit" class="btn btn-lg no-shadow btn-trans custom-rounded btn_search"  id="btnSearch">{{trans('misc.search')}}</button>
    </form>
</div>
