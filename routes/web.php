<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
/*
 |-----------------------------------
 | Index
 |-----------------------------------
 */
Route::get('/', 'HomeController@index');

Route::get('home', function(){
	return redirect('/');
});

Route::get('mode/off', function(){
    if(Auth::user()->id == 1) {
        Artisan::call('down', [
		'--message' => 'We are doing some updates, we will be back in a few minutes.',
		'--allow' => request()->ip()
	]);

	return 'Mode off';
  }

})->middleware('role');

Route::get('mode/on', function(){
    if(Auth::user()->id == 1) {
	Artisan::call('up');
	return 'Mode on';
  }
})->middleware('role');

/*
 |
 |-----------------------------------
 | Search
 |--------- -------------------------
 */
Route::get('search', 'HomeController@search');

/*
 |
 |-----------------------------------
 | Categories List
 |--------- -------------------------
 */
Route::get('category/{slug}','HomeController@category');

// Categories
Route::get('categories', function(){

	$data = App\Models\Categories::where('mode','on')->orderBy('name')->get();

	return view('default.categories')->withData($data);
});


/*
 |
 |-----------------------------------
 | Verify Account
 |--------- -------------------------
 */
Route::get('verify/account/{confirmation_code}', 'HomeController@getVerifyAccount')->where('confirmation_code','[A-Za-z0-9]+');

/*
/*
 |-----------------------------------
 | Authentication
 |-----------------------------------
 */
Auth::routes();

// Logout
Route::get('/logout', 'Auth\LoginController@logout');

/*
 |
 |-----------------------------------------------
 | Ajax Request
 |--------- -------------------------------------
 */
Route::get('ajax/donations', 'AjaxController@donations');
Route::get('ajax/campaign/updates', 'AjaxController@updatesCampaign');
Route::get('ajax/campaigns', 'AjaxController@campaigns');
Route::get('ajax/category', 'AjaxController@category');
Route::get('ajax/search', 'AjaxController@search');



/*
 |
 |-----------------------------------
 | Contact Organizer
 |-----------------------------------
 */

Route::post('contact/organizer','CampaignsController@contactOrganizer');

/*
 |
 |-----------------------------------
 | Details Campaign
 |--------- -------------------------
 */
Route::get('campaign/{id}/{slug?}','CampaignsController@view');

/*
 |
 |-----------------------------------
 | User Views LOGGED
 |--------- -------------------------
 */
Route::group(['middleware' => 'auth'], function() {

	//<-------------- Create Campaign
	Route::get('create/campaign', function(){
	return view('campaigns.create');
	});
	//  Post
	Route::post('create/campaign','CampaignsController@create');

	//<-------------- Edit Campaign
	Route::get('edit/campaign/{id}','CampaignsController@edit');
	Route::post('edit/campaign/{id}','CampaignsController@post_edit');

	//<-------------- Post a Update
	Route::get('update/campaign/{id}','CampaignsController@update');
	Route::post('update/campaign/{id}','CampaignsController@post_update');

	//<-------------- Edit post a Update
	Route::get('edit/update/{id}','CampaignsController@edit_update');
	Route::post('edit/update/{id}','CampaignsController@post_edit_update');

	Route::post('delete/image/updates','CampaignsController@delete_image_update');

	// Delete Campaign
	Route::get('delete/campaign/{id}','CampaignsController@delete');

	// Dashboard
	Route::get('dashboard','CampaignsController@dashboard');

	// Withdrawal
	Route::get('dashboard/withdrawals','CampaignsController@show_withdrawal');
	Route::post('campaign/withdrawal/{id}','CampaignsController@withdrawal');

	Route::get('dashboard/withdrawals/configure', function(){
	return view('users.withdrawals-configure');
	});

	Route::post('withdrawals/configure/{type}','CampaignsController@withdrawalConfigure');

	Route::post('delete/withdrawal/{id}','CampaignsController@withdrawalDelete');

	//<-------------- Post a Reward
	Route::get('rewards/campaign/{id}','RewardsController@create');
	Route::post('rewards/campaign/{id}','RewardsController@store');

	//<-------------- Edit Reward
	Route::get('edit/rewards/{id}','RewardsController@edit');
	Route::post('edit/rewards/{id}','RewardsController@update');

//<--------------- Delete Reward
	Route::post('delete/rewards','RewardsController@destroy');

	// Account Settings
	Route::get('account','UserController@account');
	Route::post('account','UserController@update_account');

	// Password
	Route::get('account/password','UserController@password');
	Route::post('account/password','UserController@update_password');

	// Upload Avatar
	Route::post('upload/avatar','UserController@upload_avatar');

	// Campaigns
	Route::get('dashboard/campaigns','CampaignsController@campaigns');

	// Donations
	Route::get('dashboard/donations','CampaignsController@donations');

	// Donations View Details
	Route::get('dashboard/donations/{id}', function($id) {

		$data = App\Models\Donations::leftJoin('campaigns', function($join) use ($id) {
       $join->on('donations.campaigns_id', '=', 'campaigns.id');
     })
     ->where('campaigns.user_id',Auth::user()->id)
		 ->where('donations.id', $id)
 		->where('donations.approved','1')
 	->select('donations.*')
 	->addSelect('campaigns.title')
	->firstOrFail();

		return view('users.donation-view', ['data' => $data]);
	});

	// Report Campaign
	Route::get('report/campaign/{id}/{user}','CampaignsController@report');

	// Ajax Like
	Route::post('ajax/like', 'AjaxController@like');

	// User Likes
	Route::get('user/likes', 'UserController@likes');
});
/*
 |
 |-----------------------------------
 | Admin Panel
 |--------- -------------------------
 */
Route::group(['middleware' => 'role'], function() {

    // Upgrades
	Route::get('update/{version}','UpgradeController@update');

	// Dashboard
	Route::get('panel/admin','AdminController@admin');

	// Settings
	Route::get('panel/admin/settings','AdminController@settings');
	Route::post('panel/admin/settings','AdminController@saveSettings');

	// Limits
	Route::get('panel/admin/settings/limits','AdminController@settingsLimits');
	Route::post('panel/admin/settings/limits','AdminController@saveSettingsLimits');

	// Campaigns
	Route::get('panel/admin/campaigns','AdminController@campaigns');
	Route::post('panel/admin/campaigns','AdminController@saveCampaigns');

	// Edit Campaign
	Route::get('panel/admin/campaigns/edit/{id}','AdminController@editCampaigns');
	Route::post('panel/admin/campaigns/edit/{id}','AdminController@postEditCampaigns');

	//Withdrawals
	Route::get('panel/admin/withdrawals','AdminController@withdrawals');
	Route::get('panel/admin/withdrawal/{id}','AdminController@withdrawalsView');
	Route::post('panel/admin/withdrawals/paid/{id}','AdminController@withdrawalsPaid');

	Route::post('paypal/withdrawal/ipn','AdminController@withdrawlsIpn');


	// Delete Campaign
	Route::post('panel/admin/campaign/delete','AdminController@deleteCampaign');

	// Donations
	Route::get('panel/admin/donations','AdminController@donations');
	Route::get('panel/admin/donations/{id}','AdminController@donationView');

	// Members
	Route::resource('panel/admin/members', 'AdminController',
		['names' => [
		    'edit'    => 'user.edit',
		    'destroy' => 'user.destroy'
		 ]]
	);

	// Add Member
	Route::get('panel/admin/member/add','AdminController@add_member');
	Route::post('panel/admin/member/add','AdminController@storeMember');

	// Pages
	Route::resource('panel/admin/pages', 'PagesController',
		['names' => [
		    'edit'    => 'pages.edit',
		    'destroy' => 'pages.destroy'
		 ]]
	);

	// Payments Settings
	Route::get('panel/admin/payments','AdminController@payments');
	Route::post('panel/admin/payments','AdminController@savePayments');

	Route::get('panel/admin/payments/{id}','AdminController@paymentsGateways');
	Route::post('panel/admin/payments/{id}','AdminController@savePaymentsGateways');

	// Profiles Social
	Route::get('panel/admin/profiles-social','AdminController@profiles_social');
	Route::post('panel/admin/profiles-social','AdminController@update_profiles_social');

	// Admin categories
	Route::get('panel/admin/categories','AdminController@categories');
	Route::get('panel/admin/categories/add','AdminController@addCategories');
	Route::post('panel/admin/categories/add','AdminController@storeCategories');
	Route::get('panel/admin/categories/edit/{id}','AdminController@editCategories')->where(array( 'id' => '[0-9]+'));
	Route::post('panel/admin/categories/update','AdminController@updateCategories');
	Route::post('panel/admin/categories/delete/{id}','AdminController@deleteCategories')->where(array( 'id' => '[0-9]+'));

	// Campaigns Reported
	Route::get('panel/admin/campaigns/reported','AdminController@reportedCampaigns');
	Route::post('panel/admin/campaigns/reported/delete','AdminController@reportedDeleteCampaigns');

	Route::get('panel/admin/theme','AdminController@theme');
	Route::post('panel/admin/theme','AdminController@themeStore');
	Route::get('panel/admin/gallery','AdminController@gallery');
	Route::post('panel/admin/gallery/add','AdminController@addGallery');
	Route::post('panel/admin/gallery/delete/{id}','AdminController@deleteGallery');
	Route::view('panel/admin/payment/add','admin.add-payment');
	Route::post('panel/admin/payment/add','AdminController@addPayment');

	Route::get('panel/admin/blog','AdminController@blog');
	Route::get('panel/admin/blog/delete/{id}','AdminController@deleteBlog');

	Route::view('panel/admin/blog/create','admin.create-blog');
	Route::post('panel/admin/blog/create','AdminController@createBlogStore');

	Route::get('panel/admin/blog/{id}','AdminController@editBlog');
	Route::post('panel/admin/blog/update','AdminController@updateBlog');

	Route::post('ajax/upload/image', 'AdminController@uploadImageEditor')->name('upload.image');

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

	Route::post('approve/donation','AdminController@approveDonation');
	Route::post('delete/donation','AdminController@deleteDonation');

	Route::view('panel/admin/pwa','admin.pwa')->name('pwa');
	Route::post('panel/admin/pwa','AdminController@pwa');

});// End Role

/*
 |
 |-----------------------------------
 | Donations
 |--------- -------------------------
 */
Route::get('donate/{id}/{slug?}','DonationsController@show');
Route::post('donate/{id}','DonationsController@send');

// Paypal IPN
Route::post('paypal/ipn','PayPalController@paypalIpn');

	Route::get('paypal/donation/success/{id}', function($id){

			session()->put('donation_success', trans('misc.donation_success'));
			return redirect("campaign/".$id);
	});

	Route::get('paypal/donation/cancel/{id}', function($id){

			session()->put('donation_cancel', trans('misc.donation_cancel'));
	       return redirect("campaign/".$id);
	});

/*
 |
 |------------------------
 | Pages Static Custom
 |--------- --------------
 */
Route::get('page/{page}', function( $page ){

	$response = App\Models\Pages::where( 'slug','=', $page )->firstOrFail();

	$title = $response->title.' - ';
	return view('pages.home', compact('response', 'title'));

})->where('page','[^/]*' );

/*
 |
 |------------------------
 | Embed Widget
 |--------- --------------
 */
Route::get('c/{id}/widget.js', function($id){

	$iframeUrl = url('c',$id).'/widget/show';
	return 'var html = \'<iframe align="middle" scrolling="no" width="100%" height="550" frameBorder="0" src="'.$iframeUrl.'"></iframe>\'; document.write( html );';

});

// Embed Widget iFrame
Route::get('c/{id}/widget/show', function($id){

	$response = App\Models\Campaigns::where('id',$id)->where('status','active')->firstOrFail();
	return view('includes.embed')->withResponse($response);
});

/*
 |
 |------------------------
 | v2.6
 |--------- --------------
 */

Route::get('campaigns/featured','HomeController@campaignsFeatured');
Route::get('ajax/campaigns/featured', 'AjaxController@campaignsFeatured');

/*
 |-----------------------------------
 | Social Login
 |--------- -------------------------
 */
Route::group(['middleware' => 'guest'], function() {
	Route::get('oauth/{provider}', 'SocialAuthController@redirect')->where('provider', '(facebook|google)$');
	Route::get('oauth/{provider}/callback', 'SocialAuthController@callback')->where('provider', '(facebook|google)$');
});//<--- End Group guest

/*
 |
 |------------------------
 | v3.2
 |--------- --------------
 */

Route::get('install/{addon}','InstallController@install');

// Payments Gateways
Route::get('payment/paypal', 'PayPalController@show')->name('paypal');
Route::get('payment/bank-transfer', 'BankTransferController@show')->name('bank-transfer');

Route::get('payment/stripe', 'StripeController@show')->name('stripe');
Route::post('payment/stripe/charge', 'StripeController@charge');

Route::post("ajax/upload/image", "AjaxController@uploadImageEditor")->name("upload.image")->middleware("auth");
Route::get("campaigns/latest","HomeController@campaignsLatest");
Route::get("campaigns/popular","HomeController@campaignsPopular");

Route::get("donation/pending/{id}", function($id){
		session()->put("donation_pending", trans("misc.donation_pending"));
		return redirect("campaign/".$id);
});

Route::get('gallery', 'HomeController@gallery');
Route::get('blog', 'BlogController@blog');
Route::get('blog/post/{id}/{slug?}', 'BlogController@post');

Route::get('installer/script','InstallScriptController@wizard');
Route::post('installer/script/database','InstallScriptController@database');
Route::post('installer/script/user','InstallScriptController@user');

Route::get('campaigns/ended','HomeController@campaignsEnded');

Route::get('lang/{id}', function($id){

	$lang = App\Models\Languages::where('abbreviation', $id)->firstOrFail();

	Session::put('locale', $lang->abbreviation);

   return back();

})->where(array( 'id' => '[a-z]+'));

Route::get('p/{page}','PagesController@show')->where('page','[^/]*');

Route::get('payment/coinpayments', 'CoinpaymentsController@show')->name('coinpayments');
Route::post('webhook/coinpayments', 'CoinpaymentsController@webhook')->name('coinpaymentsIPN');

Route::get('payment/instamojo', 'InstamojoController@show')->name('instamojo');
Route::get('webhook/instamojo', 'InstamojoController@webhook');

Route::get('payment/mollie', 'MollieController@show')->name('mollie');
Route::post('webhook/mollie', 'MollieController@webhook');

Route::get('payment/paystack', 'PaystackController@show')->name('paystack');

Route::get('payment/razorpay', 'RazorpayController@show')->name('razorpay');
