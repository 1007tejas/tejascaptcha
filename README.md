# Tejas Captcha for Laravel 5

TejasCaptcha is a service provider for [Laravel 5](http://www.laravel.com/).  The package randomly generates either an alpha numeric captcha or a math captcha. The TejasCaptcha interface has both refresh and audio capabilities.

## Preview

![The preview image is not available](example/githubReadme/images/tejasCaptchaPreview.png?raw=true "TejasCaptcha preview")

## Installation

The TejasCaptcha Service Provider can be installed via [Composer](http://getcomposer.org). In your laravel projects composer.json file require the latest version of the `tejas/tejascaptcha` package and set the `minimum-stability` to `dev`.

```composer.json```

![The preview image is not available](example/githubReadme/images/tejasCaptchaComposerRequire.png?raw=true "TejasCaptcha composer require section")

```
{
    "require": {
        "..."
        "tejas/tejascaptcha": "^1.0.0",
    },
}
```

![The preview image is not available](example/githubReadme/images/tejasCaptchaComposerRequire.png?raw=true "TejasCaptcha composer minimum-stability")

```
}
    "minimum-stability": "dev"
}
```

From your projects root directory open a terminal and run `composer update`.

or

Require the `tejas/tejascaptcha` package with composer:
From your projects root directory open a terminal and run `composer require tejascaptcha`

In Windows, you'll need to confirm that the following files are included in the ```php.ini file``` file; add them if needed.  `php_gd2.dll`, `php_fileinfo.dll` and `php_mbstring.dll`. These files are required for `tejascaptcha` and its dependencies.


## Usage

To use the Tejas Captcha Service Provider, you must register the provider when bootstrapping your Laravel 5 application.

Find the `providers` key in `config/app.php` and register the Tejas Captcha Service Provider.

for Laravel 5.1+

```php
    'providers' => [
        // ...
        TejasCaptchaServiceProvider::class,
    ]
```

Find the `aliases` key in `config/app.php`.

for Laravel 5.1+

```php
    'aliases' => [
        // ...
        'TejasCaptcha' => TejasCaptcha\Facades\TejasCaptcha::class,
    ]
```

## Configuration

To use your own settings, publish the 'your_project/config' directory.
```php
$ php artisan vendor:publish
```
Then in your Laravel projects config.php modify the settings.

edit `config.php`

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
    // switching to middleware
    // [your site path]/routes/web.php
    Route::any(-test', function() {
        if (request()->getMethod() == 'POST') {
            $rules = [' => 'required'];
            $validator = validator()->make(request()->all(), $rules);
            if ($validator->fails()) {
                echo '<p style="color: #ff0000;">Incorrect!</p>';
            } else {
                echo '<p style="color: #00ff30;">Matched :)</p>';
            }
        }

    });
```

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

        "laravelcollective/html": "5.8.*"

    To add the package:
        At the root of your Laravel project open 'composer.json'
        add the above package to the `require` and `require-dev sections`.
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
