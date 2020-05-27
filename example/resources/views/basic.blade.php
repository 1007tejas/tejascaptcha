<div class="flex-center">
	<div id="tejascaptcha" class="tejas tejas-menu-label col-md-8">
		{{$tejascaptcha_err = null}}
		@if (isset($tejascaptcha_error) && is_array($tejascaptcha_error) && array_key_exists('captcha_response', $tejascaptcha_error))
			{{ $tejascaptcha_err = (string)$tejascaptcha_error['captcha_response'][0] }}
		@endif

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
			<span id="error_captcha_response" class="invalid-form-field-entry captcha">{{ $tejascaptcha_err }}</span>
		  </div>
		  <div class="row md-bot-pad">
			  <input id="captcha_response" placeholder="Captcha Code (required)" class="captcha-input menu-label-input col-sm-5" data-toggle="tooltip" title="Enter the Captcha Code required" name="captcha_response" type="text">
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
