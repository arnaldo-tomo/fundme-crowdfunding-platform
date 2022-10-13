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

class StripeController extends Controller
{
  public function __construct( AdminSettings $settings, Request $request) {
    $this->settings = $settings::first();
    $this->request = $request;
  }

  public function show() {

    return response()->json([
      'success' => true,
      'insertBody' => '<i></i>'
    ]);

  }// End Show

  public function charge()
  {

    // Campaign
    $campaign = Campaigns::findOrFail($this->request->_id);

    // Get Payment Gateway
    $payment = PaymentGateways::whereId($this->request->payment_gateway)->whereName('Stripe')->firstOrFail();

    //<---- Validation
		$validator = Validator::make($this->request->all(), [
				'amount' => 'required|integer|min:'.$this->settings->min_donation_amount.'|max:'.$this->settings->max_donation_amount,
        'full_name'     => 'required|max:25',
        'email'     => 'required|email|max:100',
        'country'     => 'required',
        'postal_code'     => 'required|max:30',
        'comment'     => 'nullable|max:100',
				'payment_gateway' => 'required',

	    	]);

			if ($validator->fails()) {
			        return response()->json([
					        'success' => false,
					        'errors' => $validator->getMessageBag()->toArray(),
					    ]);
			    }

    $email  = $this->request->email;
  	$cents  = $this->settings->currency_code == 'JPY' ? $this->request->amount : ($this->request->amount*100);
  	$amount = (int)$cents;
  	$currency_code = $this->settings->currency_code;
  	$description = trans('misc.donation_for').' '.$campaign->title;
  	$nameSite = $this->settings->title;

    \Stripe\Stripe::setApiKey($payment->key_secret);

    $intent = null;
    try {
      if (isset($this->request->payment_method_id)) {
        # Create the PaymentIntent
        $intent = \Stripe\PaymentIntent::create([
          'payment_method' => $this->request->payment_method_id,
          'amount' => $amount,
          'currency' => $currency_code,
          "description" => $description,
          'confirmation_method' => 'manual',
          'confirm' => true
        ]);
      }
      if (isset($this->request->payment_intent_id)) {
        $intent = \Stripe\PaymentIntent::retrieve(
          $this->request->payment_intent_id
        );
        $intent->confirm();
      }
      return $this->generatePaymentResponse($intent);
    } catch (\Stripe\Exception\ApiErrorException $e) {
      # Display error on client
      return response()->json([
        'error' => $e->getMessage()
      ]);
    }
  }// End charge

  protected function generatePaymentResponse($intent) {
    # Note that if your API version is before 2019-02-11, 'requires_action'
    # appears as 'requires_source_action'.
    if ($intent->status == 'requires_action' &&
        $intent->next_action->type == 'use_stripe_sdk') {
      # Tell the client to handle the action
      return response()->json([
        'requires_action' => true,
        'payment_intent_client_secret' => $intent->client_secret,
      ]);
    } else if ($intent->status == 'succeeded') {
      # The payment didn’t need any additional actions and completed!
      # Handle post-payment fulfillment

      // Insert DB and send Mail
      $sql                   = new Donations;
      $sql->campaigns_id     = $this->request->_id;
      $sql->txn_id           = $intent->id;
      $sql->fullname         = $this->request->full_name;
      $sql->email            = $this->request->email;
      $sql->country          = $this->request->country;
      $sql->postal_code      = $this->request->postal_code;
      $sql->donation         = $this->request->amount;
      $sql->payment_gateway  = 'Stripe';
      $sql->comment          = $this->request->input('comment', '');
      $sql->anonymous        = $this->request->input('anonymous', '0');
      $sql->rewards_id       = $this->request->input('_pledge', 0);
      $sql->save();

      // Send Email
      $campaign = Campaigns::find($this->request->_id);

      $sender       = $this->settings->email_no_reply;
      $titleSite    = $this->settings->title;
      $_emailUser   = $this->request->email;
      $campaignID   = $campaign->id;
      $campaignTitle = $campaign->title;
      $organizerName = $campaign->user()->name;
      $organizerEmail = $campaign->user()->email;
      $fullNameUser = $this->request->full_name;
      $paymentGateway = 'Stripe';

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

      return response()->json([
        "success" => true
      ]);
    } else {
      # Invalid status
      http_response_code(500);
      return response()->json(['error' => 'Invalid PaymentIntent status']);
    }
  }// End generatePaymentResponse

}
