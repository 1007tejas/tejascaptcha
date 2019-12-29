<?php

namespace Tejas\TejasCaptcha;

use Illuminate\Routing\Controller;
use Symfony\Component\Process\Process;
// Log levels: emergency, alert, critical, error, warning, notice, info and debug.
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Session\Store as Session;
use Illuminate\Http\Request;
use Illuminate\Config\Repository;
use Tejas\TejasCaptcha\TejasCaptchaSessionCleanup;

/**
 * Class TejasCaptchaController
 * @package TejasCaptcha
 */
class TejasCaptchaController extends Controller
{
  /**
  * @var TejasCaptchaSessionCleanup
  */
  protected $cleanup;

    /**
    * @var Session
    */
    protected $session;

    /**
    * @var Repository
    */
    protected $config_repository;

    /**
    * @var bool
    */
    protected $storeAudioInSession;

    /**
    * @var string
    */
    protected $osAudioStoragePath;

    /**
    * @var string
    */
    protected $audioFilePrefix;

    /**
    * @var string
    */
    protected $audioFileSuffix;

    /**
    * @var array
    */
    protected $audioArray = Array();

    /**
    * @var string
    */
    protected $tts;


    /**
    * @var string
    */
    protected $oldTts;



     /**
      * Constructor
      *
      * @param TejasCaptchaSessionCleanup $cleanup
      * @param \TejasCaptcha $tejascaptcha
      * @param Session $session
      * @param Repository $config_repository
      * @param array $attrs
      * @return mixed
      */
     public function __construct( TejasCaptchaSessionCleanup $cleanup, Session $session, Repository $config_repository )
     {
         $this->session = $session;
         $this->config_repository = $config_repository;
         $this->cleanup = $cleanup;
     }

    public function postTejasCaptchaImage(TejasCaptcha $tejascaptcha)
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
        $result = $tejascaptcha->image_onAjaxRequest($attrs);
        $this->session->put('tejas_captcha_params.inprogress', false);
        return $result;
    }

    public function postTejasCaptchaCreateAudio(TejasCaptcha $tejascaptcha)
    {

        if (ob_get_contents()) {
            ob_clean();
        }

        $extensions = ['mp3', 'ogg', 'wav'];

        if(isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] == 'captchaAudio') {
            $_POST = Array();
            if($this->config_repository->has('tejascaptcha.audio')) {
                foreach ($this->config_repository->get('tejascaptcha.audio') as $key => $val) {
                    $this->{$key} = $val;
                    $this->session->put("tejas_captcha_audio_files.".$key, $val);
                }

                $this->audioFileSuffix = '_'.abs(random_int (PHP_INT_MIN , PHP_INT_MAX ));
                $this->session->put("tejas_captcha_audio_files.audioFileSuffix", $this->audioFileSuffix);

                if($this->session->has("tejas_captcha_audio_files.audioFileSuffixes")) {
                  $this->audioArray = $this->session->get("tejas_captcha_audio_files.audioFileSuffixes");
                }
                $this->audioArray[] = '*' . $this->audioFileSuffix . '*';
                $this->session->put("tejas_captcha_audio_files.audioFileSuffixes", $this->audioArray);

                if( !$this->session->has("tejas_captcha_audio_files.oldAudioFileSuffix")) {
                  $this->session->put("tejas_captcha_audio_files.oldAudioFileSuffix", $this->audioFileSuffix);
                }else{
                  $this->oldAudioFileSuffix = $this->session->get("tejas_captcha_audio_files.oldAudioFileSuffix");
                  foreach ($extensions as $ext) {
                    $process = new Process('rm ' . $this->osAudioStoragePath . '/' . $this->audioFilePrefix . $this->oldAudioFileSuffix . '.' . $ext);
                    $process->run();
                  }

                  $this->session->put("tejas_captcha_audio_files.oldAudioFileSuffix", $this->audioFileSuffix);
                }
                $this->tts = $this->session->get('tejas_captcha_audio_files.tts');
                $text = "-d $this->tts -f " . $this->audioFileSuffix;
                $process = new Process("python3 ../vendor/tejas/tejascaptcha/src/scripts/script.py {$text}");
                $process->run();
                // executes after the command finishes
                if (! $process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }elseif ($process->isSuccessful()) {
                    $audiofile = ['audiofile' => $this->session->get('tejas_captcha_audio_files.audioFileSuffix')];
                    $this->session->put('tejas_captcha_params.inprogress', false);
                    return json_encode($audiofile);
                }
             }
          }
      }

    public function getTejasCaptchaAudio(TejasCaptcha $tejascaptcha, $id)
    {

        if($this->session->has('tejas_captcha_audio_files')) {
          $this->osAudioStoragePath = $this->session->get('tejas_captcha_audio_files.osAudioStoragePath');
          $this->audioFilePrefix = $this->session->get('tejas_captcha_audio_files.audioFilePrefix');
          $this->audioFileSuffix = $this->session->get('tejas_captcha_audio_files.audioFileSuffix');
          $this->audioArray = $this->session->get("tejas_captcha_audio_files.audioFileSuffixes");
          $this->tts = $this->session->get('tejas_captcha_audio_files.tts');

          // 'tejas_captcha_audio_files' [
          //   "tts" => "15+6="
          //   "storeAudioInSession" => false
          //   "osAudioStoragePath" => "/var/www/dev.173.255.195.42/resources/audio"
          //   "audioFilePrefix" => "final"
          //   "audioFileSuffix" => "_6205695343759841151"
          // ]

          if($this->storeAudioInSession) {
              //$this->session->remove('tejas_captcha_audio_files');

          }elseif ($this->osAudioStoragePath !== null && (strpos($this->osAudioStoragePath, base_path())) == 0) {


              $extensions = ['mp3', 'ogg', 'wav'];
              $pattern = '/\./';
              $id_tokens = preg_split($pattern, $id, 3);

              if((count($id_tokens)) == 2 && (in_array($id_tokens[1], $extensions))) {
                $pattern = '/_/';
                $name_tokens = preg_split($pattern, $id, 3);
                $names = preg_split($pattern, $id_tokens[0], 3);

                if((count($name_tokens)) == 2 && $name_tokens[0] == 'captcha') {

                    if(file_exists($this->osAudioStoragePath . '/' . $this->audioFilePrefix . '_' . $name_tokens[1])) {

                          $fileContents = file_get_contents($this->osAudioStoragePath . '/' . $this->audioFilePrefix . '_' . $name_tokens[1]);

                          $options = Array();
                          $options['path'] = '/var/www/dev.173.255.195.42/resources/audio';
                          $options['filenames'] = $this->audioArray;

                          $this->cleanup->gc($options);
                          $this->audioArray = Array();
                          $this->session->put("tejas_captcha_audio_files.audioFileSuffixes", $this->audioArray);

                          switch ($id_tokens[1]) {
                              case 'mp3':
                                  return response($fileContents)
                                                  ->withHeaders([
                                                      'Content-type' => 'audio/mpeg',
                                                      'Cache-Control' => 'no-cache',
                                                      'Content-Disposition' =>  'attachment; filename="captcha_audio.mp3"',
                                                  ]);
                                  break;
                              case 'ogg':
                                  return response($fileContents)
                                                  ->withHeaders([
                                                      'Content-type' => 'audio/ogg',
                                                      'Cache-Control' => 'no-cache',
                                                      'Content-Disposition' =>  'attachment; filename="captcha_audio.ogg"',
                                                  ]);
                                  break;

                              case 'wav':
                                  return response($fileContents)
                                                  ->withHeaders([
                                                      'Content-type' => 'audio/wav',
                                                      'Cache-Control' => 'no-cache',
                                                      'Content-Disposition' =>  'attachment; filename="captcha_audio.wav"',
                                                  ]);
                                  break;
                            }
                            $this->session->put('tejas_captcha_params.inprogress', false);
                        }
                    }
                }
            }
        }
  }

  // /**
  //   * get audio silence
  //   *
  //   */
  //  public function getAudioSilence()
  //  {
  //      if(file_exists('../vendor/tejas/tejascaptcha/src/scripts/audio/250-milliseconds-of-silence.mp3')) {
  //          $fileContents = file_get_contents('../vendor/tejas/tejascaptcha/src/scripts/audio/250-milliseconds-of-silence.mp3');
  //          return response($fileContents)
  //                  ->withHeaders([
  //                      'Content-type' => 'audio/mpeg',
  //                      'Cache-Control' => 'no-cache',
  //                      'Content-Disposition' =>  'inline',  //'attachment; filename="captcha_audio_sss.mp3"',
  //                  ]);
  //      }else{
  //
  //          echo 'File does not exist 250-miliseconds-of-silence.mp3';
  //      }
  //
  //  }


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
        $result = $tejascaptcha->create($config);
        $this->session->put('tejas_captcha_params.inprogress', false);
        return $result;
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
      $result = $tejascaptcha->create($config);
      $this->session->put('tejas_captcha_params.inprogress', false);
      return $result;
    }
}
