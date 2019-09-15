<?php

if (!function_exists('TejasCaptcha')) {

    /**
     * @param string $config
     * @return mixed
     */
    function TejasCaptcha($config = 'default')
    {
        return app('tejascaptcha')->create($config);
    }
}

if (!function_exists('tejas_captcha_image_initialPageLoad')) {
    /**
     * @param null $attrs
     * @return mixed
     */
    function tejas_captcha_image_initialPageLoad($attrs = null)
    {
        return app('tejascaptcha')->tejas_captcha_image_onPageLoad($attrs);
    }
}

if (!function_exists('tejas_captcha_image_ajaxRequest')) {
    /**
     * @param null $attrs
     * @return mixed
     */
    function tejas_captcha_image_ajaxRequest($attrs = null)
    {
        return app('tejascaptcha')->tejas_captcha_image_onAjaxRequest($attrs);
    }
}

if (!function_exists('tejas_captcha_audio_ajaxRequest')) {

    /**
     * @param string $config
     * @return mixed
     */
    function tejas_captcha_audio_ajaxRequest($config = 'default')
    {
        return app('tejas_captcha_audio_ajaxRequest')->img($config);
    }
}
