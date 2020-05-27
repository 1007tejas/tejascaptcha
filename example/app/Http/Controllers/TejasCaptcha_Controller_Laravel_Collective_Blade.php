<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Session\Store as Session;
use App\Http\Controllers\Controller;

class TejasCaptcha_Controller_Laravel_Collective_Blade extends Controller
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

		  $validator = Validator::make($request->all(), $rules);
		  if ($validator->fails()) {
			  $errs = (Array)$validator->errors('messages');
			  foreach ($errs as $key => $value ) {
				  if (strpos($key, 'messages') !== false ) {
					$errors[] = $value;
				  }
			  }
		  // When form errors are detected the tejas captcha is regenerated,
		  // set a tejas captcha error to remind the user to key the new captcha
			  $errors[0]['captcha_response'] = 'Captcha Response (required)';
			  $contact_data['fails'] = true;
		  }
		  // When the TejasCaptcha middleware signifies that the
		  // posted captcha_response doesn't match the captcha
		  // then $request->input('errors')['captcha_response'] is set.
		  //
		  if( $request->input('errors')['captcha_response'] ) {
			  $errors[0]['captcha_response'] = [$request->input('errors')['captcha_response'][0]];
			  $contact_data['fails'] = true;
		  }

		  if( $contact_data['fails'] ) {
			  $contact_data['errors'] = $errors[0];
			  $request->merge($contact_data);

	//            rm final.mp3; espeak-ng -m -f atest -s 120 -p 70 -v mb-us1 --stdout | ffmpeg -i - -ar 44100 -ac 2 -ab 192k -f mp3 final.mp3

	//            espeak -f myfile --stdout | ffmpeg -i - -ar 44100 -ac 2 -ab 192k -f mp3 final.mp3
	//            final.mp3: Audio file with ID3 version 2.4.0, contains: MPEG ADTS, layer III, v1, 192 kbps, 44.1 kHz, Stereo

		  }
		  return response()->json($request->all());
	    }
	}
}
