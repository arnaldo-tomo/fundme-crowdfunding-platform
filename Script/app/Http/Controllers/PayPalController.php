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

class PayPalController extends Controller
{
  public function __construct( AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

    public function show() {

    if(!$this->request->expectsJson()) {
        abort(404);
    }

      // Get Payment Gateway
      $payment = PaymentGateways::findOrFail($this->request->payment_gateway);

      // Verify environment Sandbox or Live
      if ( $payment->sandbox == 'true') {
				$action = "https://www.sandbox.paypal.com/cgi-bin/webscr";
				} else {
				$action = "https://www.paypal.com/cgi-bin/webscr";
				}

			$urlSuccess = url('paypal/donation/success', $this->request->campaign_id);
			$urlCancel   = url('paypal/donation/cancel', $this->request->campaign_id);
			$urlPaypalIPN = url('paypal/ipn');

      return response()->json([
    			'success' => true,
          'insertBody' => '<form id="form_pp" name="_xclick" action="'.$action.'" method="post"  style="display:none">
          <input type="hidden" name="cmd" value="_xclick">
          <input type="hidden" name="return" value="'.$urlSuccess.'">
          <input type="hidden" name="cancel_return"   value="'.$urlCancel.'">
          <input type="hidden" name="notify_url" value="'.$urlPaypalIPN.'">
          <input type="hidden" name="currency_code" value="'.$this->settings->currency_code.'">
          <input type="hidden" name="amount" id="amount" value="'.$this->request->amount.'">
          <input type="hidden" name="custom" value="id='.$this->request->campaign_id.'&fn='.$this->request->full_name.'&mail='.$this->request->email.'&cc='.$this->request->country.'&pc='.$this->request->postal_code.'&cm='.$this->request->comment.'&anonymous='.$this->request->input('anonymous', '0').'&pl='.$this->request->input('_pledge', 0).'">
          <input type="hidden" name="item_name" value="'.trans('misc.donation_for').' '.$this->request->campaign_title.'">
          <input type="hidden" name="business" value="'.$payment->email.'">
          <input type="submit">
          </form> <script type="text/javascript">document._axclick.submit();</script>',
    	]);
    }

    public function paypalIpn() {

		$ipn = new PaypalIPNListener();

    $payment = PaymentGateways::find(1);

		if ($payment->sandbox) {
			// SandBox
			$ipn->use_sandbox = true;
			} else {
			// Real environment
			$ipn->use_sandbox = false;
			}

	    $verified = $ipn->processIpn();

      $report = $ipn->getTextReport();
      \Log::info("-----new payment-----");
      \Log::info($report);

		$custom  = $_POST['custom'];
		parse_str($custom, $donation);

		$payment_status = $_POST['payment_status'];
		$txn_id         = $_POST['txn_id'];
		$amount         = $_POST['mc_gross'];


	    if ($verified) {
	        if($payment_status == 'Completed') {

	          // Check outh POST variable and insert in DB
	          $verifiedTxnId = Donations::where('txn_id', $txn_id)->first();

			if(!isset($verifiedTxnId)) {

			   	$sql = new Donations;
		      $sql->campaigns_id = $donation['id'];
				  $sql->txn_id       = $txn_id;
				  $sql->fullname     = $donation['fn'];
				  $sql->email        = $donation['mail'];
				  $sql->country      = $donation['cc'];
				  $sql->postal_code  = $donation['pc'];
				  $sql->donation     = $amount;
				  $sql->payment_gateway = 'PayPal';
				  $sql->comment      = $donation['cm'];
				  $sql->anonymous    = $donation['anonymous'];
					$sql->rewards_id   = $donation['pl'];
				  $sql->save();

					// Send Email
				  $sender        = $this->settings->email_no_reply;
				  $titleSite     = $this->settings->title;
				  $_emailUser    = $donation['mail'];
				  $campaignID   = $donation['id'];
				  $fullNameUser = $donation['fn'];

					$queryCampaing = Campaigns::find($donation['id']);
					$campaignTitle = $queryCampaing->title;
					$organizerName = $donation['fn'];
					$organizerEmail = $donation['mail'];
					$paymentGateway = 'PayPal';

					Mail::send('emails.thanks-donor', array(
						'data' => $campaignID,
						'fullname' => $fullNameUser,
						'title_site' => $titleSite,
						'campaign_id' => $campaignID,
						'organizer_name' => $organizerName,
						'organizer_email' => $organizerEmail,
						'payment_gateway' => $paymentGateway,
					),

						function($message) use ( $sender, $fullNameUser, $titleSite, $_emailUser, $campaignTitle)
							{
									$message->from($sender, $titleSite)
										->to($_emailUser, $fullNameUser)
									->subject( trans('misc.thanks_donation').' - '.$campaignTitle.' || '.$titleSite );
							});
			}// <--- Verified Txn ID

	      } // <-- Payment status
	    } else {
	    	//Some thing went wrong in the payment !
	    }

    }//<----- End Method paypalIpn()
}
