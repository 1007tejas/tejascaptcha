
//Tejas Captcha'.
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
        for(var i = 0; i < arr.length; i++){
            str = (str == '')? str + arr[i] : str + sep + arr[i];
        }
        return str;
    }

    function audioSourceFilename(filename = null) {
        // On initial page load or on the refresh icon click vent the audio source format is:
        //  'captcha' + '.extension'
        // e.g. <source src="https://mchba.com/tejascaptcha/audio/captcha.mp3"
        //
        // On the speaker volume icon click event the audio source format is:
        // 'captcha_' + (random number filename returned by ajax) + '.extension'
        // e.g. <source src="https://mchba.com/tejascaptcha/audio/captcha_8982442082968605313.mp3"
        //
        // *This function works with both formats
        //
        $('#tejas_captcha_audio > source').each(function() {
          var array_url = this.src.split('/');
          var array_extension = array_url[array_url.length-1].split('.');

          if(filename) {
            // click event on speaker volume icon, ajax returns filename
            array_url[array_url.length-1] = 'captcha' + filename + '.' + array_extension[1];
          }else{
            // Initial page load and click event on refresh icon
            array_url[array_url.length-1] = 'captcha.' + array_extension[1];
          }

          var str = recombineUrl(array_url, '/');
          this.src = str;
        });
    }

    $('#tejas_captcha_audio_icon').click(function() {

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
                $('#tejas_captcha_audio_icon').removeClass('spinner-spin');
                $('#tejas_captcha_audio_icon').removeClass('fa-spinner');
                $('#tejas_captcha_audio_icon').addClass('fa-volume-up');
                // alert( textStatus + ':::' + errorThrown );
            },
            always: function (jqXHR) {
                $('#tejas_captcha_audio_icon').removeClass('spinner-spin');
                $('#tejas_captcha_audio_icon').removeClass('fa-spinner');
                $('#tejas_captcha_audio_icon').addClass('fa-volume-up');
                // console.log(jqXHR.status);
                // alert( jqXHR.status );
            },
        });

        $('#tejas_captcha_audio_icon').removeClass('fa-volume-up');
        $('#tejas_captcha_audio_icon').addClass('fa-spinner');
        $('#tejas_captcha_audio_icon').addClass('spinner-spin');
    });

    $('#tejas_captcha_refresh_icon').click(function()
    {
      // $('#tejas_captcha_audio_icon').prop( "disabled", true );
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({ // tejasCaptchaImageType may be one of ['', flat, 'mini', 'inverse', 'standard']
            url: "tejascaptcha/image",",
            data: {id: 'captchaImage', alt: '', class: 'captcha',  src: '', tejasCaptchaImageType: ''},
            type: 'post',
            cache: false,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function(data, textStatus) {
                var response = jQuery.parseJSON(data);
                response = FixCaptchaLabelInput(response);
                $('#captchaImage').attr(response);
                clearCaptchaResponseField();
                // $('#tejas_captcha_audio_icon').prop( "disabled", false );
            },
            error: function( jqXHR, textStatus, errorThrown ) {
                //alert( textStatus + ':::' + errorThrown );
                // $('#tejas_captcha_audio_icon').prop( "disabled", false );
            },
            always: function (jqXHR) {
                // $('#tejas_captcha_audio_icon').prop( "disabled", false );
                // console.log(jqXHR.status);
                // alert( jqXHR.status );
            },
        });

        $('#tejas_captcha_refresh_icon').addClass('sync-spin');

        $('#tejas_captcha_refresh_icon').bind('webkitAnimationEnd oanimationend msAnimationEnd animationend',
            function(e) {
              $('#tejas_captcha_refresh_icon').removeClass('sync-spin');
              clearCaptchaResponseField();
        });

        audioSourceFilename();
        $('#tejas_captcha_audio').trigger('load').trigger('pause');


    });

    $('.tejas-captcha-icon-sync').trigger('click');
    // second captcha request, first after page load using ajax
    // if this does not work then the error labels are rendered

//=============================================================================
