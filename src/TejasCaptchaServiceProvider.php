<?php

namespace Tejas\TejasCaptcha;

use Illuminate\Support\ServiceProvider;
use Session;
use Sessions\TejasCaptchaSession;

/**Tejas\TejasCaptcha\TejasCaptchaServiceProvider
 * Class TejasCaptchaServiceProvider
 * @package TejasCaptcha
 */

class TejasCaptchaServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        // Publish configuration files
        $this->publishes([
            __DIR__ . '/../config/tejascaptcha.php' => config_path('tejascaptcha.php')
        ], 'config');

        // HTTP routing
        if (strpos($this->app->version(), 'Lumen') !== false) {
            $this->app->get('tejascaptcha[/api/{config}]', 'Tejas\TejasCaptcha\LumenTejasCaptchaController@getCaptchaApi');
            $this->app->get('tejascaptcha[/{config}]', 'Tejas\TejasCaptcha\LumenTejasCaptchaController@getCaptcha');
        } else {
            if ((double)$this->app->version() >= 5.2) {
                $this->app['router']->get('tejascaptcha/api/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptchaApi')->middleware('web');
                $this->app['router']->get('tejascaptcha/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptcha')->middleware('web');
                $this->app['router']->post('tejascaptcha/tejas_captcha_image_ajaxRequest', '\Tejas\TejasCaptcha\TejasCaptchaController@getTejasCaptchaAjax')->middleware('web');
            } else {
                $this->app['router']->get('tejascaptcha/api/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptchaApi');
                $this->app['router']->get('tejascaptcha/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptcha');
                $this->app['router']->post('tejascaptcha/tejas_captcha_image_ajaxRequest', '\Tejas\TejasCaptcha\TejasCaptchaController@getTejasCaptchaAjax');
            }
        }

        // Validator extensions
        $this->app['validator']->extend('tejascaptcha', function ($attribute, $value, $parameters) {
            return captcha_check($value);
        });

        // Validator extensions
        $this->app['validator']->extend('captcha_api', function ($attribute, $value, $parameters) {
            return captcha_api_check($value, $parameters[0]);
        });

        Session::extend('TejasCaptchaSession', function ($app) {
            // Return implementation of SessionHandlerInterface...
            return new TejasCaptchaSession;
        });

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

      //$app->singleton('app', 'Illuminate\Container\Container');
      //$app->singleton('config', 'Illuminate\Config\Repository');
        // Merge configs
        $this->mergeConfigFrom(
            __DIR__ . '/../config/tejascaptcha.php', 'tejascaptcha'
        );

        // Bind tejascaptcha
        $this->app->singleton('tejascaptcha', function ($app) {
            return new TejasCaptcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }
}
