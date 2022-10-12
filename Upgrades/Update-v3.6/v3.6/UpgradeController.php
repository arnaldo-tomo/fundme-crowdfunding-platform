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

class UpgradeController extends Controller {

	/**
	 * Move a file
	 *
	 */
	private static function moveFile($file, $newFile, $copy)
	{
		if (File::exists($file) && $copy == false) {
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

	}//<--- End Method update($version)

}
