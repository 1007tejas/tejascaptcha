<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Scripts -->
		<script src="/js/jquery.min.js"></script>
		<script src="/js/app.js"></script>
		<!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
	    <!-- Styles -->
		<link href="/css/app.css" rel="stylesheet">
	    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->

		<!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
		<!-- <link href="/css/app.css" rel="stylesheet"> -->

        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>

    </head>
	<body>
		<div class="flex-center position-ref full-height">
			@if (Route::has('login'))
			<div class="top-right links">
				@auth
					<a href="{{ url('/home') }}">Home</a>
			@else
					<a href="{{ route('login') }}">Login</a>
					@if (Route::has('register'))
						<a href="{{ route('register') }}">Register</a>
					@endif
				@endauth
			</div>
			@endif
			<div class="content">
				<div class="title m-b-md">
					Laravel
				</div>
				<div class="links">
					<a href="https://laravel.com/docs">Docs</a>
					<a href="https://laracasts.com">Laracasts</a>
					<a href="https://laravel-news.com">News</a>
					<a href="https://blog.laravel.com">Blog</a>
					<a href="https://nova.laravel.com">Nova</a>
					<a href="https://forge.laravel.com">Forge</a>
					<a href="https://vapor.laravel.com">Vapor</a>
					<a href="https://github.com/laravel/laravel">GitHub</a>
				</div>
			</div>
		</div>
		<div class="flex-center">
			<div id="tejascaptcha" class="tejas tejas-menu-label col-md-8">

				<div id='contactus' class='tejas tejas-menu-label'>
				  <!-- <span><a id='contactus-closebtn' class='contactus-closebtn'>&times;</a></span> -->
				  <span id='contactus_success' class='contactus-closebtn'></span>
				  <div class='left-side-el'>
					<span class='font-normal'> Need additional information? </span>
				  </div>
				  <div class='right-side-el'>
					<span>
						<form method="POST" action="https://mchba.com:8443/tejascaptcha_verify_form" accept-charset="UTF-8" id="tejascaptcha_form">
						  @csrf
							<div class='row'>
								<label class='title-sm title-menu lbl-md-margin col-sm-12'>Contact Us</label>
							</div>
							<div class='row'>
								<label for="contacts_fname" class="tejas-menu-label menu-label-required col-sm-12">Your first name</label>
							</div>
							<div class='row'>
							@error('contacts_fname')
							  <span id='error_contacts_fname' class='invalid-form-field-entry'>{{ $message }}</span>
							@enderror
							</div>
							<div class='row'>
								<input name="contacts_fname" type="text" value="{{old('contacts_fname')}}" placeholder="First name (required)" class="menu-label-input col-sm-12" data-toggle="tooltip" title="Text Input Enter your first name">
							</div>
							</br>
							<div class='row'>
								<label for="contacts_lname" class="tejas-menu-label menu-label-required col-sm-12">Your last name</label>
							</div>
							<div class='row'>
							@error('contacts_lname')
							  <span id='error_contacts_lname' class='invalid-form-field-entry'>{{ $message }}</span>
							@enderror
							</div>
							<div class='row'>
								<input name="contacts_lname" type="text" value="{{ old('contacts_lname') }}" placeholder="Last name (required)" class="menu-label-input col-sm-12" data-toggle="tooltip" title="Text Input Enter your last name">
							</div>
						 	</br>
							<div class='row'>
								<label for="contacts_company" class="tejas-menu-label col-sm-12">Company name</label>
							</div>
							<div class='row'>
							@error('contacts_company')
							  <span id='error_contacts_company' class='invalid-form-field-entry'>{{ $message }}</span>
							@enderror
							</div>
							<div class='row'>
								<input name="contacts_company" type="text" value="{{ old('contacts_company') }}" placeholder="Company name" class="menu-label-input col-sm-12" data-toggle="tooltip" title="Text Input Enter your company name">
							</div>
							</br>
							<div class='row'>
								<label for="contacts_email" class="tejas-menu-label menu-label-required col-sm-12">Email</label>
							</div>
							<div class='row'>
							@error('contacts_email')
							<span id='error_contacts_email' class='invalid-form-field-entry'>{{ $message }}</span>
							@enderror
							</div>
							<div class='row'>
								<input name="contacts_email" type="text" value="{{ old('contacts_email') }}" placeholder="Email address (required)" class="menu-label-input col-sm-12" data-toggle="tooltip" title="Text Input Enter your email address">
							</div>
							</br>
							<div class='row'>
								<label for="contacts_phone" class="tejas-menu-label menu-label-required col-sm-12">Phone number</label>
							</div>
							<div class='row'>
							@error('contacts_phone')
							<span id='error_contacts_phone' class='invalid-form-field-entry'>{{ $message }}</span>
							@enderror
							</div>
							<div class='row'>
								<input name="contacts_phone" type="text" value="{{ old('contacts_phone') }}" placeholder="Phone number (required)" class="menu-label-input col-sm-12" data-toggle="tooltip" title="Text Input Enter your phone number">
							</div>
							</br>
							<div class='row'>
								<label for="contacts_request" class="tejas-menu-label menu-label-required col-sm-12">Requested Information</label>
							</div>
							<div class='row'>
							@error('contacts_request')
							<span id='error_contacts_request' class='invalid-form-field-entry'>{{ $message }}</span>
							@enderror
							</div>
							<div class='row'>
								<input name="contacts_request" type="text" value="{{ old('contacts_request') }}" placeholder="Requested Informaion (required)" class="menu-label-input col-sm-12" data-toggle="tooltip" title="Text Input Enter your information request">
							</div>
							</br>

							<div class='row'>
								<img {{ image_initialPageLoad(array(' id'=>'captchaImage', ' alt'=>'', ' class'=>'captcha')) }}/>
							<div class="col">
								<a href='javascript:;'>
									<div  id='tejas_captcha_refresh_icon' class='fas fa-sync tejas-captcha-icon-sync' data-toggle='tooltip' title='Button Refresh Captcha'></div>
								</a>
								<div class="row">
									<div class="col tejas-captcha-icon-no-pad">
										<a href='javascript:;' >
											<div id='tejas_captcha_audio_icon' class='fas fa-volume-up tejas-captcha-icon-volume-up' data-toggle='tooltip' title='Button Audio Play Captcha'></div>
										</a>
										<audio id='tejas_captcha_audio' preload="none">
											  <source src='tejascaptcha/audio/captcha.mp3' type="audio/mpeg">
											  <source src='tejascaptcha/audio/captcha.ogg' type="audio/ogg">
											  <source src='tejascaptcha/audio/captcha.wav' type="audio/wav">
											  Unfortunately this browser does not support HTML-5 audio.
										</audio>

									 </div>
								 </div>
							</div>
							</div>

							<div class='row sm-bot-pad'>
								<label for="contacts_request" class="tejas-menu-label small-font col-sm-12">Tejas Captcha @github</label>
							</div>
							<div class='row'>
								<label id="captchaImageLabel" for="captcha_response" class="tejas-menu-label menu-label-required col-sm-12">Enter the captcha or answer</label>
							</div>
							<div class='row'>
								@error('captcha_response')
								<span id='error_captcha_response' class='invalid-form-field-entry'>{{ $message }}</span>
								@enderror
							</div>
							<div class='row md-bot-pad'>
								<input id="captcha_response" name="captcha_response" type="text" placeholder="Captcha / Math Answer (required)" class="menu-label-input col-sm-12" data-toggle="tooltip" title="Text Input Enter Captcha or Answer Math Problem">
							</div>
							</br>

							<div class='row'>
								<label for="captcha_response" class="tejas-menu-label menu-label-required col-sm-5' 'data-toggle' => 'tooltip' 'title' => 'Label Submit Contact Us Form">Submit form</label>
								<button type="submit" class="btn btn-info  col-sm-3 btn-w-100 menu-button" data-toggle="tooltip" title="Button Submit Contact Us Form"><i class="fas fa-cloud-upload-alt fa-2x"></i></button>
							</div>
							</br>
							</form>
						</span>
					</div>
				</div>
			</div>
		</div>
		<br><br>
	</body>
</html>
