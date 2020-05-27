<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', 'TejasCaptcha_Controller_Basic_Blade@getHome');
// Route::get('tejascaptcha_verify_form','TejasCaptcha_Controller_Basic_Blade@getHome');
// Route::post('tejascaptcha_verify_form', 'TejasCaptcha_Controller_Basic_Blade@postTejascaptchaCreate')->middleware('tejascaptcha_verify_captcha');

//******************************************************************************


Route::get('/', 'TejasCaptcha_Controller_Laravel_Collective_Blade@getHome');
Route::get('tejascaptcha_verify_form','TejasCaptcha_Controller_Laravel_Collective_Blade@getHome');
Route::post('tejascaptcha_verify_form', 'TejasCaptcha_Controller_Laravel_Collective_Blade@postTejascaptchaCreate')->middleware('tejascaptcha_verify_captcha');
