<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Models\User;
use App\Models\Languages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LangController extends Controller {


	 protected function validator(array $data, $id = null) {

    	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		// Create Rules
		if( $id == null ) {
			return Validator::make($data, [
        	'name'          =>  'required',
			'abbreviation'   =>  'required|min:2|max:5|ascii_only|unique:languages',
        ]);

		// Update Rules
		} else {
			return Validator::make($data, [
	        	'name'          =>  'required',
				'abbreviation'   =>  'required|min:2|max:5|ascii_only|unique:languages,abbreviation,'.$id,
	        ]);
		}

    }

	 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
	 public function index() {

	 	$data = Languages::all();

    	return view('admin.languages')->withData($data);
	 }

	 /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
	 public function create() {
    	return view('admin.add-languages');
	 }

	 /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
	 public function store( Request $request ) {

		 $input = $request->all();

	     $validator = $this->validator($input);

			 if ($validator->fails()) {
          return redirect()->back()
 						 ->withErrors($validator)
 						 ->withInput();
 					 }

		Languages::create($input);

		\Session::flash('success_message',trans('admin.success_add'));

		return redirect('panel/admin/languages');

	}//<--- End Method

	/**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
	public function show($id) {
		//
		return redirect('panel/admin/languages');
	}//<--- End Method

	/**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
	public function edit($id) {

		$data = Languages::findOrFail($id);

    	return view('admin.edit-languages')->withData($data);

	}//<--- End Method

	/**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function update($id, Request $request) {

    $lang = Languages::findOrFail($id);

	$input = $request->all();

	     $validator = $this->validator($input,$id);

			 if ($validator->fails()) {
          return redirect()->back()
 						 ->withErrors($validator)
 						 ->withInput();
 					 }

    $lang->fill($input)->save();

    \Session::flash('success_message', trans('admin.success_update'));

    return redirect('panel/admin/languages');

	}//<--- End Method


	/**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function destroy($id){

	  $lang = Languages::findOrFail($id);

		if($lang->count() > 1) {
			$lang->delete();
		}

      return redirect('panel/admin/languages');

	}//<--- End Method


}
