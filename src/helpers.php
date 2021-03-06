<?php


/**
* Part of the Laravel Tejas/TejasCaptTcha package
 *
 * @copyright
 * @version
 * @author Jeff Hallmark
 * @contact
 * @web https://github.com/1007tejas/
 * @date 2019-08-29
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
 
if (!function_exists('TejasCaptcha')) {

    /**
     * @param string $config
     * @return mixed
     */
    function TejasCaptcha($config = null)
    {
        return app('tejascaptcha')->create();
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
