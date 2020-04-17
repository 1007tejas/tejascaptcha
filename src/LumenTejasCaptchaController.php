<?php

namespace Tejas\TejasCaptcha;

use Laravel\Lumen\Routing\Controller;

/**
 * Class TejasCaptchaController
 * @package TejasCaptcha
 */
class LumenTejasCaptchaController extends Controller
{
    /**
     * get CAPTCHA
     *
     * @param \TejasCaptcha $tejascaptcha
     * @param string $config
     * @return \Intervention\Image\ImageManager->response
     */
    public function getCaptcha(TejasCaptcha $tejascaptcha, $config = 'default')
    {
        return $tejascaptcha->create();
    }
}
