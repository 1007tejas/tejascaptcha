<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\Store as Session;
use App\Http\Controllers\Controller;

class TejasCaptcha_Controller_Basic_Blade extends Controller
{

	/**
    * Constructor
    *
    * @param Session $session
    */

    public function __construct() {
    }

    public function postTejascaptchaCreate(Request $request)
	{

		if ($request->isMethod('get')) {
		    return Redirect::back()->withInput(Input::all())->response()->json([
		       'fails' => true,
		       'errors' => [],
		       'errors' => ['error_contacts_title'=>'Bad html method for request']
		     ]);
		}
		// ***
		// Inject your Validator code here, instantiate Validoter wirh form rules
		// ***

	    // When the TejasCaptcha middleware signifies that the
	    // posted captcha_response doesn't match the captcha
	    // then $request->input('errors')['captcha_response'] is set.
	    //
	    if( $request->input('errors')['captcha_response'] ) {
			return view('welcome', ['tejascaptcha_error' => $request->input('errors')['captcha_response'][0]]);
	    }else{
			return view('welcome', ['tejascaptcha_success' => 'Success']);
		}
		return view('welcome');
	}
}
