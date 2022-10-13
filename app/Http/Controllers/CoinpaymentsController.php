<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Donations;
use App\Models\User;
use App\Models\Rewards;
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;

class CoinpaymentsController extends Controller
{
  public function __construct( AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

    public function show() {

    if (!$this->request->expectsJson()) {
        abort(404);
    }

    // Get Payment Gateway
    $payment = PaymentGateways::findOrFail($this->request->payment_gateway);

    $urlSuccess = url('paypal/donation/success', $this->request->campaign_id);
    $urlCancel   = url('paypal/donation/cancel', $this->request->campaign_id);

    $urlIPN = route('coinpaymentsIPN', [
      'id' => $this->request->campaign_id,
      'fn' => $this->request->full_name,
      'mail' => $this->request->email,
      'cc' => $this->request->country,
      'pc' => $this->request->postal_code,
      'cm' => $this->request->comment,
      'anonymous' => $this->request->input('anonymous', '0'),
      'pl' => $this->request->input('_pledge', 0)
    ]);

    $fee   = $payment->fee;
    $cents =  $payment->fee_cents;

    $amountFixed = $this->request->amount;

    $name = explode(' ', $this->request->full_name);
    $firstName = $name[0] ?? null;
    $lastName = $name[1] ?? null;

    return response()->json([
                'success' => true,
                'insertBody' => '<form id="form_cp" name="_pay" action="https://www.coinpayments.net/index.php" method="post"  style="display:none">
                <input type="hidden" name="cmd" value="_pay">
                <input type="hidden" name="reset" value="1"/>
                <input type="hidden" name="merchant" value="'.$payment->key.'">
                <input type="hidden" name="success_url" value="'.$urlSuccess.'">
                <input type="hidden" name="cancel_url"   value="'.$urlCancel.'">
                <input type="hidden" name="ipn_url" value="'.$urlIPN.'">
                <input type="hidden" name="currency" value="'.$this->settings->currency_code.'">
                <input type="hidden" name="amountf" value="'.$amountFixed.'">
                <input type="hidden" name="want_shipping" value="0">
                <input type="hidden" name="item_name" value="'.trans('misc.donation_for').' '.$this->request->campaign_title.'">
                <input type="hidden" name="email" value="'.$this->request->email.'">
                <input type="hidden" name="first_name" value="'.$firstName.'">
                <input type="hidden" name="last_name" value="'.$lastName.'">
                <input type="submit">
                </form> <script type="text/javascript">document._pay.submit();</script>',
            ]);
    }

    public function webhook(Request $request) {

    $paymentGateway = PaymentGateways::whereName('Coinpayments')->firstOrFail();

    $merchantId = $payment->key;
    $ipnSecret = $payment->key_secret;

    $currency = $this->settings->currency_code;

    // Find user
    $user = User::findOrFail($request->user);

    // Validations...
    if (! isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
      exit;
    }

    if (! isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
      exit;
    }

    $getRequest = file_get_contents('php://input');

    if ($getRequest === FALSE || empty($getRequest)) {
      exit;
    }

    if (! isset($_POST['merchant']) || $_POST['merchant'] != trim($merchantId)) {
      exit;
    }

    $hmac = hash_hmac("sha512", $getRequest, trim($ipnSecret));
    if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
      exit;
    }

    // Variables
    $ipn_type = $_POST['ipn_type'];
    $txn_id = $_POST['txn_id'];
    $item_name = $_POST['item_name'];
    $amount1 = floatval($_POST['amount1']);
    $amount2 = floatval($_POST['amount2']);
    $currency1 = $_POST['currency1'];
    $currency2 = $_POST['currency2'];
    $status = intval($_POST['status']);

    // Check Button payment
    if ($ipn_type != 'button') {
      exit;
    }

    // Check currency
    if ($currency1 != $currency) {
      exit;
    }

    if ($status >= 100 || $status == 2) {
      try {

        // Insert DB and send Mail
        $sql                   = new Donations;
        $sql->campaigns_id     = $request->campaign_id;
        $sql->txn_id           = $txn_id;
        $sql->fullname         = $request->fullname;
        $sql->email            = $request->email;
        $sql->country          = $request->country;
        $sql->postal_code      = $request->postal_code;
        $sql->donation         = $amount1;
        $sql->payment_gateway  = 'Coinpayments';
        $sql->comment          = $request->comment;
        $sql->anonymous        = $request->anonymous;
        $sql->rewards_id       = $request->pledge;
        $sql->save();

        try {
          // Send Email
          $campaign = Campaigns::find($request->campaign_id);

          $sender       = $this->settings->email_no_reply;
          $titleSite    = $this->settings->title;
          $_emailUser   = $request->email;
          $campaignID   = $campaign->id;
          $campaignTitle = $campaign->title;
          $organizerName = $campaign->user()->name;
          $organizerEmail = $campaign->user()->email;
          $fullNameUser = $request->full_name;
          $paymentGateway = 'Coinpayments';

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
        } catch (\Exception $e) {
          Log::info($e->getMessage());
        }

      } catch (\Exception $e) {
        Log::info($e->getMessage());
      }
    } // status >= 100

    }//<----- End Method webhook()
}
