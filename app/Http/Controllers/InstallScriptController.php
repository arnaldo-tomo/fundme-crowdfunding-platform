<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\AdminSettings;
use App\Helper;
use Carbon\Carbon;
use PDO;
use DB;

class InstallScriptController extends Controller
{

  public function wizard()
  {
    try {
      // Check Datebase
       AdminSettings::first();
       return redirect('/');
    } catch (\Exception $e) {
      // empty
    }

    $minVersionPHP     = '7.3.0';
    $currentVersionPHP = (int) str_replace('.', '', phpversion());
    $versionPHP = version_compare(phpversion(), $minVersionPHP, '>=') ? true : false;

    // Extensions
    $BCMath    =  extension_loaded('BCMath') ? true : false;
    $Ctype     =  extension_loaded('Ctype') ? true : false;
    $Fileinfo  =  extension_loaded('Fileinfo') ? true : false;
    $openssl   =  extension_loaded('openssl') ? true : false;
    $pdo       =  extension_loaded('pdo') ? true : false;
    $mbstring  =  extension_loaded('mbstring') ? true : false;
    $tokenizer =  extension_loaded('tokenizer') ? true : false;
    $json      =  extension_loaded('JSON') ? true : false;
    $xml       =  extension_loaded('XML') ? true : false;
    $curl      =  extension_loaded('cURL') ? true : false;
    $gd        = extension_loaded('gd') ? true : false;
    $exif      = extension_loaded('exif') ? true : false;

    return view('installer.wizard', [
      'versionPHP' => $versionPHP,
      'minVersionPHP' => $minVersionPHP,
      'BCMath' => $BCMath,
      'Ctype' => $Ctype,
      'Fileinfo' => $Fileinfo,
      'openssl' => $openssl,
      'pdo' => $pdo,
      'mbstring' => $mbstring,
      'tokenizer' => $tokenizer,
      'json' => $json,
      'xml' => $xml,
      'curl' => $curl,
      'gd' => $gd,
      'exif' => $exif,
    ]);
  }

    public function database(Request $request)
    {
      if (! $request->expectsJson()) {
          abort(500);
      }

      try {
        // Check Datebase
         AdminSettings::first();

         return response()->json([
             'success' => false,
             'errors' => ['error' => 'Error! database has already been created'],
         ]);

      } catch (\Exception $e) {
        // empty
      }

      $validator = Validator::make($request->all(), [
        'database'     => 'required|string|max:50',
        'username'     => 'required|string|max:50',
        'host'         => 'required|string|max:70',
        'app_name'     => 'required|string|max:50',
        'app_url'      => 'required|url',
        'email_admin'  => 'required|email',
      ]);

      if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

      try {
        $db = new PDO('mysql:host='.$request->host.';dbname='.$request->database.'', $request->username, $request->password);

      } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'errors' => ['error' => 'Unable to connect to the database, check your credentials'],
        ]);
      }

      // Database
      Helper::envUpdate('DB_DATABASE', $request->database);
      Helper::envUpdate('DB_USERNAME', $request->username);
      Helper::envUpdate('DB_HOST', $request->host);
      Helper::envUpdate('DB_PASSWORD', ' "'.$request->password.'" ', true);
      // App
      Helper::envUpdate('APP_NAME', ' "'.$request->app_name.'" ', true);
      Helper::envUpdate('APP_URL', trim($request->app_url, '/'));
      Helper::envUpdate('MAIL_FROM_ADDRESS', $request->email_admin);

      Artisan::call('cache:clear');
      Artisan::call('config:cache');
      Artisan::call('config:clear');

      return response()->json([
          'success' => true,
      ]);

    }

    public function user(Request $request)
    {
      if (! $request->expectsJson()) {
          abort(500);
      }

      try {
        // Update registration date
        User::whereId(1)->update([
    					'date' => Carbon::now()
    				]);

        // Login automatic
        Auth::loginUsingId(1);

      } catch (\Exception $e) {
        // Error Login
        \Log::info($e->getMessage());
      }

      return response()->json([
          'success' => true,
          'url' => url('panel/admin'),
      ]);


    }

}
