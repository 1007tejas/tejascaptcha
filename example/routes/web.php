
Route::get('/', 'TejasCaptcha_Controller@getHome');
Route::get('tejascaptcha_verify_form','TejasCaptcha_Controller@getHome');
Route::post('tejascaptcha_verify_form','TejasCaptcha_Controller@tejasCaptcha')->middleware('tejascaptcha_verify_captcha');
