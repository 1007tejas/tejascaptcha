<?php

namespace Tejas\TejasCaptcha;

use Illuminate\Routing\Controller;

/**
 * Class TejasCaptchaController
 * @package TejasCaptcha
 */
class TejasCaptchaController extends Controller
{
    /**
     * get CAPTCHA
     *
     * @param \TejasCaptcha $tejascaptcha
     * @param array $attrs
     * @return mixed
     */
    public function getTejasCaptchaAjax(TejasCaptcha $tejascaptcha)
    {
        if (ob_get_contents()) {
            ob_clean();
        }
        if(isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] == 'captchaImage') {
            $attrs = Array();
            $attrs['id'] = $_POST['id'];
            $attrs['alt'] = '';
            $attrs['class'] = $_POST['class'];
            $attrs['src'] = '';
        }
        $_POST = Array();
        return $tejascaptcha->tejas_captcha_image_onAjaxRequest($attrs);
    }

    /**
     * get CAPTCHA
     *
     * @param \TejasCaptcha $tejascaptcha
     * @param string $config
     * @return \Intervention\Image\ImageManager->response
     */
    public function getCaptcha(TejasCaptcha $tejascaptcha, $config = 'default')
    {
        if (ob_get_contents()) {
            ob_clean();
        }

        return $tejascaptcha->create($config);
    }

    /**
     * get CAPTCHA api
     *
     * @param \TejasCaptcha $tejascaptcha
     * @param string $config
     * @return \Intervention\Image\ImageManager->response
     */
    public function getCaptchaApi(TejasCaptcha $tejascaptcha, $config = 'default')
    {
        return $tejascaptcha->create($config, true);
    }
}
