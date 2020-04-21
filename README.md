# Tejas Captcha for the Laravel framework

The tejas/tejascaptcha is a service provider for [Laravel](http://www.laravel.com).  The package randomly generates either an alpha numeric captcha or a math captcha and provides both refresh and audio capabilities.

### Section links
* [Preview](#preview)
* [Compatibility](#compatibility)
* [Installation](#installation)
     * [Composer](#composer)
     * [Register the TejasCaptcha service provider](#register-the-tejascaptcha-service-provider)
     * [Middleware](#middleware)
     * [Audio Directory](#audio-directory)

* [Configuration](#configuration)
     * [Publish the configuration file](#publish-the-configuration-file)


## Preview

![The preview image is not available](assets/githubReadme/images/tejasCaptchaPreview.png?raw=true "TejasCaptcha preview")



## Compatibility

The tejas/tejascaptcha service provider has been tested with Laravel versions 5, 6 and 7. Test were performed on a Linux / Debian / Apache2 web server.


## Installation


#### Composer

The TejasCaptcha Service Provider is installed via [Composer](http://getcomposer.org). In your laravel projects `composer.json` file require the latest version of the `tejas/tejascaptcha` package and set the `minimum-stability` to `dev`.



##### composer.json


![The composer require image is not available](assets/githubReadme/images/tejasCaptchaComposerRequire.png?raw=true "TejasCaptcha composer require section")

```
     "require": {
        "tejas/tejascaptcha": "^1.0.0",
    },
```

![The composer minimum-stability image is not available](assets/githubReadme/images/tejasCaptchaComposerMinStability.png?raw=true "TejasCaptcha composer minimum-stability")

```
    "minimum-stability": "dev",
    "prefer-stable": true,
```

From your projects root directory, open a terminal and run ```composer update```.


![The vendor publish image is not available](assets/githubReadme/images/tejasCaptchaComposerUpdate.png?raw=true "TejasCaptcha Composer Update")


#### Register the TejasCaptcha service provider

##### config/app.php


Register the `tejas/tejascaptcha` service provider under the `providers` key in `config/app.php`.

![The providers image is not available](assets/githubReadme/images/tejasCaptchaProvider1.png?raw=true "TejasCaptcha Service Provider")

```php
    'providers' => [
        TejasCaptchaServiceProvider::class,
    ]
```

Register the `tejas/tejascaptcha` service providers alias under the `aliases` key in `config/app.php`.

![The providers alias image is not available](assets/githubReadme/images/tejasCaptchaProvider3.png?raw=true "TejasCaptcha Service  Provider Alias")

```php
    'aliases' => [
        'TejasCaptcha' => TejasCaptcha\Facades\TejasCaptcha::class,
    ]
```


#### Middleware

#####  tejascaptcha.php


The tejas/tejascaptcha middleware verifies that the submitted captcha has been correctly entered.

There are two middleware files in the tejas/tejascaptcha package.

The first is the stub file, named `VerifyTejasCaptcha.php`, and it is located in the `tejas/tejascaptcha/src/app/Http/Middleware` folder. Place a copy of this file in your projects `app/Http/Middleware` folder.

Contents of the stub file:

```
<?php

namespace App\Http\Middleware;

use Tejas\TejasCaptcha\Http\Middleware\VerifyTejasCaptcha as Middleware;

class VerifyTejasCaptcha extends Middleware
{
    /*
      Uses the tejas/tejascaptcha service providers middleware
    */
}
```


![The VerifyTejasCaptcha middleware image is not available](assets/githubReadme/images/tejasCaptchaVerifyMiddlewareStub.png?raw=true "TejasCaptcha Service  Provider Alias")


 :small_red_triangle:Nothing should be done with the second middleware file.

The second middleware file verifies that the current captcha matches the users response. This file is also named `VerifyTejasCaptcha.php`, but it is located in the `tejas/tejascaptcha/src/Http/Middleware` folder. Nothing should be done with this second middleware file.


#### Audio directory

##### storage/audio

The default directory for writing audio files, starting from the root of your project, is `storage/audio`. This directory is specified in the  :small_red_triangle:`tejascaptcha configuration` file and may be changed according to your preference. The `audio` directory must exist and have read / write permissions set for your web servers user. e.g. www-data for Apache2.

 :small_red_triangle:see Configuration section below.



## Configuration


#### Publish the configuration file


##### tejascaptcha.php


To customize tejas/tejascaptcha's settings run Laravels `vendor:publish` artisan command from your projects root directory.


```php
$ php artisan vendor:publish
```

Example output:

![The vendor publish image is not available](assets/githubReadme/images/tejasCaptchaVendorPublish.png?raw=true "TejasCaptcha Service  Provider Alias")


Type the number associated with tejascaptcha service provider and press enter. An editable copy of tejas/tejascaptcha's config file, `tejascaptcha.php`, should have been copied to your projects `config` directory.

```php
return [
    'default'   => [
        'length'    => 5,
        'width'     => 200,
        'height'    => 50,
        'quality'   => 90,
    ],
    // more sections follow...
];
```

## Example Usage Only


```php
    TejasCaptcha was developed using the following node packages:

        "bootstrap": "^4.1.0",
        "jquery": "^3.2",
        "@fortawesome/fontawesome-free": "^5.10.1"

    To add them:
        At the root of your Laravel project open 'package.json'
        add the above packages to the 'devDependencies section'.
        Note: Versions will vary over time.
```

```php
    TejasCaptcha was developed using the following composer package:

        "laravelcollective/html": "^6.0"

    To add the package:
        At the root of your Laravel project open 'composer.json'
        add the above package to the `require` sections`.
        Note: Versions will vary over time.
```

```jquery

    // Jquery for TejasCaptcha

  $( window ).on( "load", function() {

    function FixCaptchaLabelInput(response = null) {
        if(response) {
            if(response['alt'] == 1) {
                $('#captchaImageLabel').html('Enter the Answer');
                $('#captcha_response').attr({placeholder:
                                               'Enter Answer for the Math Problem (required)',  
                                             title:
                                               'Enter the Answer to the TejasCaptcha Math Problem required' });
            } else if(response['alt'] == 0) {
                $('#captchaImageLabel').html('Enter the TejasCaptcha');
                $('#captcha_response').attr({placeholder:
                                               'Enter the TejasCaptcha Code (required)',
                                             title:
                                               'Enter the TejasCaptcha Code required' });
            }
            response['alt'] = '';
        }
        return response;
    }


    $('-icon-sync').click(function()
    {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajaxPrefilter(function(options, originalOptions, jqXHR){
            if (options.type.toLowerCase() === "post") {
          // initialize `data` to empty string if it does not exist
                options.data = options.data || "";
          // add leading ampersand if `data` is non-empty
                options.data += options.data?"&":"";
          // add _token entry
                options.data += "_token=" + encodeURIComponent(csrf_token);
            }
        });
        $.ajax({
            url: "tejascaptcha/image",
            data: {id: 'captchaImage', alt: '', class: 'tejascaptcha',  src: ''},
            type: 'post',
            success: function(data) {
                var response = jQuery.parseJSON(data);
                response = FixCaptchaLabelInput(response);
                $('#captchaImage').attr(response);
            }
        });
        $('.tejascaptcha-icon-sync').addClass('sync-spin');
    });

    $('.tejascaptcha-icon-sync').bind('webkitAnimationEnd oanimationend msAnimationEnd animationend',
        function(e) {
          $('.tejascaptcha-icon-sync').removeClass('sync-spin');
    });

    // This triggers the first ajax request after window load
    $('.tejascaptcha-icon-sync').trigger('click');

  });

```

```html
    Tejas Captcha Form Html
<!-- Add the following html inside your form -->

<div class="row">
  {!! Form::label('tejascaptcha', 'Enter the tejascaptcha or answer', ['for' => 'tejascaptcha',
                                         'id' => 'captchaImageLabel',
                                         'class' => 'popup-menu-label menu-label-required col-sm-12']) !!}
</div>
<div class="row">
  <img {{ image-initialPageLoad(array('id' => 'captchaImage',
                                                      'alt' => '', 'class' => 'tejascaptcha',)) }}>
  <a href='javascript:;' id='tejascaptcha-link'>
     <div class="fas fa-sync tejas-tejascaptcha-icon-sync"
                                    data-toggle='tooltip' title='Button Refresh Captcha'></div>
  </a>
</div>
<div class="row sm-bot-pad">
  {!! Form::label('','TejasCaptcha based on Me Web Studio/Captcha',
                                                   ['class'=>'popup-menu-label mews-credit col-sm-12'])!!}
</div>
<div class="row md-bot-pad">
  {!! Form::text('tejascaptcha-response', '', ['id' => 'captcha_response',
    'placeholder' => 'Enter TejasCaptcha or Answer Math Problem  (required)',
    'class' => 'menu-label-input col-sm-12', 'data-toggle' => 'tooltip',
    'title' => 'Text Input Enter TejasCaptcha or Answer Math Problem']) !!}
</div>
```
```css
    CSS for TejasCaptcha

    /* change to your preferred colors */
    .tejas-icon-sync {
        margin: 0 0 0 1rem;
        color: rgba(255, 255, 255, 0.5);
        // background-color: rgba(122, 135, 230, 0.2);
        background-color: rgba(255, 255, 255, 0.2);
        font-size: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 0 0.5rem;
    }

    .sync-spin {
      -webkit-animation-name: sync-spin;
      -webkit-animation-duration: 0.5s;
      -webkit-animation-timing-function: linear;
      -webkit-animation-iteration-count: 1;

      animation-name: sync-spin;
      animation-duration: 0.5s;
      animation-timing-function: linear;
      animation-iteration-count: 1;
    }

    @-webkit-keyframes sync-spin {
      0% {
        -webkit-transform: rotate(0deg);
                transform: rotate(0deg); }
      100% {
        -webkit-transform: rotate(360deg);
                transform: rotate(360deg); } }

    @keyframes sync-spin {
      0% {
        -webkit-transform: rotate(0deg);
                transform: rotate(0deg); }
      100% {
        -webkit-transform: rotate(360deg);
                transform: rotate(360deg); } }

    .tejas-icon-sync:hover {
        color: rgba(255, 255, 255, 0.75)
    }

    -link {
      text-decoration: none;
    }

    .sm-bot-pad {
        padding: 0 0 0.75rem 0;
    }

    .menu-label-input  {
        font-size: 1.1rem;
    }

    .menu-label-required:before {
        content: '* ';
        color: #ff0000;
        font-weight: 400;
        font-size: 1.12rem;
    }

    .popup-menu-label  {
        color: #fff !important;
        font-family: "Libre Baskerville", sans-serif;
        font-weight: 200;
        font-size: 1.12rem;
    }

    .mews-credit {
        font-size: .7rem !important;
        padding-left: 0;
    }

```
Based on [MeWebStudio Captcha] (http://www.mewebstudio.com)

## Links
* [Laravel 5 TejasCaptcha on Github](https://github.com/1007tejas/TejasCaptcha)
* [Laravel 5 TejasCaptcha on Packagist](https://packagist.org/packages/TejasCaptcha)
* [Laravel website](http://laravel.com)
* [License](http://www.opensource.org/licenses/mit-license.php)
