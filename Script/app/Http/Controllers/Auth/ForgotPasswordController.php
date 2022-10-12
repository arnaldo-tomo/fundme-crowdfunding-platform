<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\AdminSettings;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validateEmail(Request $request)
    {
        $settings = AdminSettings::first();
        $request['_captcha'] = $settings->captcha;

      $messages = [
        'g-recaptcha-response.required_if' => trans('admin.captcha_error_required'),
        'g-recaptcha-response.captcha' => trans('admin.captcha_error'),
      ];
      $this->validate($request, [
        'email' => 'required|email',
        'g-recaptcha-response' => 'required_if:_captcha,==,on|captcha'
      ], $messages);
    }
}
