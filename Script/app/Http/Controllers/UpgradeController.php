<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Donations;
use App\Models\PaymentGateways;
use DB;

class UpgradeController extends Controller {

	public function __construct(AdminSettings $settings) {
	 $this->settings = $settings::first();
 }

	/**
	 * Move a file
	 *
	 */
	private static function moveFile($file, $newFile, $copy)
	{
		if (File::exists($file) && $copy == false) {
				File::delete($newFile);
				File::move($file, $newFile);
		} else if(File::exists($newFile) && isset($copy)) {
				File::copy($newFile, $file);
		}
	}

	/**
	 * Copy a directory
	 *
	 */
	private static function moveDirectory($directory, $destination, $copy)
	{
		if(File::isDirectory($directory) && $copy == false) {
				File::moveDirectory($directory, $destination);
		} else if(File::isDirectory($destination) && isset($copy)) {
				File::copyDirectory($destination, $directory);
		}
	}

	public function update($version)
	{
		$DS = DIRECTORY_SEPARATOR;

		$ROOT = base_path().$DS;
		$APP = app_path().$DS;
		$MODELS = app_path('Models').$DS;
		$CONTROLLERS = app_path('Http'. $DS . 'Controllers').$DS;
		$CONTROLLERS_AUTH = app_path('Http'. $DS . 'Controllers'. $DS . 'Auth').$DS;
		$TRAITS = app_path('Http'. $DS . 'Controllers'. $DS . 'Traits').$DS;
		$MIDDLEWARE = app_path('Http'. $DS . 'Middleware'). $DS;
		$PROVIDERS = app_path('Providers').$DS;

		$CONFIG = config_path().$DS;

		$ROUTES = base_path('routes').$DS;

		$PUBLIC_JS = public_path('js').$DS;
		$PUBLIC_ADMIN_JS = public_path('admin'. $DS . 'js').$DS;
		$PUBLIC_CSS = public_path('css').$DS;
		$PUBLIC_IMG = public_path('img').$DS;

		$VIEWS = resource_path('views').$DS;
		$VIEWS_ADMIN = resource_path('views'. $DS . 'admin').$DS;
		$VIEWS_AJAX = resource_path('views'. $DS . 'ajax').$DS;
		$VIEWS_AUTH = resource_path('views'. $DS . 'auth').$DS;
		$VIEWS_AUTH_PASSWORDS = resource_path('views'. $DS . 'auth'. $DS . 'passwords' ).$DS;
		$VIEWS_CAMPAIGNS = resource_path('views'. $DS . 'campaigns').$DS;
		$VIEWS_DEFAULT = resource_path('views'. $DS . 'default').$DS;
		$VIEWS_EMAILS = resource_path('views'. $DS . 'emails').$DS;
		$VIEWS_ERRORS = resource_path('views'. $DS . 'errors').$DS;
		$VIEWS_INCLUDES = resource_path('views'. $DS . 'includes').$DS;
		$VIEWS_INDEX = resource_path('views'. $DS . 'index').$DS;
		$VIEWS_INSTALL = resource_path('views'. $DS . 'installer').$DS;
		$VIEWS_LAYOUTS = resource_path('views'. $DS . 'layouts').$DS;
		$VIEWS_PAGES = resource_path('views'. $DS . 'pages').$DS;
		$VIEWS_USERS = resource_path('views'. $DS . 'users').$DS;

		$upgradeDone = '<h2 style="text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #4BBA0B;">'.trans('admin.upgrade_done').' <a style="text-decoration: none; color: #F50;" href="'.url('/').'">'.trans('error.go_home').'</a></h2>';

		//<---------------------------- Version 1.2
		if( $version == '1.2' ) {

			 $category = Categories::first();

			if( isset($category->image) ) {
				return redirect('/');
			} else {

				Schema::table('categories', function($table){
					$table->string('image', 200)->after('mode');
				 });

				return $upgradeDone;
			}
		}//<------------------------ Version 1.2

		//<-------------------------- Version 1.6
		if( $version == '1.6' ) {

			$admin = AdminSettings::first();

			if( isset($admin->auto_approve_campaigns) ) {
				return redirect('/');
			} else {

				Schema::table('admin_settings', function($table){
					$table->enum('auto_approve_campaigns', ['0', '1'])->default('1')->after('fee_donation');
				 });

				return $upgradeDone;
			}
		}//<------------------------- Version 1.6

		//<------------------------ Version 1.7
		if( $version == '1.7' ) {

			$admin = AdminSettings::first();
			$campaigns = Campaigns::first();

			if( isset($admin->max_donation_amount) && isset( $campaigns->featured ) ) {
				return redirect('/');
			} else {

				Schema::table('admin_settings', function($table){
					$table->unsignedInteger('max_donation_amount')->after('stripe_public_key');
				 });

				 Schema::table('campaigns', function($table){
				 	$table->enum('featured', ['0', '1'])->default('0')->after('categories_id');
				 });

				return $upgradeDone;
			}
		}//<---------------------- Version 1.7

		//<------------------------ Version 1.8
		if( $version == '1.8' ) {


			if( Schema::hasTable('campaigns_reported') ) {
				return redirect('/');
			} else {

				 Schema::create('campaigns_reported', function ($table) {

				    $table->engine = 'InnoDB';

				    $table->increments('id');
					$table->unsignedInteger('user_id');
					$table->unsignedInteger('campaigns_id');
					$table->timestamp('created_at');
				});

				return $upgradeDone;
			}
		}//<---------------------- Version 1.8


		//<------------------------ Version 1.9.1
		if( $version == '1.9.1' ) {


			if( Schema::hasTable('likes') ) {
				return redirect('/');
			} else {

				 Schema::create('likes', function ($table) {

				    $table->engine = 'InnoDB';

				    $table->increments('id');
					$table->unsignedInteger('user_id');
					$table->unsignedInteger('campaigns_id');
					$table->enum('status', ['0', '1'])->default('1');
					$table->timestamp('date');
				});

				return $upgradeDone;
			}
		}//<---------------------- Version 1.9.1

		//<------------------------ Version 2.0
		if( $version == '2.0' ) {

			$query = Campaigns::first();

			if( isset($query->deadline) ) {
				return redirect('/');
			} else {

				Schema::table('campaigns', function($table){
					$table->string('deadline', 200)->after('featured');
				 });

				return $upgradeDone;
			}
		}//<------------------------- Version 2.0

		//<------------------------ Version 2.2
		if( $version == '2.2' ) {

			$query = Donations::first();
			$admin = AdminSettings::first();

			if( Schema::hasTable('rewards') && isset($query->rewards_id) ) {
				return redirect('/');
				exit;
			} else {

				  Schema::create('rewards', function ($table) {

				  $table->engine = 'InnoDB';

				  $table->increments('id');
					$table->unsignedInteger('campaigns_id');
					$table->string('title', 200);
					$table->unsignedInteger('amount');
					$table->text('description');
					$table->unsignedInteger('quantity');
					$table->string('delivery', 200);
				});

				Schema::table('donations', function($table){
					$table->unsignedInteger('rewards_id')->after('anonymous');
				});

			}//ELSE

			if( isset($admin->enable_paypal)
		 		&& isset( $admin->enable_stripe )
				&& isset( $admin->enable_bank_transfer )

				&& isset( $admin->bank_swift_code )
				&& isset( $admin->account_number )
				&& isset( $admin->branch_name )
				&& isset( $admin->branch_address )
				&& isset( $admin->account_name )
				&& isset( $admin->iban )

				&& isset( $query->bank_swift_code )
				&& isset( $query->account_number )
				&& isset( $query->branch_name )
				&& isset( $query->branch_address )
				&& isset( $query->account_name )
				&& isset( $query->iban )
				&& isset( $query->approved )
			 ) {
				return redirect('/');
				exit;
			} else {

				Schema::table('admin_settings', function($table){
					$table->enum('enable_paypal', ['0', '1'])->default('0')->after('max_donation_amount');
					$table->enum('enable_stripe', ['0', '1'])->default('0');
					$table->enum('enable_bank_transfer', ['0', '1'])->default('0');

					$table->string('bank_swift_code', 250);
					$table->string('account_number', 250);
					$table->string('branch_name', 250);
					$table->string('branch_address', 250);
					$table->string('account_name', 250);
					$table->string('iban', 250);
				 });

				 Schema::table('donations', function($table){
 					$table->string('bank_swift_code', 250)->after('rewards_id');
 					$table->string('account_number', 250);
 					$table->string('branch_name', 250);
 					$table->string('branch_address', 250);
 					$table->string('account_name', 250);
 					$table->string('iban', 250);
 					$table->enum('approved', ['0', '1'])->default('1');
 				 });

			}

			return $upgradeDone;


		}//<------------------------- Version 2.2

		//<<---- Version 2.6 ----->>
		if( $version == '2.6' ) {

			 // Add Link to Pages Terms and Privacy
			 if( !Schema::hasColumn('admin_settings', 'link_terms', 'link_privacy', 'date_format', 'currency_position' ) ) {
				 Schema::table('admin_settings', function($table){
 					$table->string('link_terms', 200)->after('iban');
					$table->string('link_privacy', 200)->after('iban');
					$table->string('date_format', 200)->after('iban');
					$table->enum('currency_position', ['left', 'right'])->default('left');
 				 });
			 }// <<--- End Add Link to Pages Terms and Privacy


					return $upgradeDone;

		}//<<---- Version 2.6 ----->>

		//<---------------------------- Version 2.8
		if( $version == '2.8' ) {

			 $user = User::first();

			if( isset($user->username)
		 		&& isset($user->oauth_provider)
			  && isset($user->oauth_uid)){
				return redirect('/');
			} else {

				Schema::table('users', function($table){
					$table->string('username', 50)->after('bank');
					$table->string('oauth_provider', 200)->after('bank');
					$table->string('oauth_uid', 200)->after('bank');
				 });

				return $upgradeDone;
			}
		}//<------------------------ Version 2.8

		//<------------------------ Version 3.2
		if( $version == '3.2' ) {

			$admin = AdminSettings::first();

			if($admin->enable_bank_transfer == 1) {
				$textBankInfo = "".trans('misc.bank_swift_code').": ".$admin->bank_swift_code." \n".trans('misc.account_number').": ".$admin->account_number."\n".trans('misc.branch_name').": ".$admin->branch_name."\n".trans('misc.branch_address').": ".$admin->branch_address."\n".trans('misc.account_name').": ".$admin->account_name."\n".trans('misc.iban').": ".$admin->iban."";
			} else {
				$textBankInfo = '';
			}

			// Create table payment_gateways
		if( ! Schema::hasTable('payment_gateways') ) {

				 Schema::create('payment_gateways', function ($table) {

				  $table->engine = 'InnoDB';

				  $table->increments('id');
					$table->string('name', 50);
					$table->string('type');
					$table->enum('enabled', ['1', '0'])->default('1');
					$table->enum('sandbox', ['true', 'false'])->default('true');
					$table->decimal('fee', 3, 1);
					$table->decimal('fee_cents', 2, 2);
					$table->string('email', 80);
					$table->string('token', 200);
					$table->string('key', 255);
					$table->string('key_secret', 255);
					$table->text('bank_info');
				});

				\DB::table('payment_gateways')->insert([
					[
						'name' => 'PayPal',
						'type' => 'normal',
						'enabled' => $admin->enable_paypal,
						'fee' => 5.4,
						'fee_cents' => 0.30,
						'email' => 'paypal@yoursite.com',
						'key' => '',
						'key_secret' => '',
						'bank_info' => '',
						'token' => '12bGGfD9bHevK3eJN06CdDvFSTXsTrTG44yGdAONeN1R37jqnLY1PuNF0mJRoFnsEygyf28yePSCA1eR0alQk4BX89kGG9Rlha2D2KX55TpDFNR5o774OshrkHSZLOFo2fAhHzcWKnwsYDFKgwuaRg',
				],
				[
					'name' => 'Stripe',
					'type' => 'card',
					'enabled' => $admin->enable_stripe,
					'fee' => 2.9,
					'fee_cents' => 0.30,
					'email' => '',
					'key' => $admin->stripe_public_key,
					'key_secret' => $admin->stripe_secret_key,
					'bank_info' => '',
					'token' => 'asfQSGRvYzS1P0X745krAAyHeU7ZbTpHbYKnxI2abQsBUi48EpeAu5lFAU2iBmsUWO5tpgAn9zzussI4Cce5ZcANIAmfBz0bNR9g3UfR4cserhkJwZwPsETiXiZuCixXVDHhCItuXTPXXSA6KITEoT',
			],
			[
				'name' => 'Bank Transfer',
				'type' => 'bank',
				'enabled' => $admin->enable_bank_transfer,
				'fee' => 0.0,
				'fee_cents' => 0.00,
				'email' => '',
				'key' => '',
				'key_secret' => '',
				'bank_info' => $textBankInfo,
				'token' => 'zzzdH5811lZSjioHrg3zLD69DAAMvPLiwdzTouAdc7HbtaqgujPEZjH3i7RGeRtFKrY2baT7rXd6CaBtsRpo4XtgHvqCyCWiW5BlCrg1uSMCOSdi1tzPjCPx8px280YEyLvNtiRzWHJJk8WRegfTms',
		]
				]);

			}// End create table payment_gateways

			// Add bank_transfer DB
			if( !Schema::hasColumn('donations', 'bank_transfer') ) {
				Schema::table('donations', function($table){
				 $table->text('bank_transfer');
				});
			}// <<--- End Add bank_transfer DB

			return $upgradeDone;
		}//<---------------------- Version 3.2

		//<<---- Version 3.6 ----->>
		if( $version == '3.6' ) {

			 if (! Schema::hasColumn('admin_settings',
			 		'facebook_login',
					'google_login',
					'decimal_format',
					'registration_active',
					'color_default',
					'version'
					) ) {

						if(config('services.facebook.client_id') != 'APP_ID' && config('services.facebook.client_id') != '') {
							$status = 'on';
						} else {
							$status = 'off';
						}

				 Schema::table('admin_settings', function($table) use ($status) {
					 $table->enum('facebook_login', ['on', 'off'])->default($status);
					 $table->enum('google_login', ['on', 'off'])->default('off');
					 $table->enum('decimal_format', ['comma', 'dot'])->default('dot');
					 $table->enum('registration_active', ['on', 'off'])->default('on');
					 $table->string('color_default', 100);
					 $table->string('version', 5);
 				 });

				 if (Schema::hasColumn('admin_settings', 'version')) {
					 		AdminSettings::whereId(1)->update([
 								'version' => '3.6'
 							]);
 				}

				file_put_contents(
						'routes/web.php',
						"\nRoute::get('panel/admin/theme','AdminController@theme')->middleware('role');\nRoute::post('panel/admin/theme','AdminController@themeStore')->middleware('role');\n",
						FILE_APPEND
				);

					$file = 'routes/web.php';
					file_put_contents($file, str_replace('facebook', 'facebook|google', file_get_contents($file)));

				 $replace = "'google'=> [
	         'client_id' => 'GOOGLE_CLIENT_ID',
	         'client_secret' => 'GOOGLE_CLIENT_SECRET',
	         'redirect' => 'http://YOURSITE.COM/oauth/google/callback', // IMPORTANT NOT REMOVE /oauth/google/callback
				 ],
			 ];";

	 			$fileConfig = 'config/services.php';
	 			file_put_contents($fileConfig, str_replace('];', $replace, file_get_contents($fileConfig)));

				//============ Starting moving files...
	 			$path = "v$version/";
	 			$pathAdmin = "v$version/admin/";
	 			$copy = false;

	 			// app
	 			$filePathApp1 = $path.'SocialAccountService.php';
	 			$pathApp1 = app_path('SocialAccountService.php');

	 			$filePathApp2 = $path.'Helper.php';
	 			$pathApp2 = app_path('Helper.php');

	 			// Controllers
	 			$filePathController1 = $path.'AdminController.php';
	 			$pathController1 = app_path('Http/Controllers/AdminController.php');

	 			$filePathController2 = $path.'DonationsController.php';
	 			$pathController2 = app_path('Http/Controllers/DonationsController.php');

	 			$filePathController3 = $path.'RegisterController.php';
	 			$pathController3 = app_path('Http/Controllers/Auth/RegisterController.php');

	 			// public
	 			$filePathPublic1 = $path.'google.svg';
	 			$pathPublic1 = public_path('img/google.svg');

	 			$filePathPublic2 = $path.'tagsinput';
	 			$pathPublic2 = public_path('plugins/tagsinput');

	 			// resources
	 			$filePathResources1 = $pathAdmin.'dashboard.blade.php';
				$filePathResources2 = $pathAdmin.'settings.blade.php';
	 			$filePathResources2 = $pathAdmin.'settings.blade.php';
	 			$filePathResources3 = $pathAdmin.'payments-settings.blade.php';
	 			$filePathResources4 = $pathAdmin.'members.blade.php';

				$filePathResources5 = $path.'login.blade.php';
				$filePathResources6 = $path.'register.blade.php';
				$filePathResources7 = $path.'navbar.blade.php';
				$filePathResources8 = $path.'list-campaigns.blade.php';
				$filePathResources9 = $path.'embed.blade.php';
				$filePathResources10 = $path.'campaigns.blade.php';
				$filePathResources11 = $path.'view.blade.php';
				$filePathResources12 = $pathAdmin.'layout.blade.php';
				$filePathResources13 = $pathAdmin.'theme.blade.php';
				$filePathResources14 = $path.'app.blade.php';
				$filePathResources15 = $path.'donate.blade.php';

				$pathResources1 = resource_path('views/admin/dashboard.blade.php');
				$pathResources2 = resource_path('views/admin/settings.blade.php');
				$pathResources2 = resource_path('views/admin/settings.blade.php');
				$pathResources3 = resource_path('views/admin/payments-settings.blade.php');
				$pathResources4 = resource_path('views/admin/members.blade.php');

	 			$pathResources5 = resource_path('views/auth/login.blade.php');
	 			$pathResources6 = resource_path('views/auth/register.blade.php');
	 			$pathResources7 = resource_path('views/includes/navbar.blade.php');
	 			$pathResources8 = resource_path('views/includes/list-campaigns.blade.php');
	 			$pathResources9 = resource_path('views/includes/embed.blade.php');
	 			$pathResources10 = resource_path('views/users/campaigns.blade.php');
	 			$pathResources11 = resource_path('views/campaigns/view.blade.php');

				$pathResources12 = resource_path('views/admin/layout.blade.php');
				$pathResources13 = resource_path('views/admin/theme.blade.php');
				$pathResources14 = resource_path('views/app.blade.php');
				$pathResources15 = resource_path('views/default/donate.blade.php');

	 			//============== Moving Files ================//
	 			$this->moveFile($filePathApp1, $pathApp1, $copy);
	 			$this->moveFile($filePathApp2, $pathApp2, $copy);

	 			$this->moveFile($filePathController1, $pathController1, $copy);
	 			$this->moveFile($filePathController2, $pathController2, $copy);
	 			$this->moveFile($filePathController3, $pathController3, $copy);

	 			$this->moveFile($filePathPublic1, $pathPublic1, $copy);
	 			$this->moveDirectory($filePathPublic2, $pathPublic2, $copy);

	 			$this->moveFile($filePathResources1, $pathResources1, $copy);
	 			$this->moveFile($filePathResources2, $pathResources2, $copy);
	 			$this->moveFile($filePathResources3, $pathResources3, $copy);
	 			$this->moveFile($filePathResources4, $pathResources4, $copy);
	 			$this->moveFile($filePathResources5, $pathResources5, $copy);
	 			$this->moveFile($filePathResources6, $pathResources6, $copy);
	 			$this->moveFile($filePathResources7, $pathResources7, $copy);
	 			$this->moveFile($filePathResources8, $pathResources8, $copy);
	 			$this->moveFile($filePathResources9, $pathResources9, $copy);
	 			$this->moveFile($filePathResources10, $pathResources10, $copy);
	 			$this->moveFile($filePathResources11, $pathResources11, $copy);
				$this->moveFile($filePathResources12, $pathResources12, $copy);
				$this->moveFile($filePathResources13, $pathResources13, $copy);
				$this->moveFile($filePathResources14, $pathResources14, $copy);
				$this->moveFile($filePathResources15, $pathResources15, $copy);

	 			//============ End Moving Files ===============//

				 File::deleteDirectory("v$version");

			 }// <<--- End hasColumn

			 return $upgradeDone;

		}//<<---- Version 3.6 ----->>

		//<<---- Version 3.7 ----->>
		if($version == '3.7') {

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != '3.6' || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version 3.6</h2>";
			}

				//============ Starting moving files...
	 			$path           = "v$version/";
	 			$pathAdmin      = "v$version/admin/";
	 			$copy           = true;

	 			//============== Files ================//
				$file1 = $path.'AdminController.php';
				$file2 = $path.'DonationsController.php';
				$file3 = $path.'campaigns.blade.php';
				$file4 = $path.'list-campaigns.blade.php';
				$file5 = $path.'embed.blade.php';


				//============== Paths ================//
				$path1 = app_path('Http/Controllers/AdminController.php');
				$path2 = app_path('Http/Controllers/DonationsController.php');
				$path3 = resource_path('views/users/campaigns.blade.php');
				$path4 = resource_path('views/includes/list-campaigns.blade.php');
				$path5 = resource_path('views/includes/embed.blade.php');

	 			//============== Moving Files ================//
				$this->moveFile($file1, $path1, $copy);
				$this->moveFile($file2, $path2, $copy);
				$this->moveFile($file3, $path3, $copy);
				$this->moveFile($file4, $path4, $copy);
				$this->moveFile($file5, $path5, $copy);

	 			//============ End Moving Files ===============//

				// Delete folder
				if ($copy == false) {
				 File::deleteDirectory("v$version");
			 }

				 // Update Version
 				$this->settings->whereId(1)->update([
 							'version' => $version
 						]);

			 return $upgradeDone;
		}
		//<<---- Version 3.7 ----->>

		//<<---- Version 3.8 ----->>
		if($version == '3.8') {

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != '3.7' || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version 3.7</h2>";
			}

			file_put_contents(
					'routes/web.php',
					'
Route::post("ajax/upload/image", "AjaxController@uploadImageEditor")->name("upload.image")->middleware("auth");
Route::get("campaigns/latest","HomeController@campaignsLatest");
Route::get("campaigns/popular","HomeController@campaignsPopular");

Route::get("donation/pending/{id}", function($id){
		session()->put("donation_pending", trans("misc.donation_pending"));
		return redirect("campaign/".$id);
});',
					FILE_APPEND
			);

	 			//============ End Moving Files ===============//

			 if (! file_exists('public/campaigns/editor')) {
		 		mkdir('public/campaigns/editor', 0777, true);
		 	}

				 // Update Version
 				$this->settings->whereId(1)->update([
 							'version' => $version
 						]);

				\Artisan::call('view:clear');
				\Artisan::call('cache:clear');
				\Artisan::call('config:clear');

			 return $upgradeDone;
		}
		//<<---- Version 3.8 ----->>

		//<<---- Version 3.9 ----->>
		if($version == '3.9') {

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != '3.8' || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version 3.8</h2>";
			}

				 // Update Version
 				$this->settings->whereId(1)->update([
 							'version' => $version
 						]);

				\Artisan::call('view:clear');
				\Artisan::call('cache:clear');
				\Artisan::call('config:clear');

			 return $upgradeDone;
		}
		//<<---- Version 3.9 ----->>

		//<<---- Version 4.0 ----->>
		if($version == '4.0') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = false;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != $oldVersion || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version 3.9</h2>";
			}

			// Replace String
			$findStringLang = ');';
			$replaceLang    = "
	//----- Version 4.0
	'protected_recaptcha' => 'protected by reCAPTCHA',
	'gallery' => 'Gallery',
	'add_payment' => 'Add Payment',
	'image' => 'Image',
	'captcha_on_donations' => 'Captcha on donations',
);";
			$fileLang = 'resources/lang/'. config('app.locale') .'/misc.php';

			 file_put_contents($fileLang, str_replace($findStringLang, $replaceLang, file_get_contents($fileLang)));

			// Replace String
			$findString = 'INVISIBLE_RECAPTCHA_BADGEHIDE=false';
			$replace    = 'INVISIBLE_RECAPTCHA_BADGEHIDE=true';
			$file       = '.env';

			 file_put_contents($file, str_replace($findString, $replace, file_get_contents($file)));
			//<=== End Replace String

			file_put_contents(
					'routes/web.php',
					"
Route::get('panel/admin/gallery','AdminController@gallery')->middleware('role');
Route::post('panel/admin/gallery/add','AdminController@addGallery')->middleware('role');
Route::post('panel/admin/gallery/delete/{id}','AdminController@deleteGallery')->middleware('role');
Route::view('panel/admin/payment/add','admin.add-payment')->middleware('role');
Route::post('panel/admin/payment/add','AdminController@addPayment')->middleware('role');
Route::get('gallery', 'HomeController@gallery');",
					FILE_APPEND
			);

			file_put_contents(
					'public/css/styles.css',
					"
/*********** v4.0 *******/
#desc li {
  list-style: inherit;
}
#desc ol,
#desc ul {
  margin-top: 1rem;
  margin-bottom: 1rem;
}
.fb-messenger {
  color: #448aff !important;
}
.btn-telegram {
  color: #20a0e1 !important;
}
.btn-whatsapp {
  color: #50b154 !important;
}
a:hover.social-share {
  text-decoration: none;
}
a.social-share i {
  color: #797979;
  font-size: 32px;
}",
			FILE_APPEND
	);

			// Create table Gallery
		if( ! Schema::hasTable('gallery') ) {
					 	Schema::create('gallery', function ($table) {
					  $table->engine = 'InnoDB';
					  $table->increments('id');
						$table->string('image', 50);
					});
			}// End create table Gallery

			DB::table('reserved')->insert(
				['name' => 'gallery']
			);

			if (! Schema::hasColumn('admin_settings', 'captcha_on_donations') ) {
				Schema::table('admin_settings', function($table){
				 $table->enum('captcha_on_donations', ['on', 'off'])->default('on');
				});
			}

			if (! Schema::hasColumn('users', 'phone', 'street') ) {
				Schema::table('users', function($table){
				 $table->unsignedInteger('phone');
				 $table->string('street');
				});
			}

			if (! file_exists('public/gallery')) {
			 mkdir('public/gallery', 0777, true);
		 }

		 //============== Files ================//

		 // APP
		 $file1 = 'Helper.php';
		 $file2 = 'AdminController.php';
		 $file3 = 'HomeController.php';
		 $file4 = 'CampaignsController.php';
		 $file5 = 'PayPalController.php';
		 $file6 = 'DonationsController.php';
		 $file7 = 'RegisterController.php';
		 $file8 = 'Gallery.php';

		 // PUBLIC
		 $file9 = 'smartphoto.min.js';
		 $file10 = 'smartphoto.min.css';
		 $file11 = 'core.min.js';
		 $file12 = 'bootstrap.bundle.min.js.map';
		 $file13 = 'core.css';
		 $file14 = 'bootstrap.min.css.map';

		 // RESOURCES
		 $file15 = 'navbar.blade.php';
		 $file16 = 'embed.blade.php';
		 $file17 = 'list-campaigns.blade.php';
		 $file18 = 'settings.blade.php';
		 $file19 = 'footer.blade.php';
		 $file20 = 'view.blade.php';
		 $file21 = 'gallery-photo.blade.php';
		 $file22 = 'gallery.blade.php';
		 $file23 = 'donate.blade.php';
		 $file24 = 'add-payment.blade.php';
		 $file25 = 'login.blade.php';
		 $file26 = 'register.blade.php';
		 $file27 = 'email.blade.php';
		 $file28 = 'reset.blade.php';
		 $file29 = 'layout.blade.php';
		 $file30 = 'app.blade.php';


		 //============== Moving Files ================//
		 $this->moveFile($path.$file1, $APP.$file1, $copy);
		 $this->moveFile($path.$file2, $CONTROLLERS.$file2, $copy);
		 $this->moveFile($path.$file3, $CONTROLLERS.$file3, $copy);
		 $this->moveFile($path.$file4, $CONTROLLERS.$file4, $copy);
		 $this->moveFile($path.$file5, $CONTROLLERS.$file5, $copy);
		 $this->moveFile($path.$file6, $CONTROLLERS.$file6, $copy);
		 $this->moveFile($path.$file7, $CONTROLLERS.$file7, $copy);
		 $this->moveFile($path.$file8, $MODELS.$file8, $copy);

		 $this->moveFile($path.$file9, $PUBLIC_JS.$file9, $copy);
		 $this->moveFile($path.$file10, $PUBLIC_CSS.$file10, $copy);
		 $this->moveFile($path.$file11, $PUBLIC_JS.$file11, $copy);
		 $this->moveFile($path.$file12, $PUBLIC_JS.$file12, $copy);
		 $this->moveFile($path.$file13, $PUBLIC_CSS.$file13, $copy);
		 $this->moveFile($path.$file14, $PUBLIC_CSS.$file14, $copy);

		 $this->moveFile($path.$file15, $VIEWS_INCLUDES.$file15, $copy);
		 $this->moveFile($path.$file16, $VIEWS_INCLUDES.$file16, $copy);
		 $this->moveFile($path.$file17, $VIEWS_INCLUDES.$file17, $copy);
		 $this->moveFile($path.$file18, $VIEWS_ADMIN.$file18, $copy);
		 $this->moveFile($path.$file19, $VIEWS_INCLUDES.$file19, $copy);
		 $this->moveFile($path.$file20, $VIEWS_CAMPAIGNS.$file20, $copy);
		 $this->moveFile($path.$file21, $VIEWS_DEFAULT.$file21, $copy);
		 $this->moveFile($path.$file22, $VIEWS_ADMIN.$file22, $copy);
		 $this->moveFile($path.$file23, $VIEWS_DEFAULT.$file23, $copy);
		 $this->moveFile($path.$file24, $VIEWS_ADMIN.$file24, $copy);
		 $this->moveFile($path.$file25, $VIEWS_AUTH.$file25, $copy);
		 $this->moveFile($path.$file26, $VIEWS_AUTH.$file26, $copy);
		 $this->moveFile($path.$file27, $VIEWS_AUTH_PASSWORDS.$file27, $copy);
		 $this->moveFile($path.$file28, $VIEWS_AUTH_PASSWORDS.$file28, $copy);
		 $this->moveFile($path.$file29, $VIEWS_ADMIN.$file29, $copy);
		 $this->moveFile($path.$file30, $VIEWS.$file30, $copy);

		 // Copy UpgradeController
		 if ($copy == true) {
			 $this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		}

		 // Delete folder
		 if ($copy == false) {
			File::deleteDirectory("v$version");
		}

	 	// Update Version
		$this->settings->whereId(1)->update([
					'version' => $version
				]);

				\Artisan::call('view:clear');
				\Artisan::call('cache:clear');
				\Artisan::call('config:clear');

			 return $upgradeDone;
		}
		//<<---- Version 4.0 ----->>

		//<<---- Version 4.1 ----->>
		if($version == '4.1') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = false;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != $oldVersion || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version 4.0</h2>";
			}

			// Replace String
			$findStringLang = ');';
			$replaceLang    = "
	//----- Version 4.1
	'blog' => 'Blog',
	'latest_blog' => 'Latest Blog',
	'subtitle_blog' => 'News, resources and tips to help raise more money for your cause',
	'continue_reading' => 'Continue reading',
	'others_posts' => 'Others posts',
	'post' => 'Post',
	'video' => 'Video',
	'video_description' => 'Enter the Url of a video on Youtube or Vimeo',
	'minimum_width_img_blog' => 'Minimum width 650x430',
	'blog_deleted' => 'Blog Deleted!',
	'video_url_invalid'  => 'URL invalid only support Youtube and Vimeo.',
);";
			$fileLang = 'resources/lang/'. config('app.locale') .'/misc.php';

			 file_put_contents($fileLang, str_replace($findStringLang, $replaceLang, file_get_contents($fileLang)));

			file_put_contents(
					'routes/web.php',
					"
Route::get('blog', 'BlogController@blog');
Route::get('blog/post/{id}/{slug?}', 'BlogController@post');

Route::get('panel/admin/blog','AdminController@blog')->middleware('role');
Route::get('panel/admin/blog/delete/{id}','AdminController@deleteBlog')->middleware('role');

Route::view('panel/admin/blog/create','admin.create-blog');
Route::post('panel/admin/blog/create','AdminController@createBlogStore')->middleware('role');

Route::get('panel/admin/blog/{id}','AdminController@editBlog')->middleware('role');
Route::post('panel/admin/blog/update','AdminController@updateBlog')->middleware('role');

Route::post('ajax/upload/image', 'AdminController@uploadImageEditor')->name('upload.image')->middleware('role');",
					FILE_APPEND
			);

			// Create table Blogs
		if( ! Schema::hasTable('blogs') ) {
					 	Schema::create('blogs', function ($table) {
					  $table->engine = 'InnoDB';
					  $table->increments('id');
						$table->string('title', 255);
						$table->string('slug', 255)->index();
						$table->string('image', 255);
						$table->text('content', 255);
						$table->string('tags', 255)->index();
						$table->unsignedInteger('user_id');
						$table->timestamp('date');
					});
			}// End create table Blogs

			DB::table('reserved')->insert(
				['name' => 'blog']
			);

			if (! Schema::hasColumn('campaigns', 'video') ) {
				Schema::table('campaigns', function($table){
				 $table->string('video', 200);
				});
			}

			if (! file_exists('public/blog')) {
			 mkdir('public/blog', 0777, true);
		 }

		 //============== Files ================//

		 // APP
		 $file1 = 'Helper.php';
		 $file2 = 'AdminController.php';
		 $file3 = 'BlogController.php';
		 $file4 = 'CampaignsController.php';

		 // PUBLIC
		 $file9 = 'ckeditor-init.js';

		 // PUBLIC ADMIN
		 $file11 = 'functions.js';

		 // RESOURCES
		 $file15 = 'blog.blade.php';
		 $file16 = 'blog.blade.php';
		 $file17 = 'theme.blade.php';
		 $file18 = 'create-blog.blade.php';
		 $file19 = 'footer.blade.php';
		 $file20 = 'view.blade.php';
		 $file21 = 'edit-blog.blade.php';
		 $file22 = 'edit.blade.php';
		 $file23 = 'create.blade.php';
		 $file24 = 'post.blade.php';
		 $file29 = 'layout.blade.php';
		 $file30 = 'dashboard.blade.php';


		 //============== Moving Files ================//
		 $this->moveFile($path.$file1, $APP.$file1, $copy);
		 $this->moveFile($path.$file2, $CONTROLLERS.$file2, $copy);
		 $this->moveFile($path.$file3, $CONTROLLERS.$file3, $copy);
		 $this->moveFile($path.$file4, $CONTROLLERS.$file4, $copy);

		 $this->moveFile($path.$file9, $PUBLIC_JS.$file9, $copy);
		 $this->moveFile($path.$file11, $PUBLIC_ADMIN_JS.$file11, $copy);

		 $this->moveFile($path.$file15, $VIEWS_INDEX.$file15, $copy);
		 $this->moveFile($pathAdmin.$file16, $VIEWS_ADMIN.$file16, $copy);
		 $this->moveFile($path.$file17, $VIEWS_ADMIN.$file17, $copy);
		 $this->moveFile($path.$file18, $VIEWS_ADMIN.$file18, $copy);
		 $this->moveFile($path.$file19, $VIEWS_INCLUDES.$file19, $copy);
		 $this->moveFile($path.$file20, $VIEWS_CAMPAIGNS.$file20, $copy);
		 $this->moveFile($path.$file21, $VIEWS_ADMIN.$file21, $copy);
		 $this->moveFile($path.$file22, $VIEWS_CAMPAIGNS.$file22, $copy);
		 $this->moveFile($path.$file23, $VIEWS_CAMPAIGNS.$file23, $copy);
		 $this->moveFile($path.$file24, $VIEWS_INDEX.$file24, $copy);
		 $this->moveFile($path.$file29, $VIEWS_ADMIN.$file29, $copy);
		 $this->moveFile($path.$file30, $VIEWS_ADMIN.$file30, $copy);

		 // Copy UpgradeController
		 if ($copy == true) {
			 $this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		}

		 // Delete folder
		 if ($copy == false) {
			File::deleteDirectory("v$version");
		}

	 	// Update Version
		$this->settings->whereId(1)->update([
					'version' => $version
				]);

				\Artisan::call('view:clear');
				\Artisan::call('cache:clear');
				\Artisan::call('config:clear');

			 return $upgradeDone;
		}
		//<<---- Version 4.1 ----->>

		//<<---- Version 4.2 ----->>
		if($version == '4.2') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = true;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != $oldVersion || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version 4.0</h2>";
			}

		if (! file_exists('resources/views/installer')) {
		 mkdir('resources/views/installer', 0777, true);
		}
			// Replace String
			$findStringLang = ');';
			$replaceLang    = "
	//----- Version 4.2
	'campaigns_ended' => 'Campaigns Ended',
	'campaigns_ended_subtitle' => 'Campaigns that have ended',
	'ended' => 'Ended'
);";
			$fileLang = 'resources/lang/'. config('app.locale') .'/misc.php';

			 file_put_contents($fileLang, str_replace($findStringLang, $replaceLang, file_get_contents($fileLang)));

			 // Routes
			file_put_contents(
					'routes/web.php',
					"
Route::get('installer/script','InstallScriptController@wizard');
Route::post('installer/script/database','InstallScriptController@database');
Route::post('installer/script/user','InstallScriptController@user');

Route::get('campaigns/ended','HomeController@campaignsEnded');

Route::get('panel/admin/languages','LangController@index');
Route::get('panel/admin/languages/create','LangController@create');
Route::post('panel/admin/languages/create','LangController@store');
Route::get('panel/admin/languages/edit/{id}','LangController@edit')->where( array( 'id' => '[0-9]+'));
Route::post('panel/admin/languages/edit/{id}', 'LangController@update')->where(array( 'id' => '[0-9]+'));
Route::resource('panel/admin/languages', 'LangController',
	['names' => [
			'destroy' => 'languages.destroy'
	 ]]
);
Route::get('lang/{id}', function(\$id){

	\$lang = App\Models\Languages::where('abbreviation', \$id)->firstOrFail();

	Session::put('locale', \$lang->abbreviation);

   return back();

})->where(array( 'id' => '[a-z]+'));",
					FILE_APPEND
			);

			// Create table Languages
		if (! Schema::hasTable('languages') ) {
					 	Schema::create('languages', function ($table) {
					  $table->engine = 'InnoDB';
					  $table->increments('id');
						$table->string('name', 100);
						$table->string('abbreviation', 32);
					});
			}// End create table Languages

			if (Schema::hasTable('languages') ) {
				DB::table('languages')->insert(
					[
						'name' => 'English',
						'abbreviation' => 'en'
				]
				);
			}// End hasTable Languages

			if (! Schema::hasColumn('payment_gateways', 'paypal_form') ) {
				Schema::table('payment_gateways', function($table){
				 $table->string('paypal_form', 200);
				});
			}

			if (Schema::hasColumn('payment_gateways', 'paypal_form') ) {
				PaymentGateways::whereId(1)->update([
							'paypal_form' => 'normal'
						]);
			}

			DB::statement('ALTER TABLE campaigns MODIFY COLUMN description MEDIUMTEXT');

		$replace = '<!-- Links -->
		<li @if(Request::is(\'panel/admin/languages\')) class="active" @endif>
			<a href="{{ url(\'panel/admin/languages\') }}"><i class="fa fa-language"></i> <span>{{ trans(\'admin.languages\') }}</span></a>
		</li><!-- ./Links -->

		</ul><!-- /.sidebar-menu -->';

		 $fileConfig = 'resources/views/admin/layout.blade.php';
		 file_put_contents(
					 $fileConfig,
					 str_replace('</ul><!-- /.sidebar-menu -->', $replace,
					 file_get_contents($fileConfig)
				 ));



		 //============== Files ================//
		 $files = [
			 'InstallScriptController.php' => $CONTROLLERS,
			 'HomeController.php' => $CONTROLLERS,
			 'AdminController.php' => $CONTROLLERS,
			 'CampaignsController.php' => $CONTROLLERS,
			 'LangController.php' => $CONTROLLERS,
			 'Languages.php' => $MODELS,
			 'ViewServiceProvider.php' => $PROVIDERS,
			 'Language.php' => $MIDDLEWARE,
			 'Kernel.php' => app_path('Http').$DS,

			 'app.blade.php' => $VIEWS,
			 'wizard.blade.php' => $VIEWS_INSTALL,
			 'navbar.blade.php' => $VIEWS_INCLUDES,
			 'css_general.blade.php' => $VIEWS_INCLUDES,
			 'footer.blade.php' => $VIEWS_INCLUDES,
			 'post.blade.php' => $VIEWS_INDEX,
			 'javascript_general.blade.php' => $VIEWS_INCLUDES,

			 'installer.js' => $PUBLIC_JS,
		 ];

		 $filesAdmin = [
		 'add-languages.blade.php' => $VIEWS_ADMIN,
		 'edit-languages.blade.php' => $VIEWS_ADMIN,
		 'languages.blade.php' => $VIEWS_ADMIN,
		 'create-blog.blade.php' => $VIEWS_ADMIN,
	 ];


		 //============== Moving Files ================//

		 // Files
		 foreach ($files as $file => $root) {
				$this->moveFile($path.$file, $root.$file, $copy);
		 }

		 // Files Admin
		 foreach ($filesAdmin as $file => $root) {
				$this->moveFile($pathAdmin.$file, $root.$file, $copy);
		 }

		 // Copy UpgradeController
		 if ($copy == true) {
			 $this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		}

		 // Delete folder
		 if ($copy == false) {
			File::deleteDirectory("v$version");
		}

	 	// Update Version
		$this->settings->whereId(1)->update([
					'version' => $version
				]);

		@rename($PUBLIC_JS.'jqueryTimeago.js', $PUBLIC_JS.'jqueryTimeago_en.js');

				\Artisan::call('view:clear');
				\Artisan::call('cache:clear');
				\Artisan::call('config:clear');

			 return $upgradeDone;
		}
		//<<---- Version 4.2 ----->>

		//<<---- Version 4.3 ----->>
		if($version == '4.3') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = true;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

			if ($this->settings->version != $oldVersion || !$this->settings->version) {
				return "<h2 style='text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #ff0000;'>Error! you must update from version 4.0</h2>";
			}

		 //============== Files ================//
		 $files = [
			 'InstallScriptController.php' => $CONTROLLERS,// v4.3
			 'AdminController.php' => $CONTROLLERS,// v4.3
			 'CampaignsController.php' => $CONTROLLERS,// v4.3

			 'Helper.php' => $APP,// v4.3

			 'create.blade.php' => $VIEWS_CAMPAIGNS,// V4.3

			 'dashboard.blade.php' => $VIEWS_USERS,// V4.3
			 'donations.blade.php' => $VIEWS_USERS,// V4.3

			 'wizard.blade.php' => $VIEWS_INSTALL,// v4.3
			 'installer.js' => $PUBLIC_JS,// v4.3
		 ];

		 $filesAdmin = [
		 'dashboard.blade.php' => $VIEWS_ADMIN,// V4.3
		 'donations.blade.php' => $VIEWS_ADMIN,// V4.3
	 ];


		 //============== Moving Files ================//

		 // Files
		 foreach ($files as $file => $root) {
				$this->moveFile($path.$file, $root.$file, $copy);
		 }

		 // Files Admin
		 foreach ($filesAdmin as $file => $root) {
				$this->moveFile($pathAdmin.$file, $root.$file, $copy);
		 }

		 // Copy UpgradeController
		 if ($copy == true) {
			 $this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		}

		 // Delete folder
		 if ($copy == false) {
			File::deleteDirectory("v$version");
		}

	 	// Update Version
		$this->settings->whereId(1)->update([
					'version' => $version
				]);

				\Artisan::call('view:clear');
				\Artisan::call('cache:clear');
				\Artisan::call('config:clear');

			 return $upgradeDone;
		}
		//<<---- Version 4.3 ----->>

		//<<---- Version 4.5 ----->>
		if ($version == '4.5') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = true;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

		 //============== Files ================//
		 $files = [
			 'serviceworker.js' => $ROOT,

			 'AppServiceProvider.php' => $PROVIDERS,// v4.5

			 'AdminController.php' => $CONTROLLERS,// v4.5
			 'PagesController.php' => $CONTROLLERS,// v4.5

			 'Helper.php' => $APP,// v4.5

			 'core.min.js' => $PUBLIC_JS,// v4.5

			 'app.blade.php' => $VIEWS,// v4.5

			 'donate.blade.php' => $VIEWS_DEFAULT,// V4.5
			 'category.blade.php' => $VIEWS_DEFAULT,// V4.5

			 'home.blade.php' => $VIEWS_PAGES,// V4.5

			 'javascript_general.blade.php' => $VIEWS_INCLUDES,// v4.5
			 'categories-listing.blade.php' => $VIEWS_INCLUDES,// v4.5
			 'list-campaigns.blade.php' => $VIEWS_INCLUDES,// v4.5
			 'footer.blade.php' => $VIEWS_INCLUDES,// v4.5
			 'navbar.blade.php' => $VIEWS_INCLUDES,// v4.5

			 'edit.blade.php' => $VIEWS_CAMPAIGNS,// V4.5
			 'create.blade.php' => $VIEWS_CAMPAIGNS,// V4.5
			 'view.blade.php' => $VIEWS_CAMPAIGNS,// V4.5

			 'dashboard.blade.php' => $VIEWS_USERS,// V4.5

		 ];

		 $filesAdmin = [
		 'add-page.blade.php' => $VIEWS_ADMIN,// V4.5
		 'edit-page.blade.php' => $VIEWS_ADMIN,// V4.5
		 'dashboard.blade.php' => $VIEWS_ADMIN,// V4.5
		 'pwa.blade.php' => $VIEWS_ADMIN,// V4.5
		 'pages.blade.php' => $VIEWS_ADMIN,// V4.5
		 'settings.blade.php' => $VIEWS_ADMIN,// V4.5
	 ];

		 //============== Moving Files ================//

		 // Files
		 foreach ($files as $file => $root) {
				$this->moveFile($path.$file, $root.$file, $copy);
		 }

		 // Files Admin
		 foreach ($filesAdmin as $file => $root) {
				$this->moveFile($pathAdmin.$file, $root.$file, $copy);
		 }

		 // Copy Folders
		 $filePathPublic1 = $path.'images';
		 $pathPublic1 = public_path('images');

		 $this->moveDirectory($filePathPublic1, $pathPublic1, $copy);

		 // Copy Folders
		 $filePathPublic2 = $path.'laravelpwa';
		 $pathPublic2 = resource_path('views'.$DS.'vendor'.$DS.'laravelpwa');

		 $this->moveDirectory($filePathPublic2, $pathPublic2, $copy);

		 //============== QUERY SQL =======================

		 // Routes
		file_put_contents(
				'routes/web.php',
				"
Route::get('p/{page}','PagesController@show')->where('page','[^/]*');

Route::view('panel/admin/pwa','admin.pwa')->name('pwa');
Route::post('panel/admin/pwa','AdminController@pwa');",
				FILE_APPEND
		);

		$replace = '<!-- Links -->
		<li @if(Request::is(\'panel/admin/pwa\')) class="active" @endif>
			<a href="{{ url(\'panel/admin/pwa\') }}"><i class="fa fa-mobile"></i> <span>PWA</span></a>
		</li><!-- ./Links -->

		</ul><!-- /.sidebar-menu -->';

		 $fileConfig = 'resources/views/admin/layout.blade.php';
		 file_put_contents(
					 $fileConfig,
					 str_replace('</ul><!-- /.sidebar-menu -->', $replace,
					 file_get_contents($fileConfig)
				 ));

		 if (! Schema::hasColumn('pages', 'lang')) {
						 Schema::table('pages', function($table) {
						 $table->char('lang', 10)->default(session('locale'));
				 });
			 }

			 file_put_contents(
 					'.env',
 					"\nDEFAULT_LOCALE=".session('locale')."\n\nPWA_SHORT_NAME=\"Fundme\"\nPWA_ICON_72=public/images/icons/icon-72x72.png\nPWA_ICON_96=public/images/icons/icon-96x96.png\nPWA_ICON_128=public/images/icons/icon-128x128.png\nPWA_ICON_144=public/images/icons/icon-144x144.png\nPWA_ICON_152=public/images/icons/icon-152x152.png\nPWA_ICON_384=public/images/icons/icon-384x384.png\nPWA_ICON_512=public/images/icons/icon-512x512.png\n\nPWA_SPLASH_640=public/images/icons/splash-640x1136.png\nPWA_SPLASH_750=public/images/icons/splash-750x1334.png\nPWA_SPLASH_1125=public/images/icons/splash-1125x2436.png\nPWA_SPLASH_1242=public/images/icons/splash-1242x2208.png\nPWA_SPLASH_1536=public/images/icons/splash-1536x2048.png\nPWA_SPLASH_1668=public/images/icons/splash-1668x2224.png\nPWA_SPLASH_2048=public/images/icons/splash-2048x2732.png\n",
 					FILE_APPEND
 			);

		 //============== END QUERY SQL ===================


		 // Copy UpgradeController
		 if ($copy == true) {
			 $this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		}

		 // Delete folder
		 if ($copy == false) {
			File::deleteDirectory("v$version");
		}

	 	// Update Version
		$this->settings->whereId(1)->update([
					'version' => $version
				]);

				\Artisan::call('view:clear');
				\Artisan::call('cache:clear');
				\Artisan::call('config:clear');

			 return $upgradeDone;
		}
		//<<---- Version 4.5 ----->>

		//<<---- Version 4.7 ----->>
		if ($version == '4.7') {

			//============ Starting moving files...
			$oldVersion = $this->settings->version;
			$path       = "v$version/";
			$pathAdmin  = "v$version/admin/";
			$copy       = false;

			if ($this->settings->version == $version) {
				return redirect('/');
			}

		 //============== Files ================//
		 $files = [
			 'web.php' => $ROUTES,//v4.7

			 'AdminController.php' => $CONTROLLERS,//v4.7
			 'DonationsController.php' => $CONTROLLERS,//4.7
			 'PagesController.php' => $CONTROLLERS,//4.7

			 'footer.blade.php' => $VIEWS_INCLUDES,//4.7

			 '404.blade.php' => $VIEWS_ERRORS,//4.7
			 '500.blade.php' => $VIEWS_ERRORS,//4.7

		 ];

		 $filesAdmin = [
		 'reported-campaigns.blade.php' => $VIEWS_ADMIN,//4.7
		 'withdrawals.blade.php' => $VIEWS_ADMIN,//4.7
		 'members.blade.php' => $VIEWS_ADMIN,//4.7
		 'campaigns.blade.php' => $VIEWS_ADMIN,//4.7
		 'pages.blade.php' => $VIEWS_ADMIN,//4.7
		 'donations.blade.php' => $VIEWS_ADMIN,//4.7
	 ];

		 //============== Moving Files ================//

		 // Files
		 foreach ($files as $file => $root) {
				$this->moveFile($path.$file, $root.$file, $copy);
		 }

		 // Files Admin
		 foreach ($filesAdmin as $file => $root) {
				$this->moveFile($pathAdmin.$file, $root.$file, $copy);
		 }

		 // Copy UpgradeController
		 if ($copy == true) {
			 $this->moveFile($path.'UpgradeController.php', $CONTROLLERS.'UpgradeController.php', $copy);
		}

		 if ($copy == false) {

			 //============== QUERY SQL =======================
			 //============== END QUERY SQL ===================

			// Delete folder
			File::deleteDirectory("v$version");
		}

	 	// Update Version
		$this->settings->whereId(1)->update([
					'version' => $version
				]);

				\Artisan::call('view:clear');
				\Artisan::call('cache:clear');
				\Artisan::call('config:clear');

			 return $upgradeDone;
		}
		//<<---- Version 4.7 ----->>

	}//<--- End Method update($version)
}
