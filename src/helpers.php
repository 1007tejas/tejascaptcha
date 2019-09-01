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

if (!function_exists('captcha_src')) {
    /**
     * @param string $config
     * @return string
     */
    function captcha_src($config = 'default')
    {
        return app('tejascaptcha')->src($config);
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

if (!function_exists('captcha_img')) {

    /**
     * @param string $config
     * @return mixed
     */
    function captcha_img($config = 'default')
    {
        return app('tejascaptcha')->img($config);
    }
}

if (!function_exists('captcha_check')) {
    /**
     * @param $value
     * @return bool
     */
    function captcha_check($value)
    {
        return app('tejascaptcha')->check($value);
    }
}

if (!function_exists('captcha_api_check')) {
    /**
     * @param $value
     * @return bool
     */
    function captcha_api_check($value, $key)
    {
        return app('tejascaptcha')->check_api($value, $key);
    }
}
