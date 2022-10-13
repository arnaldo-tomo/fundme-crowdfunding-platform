<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Helper;

class UserController extends Controller
{

	protected function validator(array $data, $id = null) {

    	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		// Validate if have one letter
	Validator::extend('letters', function($attribute, $value, $parameters){
    	return preg_match('/[a-zA-Z0-9]/', $value);
	});

	$messages = array (
		'countries_id.required' => trans('misc.please_select_country'),
	);

			return Validator::make($data, [
	        'full_name' => 'required|min:3|max:25',
			'email'     => 'required|email|unique:users,email,'.$id,
			'countries_id'     => 'required',
	        ],$messages);

    }//<--- End Method


    public function account()
    {
		return view('users.account');
    }//<--- End Method

	public function update_account(Request $request)
    {

	   $input = $request->all();
	   $id = Auth::user()->id;

	   $validator = $this->validator($input,$id);

		 if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }


	   $user = User::find($id);
	   $user->name        = $input['full_name'];
	   $user->email        = trim($input['email']);
	   $user->countries_id = $input['countries_id'];
	   $user->save();

	   \Session::flash('notification',trans('auth.success_update'));

	   return redirect('account');

	}//<--- End Method

	public function password()
    {
		return view('users.password');
    }//<--- End Method

    public function update_password(Request $request)
    {

	   $input = $request->all();
	   $id = Auth::user()->id;

		   $validator = Validator::make($input, [
			'old_password' => 'required|min:6',
	        'password'     => 'required|min:6',
    	]);

			if ($validator->fails()) {
             return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();
         }

	   if (!\Hash::check($input['old_password'], Auth::user()->password) ) {
		    return redirect('account/password')->with( array( 'incorrect_pass' => trans('misc.password_incorrect') ) );
		}

	   $user = User::find($id);
	   $user->password  = \Hash::make($input[ "password"] );
	   $user->save();

	   \Session::flash('notification',trans('auth.success_update_password'));

	   return redirect('account/password');

	}//<--- End Method

		public function delete()
    {
    	if( Auth::user()->id == 1 ) {
    		return redirect('account');
    	}
		return view('users.delete');
    }//<--- End Method

	public function delete_account()
    {
    	if( Auth::user()->id == 1 ) {
    		return redirect('account');
    	}

		$id = Auth::user()->id;

		// Find User
    	$user = User::find($id);

		// Stop Campaigns
		$allCampaigns = Campaigns::where('user_id',$id)->update(array('finalized' => '1'));

		//<<<-- Delete Avatar -->>>/
		$fileAvatar    = 'public/avatar/'.Auth::user()->avatar;

		if ( \File::exists($fileAvatar) && Auth::user()->avatar != 'default.jpg' ) {
			 \File::delete($fileAvatar);
		}//<--- IF FILE EXISTS

		\Session::flush();
		Auth::logout();

        $user->delete();
		return redirect('/');

    }//<--- End Method

    public function upload_avatar(Request $request){

	   $settings  = AdminSettings::first();
	   $id = Auth::user()->id;

		$validator = Validator::make($request->all(), [
		'photo' => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=125,min_height=125|max:'.$settings->file_size_allowed.'',
	    	]);

		   if ($validator->fails()) {
		        return response()->json([
				        'success' => false,
				        'errors' => $validator->getMessageBag()->toArray(),
				    ]);
		    }

		// PATHS
		$temp    = 'public/temp/';
	    $path    = 'public/avatar/';
		$imgOld      = $path.Auth::user()->avatar;

		 //<--- HASFILE PHOTO
	    if( $request->hasFile('photo') )	{

			$extension  = $request->file('photo')->getClientOriginalExtension();
			$avatar       = strtolower(Auth::user()->id.time().str_random(15).'.'.$extension );

			if( $request->file('photo')->move($temp, $avatar) ) {

				set_time_limit(0);

				Helper::resizeImageFixed( $temp.$avatar, 125, 125, $temp.$avatar );

				// Copy folder
				if ( \File::exists($temp.$avatar) ) {
					/* Avatar */
					\File::copy($temp.$avatar, $path.$avatar);
					\File::delete($temp.$avatar);
				}//<--- IF FILE EXISTS

				//<<<-- Delete old image -->>>/
				if ( \File::exists($imgOld) && $imgOld != $path.'default.jpg' ) {
					\File::delete($temp.$avatar);
					\File::delete($imgOld);
				}//<--- IF FILE EXISTS #1

				// Update Database
				User::where( 'id', Auth::user()->id )->update( array( 'avatar' => $avatar ) );

				return response()->json([
				        'success' => true,
				        'avatar' => url($path.$avatar),
				    ]);

			}// Move
	    }//<--- HASFILE PHOTO
    }//<--- End Method

    public function likes()
    {
    	$data = Campaigns::leftjoin('likes', 'campaigns.id', '=', \DB::raw('likes.campaigns_id AND likes.status = "1"'))
		->where('campaigns.status', 'active' )
		->where('likes.user_id', '=', Auth::user()->id)
		->groupBy('likes.id')
		->orderBy('likes.id', 'desc' )
		->select('campaigns.*')
		->paginate( 12 );

		return view('users.likes',['data' => $data]);
    }//<--- End Method

}
