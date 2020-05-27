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
				{!! Form::model( [$data], [ "id=","'contact_form'", 'action' => ['TejasCaptcha_Controller_Laravel_Collective_Blade@postTejascaptchaCreate']] ) !!}
					<div class='row'>
						{!! Form::label('', 'Contact Us', ['class' => 'title-sm title-menu lbl-md-margin col-sm-12']) !!}
					</div>
					<div class='row'>
						{!! Form::label('contacts_fname', 'Your first name', ['for' => 'contacts_fname', 'class' => 'tejas-menu-label menu-label-required col-sm-12']) !!}
					</div>
					<div class='row'>
					  <span id='error_contacts_fname' class='invalid-form-field-entry'></span>
					</div>
					<div class='row'>
						{!! Form::text('contacts_fname', $data['contact']?$data['contact']['contacts_fname']:null,
						['placeholder' => 'First name (required)',
						'class' => 'menu-label-input col-sm-12'.($errors->has('contacts_fname')?' form-error':'')]) !!}
					</div>
				  </br>
					<div class='row'>
						{!! Form::label('contacts_lname', 'Your last name', ['for' => 'contacts_lname', 'class' => 'tejas-menu-label menu-label-required col-sm-12']) !!}
					</div>
					<div class='row'>
					  <span id='error_contacts_lname' class='invalid-form-field-entry'></span>
					</div>
					<div class='row'>
						{!! Form::text('contacts_lname', $data['contact']?$data['contact']['contacts_lname']:null,
						['placeholder' => 'Last name (required)',
						'class' => 'menu-label-input col-sm-12'.($errors->has('contacts_lname')?' form-error':'')]) !!}
					</div>
				  </br>
					<div class='row'>
						{!! Form::label('contacts_company', ' Company name', ['for' => 'contacts_company', 'class' => 'tejas-menu-label col-sm-12']) !!}
					</div>
					<div class='row'>
					  <span id='error_contacts_company' class='invalid-form-field-entry'></span>
					</div>
					<div class='row'>
						{!! Form::text('contacts_company', $data['contact']?$data['contact']['contacts_company']:null,
						['placeholder' => 'Company name',
						'class' => 'menu-label-input col-sm-12'.($errors->has('contacts_company')?' form-error':'')]) !!}
					</div>
				  </br>
				  <div class='row'>
					  {!! Form::label('contacts_email', 'Email', ['for' => 'contacts_email', 'class' => 'tejas-menu-label menu-label-required col-sm-12']) !!}
				  </div>
				  <div class='row'>
					<span id='error_contacts_email' class='invalid-form-field-entry'></span>
				  </div>
				  <div class='row'>
					  {!! Form::text('contacts_email', $data['contact']?$data['contact']['contacts_email']:null,
					  ['placeholder' => 'Email address (required)',
					  'class' => 'menu-label-input col-sm-12'.($errors->has('contacts_email')?' form-error':'')]) !!}
				  </div>
				</br>
				  <div class='row'>
					  {!! Form::label('contacts_phone', 'Phone number', ['for' => 'contacts_phone',
					  'class' => 'tejas-menu-label menu-label-required col-sm-12']) !!}
				  </div>
				  <div class='row'>
					<span id='error_contacts_phone' class='invalid-form-field-entry'></span>
				  </div>
				  <div class='row'>
					  {!! Form::text('contacts_phone', $data['contact']?$data['contact']['contacts_phone']:null,
					  ['placeholder' => 'Phone number (required)',
					  'class' => 'menu-label-input col-sm-12'.($errors->has('contacts_phone')?' form-error':'')]) !!}
				  </div>
				</br>
				  <div class='row'>
					  {!! Form::label('contacts_request', 'Requested Information', ['for' => 'contacts_request',
					  'class' => 'tejas-menu-label menu-label-required col-sm-12'.($errors->has('contacts_request')?' form-error':'')]) !!}
				  </div>
				  <div class='row'>
					<span id='error_contacts_request' class='invalid-form-field-entry'></span>
				  </div>
				  <div class='row'>
					  {!! Form::textarea('contacts_request', $data['contact']?$data['contact']['contacts_request']:null,
					  ['placeholder' => 'Requested Informaion (required)', 'rows' => '5', 'cols' => '50',
					  'class' => 'menu-label-input col-sm-12']) !!}
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
					  {!! Form::label('', 'Tejas Captcha @github', ['class' => 'tejas-menu-label mews-credit col-sm-12']) !!}
				  </div>
				  <div class='row'>
					  {!! Form::label('captcha', 'Enter the captcha or answer', ['for' => 'captcha', 'id' => 'captchaImageLabel',
					  'class' => 'tejas-menu-label menu-label-required col-sm-12'.($errors->has('captcha_response')?' form-error':'')]) !!}
				  </div>
				  <div class='row'>
					<span id='error_captcha_response' class='invalid-form-field-entry'></span>
				  </div>
				  <div class='row md-bot-pad'>
					  {!! Form::text('captcha_response', null, ['id' => 'captcha_response', 'placeholder' => 'Captcha / Math Answer (required)',
					  'class' => 'captcha-input menu-label-input col-sm-12'.($errors->has('captcha_response')?' form-error':''),
					  'data-toggle' => 'tooltip', 'title' => 'Text Input Enter Captcha or Answer Math Problem']) !!}
				  </div>
				</br>

				<div class='row'>
					{!! Form::label('submit-label', 'Submit form', ['for' => 'captcha', 'class' => 'tejas-menu-label col-sm-4', 'data-toggle' => 'tooltip', 'title' => 'Label Submit Contact Us Form']) !!}
					{!! Form::button("<i class='fas fa-cloud-upload-alt fa-2x'></i>", ['type' => 'submit', 'class' => 'btn btn-info  col-sm-2 btn-w-100', 'data-toggle' => 'tooltip', 'title' => 'Button Submit Contact Us Form']) !!}

				</div>
			  </br>
			  {!! Form::close() !!}
			</span>
		  </div>
		</div>

	</div>
</div>
<br><br>
