<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Session\Store as Session;
use App\Http\Controllers\Controller;

class TejasCaptcha_Controller_Basic_Blade extends Controller
{
	/**
    * Constructor
    *
    * @param MessageBag $messageBag
    */

	/**
    * Constructor
    *
    * @param Session $session
    */

    public function __construct() {
    }

	public function getHome()
    {
      return view('welcome');
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
		// See TejasCaptcha_Controller_Laravel_Blade_Form.php for implementation with
		// Illuminate\Support\Facades\Validator class;
		// ***

	    // When the TejasCaptcha middleware signifies that the
	    // posted captcha_response doesn't match the captcha
	    // then $request->input('errors')['captcha_response'] is set.
	    //
	    if( $request->input('errors')['captcha_response'] ) {
			$messageBag = new MessageBag($request->input('errors'));
			return view('welcome')->withErrors($messageBag);
	    }
		return view('welcome');
	}
}
