<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Donations;
use App\Models\User;
use App\Models\Rewards;
use Fahim\PaypalIPN\PaypalIPNListener;
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;

class DonationsController extends Controller
{
	public function __construct( AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, $slug = null)
    {

	   $response = Campaigns::where('id',$id)->where('status','active')->firstOrFail();

		 $pledgeID = $this->request->input('pledge');

		 $findPledge = $response->rewards->find($pledgeID);

		 if( isset( $findPledge ) ) {
			 $pledgeClaimed = $response->donations()->where('rewards_id',$findPledge->id)->count();
		 }

		 if( isset( $findPledge ) && $pledgeClaimed < $findPledge->quantity ) {
			 $pledge = $findPledge;
		 } else {
			  $pledge = null;
		 }

	   $timeNow = strtotime(Carbon::now());

		if( $response->deadline != '' ) {
		    $deadline = strtotime($response->deadline);
		}

	   // Redirect if campaign is ended
	   if( !isset( $deadline ) && $response->finalized == 1 ) {
	   	 return redirect('campaign/'.$response->id);
	   } else if(  isset( $deadline ) && $response->finalized == 1 ) {
	   	return redirect('campaign/'.$response->id);
	   } else if(  isset( $deadline ) && $deadline < $timeNow ) {
	   	return redirect('campaign/'.$response->id);
	   }

		$uriCampaign = $this->request->path();

		if( str_slug( $response->title ) == '' ) {

				$slugUrl  = '';
			} else {
				$slugUrl  = '/'.str_slug( $response->title );
			}

			$url_campaign = 'donate/'.$response->id.$slugUrl;

			//<<<-- * Redirect the user real page * -->>>
			$uriCanonical = $url_campaign;

			if( $uriCampaign != $uriCanonical ) {
				return redirect($uriCanonical);
			}

			$percentage = number_format($response->donations()->sum('donation') / $response->goal * 100, 2, '.', '');

			// All Donations
			$donations = $response->donations()->orderBy('id','desc')->paginate(2);

			// Updates
			$updates = $response->updates()->orderBy('id','desc')->paginate(1);

			if( str_slug( $response->title ) == '' ) {
				$slug_url  = '';
			} else {
				$slug_url  = '/'.str_slug( $response->title );
			}

			// Bank Transfer Info
			$_bankTransfer = PaymentGateways::where('id', 3)->where('enabled', '1')->select('bank_info')->first();

			// Stripe Key
			$_stripe = PaymentGateways::where('id', 2)->where('enabled', '1')->select('key')->first();

		return view('default.donate')
				->with([
					'response' => $response,
					'pledge' => $pledge,
					'percentage' => $percentage,
					'donations' => $donations,
					'updates' => $updates,
					'slug_url' => $slug_url,
					'_bankTransfer' => $_bankTransfer,
					'_stripe' => $_stripe
				]);
    }// End Method

		// Send donation and validation
    public function send() {

			$campaign = Campaigns::findOrFail($this->request->_id);

			//<---- Verify Pledge send
			if(isset($this->request->_pledge) && $this->request->_pledge != 0 ){
				$findPledge = $campaign->rewards->where('id', $this->request->_pledge)
						->where('campaigns_id', $this->request->_id)
						->where('amount', $this->request->amount)->first();

				$pledgeClaimed = $campaign->donations()->where('rewards_id', $findPledge->id)->count();
			}

			if(isset($findPledge) && $pledgeClaimed < $findPledge->quantity) {
				$this->request->_pledge = $findPledge->id;
			} else {
				$this->request->_pledge = 0;
			}

			// Currency Position
			if($this->settings->currency_position == 'right') {
				$currencyPosition =  2;
			} else {
				$currencyPosition =  null;
			}

			Validator::extend('check_payment_gateway', function($attribute, $value, $parameters)
			{
				return PaymentGateways::find($value);
			});

			$data = $this->request->all();

			if (auth()->check() && $this->settings->captcha_on_donations == 'on') {
				$data['_captcha'] = 1;
			} else {
				$data['_captcha'] = $this->settings->captcha_on_donations == 'off' ? $data['_captcha'] = 1 : $data['_captcha'] = 0;
			}

			$messages = array (
			'amount.min' => trans('misc.amount_minimum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
			'amount.max' => trans('misc.amount_maximum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
			'payment_gateway.check_payment_gateway' => trans('admin.payments_error'),

			'bank_transfer.required_if' => trans('admin.bank_transfer_required'),
			'bank_transfer.min' => trans('admin.bank_transfer_limit'),
			'bank_transfer.max' => trans('admin.bank_transfer_limit'),
			'g-recaptcha-response.required_if' => trans('admin.captcha_error_required'),
	    'g-recaptcha-response.captcha' => trans('admin.captcha_error'),
		);

		//<---- Validation
		$validator = Validator::make($data, [
				'amount' => 'required|integer|min:'.$this->settings->min_donation_amount.'|max:'.$this->settings->max_donation_amount,
        'full_name'     => 'required|max:25',
        'email'     => 'required|email|max:100',
        'country'     => 'required',
        'postal_code'     => 'required|max:30',
        'comment'     => 'nullable|max:100',
				'payment_gateway' => 'required|check_payment_gateway',
				'bank_transfer' => 'required_if:payment_gateway,==,3|min:10|max:300',
				'g-recaptcha-response' => 'required_if:_captcha,==,0|captcha'

	    	],$messages);

			if ($validator->fails()) {
			        return response()->json([
					        'success' => false,
					        'errors' => $validator->getMessageBag()->toArray(),
					    ]);
			    }

					// Get name of Payment Gateway
					$payment = PaymentGateways::find($this->request->payment_gateway);

					if(!$payment) {
						return response()->json([
								'success' => false,
								'errors' => ['error' => trans('admin.payments_error')],
						]);
					}

					$data = [
						'campaign_id'    => $campaign->id,
						'campaign_title' => $campaign->title,
					];

					$data_all = $this->request->except(['_token']);
					$dataGlobal = array_merge($data, $data_all);

					// Send data to the payment processor
						return redirect()->route(str_slug($payment->name), $dataGlobal);
			}//<--------- End Method  Send

}
