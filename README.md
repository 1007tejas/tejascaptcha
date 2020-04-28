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

![The preview image is not available](assets/githubReadme/images/tejasCaptchaPreview.png?raw=true "TejasCaptcha Preview")



## Compatibility

The tejas/tejascaptcha service provider has been tested with Laravel versions 5, 6 and 7. Test were performed on a Linux / Debian / Apache2 web server.


## Installation


#### Composer

The TejasCaptcha Service Provider is installed via [Composer](http://getcomposer.org). In your laravel projects `composer.json` file require the latest version of the `tejas/tejascaptcha` package and set the `minimum-stability` to `dev`.



##### composer.json


![The composer require image is not available](assets/githubReadme/images/tejasCaptchaComposerRequire.png?raw=true "TejasCaptcha Composer Require Section")

```
     "require": {
        "tejas/tejascaptcha": "^1.0",
    },
```

![The composer minimum-stability image is not available](assets/githubReadme/images/tejasCaptchaComposerMinStability.png?raw=true "TejasCaptcha Composer Minimum-Stability")

```
    "minimum-stability": "dev",
    "prefer-stable": true,
```

From your projects root directory, open a terminal and run ```composer update```.


![The composer update image is not available](assets/githubReadme/images/tejasCaptchaComposerUpdate.png?raw=true "TejasCaptcha Composer Update")


#### Register the TejasCaptcha service provider

##### config/app.php


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


![The tejascaptcha middleware image is not available](assets/githubReadme/images/tejasCaptchaVerifyMiddlewareStub.png?raw=true "TejasCaptcha Middleware")


 :small_red_triangle:Nothing should be done with the second middleware file.

The second middleware file verifies that the current captcha matches the users response. This file is also named `VerifyTejasCaptcha.php`, but it is located in the `tejas/tejascaptcha/src/Http/Middleware` folder. Nothing should be done with this second middleware file.


#### Audio directory

##### storage/app/audio

The default directory for writing audio files, starting from the root of your project, is `storage/app/audio`. This directory is specified in the  :small_red_triangle:`tejascaptcha configuration` file and may be changed according to your preference. The `audio` directory must exist and have read / write permissions set for your web servers user. e.g. www-data for Apache2.


![The tejascaptcha audio directory permissions image is not available](assets/githubReadme/images/tejasCaptchaAudioDirectoryPermissions.png?raw=true "TejasCaptcha Audio Directory Permissions")


 :small_red_triangle:see Configuration section below.



## Configuration


#### Publish the configuration file


##### tejascaptcha.php


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
        'osAudioDirectory' => '/storage/app/audio',
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

The `audio` key specifies the where the audio files are stored and the audio
files name prefix.


## Example Usage Only


Based on [MeWebStudio Captcha] (http://www.mewebstudio.com)

## Links
* [Laravel TejasCaptcha on Github](https://github.com/1007tejas/TejasCaptcha)
* [Laravel TejasCaptcha on Packagist](https://packagist.org/packages/TejasCaptcha)
* [Laravel website](http://laravel.com)
* [License](http://www.opensource.org/licenses/mit-license.php)
