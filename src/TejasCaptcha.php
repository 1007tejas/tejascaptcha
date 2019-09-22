<?php

namespace Tejas\TejasCaptcha;

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

use Exception;
use Illuminate\Config\Repository;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Illuminate\Session\Store as Session;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Log;
// Log levels: emergency, alert, critical, error, warning, notice, info and debug.
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
/**
 * Class TejasCaptcha
 * @package TejasCaptcha
 */
class TejasCaptcha
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Repository
     */
    protected $config_repository;

    /**
     * @var ImageManager
     */
    protected $imageManager_fn;

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
     * @var ImageManager->canvas
     */
    protected $canvas;

    /**
     * @var ImageManager->image
     */
    protected $image;

    /**
     * @var array
     */
    protected $backgrounds = [];

    /**
     * @var array
     */
    protected $fonts = [];

    /**
     * @var array
     */
    protected $fontColors = [];

    /**
     * @var int
     */
    protected $length = 5;

    /**
     * @var int
     */
    protected $width = 230;

    /**
     * @var int
     */
    protected $height = 50;

    /**
     * @var int
     */
    protected $angle = 15;

    /**
     * @var int
     */
    protected $lines = 5;

    /**
     * @var array
     */
    protected $natural_numbers = ['1', '2', '3', '4', '6', '7', '8', '9'];

    /**
     * @var array
     */
    protected $alpha_characters_no_vowels = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'];

    /**
     * @var array
     */
    protected $text;

    /**
     * @var int
     */
    protected $contrast = 0;

    /**
     * @var int
     */
    protected $quality = 90;

    /**
     * @var int
     */
    protected $sharpen = 0;

    /**
     * @var int
     */
    protected $blur = 0;

    /**
     * @var bool
     */
    protected $bgImage = true;

    /**
     * @var string
     */
    protected $bgColor = '#ffffff';

    /**
     * @var bool
     */
    protected $invert = false;

    /**
     * @var bool
     */
    protected $sensitive = false;

    /**
     * @var int
     */
    protected $textLeftPadding = 4;

    /**
     * @var bool
     */
    protected $math = 0;

    /**
     * @var bool
     */
    protected $math_generated = 0;

    /**
     * @var bool
     */
    protected $storeAudioInSession = false;

    /**
     * @var string
     */
    protected $osAudioStoragePath = null;

    /**
     * @var string
     */
    protected $audioFilePrefix = null;

    /**
     * @var string
     */
    protected $audioFileSuffix = null;

    /**
     * Constructor
     *
     * @param Filesystem $files
     * @param Repository $config_repository
     * @param ImageManager $imageManager
     * @param Session $session
     * @param Hasher $hasher
     * @param Str $str
     * @throws Exception
     * @internal param Validator $validator
     */
    public function __construct(
        Filesystem $files,
        Repository $config,
        ImageManager $imageManager,
        Session $session,
        Hasher $hasher,
        Str $str
    )
    {
        $this->filesystem = $files;
        $this->config_repository = $config;
        $this->imageManager_fn = $imageManager;
        $this->session = $session;
        $this->hasher_fn = $hasher;
        $this->str_fn = $str;
    }

    /**
     * @param string $config
     * @return void
     */
    protected function configure($config_section = 'default')
    {
        if ($this->config_repository->has('tejascaptcha.' . $config_section)) {
            foreach ($this->config_repository->get('tejascaptcha.' . $config_section) as $key => $val) {
                $this->{$key} = $val;
            }
        }

      {
            if ($this->session->has('tejas_captcha_audio_files')
                && $this->session->has('tejas_captcha_audio_files.audioFileSuffix')) {

                foreach ($this->session->get('tejas_captcha_audio_files') as $key => $val) {
                    $this->{$key} = $val;
                }
                $extensions = ['mp3', 'ogg', 'wav'];
                foreach ($extensions as $key => $value) {
                    $file = $this->osAudioStoragePath.'/'.$this->audioFilePrefix.$this->audioFileSuffix.'.'.$value;
                    try {
                      unlink($file);
                    } catch(Exception $e) {
                      Log::debug('Caught exception: ' . $e->getMessage() . "\n");
                    }
                }
                $this->audioFileSuffix = '_'.abs(random_int (PHP_INT_MIN , PHP_INT_MAX ));
                $this->session->put('tejas_captcha_audio_files.audioFileSuffix', $this->audioFileSuffix);

            }elseif ($this->config_repository->has('tejascaptcha.audio')) {
                $this->session->put('tejas_captcha_audio_files');
                foreach ($this->config_repository->get('tejascaptcha.audio') as $key => $val) {
                    $this->{$key} = $val;
                    $this->session->put("tejas_captcha_audio_files.$key", $val);
                }
                $this->audioFileSuffix = '_'.abs(random_int (PHP_INT_MIN , PHP_INT_MAX ));
                $this->session->put('tejas_captcha_audio_files.audioFileSuffix', $this->audioFileSuffix);
            }
      }
        // math and math_generated are not configuration items but they
        // need to persist across captcha refrshes, initialize them here.
        if (!$this->session->has('tejas_captcha_vars') || count($this->session->get('tejas_captcha_vars'))!= 2) {
            $this->session->put('tejas_captcha_vars', [
                'math' => 0,
                'math_generated' => 0
            ]);
        }
        $this->math = $this->session->get('tejas_captcha_vars.math');
        $this->math_generated = $this->session->get('tejas_captcha_vars.math_generated');

        // These 4 are not configuration items, initialize them here.
        $this->oldx = 0;
        $this->min_color = 64;
        $this->max_color = 200;
        $this->color_threshhold = 128;
}


    /**
     * Create tejascaptcha image
     *
     * @param string $config
     * @param boolean $api
     * @return imageManager->response
     */
    public function create($config_section = 'default', $api = false)
    {
        $this->backgrounds = $this->filesystem->files(__DIR__ . '/../assets/backgrounds');
        $this->fonts = $this->filesystem->files(__DIR__ . '/../assets/fonts');

        if (app()->version() >= 5.5) {
            $this->fonts = array_map(function ($file) {
                return $file->getPathName();
            }, $this->fonts);
        }

        $this->fonts = array_values($this->fonts); //reset fonts array index

        $this->configure($config_section);

        if($this->math_generated) {
            $this->math_generated = 0;
        } else {
            $this->math = ( random_int(PHP_INT_MIN, PHP_INT_MAX)%2 == 0 ) ? 1 : 0;
            $this->session->put('tejas_captcha_vars.math', $this->math);
            $this->math_generated = 1;
        }
        $this->session->put('tejas_captcha_vars.math_generated', $this->math_generated);

        // Log::debug('create: $math: '.$this->math.' $math_generated: '.$this->math_generated);

        $generator = $this->generate();
        $this->text = $generator['value'];

        $this->canvas = $this->imageManager_fn->canvas(
            $this->width,
            $this->height,
            $this->bgColor
        );

        if ($this->bgImage) {
            $this->image = $this->imageManager_fn->make($this->background())->resize(
                $this->width,
                $this->height
            );
            $this->canvas->insert($this->image);
        } else {
            $this->image = $this->canvas;
        }

        if ($this->contrast != 0) {
            $this->image->contrast($this->contrast);
        }

        $this->text();

        $this->lines();

        if ($this->sharpen) {
            $this->image->sharpen($this->sharpen);
        }
        if ($this->invert) {
            $this->image->invert($this->invert);
        }
        if ($this->blur) {
            $this->image->blur($this->blur);
        }

        return $api ? [
            'sensitive' => $generator['sensitive'],
            'key' => $generator['key'],
            'img' => $this->image->encode('data-url')->encoded
        ] : $this->image->response('png', $this->quality);
    }

    /**
     * Image backgrounds
     *
     * @return string
     */
    protected function background()
    {
        return $this->backgrounds[random_int(0, count($this->backgrounds) - 1)];
    }

    /**
     * Generate tejascaptcha text
     *
     * @return string
     */
    protected function generate()
    {
      $bag = [];
      $key = '';
      $all_characters_no_vowels = Array();
      $x = $this->oldx;

      if ($this->math) {
          while($this->oldx == $x) {
              $x = random_int(10, 30);
              $y = random_int(1, 9);
          }
          $this->oldx = $x;

          $tts = $bag = "$x + $y = ";
          $key = $x + $y;
          $key .= '';
      } else {
          $all_characters_no_vowels = array_merge($this->alpha_characters_no_vowels, $this->natural_numbers);
          for ($i = 0; $i < $this->length; $i++) {
              $character_index = random_int(0, count($all_characters_no_vowels) - 1);
              $onechar = $all_characters_no_vowels[$character_index];

              if(is_numeric($onechar)) {
                  $bag[] = $onechar;
              }else{
                  $bag[] = $this->sensitive ? ((random_int(PHP_INT_MIN, PHP_INT_MAX)%2 == 0 ) ? $this->str_fn->upper($onechar) : $onechar) : $onechar;
              }
          }
          $tts = $key = $bag = implode('', $bag);
      }

      $hash = $this->hasher_fn->make($key);

      $this->session->put('tejas_captcha_params', [
          'sensitive' => $this->sensitive,
          'key' => $hash
      ]);

      $tts = preg_replace('/\s+/', '', $tts);
      $text = "-d $tts -f " . $this->session->get('tejas_captcha_audio_files.audioFileSuffix');
      $process = new Process("python3 ../vendor/tejas/tejascaptcha/src/scripts/script.py {$text}");
      $process->run();
      // executes after the command finishes
      if (!$process->isSuccessful()) {
          throw new ProcessFailedException($process);
      }
      // else{
      //   dd( $process->getOutput(), $text );
      // }


      return [
          'value' => $bag,
          'sensitive' => $this->sensitive,
          'key' => $hash
      ];
    }

    /**
     * Writing tejascaptcha text
     */
    protected function text()
    {
          if (is_string($this->text)) {

                $text = $this->text;

                $this->length = ($this->math)? strlen($text) : $this->length;
                $marginTop = $this->image->height() / $this->length;

                $text = str_split($text);

                foreach ($text as $key => $onechar) {
                    $marginLeft = $this->textLeftPadding + ($key * ($this->image->width() - $this->textLeftPadding) / $this->length);

                    $this->image->text($onechar, $marginLeft, $marginTop, function ($font) {
                        $font->file($this->font());
                        $font->size($this->fontSize());
                        $font->color($this->fontColor());
                        $font->align('left');
                        $font->valign('top');
                        $font->angle($this->angle());
                    });
                }
          }
          $text = $this->text = null;
    }

    /**
     * Image fonts
     *
     * @return string
     */
    protected function font()
    {
        return $this->fonts[random_int(0, count($this->fonts) - 1)];
    }

    /**
     * Random font size
     *
     * @return integer
     */
    protected function fontSize()
    {
        return random_int($this->image->height() - 10, $this->image->height());
    }

    /**
     * Random font color
     *
     * @return array
     */
    protected function fontColor()
    {
        $min_color = $this->min_color;
        $max_color = $this->max_color;
        $color_threshhold = $this->color_threshhold;

        $red = 255;
        $blue = 255;
        $green = 255;

        if (!empty($this->fontColors)) {
            $color = $this->fontColors[random_int(0, count($this->fontColors) - 1)];
        } else {
            while( $red >= $color_threshhold && $green >= $color_threshhold && $blue >= $color_threshhold ) {
              $red = random_int($min_color, $max_color);
              $blue = random_int($min_color, $max_color);
              $green = random_int($min_color, $max_color);
            }
            $color = [$red, $green, $blue];
        }

        return $color;
    }

    /**
     * Angle
     *
     * @return int
     */
    protected function angle()
    {
        return random_int((-1 * $this->angle), $this->angle);
    }

    /**
     * Random image lines
     *
     * @return \Intervention\Image\Image
     */
    protected function lines()
    {
        for ($i = 0; $i <= $this->lines; $i++) {
            $this->image->line(
                random_int(0, $this->image->width()) + $i * random_int(0, $this->image->height()),
                random_int(0, $this->image->height()),
                random_int(0, $this->image->width()),
                random_int(0, $this->image->height()),
                function ($draw) {
                    $draw->color($this->fontColor());
                }
            );
        }

        return $this->image;
    }

    /**
     * TejasCaptcha check
     *
     * @param $value
     * @return bool
     */
    public function check_api($value, $key)
    {
        return $this->hasher_fn->check($value, $key);
    }

    /**
     * Generate tejascaptcha image source
     *
     * @param null $config
     * @return string
     */
    public function src($config = null)
    {
        return url('tejascaptcha' . ($config ? '/' . $config : '/default')) . '?' . $this->str_fn->random(8);
    }

    /**
     * Generate tejascaptcha image source
     *
     * @param null $attrs
     * @return string
     */
    public function tejas_captcha_image_onAjaxRequest($attrs = null)
    {
      foreach ($attrs as $attr => $value) {
          if ($attr == 'src') {
              $attrs ['src'] = url('tejascaptcha') . "?" . $this->str_fn->random();
          }
          if ($attr == 'alt') {
            // this never gets to the Dom if the provided jquery ajax is used

              $this->math = ( random_int(PHP_INT_MIN, PHP_INT_MAX)%2 == 0 ) ? 1 : 0;
              $attrs['alt'] = $this->math;
              $this->math_generated = 1;

              $this->session->put('tejas_captcha_vars.math', $this->math);
              $this->session->put('tejas_captcha_vars.math_generated', $this->math_generated);

              // Log::debug('tejas_captcha_image-onAjaxRequest: $math: '.$this->math.' $math_generated: '.$this->math_generated);

          }
      }
      return json_encode($attrs);
    }


    /**
     * Generate tejascaptcha image source
     *
     * @param null $attrs
     * @return string
     */
    public function tejas_captcha_image_onPageLoad($attrs = null)
    { // this is hee in case no jquery
      $attrs_str = '';
      foreach ($attrs as $attr => $value) {
          if ($attr == 'src') {
              //Neglect src attribute
              continue;
          }
          if ($attr == 'alt') {

              $this->math = ( random_int(PHP_INT_MIN, PHP_INT_MAX)%2 == 0 ) ? 1 : 0;
              $value = $this->math;
              $value = '';
              $this->math_generated = 1;

              $this->session->put('tejas_captcha_vars.math', $this->math);
              $this->session->put('tejas_captcha_vars.math_generated', $this->math_generated);

              // Log::debug('tejas_captcha_image-onPageLoad: $math: '.$this->math.' $math_generated: '.$this->math_generated);
          }
          $attrs_str .= $attr . "='" . $value . "'";
      }
      $attrs_str .= ' src=' . "'" . url('tejascaptcha') . "?" . $this->str_fn->random() . "'";
      return new HtmlString(trim($attrs_str));
    }
}
