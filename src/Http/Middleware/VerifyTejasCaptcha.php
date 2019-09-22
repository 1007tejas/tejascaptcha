<?php

namespace Tejas\TejasCaptcha\Http\Middleware;

use Closure;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Session\Store as Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use \Illuminate\Http\Request;

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
     * @var Str
     */
    protected $str_fn;

    /**
     * @var bool
     */
    protected $verifyTejasCaptcha = true;

    /**
     * Constructor
     *
     * @param Session $session
     * @param Str $str
     * @param Hasher $hasher
     */

    public function __construct( Session $session, Hasher $hasher, Str $str ) {
        $this->session = $session;
        $this->hasher_fn = $hasher;
        $this->str_fn = $str;
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
                  $data = [ 'errors' => ['captcha_response' => ['Incorrect Captcha Response']], 'captcha_response' => '' ];
                  $request->merge($data);
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
        if (!$this->session->has('tejas_captcha_params')) {
            return false;
        }

        $key = $this->session->get('tejas_captcha_params.key');
        $sensitive = $this->session->get('tejas_captcha_params.sensitive');

        if (!$sensitive) {
            $value = $this->str_fn->lower($value);
        }

        $check = $this->hasher_fn->check($value, $key);
        //  if verify pass,remove session
        if ($check) {
            $this->session->remove('tejas_captcha_params');
            {
                if ($this->session->has('tejas_captcha_audio_files')
                    && $this->session->has('tejas_captcha_audio_files.audioFileSuffix')) {

                    $extensions = ['mp3', 'ogg', 'wav'];
                    foreach ($extensions as $key => $value) {

                        $file = $this->session->get('tejas_captcha_audio_files.osAudioStoragePath')
                                .'/'.
                                $this->session->get('tejas_captcha_audio_files.audioFilePrefix')
                                . $this->session->get('tejas_captcha_audio_files.audioFileSuffix')
                                . '.' . $value;
                        try {
                          unlink($file);
                        } catch(Exception $e) {
                          Log::debug('Caught exception: ' . $e->getMessage() . "\n");
                        }
                    }
                }
            }
        }

        return $check;
    }
}
