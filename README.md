# Tejas Captcha for the Laravel framework #


The tejas/tejascaptcha is a service provider for [Laravel](http://www.laravel.com).  The package randomly generates either an alpha numeric captcha or a math captcha and provides both refresh and audio capabilities.


## Section links ##

* [Preview](#preview)
* [Compatibility](#compatibility)
* [Installation](#installation)
     * [Tejas Captcha](#tejas-captcha)
     * [Tejas Captcha Middleware](#tejas-captcha-middleware)
     * [Bootstrap - Laravel Installation](#bootstrap---laravel-installation)
     * [Tejas Captcha Makefile - Post Install](#tejas-captcha-makefile---post-install)
     * [Register the Tejas Captcha service provider](#register-the-tejas-captcha-service-provider)

* [Configuration](#configuration)
     * [Publish the configuration file](#publish-the-configuration-file)


## Preview ##


![The preview image is not available](assets/githubReadme/images/tejasCaptchaPreview.png?raw=true "TejasCaptcha Preview")


## Compatibility ##


The tejas/tejascaptcha service provider has been tested with Laravel versions 5, 6 and 7. Test were performed on a Linux / Debian / Apache2 web server.


## Installation ##


### Tejas Captcha ###


The TejasCaptcha Service Provider is installed via [Composer](http://getcomposer.org).

From your projects root directory, open a terminal and run the command `composer require tejas/tejascaptcha`.

![The composer require cmd image is not available](assets/githubReadme/images/tejasCaptchaComposerRequireCmd.png?raw=true "TejasCaptcha Composer Require Cmd")


### Tejas Captcha Middleware ###

__app/Http/Middleware/VerifyTejasCaptcha.php__


There are two middleware files in the tejas/tejascaptcha package.

The first is the stub file, named `VerifyTejasCaptcha.php`, and it is located in the `vendor/tejas/tejascaptcha/src/app/Http/Middleware` folder. Place a copy of this file in your projects `app/Http/Middleware` folder.

From your projects root directory, open a terminal and run the command:
`cp vendor/tejas/tejascaptcha/src/app/Http/Middleware/VerifyTejasCaptcha.php app/Http/Middleware/`.


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


![The tejascaptcha middleware image is not available](assets/githubReadme/images/tejasCaptchaVerifyMiddlewareStub.png?raw=true "TejasCaptcha Middleware")


 :small_red_triangle:Nothing should be done with the second middleware file.

The second middleware file verifies that the current captcha matches the users response. This file is also named `VerifyTejasCaptcha.php`, but it is located in the `tejas/tejascaptcha/src/Http/Middleware` folder. Nothing should be done with this second middleware file.

__app/Http/Kernel.php__

In the `app/Http/Kernel.php` file at the bottom of the `routeMiddleware` section insert the following line:
* `'tejascaptcha_verify_captcha' => \App\Http\Middleware\VerifyTejasCaptcha::class,`

![The tejascaptcha middleware Kernel file image is not available](assets/githubReadme/images/tejasCaptchaVerifyMiddlewareKernel.png?raw=true "TejasCaptcha Middleware Kernel file")

__example/routes/web.php__


##### Example form submission route using tejascaptcha middleware  #####

This route is declared in the `example/routes/web.php` file. It is part of the working example files included with the Tejas Captcha package. See example/README.md for implementation details.

`Route::post('tejascaptcha_verify_form', 'TejasCaptcha_Controller_Laravel_Blade@postTejascaptchaCreate')->middleware('tejascaptcha_verify_captcha');`


![The tejascaptcha Routing image is not available](assets/githubReadme/images/tejascaptchaVerifyRouting.png?raw=true "TejasCaptcha Routing")

__example/resources/js/tejascaptcha.js__

Form submission for Tejas Captcha forms is via Jquery Ajax. The above route `'tejascaptcha_verify_form'` is appended to the Ajax request. See below excerpt from `tejascaptcha.js`


![The tejascaptcha Ajax Form Submit image is not available](assets/githubReadme/images/tejascaptchaAjaxFormSubmit.png?raw=true "TejasCaptcha  Form Submit")


### Bootstrap - Laravel Installation ###


Open a terminal, navigate to your projects root directory and run:
* `composer require laravel/ui`
* `php artisan ui bootstrap`
	* Alternatively if you also want the auth scaffoldings: `php artisan ui bootstrap --auth`
* `npm install && npm run dev`


###  Tejas Captcha Makefile - Post Install ###

##### This step is optional. #####

To install the included Makefile run this from your projects root directory.

* cd vendor/tejas/tejascaptcha/scripts && bash postinstall.sh && cd ../../../../

The Makefile is installed inside the tejascaptcha directory in your projects root.

To run the tejascaptcha/Makefile after installing it; From your projects
root directory run the following command with one of the specified make actions:

* make -C tejascaptcha [ install update test remove show_version ]


### Register the Tejas Captcha service provider ###

##### This step is optional, `tejas/tejascaptcha` uses `Composer` `package discovery`. #####

__config/app.php__

Register the `tejas/tejascaptcha` service provider under the `providers` key in `config/app.php`.

![The service providers image is not available](assets/githubReadme/images/tejasCaptchaProvider1.png?raw=true "TejasCaptcha Service Provider")

```php
    'providers' => [
        Tejas\TejasCaptcha\TejasCaptchaServiceProvider::class,
    ]
```

Register the `tejas/tejascaptcha` service providers alias under the `aliases` key in `config/app.php`.

![The service providers alias image is not available](assets/githubReadme/images/tejasCaptchaProvider3.png?raw=true "TejasCaptcha Service Providers Alias")

```php
    'aliases' => [
        'TejasCaptcha' => Tejas\TejasCaptcha\Facades\TejasCaptcha::class,
    ]
```


## Configuration ##


### Publish the configuration file ###


__config/tejascaptcha.php__


To customize tejas/tejascaptcha's settings run Laravels `vendor:publish` artisan command from your projects root directory.


```
$ php artisan vendor:publish
```

Example output:

![The vendor publish image is not available](assets/githubReadme/images/tejasCaptchaVendorPublish.png?raw=true "Vendor Publish")


Type the number associated with tejascaptcha service provider and press enter. An editable copy of tejas/tejascaptcha's config file, `tejascaptcha.php`, should have been copied to your projects `config` directory.

Abbreviated default config file:

```
<?php
return [
    'config_section_key' => 'standard',

    'standard' => [
        'length' => 5,
        'width' => 230,
        'height' => 50,
        'quality' => 90,
        'sensitive' => false,
    ],
    'flat' => [], 'mini' => [], 'inverse' => [],

    'audio' => [
        'audioFilePrefix' => 'final'
    ]
];

```

There are 7 keys in the config file. The `config_section_key` may point to one
of the four pre-built image representation sections, `standard` is the default
`config_section_key` value. You may override this value by setting it to
`'flat', 'mini' or 'inverse'`. :small_red_triangle:You may also override the
image selection by specifying `'flat', 'mini' or 'inverse'`as a post value
of `tejascaptchaImageType`.

:small_red_triangle:refer to the `tejas/tejascaptcha/example/js/tejascaptcha.js`
file in the `$('#tejas_captcha_refresh_icon')` click function.

The `audio` key specifies the audio files name prefix.


## Example Usage Only ##



## Links ##
* [Forked from MeWebStudio Captcha](http://www.mewebstudio.com)
* [Laravel TejasCaptcha on Github](https://github.com/1007tejas/TejasCaptcha)
* [Laravel TejasCaptcha on Packagist](https://packagist.org/packages/TejasCaptcha)
* [Laravel website](http://laravel.com)
* [License](http://www.opensource.org/licenses/mit-license.php)
