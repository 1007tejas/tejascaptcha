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
        //if (strpos($this->app->version(), 'Lumen') !== false) {
            // $this->app->get('tejascaptcha[/api/{config}]', 'Tejas\TejasCaptcha\LumenTejasCaptchaController@getCaptchaApi');
            //$this->app->get('tejascaptcha[/{config}]', 'Tejas\TejasCaptcha\LumenTejasCaptchaController@getCaptcha');
      //  } else {
            if(($this->session->has('tejas_captcha_params') &&
                $this->session->has('tejas_captcha_params.inprogress') &&
                $this->session->get('tejas_captcha_params.inprogress')=== false) ||
                !$this->session->has('tejas_captcha_params.inprogress')) {

                $this->session->put('tejas_captcha_params.inprogress', true);
                $this->app['router']->post('tejascaptcha/create_audio', '\Tejas\TejasCaptcha\TejasCaptchaController@postTejasCaptchaCreateAudio')->middleware('web');
                $this->app['router']->get('tejascaptcha/audio/{id}', '\Tejas\TejasCaptcha\TejasCaptchaController@getTejasCaptchaAudio')->middleware('web');
            }
            // $this->app['router']->get('tejascaptcha/audiolence', '\Tejas\TejasCaptcha\TejasCaptchaController@getAudioSilence');
            $this->app['router']->get('tejascaptcha/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptcha')->middleware('web');
            $this->app['router']->post('tejascaptcha/image', '\Tejas\TejasCaptcha\TejasCaptchaController@postTejasCaptchaImage')->middleware('web');

            //if ((double)$this->app->version() >= 5.2) {
            // $this->app['router']->get('tejascaptcha/api/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptchaApi')->middleware('web');
            // $this->app['router']->get('tejascaptcha/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptcha')->middleware('web');
            // $this->app['router']->post('tejascaptcha/image', '\Tejas\TejasCaptcha\TejasCaptchaController@postTejasCaptchaImage')->middleware('web');
            // $this->app['router']->post('tejascaptcha/create_audio', '\Tejas\TejasCaptcha\TejasCaptchaController@postTejasCaptchaCreateAudio')->middleware('web');
            // $this->app['router']->get('tejascaptcha/audio/{id}', '\Tejas\TejasCaptcha\TejasCaptchaController@getTejasCaptchaAudio')->middleware('web');
            //} else {
            // $this->app['router']->get('tejascaptcha/api/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptchaApi');
            //     $this->app['router']->get('tejascaptcha/{config?}', '\Tejas\TejasCaptcha\TejasCaptchaController@getCaptcha')->middleware('web');
            //     $this->app['router']->post('tejascaptcha/image', '\Tejas\TejasCaptcha\TejasCaptchaController@postTejasCaptchaImage')->middleware('web');
            //     $this->app['router']->post('tejascaptcha/create_audio', '\Tejas\TejasCaptcha\TejasCaptchaController@postTejasCaptchaCreateAudio')->middleware('web');
            //     $this->app['router']->get('tejascaptcha/audio/{id}', '\Tejas\TejasCaptcha\TejasCaptchaController@getTejasCaptchaAudio')->middleware('web');
        //}

        // // Validator extensions
        // $this->app['validator']->extend('tejascaptcha', function ($attribute, $value, $parameters) {
        //     return captcha_check($value);
        // });
        //
        // // Validator extensions
        // $this->app['validator']->extend('captcha_api', function ($attribute, $value, $parameters) {
        //     return captcha_api_check($value, $parameters[0]);
        // });
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

        // foreach ($this->app['config']->get('tejascaptcha.default') as $key => $val) {
        //     echo $key . ' = ' . $val . '<br>';
        // }
        // foreach ($this->app['config']->get('tejascaptcha.audio') as $key => $val) {
        //     echo $key . ' = ' . $val . '<br>';
        // }
        // echo base_path();
        //exit;
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
