<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentGateways;
use App\Models\User;
use App\Models\AdminSettings;

class InstallController extends Controller
{
    public function __construct() {
      $this->middleware('role');
    }

    public function install($addon)
    {
      //<-------------- Install --------------->
      if ($addon == 'coinpayments') {

        $verifyPayment = PaymentGateways::whereName('Coinpayments')->first();

        if (! $verifyPayment) {

          // Controller
          $filePathController = 'coinpayments-payment/CoinpaymentsController.php';
          $pathController = app_path('Http/Controllers/CoinpaymentsController.php');

          if (\File::exists($filePathController) ) {
            rename($filePathController, $pathController);
          }//<--- IF FILE EXISTS

          // View
          $filePathView = 'coinpayments-payment/coinpayments-settings.blade.php';
          $pathView = resource_path('views/admin/coinpayments-settings.blade.php');

          if (\File::exists($filePathView) ) {
            rename($filePathView, $pathView);
          }//<--- IF FILE EXISTS

          file_put_contents(
              'routes/web.php',
              "\nRoute::get('payment/coinpayments', 'CoinpaymentsController@show')->name('coinpayments');\nRoute::post('webhook/coinpayments', 'CoinpaymentsController@webhook')->name('coinpaymentsIPN');\n",
              FILE_APPEND
          );

          if (Schema::hasTable('payment_gateways')) {
              \DB::table('payment_gateways')->insert(
      				[
      					'name' => 'Coinpayments',
      					'type' => 'normal',
      					'enabled' => '0',
      					'fee' => 0.0,
      					'fee_cents' => 0.00,
      					'email' => '',
      					'key' => '',
      					'key_secret' => '',
      					'bank_info' => '',
      					'token' => str_random(150),
      			]
          );
        }

        $indexPath = 'coinpayments-payment/index.php';
        unlink($indexPath);

        rmdir('coinpayments-payment');

        $getPayment = PaymentGateways::whereName('Coinpayments')->firstOrFail();

          return redirect('panel/admin/payments/'.$getPayment->id);
        } else {
          return redirect('/');
        }

    }
  }//<---------------------- End Install

}
