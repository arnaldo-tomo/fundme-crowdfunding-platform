<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\CampaignsReported;
use App\Models\Donations;
use App\Models\Categories;
use App\Models\Withdrawals;
use App\Models\PaymentGateways;
use App\Helper;
use Carbon\Carbon;
use Fahim\PaypalIPN\PaypalIPNListener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Gallery;
use App\Models\Blogs;
use Image;
use Mail;

class AdminController extends Controller {

	public function __construct( AdminSettings $settings) {
		$this->settings = $settings::first();
	}

	public function index(Request $request)
	{
		$query = $request->input('q');

		if( $query != '' && strlen( $query ) > 2 ) {
		 	$data = User::where('name', 'LIKE', '%'.$query.'%')
		 	->orderBy('id','desc')->paginate(20);
		 } else {
		 	$data = User::orderBy('id','desc')->paginate(20);
		 }

    	return view('admin.members', ['data' => $data,'query' => $query]);
	 }

	public function edit($id) {

		$data = User::findOrFail($id);

		if( $data->id == 1 || $data->id == Auth::user()->id ) {
			\Session::flash('info_message', trans('admin.user_no_edit'));
			return redirect('panel/admin/members');
		}
    	return view('admin.edit-member')->withData($data);

	}//<--- End Method

	public function update($id, Request $request) {

    $user = User::findOrFail($id);

	$input = $request->all();

	if( !empty( $request->password ) )	 {
		$rules = array(
			'name' => 'required|min:3|max:25',
			'email'     => 'required|email|unique:users,email,'.$id,
			 'password' => 'min:6',
			);

			$password = \Hash::make($request->password);

	} else {
		$rules = array(
			'name' => 'required|min:3|max:25',
			'email'     => 'required|email|unique:users,email,'.$id,
			);

			$password = $user->password;
	}

	   $this->validate($request,$rules);

	  $user->name = $request->name;
	  $user->email = $request->email;
	  $user->role = $request->role;
	  $user->password = $password;
      $user->save();

    \Session::flash('success_message', trans('admin.success_update'));

    return redirect('panel/admin/members');

	}//<--- End Method

	public function destroy($id)
    {
    	$user = User::findOrFail($id);

    	if( $user->id == 1 || $user->id == Auth::user()->id ) {
    		return redirect('panel/admin/members');
			exit;
    	}

		// Find User

		// Stop Campaigns
		$allCampaigns = Campaigns::where('user_id',$id)->update(array('finalized' => '1'));

		//<<<-- Delete Avatar -->>>/
		$fileAvatar    = 'public/avatar/'.$user->avatar;

		if ( \File::exists($fileAvatar) && $user->avatar != 'default.jpg' ) {
			 \File::delete($fileAvatar);
		}//<--- IF FILE EXISTS


        $user->delete();
		return redirect('panel/admin/members');

    }//<--- End Method

    public function add_member() {
    	return view('admin.add-member');
    }

	public function storeMember(Request $request){

		$this->validate($request, [
			'name' => 'required|min:3|max:30',
            'email'     => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

		$user = new User;
		$user->name = $request->name;
		$user->email = $request->email;
		$user->role = $request->role;
		$user->avatar = 'default.jpg';
		$user->token = str_random(80);
		$user->password = \Hash::make($request->password);
		$user->save();

		 \Session::flash('success_message', trans('admin.success_add'));
		return redirect('panel/admin/members');

	}

	// START
	public function admin()
	{
		$total_campaigns = Campaigns::count();
		$campaigns       = Campaigns::orderBy('id','DESC')->take(4)->get();
		$users        	 = User::orderBy('id','DESC')->take(4)->get();
		$total_raised_funds = Donations::where('approved','1')->sum('donation');
		$total_donations = Donations::count();

		//  Calcule Chart Earnings last 30 days
    for ($i=0; $i <= 30; ++$i) {

      $date = date('Y-m-d', strtotime('-'.$i.' day'));

      // Donations last 30 days
      $donationsLast30 = Donations::whereApproved('1')->whereDate('date', '=', $date)->count();

      // Format Date on Chart
      $formatDate = Helper::formatDateChart($date);
      $monthsData[] =  "'$formatDate'";

      // Earnings last 30 days
      $lastDonations[] = $donationsLast30;
    }

		$label = implode(',', array_reverse($monthsData));
		$datalastDonations = implode(',', array_reverse($lastDonations));

		return view('admin.dashboard', [
			'users' => $users,
			'total_raised_funds' => $total_raised_funds,
			'campaigns' => $campaigns,
			'total_donations' => $total_donations,
			'total_campaigns' => $total_campaigns,
			'label' => $label,
			'datalastDonations' => $datalastDonations
		]);

	}//<--- END METHOD

	public function settings() {

		return view('admin.settings')->withSettings($this->settings);

	}//<--- END METHOD

	public function saveSettings(Request $request) {

		$rules = array(
            'title'            => 'required',
	        'welcome_text' 	   => 'required',
	        'welcome_subtitle' => 'required',
	        'keywords'         => 'required',
	        'description'      => 'required',
	        'email_no_reply'   => 'required',
	        'email_admin'      => 'required',
					'link_terms'      => 'required|url',
					'link_privacy'      => 'required|url',
        );

		$this->validate($request, $rules);

		$sql                      = AdminSettings::first();
		$sql->title               = $request->title;
		$sql->welcome_text        = $request->welcome_text;
		$sql->welcome_subtitle    = $request->welcome_subtitle;
		$sql->keywords            = $request->keywords;
		$sql->description         = $request->description;
		$sql->email_no_reply      = $request->email_no_reply;
		$sql->email_admin         = $request->email_admin;
		$sql->link_terms         = $request->link_terms;
		$sql->link_privacy         = $request->link_privacy;
		$sql->date_format         = $request->date_format;
		$sql->auto_approve_campaigns = $request->auto_approve_campaigns ?? '0';
		$sql->captcha                = $request->captcha ?? 'off';
		$sql->captcha_on_donations  =$request->captcha_on_donations ?? 'off';
		$sql->registration_active    = $request->registration_active ?? 'off';
		$sql->facebook_login         = $request->facebook_login ?? 'off';
		$sql->google_login           = $request->google_login ?? 'off';
		$sql->email_verification = $request->email_verification ?? '0';
		$sql->save();

		// Default locale
		Helper::envUpdate('DEFAULT_LOCALE', $request->default_language);

		\Session::flash('success_message', trans('admin.success_update'));

    	return redirect('panel/admin/settings');

	}//<--- END METHOD

	public function settingsLimits() {

		return view('admin.limits')->withSettings($this->settings);

	}//<--- END METHOD

	public function saveSettingsLimits(Request $request) {


		$rules = array(
	        'min_campaign_amount'             => 'required|integer|min:1',
	        'max_campaign_amount'             => 'required|integer|min:1',
	        'min_donation_amount'             => 'required|integer|min:1',
	        'max_donation_amount'             => 'required|integer|min:1',
        );

		$this->validate($request, $rules);

		$sql                      = AdminSettings::first();
		$sql->result_request      = $request->result_request;
		$sql->file_size_allowed   = $request->file_size_allowed;
		$sql->min_campaign_amount   = $request->min_campaign_amount;
		$sql->max_campaign_amount   = $request->max_campaign_amount;
		$sql->min_donation_amount   = $request->min_donation_amount;
		$sql->max_donation_amount   = $request->max_donation_amount;

		$sql->save();

		\Session::flash('success_message', trans('admin.success_update'));

    	return redirect('panel/admin/settings/limits');

	}//<--- END METHOD

	public function profiles_social(){
		return view('admin.profiles-social')->withSettings($this->settings);
	}//<--- End Method

	public function update_profiles_social(Request $request) {

		$sql = AdminSettings::find(1);

		$rules = array(
            'twitter'    => 'url',
            'facebook'   => 'url',
            'googleplus' => 'url',
            'linkedin'   => 'url',
        );

		$this->validate($request, $rules);

	    $sql->twitter       = $request->twitter;
		$sql->facebook      = $request->facebook;
		$sql->googleplus    = $request->googleplus;
		$sql->instagram     = $request->instagram;

		$sql->save();

	    \Session::flash('success_message', trans('admin.success_update'));

	    return redirect('panel/admin/profiles-social');
	}//<--- End Method

	public function donations(){

		$data = Donations::orderBy('id','DESC')->paginate(100);
		return view('admin.donations', ['data' => $data, 'settings' => $this->settings]);
	}//<--- End Method

	public function donationView($id){

		$data = Donations::findOrFail($id);
		return view('admin.donation-view', ['data' => $data, 'settings' => $this->settings]);
	}//<--- End Method

	public function payments(){
		return view('admin.payments-settings')->withSettings($this->settings);
	}//<--- End Method

	public function savePayments(Request $request) {

		$sql = AdminSettings::find(1);

		$rules = [
			'currency_code' => 'required|alpha',
			'currency_symbol' => 'required',
		];

		$this->validate($request, $rules);

		$sql->currency_symbol  = $request->currency_symbol;
		$sql->currency_code    = strtoupper($request->currency_code);
		$sql->fee_donation = $request->fee_donation;
		$sql->currency_position   = $request->currency_position;
		$sql->decimal_format = $request->decimal_format;
		$sql->save();

	    \Session::flash('success_message', trans('admin.success_update'));

	    return redirect('panel/admin/payments');
	}//<--- End Method

	public function campaigns(){

	$_data = Campaigns::orderBy('id','DESC')->paginate(50);

	// Deadline
	$timeNow = strtotime(Carbon::now());

		foreach ($_data as $key) {
			if( $key->deadline != '' ) {
		    $_deadline = strtotime($key->deadline);

			  if( $_deadline < $timeNow && $key->finalized == '0' ) {
			  	$sql = Campaigns::find($key->id);
			  	$sql->finalized = '1';
				$sql->save();
			  }
		  }
		}

		$data = Campaigns::orderBy('id','DESC')->paginate(50);
		return view('admin.campaigns', ['data' => $data, 'settings' => $this->settings]);
	}//<--- End Method

	public function withdrawals(){

		$data = Withdrawals::orderBy('id','DESC')->paginate(50);
		return view('admin.withdrawals', ['data' => $data, 'settings' => $this->settings]);
	}//<--- End Method

	public function withdrawalsView($id){
		$data = Withdrawals::findOrFail($id);
		return view('admin.withdrawal-view', ['data' => $data, 'settings' => $this->settings]);
	}//<--- End Method

	public function withdrawalsPaid(Request $request) {

		$data = Withdrawals::findOrFail($request->id);
		$user = User::find($data->campaigns()->user_id);

		$data->status       = 'paid';
		$data->date_paid = Carbon::now();
		$data->save();

		//<------ Send Email to User ---------->>>
		$amount    = $this->settings->currency_symbol.$data->amount.' '.$this->settings->currency_code;
		$sender     = $this->settings->email_no_reply;
	    $titleSite     = $this->settings->title;
		$campaign = $data->campaigns()->title;
		$fullNameUser = $user->name;
		$_emailUser = $user->email;

		Mail::send('emails.withdrawal-processed', array(
					'campaign' => $campaign,
					'amount' => $amount,
					'title_site' => $titleSite,
					'fullname' => $fullNameUser
		),
					function($message) use ( $sender, $fullNameUser, $titleSite, $_emailUser)
						{
						    $message->from($sender, $titleSite)
						    	->to($_emailUser, $fullNameUser)
								->subject( trans('misc.withdrawal_processed').' - '.$titleSite );
						});
			//<------ Send Email to User ---------->>>

		return redirect('panel/admin/withdrawals');

	}//<--- End Method

	public function withdrawlsIpn(){

		$ipn = new PaypalIPNListener();

		$ipn->use_curl = false;

		if ( $this->settings->paypal_sandbox == 'true') {
			// SandBox
			$ipn->use_sandbox = true;
			} else {
			// Real environment
			$ipn->use_sandbox = false;
			}

	    $verified = $ipn->processIpn();

		$withdrawalsID    = $_POST['custom'];
		$payment_status = $_POST['payment_status'];
		$txn_id               = $_POST['txn_id'];

		if ($verified) {
	        if($payment_status == 'Completed'){

				$verifiedTxnId = Withdrawals::where('txn_id',$txn_id)->first();
				$data             = Withdrawals::find($withdrawalsID);
				$user            = User::find($data->campaigns()->user_id);

				if( !isset( $verifiedTxnId ) && isset($data) ) {
					$data->status       = 'paid';
					$data->date_paid = Carbon::now();
					$data->save();

					//<------ Send Email to User ---------->>>
		$amount    = $this->settings->currency_symbol.$data->amount.' '.$this->settings->currency_code;
		$sender     = $this->settings->email_no_reply;
	    $titleSite     = $this->settings->title;
		$campaign = $data->campaigns()->title;
		$fullNameUser = $user->name;
		$_emailUser = $user->email;

		Mail::send('emails.withdrawal-processed', array(
					'campaign' => $campaign,
					'amount' => $amount,
					'title_site' => $titleSite,
					'fullname' => $fullNameUser
		),
					function($message) use ( $sender, $fullNameUser, $titleSite, $_emailUser)
						{
						    $message->from($sender, $titleSite)
						    	->to($_emailUser, $fullNameUser)
								->subject( trans('misc.withdrawal_processed').' - '.$titleSite );
						});
			//<------ Send Email to User ---------->>>

				}// Isset $verifiedTxnId

	        }// $payment_status
		}// $verified

	}//<--- End Method


	public function editCampaigns($id){

		$data = Campaigns::findOrFail($id);
		return view('admin.edit-campaign', ['data' => $data, 'settings' => $this->settings]);
	}

	public function postEditCampaigns(Request $request){

		$sql = Campaigns::findOrFail($request->id);

		Validator::extend('text_required', function($attribute, $value, $parameters)
			{
				$value = preg_replace("/\s|&nbsp;/",'',$value);
			    return strip_tags($value);
			});

			if($this->settings->currency_position == 'right') {
	      $currencyPosition =  2;
	    } else {
	      $currencyPosition =  null;
	    }

		$messages = array (
		'description.required' => trans('misc.description_required'),
		'description.text_required' => trans('misc.text_required'),
		'categories_id.required' => trans('misc.please_select_category'),
		'goal.min' => trans('misc.amount_minimum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
		'goal.max' => trans('misc.amount_maximum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
	);

		$rules = array(
            'title'             => 'required|min:3|max:45',
            'categories_id'  => 'required',
	    	'goal'             => 'required|integer|max:'.$this->settings->max_campaign_amount.'|min:'.$this->settings->min_campaign_amount,
	    	 'location'        => 'required|max:50',
	        'description'  => 'text_required|required|min:20',
        );

		$this->validate($request, $rules, $messages);

		$description = html_entity_decode($request->description);

		//<= HTML Clean
		$configuration = [
		'HTML.Allowed' => 'iframe[src|width|height],strong,em,a[href],ul,ol,li,br,img[src|width|height]',
		 "HTML.SafeIframe" => true,
		"URI.SafeIframeRegexp" => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
		];

	$description = \Purify::clean($request->description, $configuration);

		//REMOVE SCRIPT Iframe
		//$description = Helper::removeTagIframe($description);

		$description = trim(Helper::spaces($description));

		// Status
		if( $request->finalized == 'pending' ) {
			$statusGlobal = 'pending';
			$request->finalized = '0';
		} elseif($request->finalized == '0' || $request->finalized == 1 ) {
			$statusGlobal = 'active';
			$request->finalized = $request->finalized;
		}

		$sql->title = $request->title;
		$sql->goal = $request->goal;
		$sql->location = $request->location;
		$sql->description = Helper::removeBR($description);
		$sql->finalized = $request->finalized;
		$sql->status = $statusGlobal;
		$sql->categories_id = $request->categories_id;
		$sql->featured = $request->featured;
		$sql->save();

		\Session::flash('success_message', trans('admin.success_update'));
	    return redirect('panel/admin/campaigns');
	}

	public function deleteCampaign(Request $request){

		$data = Campaigns::findOrFail($request->id);

		$path_small     = 'public/campaigns/small/';
		$path_large     = 'public/campaigns/large/';
		$path_updates = 'public/campaigns/updates/';

		$updates = $data->updates()->get();

		//Delete Updates
		foreach ($updates as $key) {

			if ( \File::exists($path_updates.$key->image) ) {
					\File::delete($path_updates.$key->image);
				}//<--- if file exists

				$key->delete();
		}//<--

		// Delete Campaign
		if ( \File::exists($path_small.$data->small_image) ) {
					\File::delete($path_small.$data->small_image);
				}//<--- if file exists

				if ( \File::exists($path_large.$data->large_image) ) {
					\File::delete($path_large.$data->large_image);
				}//<--- if file exists

				//Delete campaign Reported
				$campaignReporteds = CampaignsReported::where('campaigns_id',$request->id)->get();

				if($campaignReporteds) {
					foreach ($campaignReporteds as $campaignReported) {
							$campaignReported->delete();
					}//<-- foreach
				}// IF

		 $data->delete();

		 \Session::flash('success_message', trans('misc.success_delete'));
	    return redirect('panel/admin/campaigns');
	}

	// START
	public function categories() {

		$categories      = Categories::orderBy('name')->get();
		$totalCategories = count( $categories );

		return view('admin.categories', compact( 'categories', 'totalCategories' ));

	}//<--- END METHOD

	public function addCategories() {

		return view('admin.add-categories');

	}//<--- END METHOD

	public function storeCategories(Request $request) {

		$temp = 'public/temp/'; // Temp
	  $path = 'public/img-category/'; // Path General

		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		$rules = array(
            'name'        => 'required',
	        'slug'        => 'required|ascii_only|unique:categories',
	        'thumbnail'   => 'mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=457,min_height=359',
        );

		$this->validate($request, $rules);

		if ($request->hasFile('thumbnail'))	{

		$extension      = $request->file('thumbnail')->getClientOriginalExtension();
		$type_mime_shot = $request->file('thumbnail')->getMimeType();
		$sizeFile       = $request->file('thumbnail')->getSize();
		$thumbnail      = $request->slug.'-'.str_random(32).'.'.$extension;

		if( $request->file('thumbnail')->move($temp, $thumbnail) ) {

			$image = Image::make($temp.$thumbnail);

			if(  $image->width() == 457 && $image->height() == 359 ) {

					\File::copy($temp.$thumbnail, $path.$thumbnail);
					\File::delete($temp.$thumbnail);

			} else {
				$image->fit(457, 359)->save($temp.$thumbnail);

				\File::copy($temp.$thumbnail, $path.$thumbnail);
				\File::delete($temp.$thumbnail);
			}

			}// End File
		} // HasFile

else {
	$thumbnail = '';
}

		$sql        = new Categories();
		$sql->name  = $request->name;
		$sql->slug  = $request->slug;
		$sql->mode  = $request->mode;
		$sql->image = $thumbnail;
		$sql->save();

		\Session::flash('success_message', trans('admin.success_add_category'));

    	return redirect('panel/admin/categories');

	}//<--- END METHOD

	public function editCategories($id) {

		$categories        = Categories::find( $id );

		return view('admin.edit-categories')->with('categories',$categories);

	}//<--- END METHOD

	public function updateCategories( Request $request ) {


		$categories = Categories::find($request->id);
		$temp       = 'public/temp/'; // Temp
	  $path       = 'public/img-category/'; // Path General

	    if (! isset($categories)) {
			return redirect('panel/admin/categories');
		}

		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		$rules = array(
            'name'        => 'required',
	        'slug'        => 'required|ascii_only|unique:categories,slug,'.$request->id,
	        'thumbnail'   => 'mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=457,min_height=359',
	     );

		$this->validate($request, $rules);

		if ($request->hasFile('thumbnail'))	{

		$extension      = $request->file('thumbnail')->getClientOriginalExtension();
		$type_mime_shot = $request->file('thumbnail')->getMimeType();
		$sizeFile       = $request->file('thumbnail')->getSize();
		$thumbnail      = $request->slug.'-'.str_random(32).'.'.$extension;

		if ($request->file('thumbnail')->move($temp, $thumbnail)) {

			$image = Image::make($temp.$thumbnail);

			if ($image->width() == 457 && $image->height() == 359) {

					\File::copy($temp.$thumbnail, $path.$thumbnail);
					\File::delete($temp.$thumbnail);

			} else {
				$image->fit(457, 359)->save($temp.$thumbnail);

				\File::copy($temp.$thumbnail, $path.$thumbnail);
				\File::delete($temp.$thumbnail);
			}

			// Delete Old Image
			\File::delete($path.$categories->image);

			}// End File
		} // HasFile
		else {
			$thumbnail = $categories->image;
		}

		// UPDATE CATEGORY
		$categories->name  = $request->name;
		$categories->slug  = $request->slug;
		$categories->mode  = $request->mode ?? 'off';
		$categories->image = $thumbnail;
		$categories->save();

		\Session::flash('success_message', trans('misc.success_update'));

    	return redirect('panel/admin/categories');

	}//<--- END METHOD

	public function deleteCategories($id){

		$categories        = Categories::find( $id );
		$thumbnail          = 'public/img-category/'.$categories->image; // Path General

		if( !isset($categories) ) {
			return redirect('panel/admin/categories');
		} else {

			// Delete Category
			$categories->delete();

			// Delete Thumbnail
			if ( \File::exists($thumbnail) ) {
				\File::delete($thumbnail);
			}//<--- IF FILE EXISTS

			return redirect('panel/admin/categories');
		}
	}//<--- END METHOD

	public function reportedCampaigns(){
		$data = CampaignsReported::orderBy('id','DESC')->paginate(50);
		return view('admin.reported-campaigns')->withData($data);
	}//<--- END METHOD

	public function reportedDeleteCampaigns(Request $request){

		$report = CampaignsReported::find($request->id);

		if( isset( $report ) ) {
			$report->delete();
			return redirect('panel/admin/campaigns/reported');
		} else {
			return redirect('panel/admin/campaigns/reported');
		}

	}//<--- END METHOD

	public function approveDonation(Request $request)
	{
		$sql = Donations::findOrFail($request->id);
		$sql->approved = '1';
		$sql->save();
		return redirect('panel/admin/donations');
	}//<--- END METHOD

	public function deleteDonation(Request $request)
	{
	  $sql = Donations::findOrFail($request->id);

      $sql->delete();

      return redirect('panel/admin/donations');

	}//<--- End Method

	public function paymentsGateways($id)
	{
		$data = PaymentGateways::findOrFail($id);
		$name = ucfirst($data->name);

		return view('admin.'.str_slug($name).'-settings')->withData($data);
	}//<--- End Method

	public function savePaymentsGateways($id, Request $request)
	{
		$data = PaymentGateways::findOrFail($id);

		$input = $request->all();

		// Sandbox off
		if (! $request->sandbox) {
		   $input['sandbox'] = 'false';
		}

		// Enabled off
		if (! $request->enabled) {
		   $input['enabled'] = '0';
		}

		$this->validate($request, [
            'email'    => 'email',
        ]);

		$data->fill($input)->save();

		\Session::flash('success_message', trans('admin.success_update'));

    return back();
	}//<--- End Method

	public function theme()
	{
		return view('admin.theme');

	}//<--- End method

	public function themeStore(Request $request) {

		$temp  = 'public/temp/'; // Temp
	  $path  = 'public/img/'; // Path
		$pathAvatar = 'public/avatar/'; // Path
		$pathCategory = 'public/img-category/'; // Path

		$rules = array(
          'logo'   => 'mimes:png',
					'watermark'   => 'mimes:png',
					'favicon'   => 'mimes:png',
					'index_image_bottom'   => 'mimes:jpg,jpeg',
					'avatar'   => 'mimes:jpg,jpeg',
					'image_category' => 'mimes:jpg,jpeg',
					'slider_1' => 'mimes:jpg,jpeg',
					'slider_2' => 'mimes:jpg,jpeg',
					'slider_3' => 'mimes:jpg,jpeg',
        );

		$this->validate($request, $rules);

		//======= LOGO
		if( $request->hasFile('logo') )	{

		$extension = $request->file('logo')->getClientOriginalExtension();
		$file      = 'logo.'.$extension;

		if($request->file('logo')->move($temp, $file) ) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			}// End File
		} // HasFile

		//======= LOGO Watermark
		if( $request->hasFile('logo_footer') )	{

		$extension = $request->file('logo_footer')->getClientOriginalExtension();
		$file      = 'watermark.'.$extension;

		if($request->file('logo_footer')->move($temp, $file) ) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			}// End File
		} // HasFile

		//======== FAVICON
		if($request->hasFile('favicon') )	{

		$extension  = $request->file('favicon')->getClientOriginalExtension();
		$file       = 'favicon.'.$extension;

		if( $request->file('favicon')->move($temp, $file) ) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			}// End File
		} // HasFile

		//======== slider_1
		if($request->hasFile('slider_1') )	{

		$extension  = $request->file('slider_1')->getClientOriginalExtension();
		$file       = 'slider-1.'.$extension;

		if( $request->file('slider_1')->move($temp, $file) ) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			}// End File
		} // HasFile

		//======== slider_2
		if($request->hasFile('slider_2') )	{

		$extension  = $request->file('slider_2')->getClientOriginalExtension();
		$file       = 'slider-2.'.$extension;

		if( $request->file('slider_2')->move($temp, $file) ) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			}// End File
		} // HasFile

		//======== slider_3
		if($request->hasFile('slider_3') )	{

		$extension  = $request->file('slider_3')->getClientOriginalExtension();
		$file       = 'slider-3.'.$extension;

		if( $request->file('slider_3')->move($temp, $file) ) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			}// End File
		} // HasFile

		//======== index_image_bottom
		if($request->hasFile('index_image_bottom') )	{

		$extension  = $request->file('index_image_bottom')->getClientOriginalExtension();
		$file       = 'cover.'.$extension;

		if( $request->file('index_image_bottom')->move($temp, $file) ) {
			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);
			}// End File
		} // HasFile

		//======== Avatar
		if($request->hasFile('avatar') )	{

		$extension  = $request->file('avatar')->getClientOriginalExtension();
		$file       = 'default.'.$extension;

		if( $request->file('avatar')->move($temp, $file) ) {
			\File::copy($temp.$file, $pathAvatar.$file);
			\File::delete($temp.$file);
			}// End File
		} // HasFile

		//======== Image Category
		if($request->hasFile('image_category') )	{

		$extension  = $request->file('image_category')->getClientOriginalExtension();
		$file       = 'default.'.$extension;

		if( $request->file('image_category')->move($temp, $file) ) {
			\File::copy($temp.$file, $pathCategory.$file);
			\File::delete($temp.$file);
			}// End File
		} // HasFile

		// Update Color Default
		$this->settings->whereId(1)->update(['color_default' => $request->get('color')]);

		\Artisan::call('cache:clear');

		return redirect('panel/admin/theme')
			 ->with('success_message', trans('misc.success_update'));

	}//<--- End method

	public function gallery()
	{
		return view('admin.gallery')->withData(Gallery::all()->sortByDesc('id'));

	}//<--- End method

	public function addGallery(Request $request)
	{
		$temp  = 'public/temp/'; // Temp
	  $path  = 'public/gallery/'; // Path

		$rules = array(
					'image' => 'required|mimes:jpg,jpeg,png,gif|dimensions:min_width=1280',

        );

		$this->validate($request, $rules);

		if ($request->hasFile('image')) {

		$extension  = $request->file('image')->getClientOriginalExtension();
		$file       = 'image-'.time().'.'.$extension;
		$fileThumb  = 'thumb-image-'.time().'.'.$extension;

		if ($request->file('image')->move($temp, $file)) {

			$image = Image::make($temp.$file);

			$image->resize(1280, null, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			})->save($temp.$file);

			\File::copy($temp.$file, $path.$file);
			\File::delete($temp.$file);

			$image->fit(360, 360)->save($temp.$fileThumb);
			\File::copy($temp.$fileThumb, $path.$fileThumb);
			\File::delete($temp.$fileThumb);


		 }// End File

		} // HasFile

		$sql  = New Gallery;
		$sql->image  = $file;
		$sql->save();

		\Session::flash('success_message',trans('admin.success_add'));

		return redirect('panel/admin/gallery');

	}//<--- End method

	public function deleteGallery(Request $request)
	{
		$image = Gallery::findOrFail($request->id);

		$path = 'public/gallery/';

		//Delete
		if (\File::exists($path.$image->image) ) {
				\File::delete($path.$image->image);
			}//<--- if file exists

			//Delete
			if (\File::exists($path.'thumb-'.$image->image) ) {
					\File::delete($path.'thumb-'.$image->image);
				}//<--- if file exists

		$image->delete();

		return redirect('panel/admin/gallery');

	}//<--- End method

	public function addPayment(Request $request)
	{
		$donation = $request->all();

		$this->validate($request, [
			'campaign' => 'required',
			'donation' => 'required|integer|min:'.$this->settings->min_donation_amount,
			'full_name'     => 'required',
			'email'     => 'required|email|max:100',
			'country'     => 'required',
			'postal_code'     => 'required|max:30',
			'transaction_id'     => 'required',
			'payment_gateway' => 'required',
        ]);

		$sql = new Donations;
		$sql->campaigns_id = $donation['campaign'];
		$sql->txn_id       = $donation['transaction_id'];
		$sql->fullname     = $donation['full_name'];
		$sql->email        = $donation['email'];
		$sql->country      = $donation['country'];
		$sql->postal_code  = $donation['postal_code'];
		$sql->donation     = $donation['donation'];
		$sql->payment_gateway = $donation['payment_gateway'];
		$sql->save();

		 \Session::flash('success_message', trans('admin.success_add'));
		return back();
	}

	public function uploadImageEditor(Request $request)
	{
		if ($request->hasFile('upload')) {

			$validator = Validator::make($request->all(), [
				'upload' => 'required|mimes:jpg,gif,png,jpe,jpeg|max:'.$this->settings->file_size_allowed.'',
						]);

			if ($validator->fails()) {
 	        return response()->json([
 			        'uploaded' => 0,
							'error' => ['message' => trans('misc.upload_image_error_editor').' '.Helper::formatBytes($this->settings->file_size_allowed * 1024)],
 			    ]);
 	    } //<-- Validator


        $originName = $request->file('upload')->getClientOriginalName();
        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $extension = $request->file('upload')->getClientOriginalExtension();
        $fileName = str_random().'_'.time().'.'.$extension;

				$request->file('upload')->move(public_path('blog'), $fileName);

        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $url = asset('public/blog/'.$fileName);
        $msg = 'Image uploaded successfully';
        $response = "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg');</script>";

				return response()->json([ 'fileName' => $fileName, 'uploaded' => true, 'url' => $url, ]);
    }
	}// End Method

	public function blog()
	{
		$data = Blogs::orderBy('id','desc')->paginate(50);
		return view('admin.blog', ['data' => $data]);
	}//<--- End Method

	public function createBlogStore(Request $request)
	{
		$rules = [
            'title'     => 'required',
						'thumbnail' => 'required|dimensions:min_width=650,min_height=430',
						'tags'      => 'required',
						'content'   => 'required',
	     ];

		$this->validate($request, $rules);

		// Image
		if( $request->hasFile('thumbnail') ) {

			$image     =  $request->file('thumbnail');
			$extension = $image->getClientOriginalExtension();
			$thumbnail = str_random(55).'.'.$extension;
			$path = public_path('blog/'.$thumbnail);

		$imageResize  = Image::make($image)->resize(650, null, function ($constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		})->save($path);

		} // HasFile Image

		$data = New Blogs;
		$data->slug = str_slug($request->title);
		$data->title = $request->title;
		$data->image = $thumbnail;
		$data->tags = $request->tags;
		$data->content = $request->content;
		$data->user_id = Auth::user()->id;
		$data->save();

		\Session::flash('success_message',trans('admin.success_add'));
		return redirect('panel/admin/blog');

	}//<--- END METHOD

	public function editBlog($id)
	{
		$data = Blogs::findOrFail($id);

		return view('admin.edit-blog', ['data' => $data ]);

	}//<--- End Method

	public function updateBlog(Request $request)
	{
		$data = Blogs::findOrFail($request->id);

		$rules = [
            'title'   => 'required',
						'thumbnail' => 'dimensions:min_width=650,min_height=430',
						'tags'    => 'required',
						'content' => 'required',
	     ];

		$this->validate($request, $rules);

		$thumbnail = $data->image;

		// Image
		if( $request->hasFile('thumbnail') ) {

			$image     =  $request->file('thumbnail');
			$extension = $image->getClientOriginalExtension();
			$thumbnail = str_random(55).'.'.$extension;
			$path = public_path('blog/'.$thumbnail);

		$imageResize  = Image::make($image)->resize(650, null, function ($constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		})->save($path);

		// Delete Old Thumbnail
		$pathDelete = 'public/blog/';
		\File::delete($pathDelete.$data->image);

		} // HasFile Image

		$data->title = $request->title;
		$data->slug = str_slug($request->title);
		$data->image = $thumbnail;
		$data->tags = $request->tags;
		$data->content = $request->content;
		$data->save();

		return back()->withSuccessMessage(trans('admin.success_update'));

	}//<--- END METHOD

	public function deleteBlog($id)
	{
		$data = Blogs::findOrFail($id);

		$path = 'public/blog/';

		// Delete Old Thumbnail
		\File::delete($path.$data->image);

		$data->delete();

		return redirect('panel/admin/blog')->withSuccessMessage(trans('misc.blog_deleted'));

	}//<--- END METHOD

	public function pwa(Request $request)
	{

		$allImgs = $request->file('files');

		if ($allImgs) {
			foreach ($allImgs as $key => $file) {

				$filename = md5(uniqid()).'.'.$file->getClientOriginalExtension();
				$file->move(public_path('images/icons'), $filename);

				\File::delete(env($key));

				$envIcon = 'public/images/icons/' . $filename;
				Helper::envUpdate($key, $envIcon);
			}
		}

		// Updaye Short Name
			Helper::envUpdate('PWA_SHORT_NAME', ' "'.$request->PWA_SHORT_NAME.'" ', true);

		return back()->withSuccessMessage(trans('admin.success_update'));

	}

}// End Class
