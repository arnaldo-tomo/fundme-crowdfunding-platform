<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\AdminSettings;

class ViewServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		try {
			// Admin Settings
			 $settings = AdminSettings::first();
			 View()->share('settings', $settings);
		} catch (\Exception $exception) {
			// Nothing
		}

	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{

	}

}
