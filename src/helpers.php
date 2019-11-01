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

if (!function_exists('image_initialPageLoad')) {
    /**
     * @param null $attrs
     * @return mixed
     */
    function image_initialPageLoad($attrs = null)
    {
        return app('tejascaptcha')->image_onPageLoad($attrs);
    }
}

if (!function_exists('image')) {
    /**
     * @param null $attrs
     * @return mixed
     */
    function image($attrs = null)
    {
        return app('tejascaptcha')->image_onAjaxRequest($attrs);
    }
}
