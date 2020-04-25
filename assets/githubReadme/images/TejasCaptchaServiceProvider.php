<?php

namespace Tejas\TejasCaptcha;

/**
 * Part of Laravel 5 TejasCaptcha package
 *
 * @copyright
 * @version
 * @author Jeff Hallmark
 * @contact
 * @web https://github.com/1007tejas/
 * @date 2019-08-29
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

use Illuminate\Support\ServiceProvider;
use Illuminate\Session\Store as Session;

class TejasCaptchaServiceProvider extends ServiceProvider
{
    /**
    * @var Session
    */
    protected $session;

    /**
     * Boot the service provider.
     * @param Session $session
     * @return null
     */
    public function boot(Session $session)
    {
        $this->session = $session;

        // Publish configuration files
        $this->publishes([
            __DIR__ . '/../config/tejascaptcha.php' => config_path('tejascaptcha.php')
        ], 'config');

        // HTTP routing

        if(( $this->session->has('tejas_captcha_params')
             && $this->session->has('tejas_captcha_params.inprogress')
             && $this->session->get('tejas_captcha_params.inprogress') === false)
             || ( !$this->session->has('tejas_captcha_params')
             || !$this->session->has('tejas_captcha_params.inprogress') )) {

            $this->session->put('tejas_captcha_params.inprogress', true);
            $this->app['router']->post('tejascaptcha/create_audio', '\Tejas\TejasCaptcha\TejasCaptchaController@postTejasCaptchaCreateAudio')->middleware('web');
            $this->app['router']->get('tejascaptcha/audio/{id}', '\Tejas\TejasCaptcha\TejasCaptchaController@getTejasCaptchaAudio')->middleware('web');
        }
        $this->app['router']->get('tejascaptcha/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptcha')->middleware('web');
        $this->app['router']->post('tejascaptcha/image', '\Tejas\TejasCaptcha\TejasCaptchaController@postTejasCaptchaImage')->middleware('web');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
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
