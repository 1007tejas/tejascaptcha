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

			<form method="POST" action="https://mchba.com:8443/tejascaptcha_verify_form" accept-charset="UTF-8" id="tejascaptcha_form">
			  @csrf
			  <div class="row">
				  <img id="captchaImage" alt="" class="captcha" src="https://mchba.com:8443/tejascaptcha">

				  <div class="col">
					  <a href="javascript:;">
						  <div id="tejas_captcha_refresh_icon" class="fas fa-sync tejas-captcha-icon-sync" data-toggle="tooltip" title="Button Refresh Captcha"></div>
					  </a>

					  <div class="row">
						  <div class="col tejas-captcha-icon-no-pad">
							  <a href="javascript:;">
								  <div id="tejas_captcha_audio_icon" class="fas tejas-captcha-icon-volume-up fa-volume-up" data-toggle="tooltip" title="Button Audio Play Captcha"></div>
							  </a>
							  <audio id="tejas_captcha_audio" preload="none">
								  <source src='tejascaptcha/audio/captcha.mp3' type="audio/mpeg">
								  <source src='tejascaptcha/audio/captcha.ogg' type="audio/ogg">
								  <source src='tejascaptcha/audio/captcha.wav' type="audio/wav">
								  Unfortunately this browser does not support HTML-5 audio.
							  </audio>

						  </div>
					  </div>
				  </div>
			  </div>

			  <div class="row sm-bot-pad">
				  <label for="" class="tejas-menu-label small-font col-sm-12">Tejas Captcha @github</label>
			  </div>
			  <div class="row">
				  <label for="captcha" id="captchaImageLabel" class="tejas-menu-label menu-label-required col-sm-12">Enter the Captcha</label>
			  </div>
			  <div class="row">
				@error('captcha_response')
				<span id="error_captcha_response" class="invalid-form-field-entry captcha">{{ $message }}</span>
				@enderror
			  </div>
			  <div class="row md-bot-pad">
				  <input id="captcha_response" name="captcha_response" type="text" placeholder="Captcha Code (required)" class="captcha-input menu-label-input col-sm-5" data-toggle="tooltip" title="Enter the Captcha Code required">
			  </div>
			  <br>
			  <div class="row">
				<div class="col-md-6"
					<label for="submit-label" class="tejas-menu-label col-sm-5" data-toggle="tooltip" title="Label Submit Contact Us Form">Submit form</label>
					<button type="submit" class="btn btn-info  col-sm-3 btn-w-100 menu-button" data-toggle="tooltip" title="Button Submit Contact Us Form"><i class="fas fa-cloud-upload-alt fa-2x"></i></button>
				</div>
			  </div>
			</form>
		</div>
		</div>
		<br><br>
	</body>
</html>
