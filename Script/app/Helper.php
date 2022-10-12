<?php

namespace App;

use Image;
use App\Models\AdminSettings;
use App\Models\Donations;
use App\Models\Pages;
use App\Models\PaymentGateways;
use App\Models\Campaigns;

class Helper
{
	// spaces
	public static function spacesUrlFiles($string) {
	  return ( preg_replace('/(\s+)/u','_',$string ) );

	}

	public static function spacesUrl($string) {
	  return ( preg_replace('/(\s+)/u','+',trim( $string ) ) );

	}

	public static function removeLineBreak( $string )  {
		return str_replace(array("\r\n", "\r"), "", $string);
	}

    public static function hyphenated($url)
    {
        $url = strtolower($url);
        //Rememplazamos caracteres especiales latinos
        $find = array('á','é','í','ó','ú','ñ');
        $repl = array('a','e','i','o','u','n');
        $url = str_replace($find,$repl,$url);
        // Añaadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
                $url = str_replace ($find, '-', $url);
        // Eliminamos y Reemplazamos demás caracteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace ($find, $repl, $url);
        //$palabra=trim($palabra);
        //$palabra=str_replace(" ","-",$palabra);
        return $url;
        }

	// Text With (2) line break
	public static function checkTextDb( $str ) {

		//$str = trim( self::spaces( $str ) );
		if( mb_strlen( $str, 'utf8' ) < 1 ) {
			return false;
		}
		$str = preg_replace('/(?:(?:\r\n|\r|\n)\s*){3}/s', "\r\n\r\n", $str);
		$str = trim($str,"\r\n");

		return $str;
	}

	public static function checkText( $str ) {

		//$str = trim( self::spaces( $str ) );
		if( mb_strlen( $str, 'utf8' ) < 1 ) {
			return false;
		}

		$str = nl2br( e( $str ) );
		$str = str_replace( array( chr( 10 ), chr( 13 ) ), '' , $str );

		$str = stripslashes( $str );

		return $str;
	}

	public static function formatNumber( $number ) {
    if( $number >= 1000 &&  $number < 1000000 ) {

       return number_format( $number/1000, 1 ). "k";
    } else if( $number >= 1000000 ) {
		return number_format( $number/1000000, 1 ). "M";
	} else {
        return $number;
    }
   }//<<<<--- End Function

   public static function formatNumbersStats( $number ) {

    if( $number >= 100000000 ) {
		return '<span class="counter">'.number_format( $number/1000000, 0 ). "</span>M";
	} else {
        return '<span class="counter">'.number_format( $number ).'</span>';
    }
   }//<<<<--- End Function

   public static function spaces($string) {
	  return ( preg_replace('/(\s+)/u',' ',$string ) );

	}

	public static function resizeImage( $image, $width, $height, $scale, $imageNew = null ) {

		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image);
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image);
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image);
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);

	switch($imageType) {
		case "image/gif":
	  		imagegif( $newImage, $imageNew );
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg( $newImage, $imageNew ,90 );
			break;
		case "image/png":
		case "image/x-png":
			imagepng( $newImage, $imageNew );
			break;
    }

	chmod($image, 0777);
	return $image;
	}

public static function resizeImageFixed( $image, $width, $height, $imageNew = null ) {

	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImage = imagecreatetruecolor($width,$height);

	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image);
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image);
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image);
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
  	}
	if( $width/$imagewidth > $height/$imageheight ){
        $nw = $width;
        $nh = ($imageheight * $nw) / $imagewidth;
        $px = 0;
        $py = ($height - $nh) / 2;
    } else {
        $nh = $height;
        $nw = ($imagewidth * $nh) / $imageheight;
        $py = 0;
        $px = ($width - $nw) / 2;
    }

	imagecopyresampled($newImage,$source,$px, $py, 0, 0, $nw, $nh, $imagewidth, $imageheight);

	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$imageNew);
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$imageNew,90);
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$imageNew);
			break;
    }

		chmod($image, 0777);
		return $image;
	}

	public static function getHeight( $image ) {
		$size   = getimagesize( $image );
		$height = $size[1];
		return $height;
	}

	public static function getWidth( $image ) {
		$size  = getimagesize( $image);
		$width = $size[0];
		return $width;
	}
	public static function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('', 'kB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
  }

	public static function removeHTPP($string){
		$string = preg_replace('#^https?://#', '', $string);
		return $string;
	}

	public static function Array2Str( $kvsep, $entrysep, $a ){
		$str = "";
			foreach ( $a as $k => $v ){
				$str .= "{$k}{$kvsep}{$v}{$entrysep}";
				}
		return $str;
	}

	public static function removeBR($string) {
		$html    = preg_replace( '[^(<br( \/)?>)*|(<br( \/)?>)*$]', '', $string );
		$output = preg_replace('~(?:<br\b[^>]*>|\R){3,}~i', '<br /><br />', $html);
		return $output;
	}

	public static function removeTagScript( $html ){

			  	//parsing begins here:
				$doc = new \DOMDocument();
				@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
				$nodes = $doc->getElementsByTagName('script');

				$remove = [];

				foreach ($nodes as $item) {
					$remove[] = $item;
				}

				foreach ($remove as $item) {
					$item->parentNode->removeChild($item);
				}

				return preg_replace(
					'/^<!DOCTYPE.+?>/', '',
					str_replace(
					array('<html>', '</html>', '<body>', '</body>', '<head>', '</head>', '<p>', '</p>', '&nbsp;' ),
					array('','','','','',' '),
					$doc->saveHtml() ));
	}// End Method

	public static function removeTagIframe( $html ){

			  	//parsing begins here:
				$doc = new \DOMDocument();
				@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
				$nodes = $doc->getElementsByTagName('iframe');

				$remove = [];

				foreach ($nodes as $item) {
					$remove[] = $item;
				}

				foreach ($remove as $item) {
					$item->parentNode->removeChild($item);
				}

				return preg_replace(
					'/^<!DOCTYPE.+?>/', '',
					str_replace(
					array('<html>', '</html>', '<body>', '</body>', '<head>', '</head>', '<p>', '</p>', '&nbsp;' ),
					array('','','','','',' '),
					$doc->saveHtml() ));
	}// End Method

	public static function fileNameOriginal($string){
		return pathinfo($string, PATHINFO_FILENAME);
	}

	public static function formatDate( $date ){

		$day    = date('d', strtotime($date));
		$_month = date('m', strtotime($date));
		$month  = trans("months.$_month");
		$year   = date('Y', strtotime($date));

		$dateFormat = $month.' '.$day.', '.$year;

		return $dateFormat;
	}

	public static function watermark( $name, $watermarkSource ) {

		$thumbnail = Image::make($name);
		$watermark = Image::make($watermarkSource);
		$x = 0;

		while ($x < $thumbnail->width()) {
		    $y = 0;

		    while($y < $thumbnail->height()) {
		        $thumbnail->insert($watermarkSource, 'top-left', $x, $y);
		        $y += $watermark->height();
		    }

		    $x += $watermark->width();
		}

		$thumbnail->save($name)->destroy();
	}

	public static function amountFormat($value, $decimal = null) {

		$settings = AdminSettings::first();

 	 if ($settings->decimal_format == 'dot') {
 		 $decimalDot = '.';
 		 $decimalComma = ',';
 	 } else {
 		 $decimalDot = ',';
 		 $decimalComma = '.';
 	 }

		if($settings->currency_position == 'left') {
			$amount = $settings->currency_symbol.number_format($value, $decimal, $decimalDot, $decimalComma);
		} elseif($settings->currency_position == 'right') {
			$amount = number_format($value, $decimal, $decimalDot, $decimalComma).$settings->currency_symbol;
		} else {
			$amount = $settings->currency_symbol.number_format($value, $decimal, $decimalDot, $decimalComma);
		}

	 return $amount;

	}

	public static function cleanBR($string) {
		$output = preg_replace('#(<br *\/?>\s*(&nbsp;)*)+#', '<br>', $string);
		return $output;
	}

	public static function videoUrl($url)
	{
		$urlValid = filter_var($url, FILTER_VALIDATE_URL) ? true : false;

		if ($urlValid) {
			$parse = parse_url($url);
			$host  = strtolower($parse['host']);

			if ($host) {
				if (in_array($host, array(
					'youtube.com',
					'www.youtube.com',
					'youtu.be',
					'www.youtu.be',
					'vimeo.com',
					'player.vimeo.com'))) {
						return $host;
				}
			}
		}
	} // End videoUrl

	public static function getYoutubeId($url) {
	 $pattern =
			 '%^# Match any youtube URL
			(?:https?://)?
			(?:www\.)?
			(?:
				youtu\.be/
			| youtube\.com
				(?:
					/embed/
				| /v/
				| .*v=
				)
			)
			([\w-]{10,12})
			($|&).*
			$%x'
			;

			$result = preg_match( $pattern, $url, $matches );
			if ( $matches ) {
					return $matches[1];
			}
			return false;
	}//<<<-- End

	public static function getVimeoId($url)
	{

		$url = explode('/',$url);
		return $url[3];
	}

	public static function envUpdate($key, $value, $comma = false)
   {
       $path = base_path('.env');
 			$value = trim($value);
 			$env = $comma ? '"'.env($key).'"' : env($key);

       if (file_exists($path)) {

           file_put_contents($path, str_replace(
               $key . '=' . $env, $key . '=' . $value, file_get_contents($path)
           ));
       }
   }

	 public static function earningsNet($type)
	 {
		 $settings = AdminSettings::first();

		 switch ($type) {
 			case 'admin':

			$earningsNetAmin = 0;

			foreach (Donations::where('approved','1')->get() as $key) {

 				foreach (PaymentGateways::all() as $payment) {

 				$paymentGateway = strtolower($payment->name);
 				$paymentGatewayDonation = strtolower($key->payment_gateway);

 				if ($paymentGatewayDonation == $paymentGateway) {
 					$fee   = $payment->fee;
 					$cents = $payment->fee_cents;

 					$amountGlobal = $key->donation - ($key->donation * $fee/100) - $cents;
 					$earningsNetUser = $amountGlobal - ($amountGlobal * $settings->fee_donation/100);
 					$earningsNetAmin += ($amountGlobal - $earningsNetUser);
 				} // IF
 			}// PaymentGateways()
 		}//<-- foreach

		return $settings->currency_code == 'JPY' ? round($earningsNetAmin) : Helper::amountFormat($earningsNetAmin, 2);
 				break;

 			case 'user':

			$earningsNetUser = 0;

			foreach (Campaigns::whereUserId(auth()->user()->id)->get() as $campaign) {
				foreach (Donations::whereCampaignsId($campaign->id)->get() as $key) {

		 				foreach (PaymentGateways::all() as $payment) {

		 				$paymentGateway = strtolower($payment->name);
		 				$paymentGatewayDonation = strtolower($key->payment_gateway);

		 				if ($paymentGatewayDonation == $paymentGateway) {
		 					$fee   = $payment->fee;
		 					$cents = $payment->fee_cents;

		 					$amountGlobal = $key->donation - ($key->donation * $fee/100) - $cents;
		 					$earningsNetUser += $amountGlobal - ($amountGlobal * $settings->fee_donation/100);
		 				} // IF
		 			}// PaymentGateways()
		 		}//<-- foreach
			}

			return $settings->currency_code == 'JPY' ? round($earningsNetUser) : Helper::amountFormat($earningsNetUser, 2);
 				break;
 		}

	}// End Method

	public static function earningsNetDonation($id, $type)
	{
		$settings = AdminSettings::first();

		$donation = Donations::whereId($id)->where('approved','1')->first();
		$paymentGateway = PaymentGateways::whereName($donation->payment_gateway)->first();

		$fee   = $paymentGateway->fee;
		$cents = $paymentGateway->fee_cents;

		$amountGlobal = $donation->donation - ($donation->donation * $fee/100) - $cents;
		$earningsNetUser = $amountGlobal - ($amountGlobal * $settings->fee_donation/100);
		$earningsNetAmin = ($amountGlobal - $earningsNetUser);

		switch ($type) {
			 case 'admin':
				 return $settings->currency_code == 'JPY' ? round($earningsNetAmin) : Helper::amountFormat($earningsNetAmin, 2);
				 break;

			 case 'user':
				 return $settings->currency_code == 'JPY' ? round($earningsNetUser) : Helper::amountFormat($earningsNetUser, 2);
				 break;
		}

 }// End Method

 public static function pages($showNavbar = false)
 {
	 $pagesLocale  = $showNavbar ? Pages::whereLang(session('locale'))->whereShowNavbar('1')->get() : Pages::whereLang(session('locale'))->get();

	 if ($pagesLocale->count() <> 0) {
			 return $pagesLocale;
	 } else {
		 return $showNavbar ? Pages::whereLang(env('DEFAULT_LOCALE'))->whereShowNavbar('1')->get() : Pages::whereLang(env('DEFAULT_LOCALE'))->get();
	 }
 }

 public static function formatDateChart($date){

	 $day    = date('d', strtotime($date));
	 $_month = date('m', strtotime($date));
	 $month  = trans("months.$_month");

	 $dateFormat = $month.' '.$day;

	 return $dateFormat;
 }

}//<--- End Class
