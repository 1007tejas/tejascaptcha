$( window ).on( "load", function() {

//Tejas Captcha'.

	$(window).scrollTop(200);

	var stop_captcha_refresh = false;

	function clearCaptchaResponseField() {
		$('#captcha_response').val(null);
	}

    function FixCaptchaLabelInput(response = null) {
        if(response) {
            if(response['alt'] === 1) {
                $('#captchaImageLabel').html('Enter the Answer');
                $('#captcha_response').attr({placeholder: 'Answer for the Math Problem (required)', title: 'Enter the Answer to the Captcha Math Problem required' });
            } else if(response['alt'] === 0) {
                $('#captchaImageLabel').html('Enter the Captcha');
                $('#captcha_response').attr({placeholder: 'Captcha Code (required)', title: 'Enter the Captcha Code required' });
            } else {
                $('#captchaImageLabel').html('The Server returned an incorrect Captcha status');
                $('#captchaImage').attr({src: '', alt: 'Captcha image unavailable' });
                $('#captcha_response').attr({placeholder: 'The Server did not return a correct response.', title: 'The Server did not return a correct response.' });
                $('#tejas_captcha_audio').attr('src', '');
            }
            response['alt'] = '';
        }
        return response;
    }

    function recombineUrl(arr, sep) {
        var str = '';
        var i = 0
        for(; i < arr.length; i++){
            str = (str == '')? str + arr[i] : str + sep + arr[i];
        }
        return str;
    }

    function audioSourceFilename(filename = null) {
        // On initial page load the audio source format is:
        //  'captcha' + '.extension'
        // e.g. <source src="https://mchba.com/tejascaptcha/audio/captcha.mp3"
        //
        // After clicking the refresh or speaker volume icon the audio source format is:
        // 'captcha_' + (random number filename) + '.extension'
        // e.g. <source src="https://mchba.com/tejascaptcha/audio/captcha_8982442082968605313.mp3"
        //
        // *This function works with both formats
        //
        $('#tejas_captcha_audio > source').each(function() {
          var array_url = this.src.split('/');
          var array_extension = array_url[array_url.length-1].split('.');

          if(filename) {
            // clicked on speaker volume icon and ajax returned filename
            array_url[array_url.length-1] = 'captcha' + filename + '.' + array_extension[1];
          }else{
            // Initial page load
            //array_url[array_url.length-1] = 'captcha_' + 1 + Math.floor(Math.random() * 10000) + '.' + array_extension[1];
            array_url[array_url.length-1] = 'captcha.' + array_extension[1];
          }

          var str = recombineUrl(array_url, '/');
          this.src = str;
        });
    }




	$('#tejas_captcha_refresh_icon').click(function()
	{
		if (stop_captcha_refresh) {
			return;
		}

		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
		$.ajax({ // tejasCaptchaImageType may be one of ['', flat, 'mini', 'inverse', 'standard']
			url: "tejascaptcha/image",
			data: {id: 'captchaImage', alt: '', class: 'captcha',  src: '', tejasCaptchaImageType: ''},
			type: 'post',
			cache: false,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
			success: function(data, textStatus) {
				if (!stop_captcha_refresh) {
					var response = jQuery.parseJSON(data);
					response = FixCaptchaLabelInput(response);
					$('#captchaImage').attr(response);
					clearCaptchaResponseField();
					// $('#tejas_captcha_audio_icon').prop( "disabled", false );
				}
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				// alert( textStatus + ':::' + errorThrown );
				// $('#tejas_captcha_audio_icon').prop( "disabled", false );
			},
			always: function (jqXHR) {
				// $('#tejas_captcha_audio_icon').prop( "disabled", false );
				// console.log(jqXHR.status);
				// alert( jqXHR.status );
			},
		});

		$('#tejas_captcha_refresh_icon').bind('webkitAnimationEnd oanimationend msAnimationEnd animationend',
		function(e) {
			$('#tejas_captcha_refresh_icon').removeClass('sync-spin');
			clearCaptchaResponseField();
		});

		$('#tejas_captcha_audio').trigger('pause');

		audioSourceFilename();

		$('#tejas_captcha_refresh_icon').addClass('sync-spin');

	});
	$('.tejas-captcha-icon-sync').trigger('click');




    $('#tejas_captcha_audio_icon').click(function()
    {

		$.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "tejascaptcha/create_audio",
			data: {id: 'captchaAudio'},
			dataType: 'json',
			type: 'post',
			cache: false,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
			success: function(data, textStatus) {

				stop_captcha_refresh = false;
				$('#tejas_captcha_audio_icon').removeClass('spinner-spin');
				$('#tejas_captcha_audio_icon').removeClass('fa-spinner');
				$('#tejas_captcha_audio_icon').addClass('fa-volume-up');

				audioSourceFilename(data.audiofile);
				try {
				$('#tejas_captcha_audio').trigger('load').trigger('play');
				} catch (e) {
				$('#tejas_captcha_audio').trigger('play');
				}

				clearCaptchaResponseField();
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				stop_captcha_refresh = false;
				$('#tejas_captcha_audio_icon').removeClass('spinner-spin');
				$('#tejas_captcha_audio_icon').removeClass('fa-spinner');
				$('#tejas_captcha_audio_icon').addClass('fa-volume-up');
				// alert( textStatus + ':::' + errorThrown );
			},
			always: function (jqXHR) {
				stop_captcha_refresh = false;
				$('#tejas_captcha_audio_icon').removeClass('spinner-spin');
				$('#tejas_captcha_audio_icon').removeClass('fa-spinner');
				$('#tejas_captcha_audio_icon').addClass('fa-volume-up');
				// console.log(jqXHR.status);
				// alert( jqXHR.status );
			},
		});

		stop_captcha_refresh = true;

		$('#tejas_captcha_audio_icon').removeClass('fa-volume-up');
		$('#tejas_captcha_audio_icon').addClass('fa-spinner');
		$('#tejas_captcha_audio_icon').addClass('spinner-spin');

	});



    // second captcha request, first after page load using ajax
    // if this does not work then the default labels are rendered

// To send form via ajax uncomment the following $(document).on('submit', 'form#contact_form' Code and
// run ' npm run dev '

	// $(document).on('submit', 'form#contact_form', function (event)
	// {
	// 	event.preventDefault();
	//
	// 	var form = $(this);
	// 	var data = new FormData($(this)[0]);
	// 	var url_tokens = event.currentTarget.action.split('/');
	// 	var ajax_url = url_tokens[0] + '//' + url_tokens[2] + "/" + "tejascaptcha_verify_form";
	// 	//var url = window.location.protocol + "//" + window.location.host + "/" + "contact";
	// 	$.ajaxSetup({
	// 		headers: {
	// 			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 		}
	// 	});
	// 	$.ajax({
	// 		type: form.attr('method'),
	// 		url: ajax_url,
	// 		data: data,
	// 		cache: false,
	// 		contentType: false, //'application/x-www-form-urlencoded; charset=UTF-8',
	// 		processData: false,
	// 		success: function (data) {
	// 			clearCaptchaResponseField();
	// 			// regenerate captcha; if captcha verification has suceeded
	// 			// then the captcha has been deleted from the session.
	// 			$('#tejas_captcha_refresh_icon').click();
	//
	// 			$('#contactus_success').html('');
	// 			$('#contactus_success').hide();
	// 			$('#contactus_success').removeClass('correct-form-field-entry');
	//
	// 			$('.invalid-form-field-entry').html('');
	// 			$('.invalid-form-field-entry').hide();
	// 			$('.invalid-form-field-entry').removeClass('invalid-form-field-entry');
	// 			if (data.fails) {
	// 				for (control in data.errors) {
	// 					$('#error_' + control).addClass('invalid-form-field-entry');
	// 					$('#error_' + control).html(data.errors[control]);
	// 					$('#error_' + control).show();
	// 				}
	// 				setFooterHeight();
	// 			} else {
	// 				$('#contactus_success').addClass('correct-form-field-entry');
	// 				$('#contactus_success').html('Success');
	// 				$('#contactus_success').show();
	//
	// 				$(window).scrollTop(200);
	// 			}
	// 		},
	// 		error: function (xhr, textStatus, errorThrown) {
	// 			console.log("Error: Submit: " + textStatus +' '+ errorThrown);
	// 		},
	// 		always: function(data, textStatus, xhr) {
	// 			var responseCode = null;
	//
	// 			if (textStatus == "error") {
	// 				// data variable is actually xhr
	// 				responseCode = data.status;
	// 				if (data.responseText) {
	// 					try {
	// 						data = JSON.parse(data.responseText);
	// 					} catch (e) {}
	// 				}
	// 			}else{ responseCode = xhr.status; }
	//
	// 			console.log("Response code " + responseCode);
	// 			console.log("JSON Data " + data);
	// 		}
	// 	});
	// 	return false;
	//   });
//=============================================================================
});
