<?php

namespace Tejas\TejasCaptcha\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Session\Store as Session;
use Illuminate\Support\Facades\Log;

/**
 * Laravel 5 TejasCaptcha package
 *
 * @copyright
 * @version
 * @author Jeff Hallmark
 * @contact
 * @web https://github.com/1007tejas/
 * @date 2019-08-29
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Class VerifyTejasCaptcha
 * @package TejasCaptcha
 */
class VerifyTejasCaptcha
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Hasher
     */
    protected $hasher_fn;

    /**
     * @var bool
     */
    protected $verifyTejasCaptcha = true;


    /**
     * Constructor
     *
     * @param Session $session
     * @param Hasher $hasher
     * @throws Exception
     */

    public function __construct(
        Session $session,
        Hasher $hasher
    )
    {
        $this->session = $session;
        $this->hasher_fn = $hasher;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($this->verifyTejasCaptcha){
            $value = ($request->input('captcha_response')) ? $request->input('captcha_response') : false;
            if ( !$value || !$this->verifyCaptcha($value) ) {
                throw new VerifyTejasCaptchaException('Tejas Captcha mismatch.');
            }
        }
        return $next($request);
    }

    /**
     * TejasCaptcha check
     *
     * @param $value
     * @return bool
     */
    public function verifyCaptcha($value)
    {
        if (!$this->session->has('tejascaptcha')) {
            return false;
        }

        $key = $this->session->get('tejascaptcha.key');
        $sensitive = $this->session->get('tejascaptcha.sensitive');

        if (!$sensitive) {
            $value = $this->str_fn->lower($value);
        }

        $check = $this->hasher_fn->check($value, $key);
        //  if verify pass,remove session
        if ($check) {
            $this->session->remove('tejascaptcha');
        }

        return $check;
    }
}
