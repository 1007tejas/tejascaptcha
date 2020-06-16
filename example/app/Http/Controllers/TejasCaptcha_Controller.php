<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Session\Store as Session;
use App\Http\Controllers\Controller;

class TejasCaptcha_Controller extends Controller
{

	/**
    * Constructor
    *
    * @param Session $session
    */

    public function __construct() {
    }


	public function getHome()
	{
	  return view('tejascaptcha');
	}

    public function tejasCaptcha(Request $request)
	{
		if ($request->isMethod('get')) {
			return Redirect::back()->withInput(Input::all())->response()->json([
			 'fails' => true,
			 'errors' => [],
			 'errors' => ['error_contacts_title'=>'Bad html method for request']
			]);
		}else{
			$errors = Array();
			$contact_data = [ 'fails' => false ];
			$rules = [
			'contacts_fname' => 'required',
			'contacts_lname' => 'required',
			'contacts_email' => 'required|email:dns,strict',
			'contacts_phone' => 'required',
			'contacts_request' => 'required'
			];

			// When the TejasCaptcha middleware signifies that the
			// posted captcha_response doesn't match the captcha
			// then $request->input('errors')['captcha_response'] is set.
			//
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				if( $request->input('errors')['captcha_response'] ) {

					$validator->errors()->add('captcha_response', $request->input('errors')['captcha_response'][0]);
				}else{
					// When form errors are detected the tejas captcha is regenerated,
					// set a tejas captcha error to remind the user to key the new captcha
					$validator->errors()->add('captcha_response', 'Captcha Response (required)');
				}
				$request->flashExcept('captcha_response');
				return view('tejascaptcha')->withErrors($validator->errors());

			}elseif( $request->input('errors')['captcha_response'] ) {
				$messageBag = new MessageBag($request->input('errors'));
				$request->flashExcept('captcha_response');
				return view('tejascaptcha')->withErrors($messageBag);
			}
		}
		return view('tejascaptcha');
    }
}
