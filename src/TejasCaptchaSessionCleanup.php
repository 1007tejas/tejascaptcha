<?php

namespace Tejas\TejasCaptcha;

/**
 * Part of Laravel 5 TejasCaptcha package
 *
 * @copyright
 * @version
 * @author Jeff Hallmark
 * @contact
 * @web https://github.com/1007tejas/
 * @date 2019-08-29
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

/**
 * Class TejasCaptchaSessionCleanup
 * @package TejasCaptcha
 */
class TejasCaptchaSessionCleanup {

  /**
   * The filesystem instance.
   *
   * @var Filesystem
   */
  protected $filesystem;

  /**
   * The path where final audio files are stored.
   *
   * @var string
   */
  protected $path;

  /**
   * The path where final audio files are stored.
   *
   * @var string
   */
  protected $filenames;

  /**
   * The number of minutes the session audio should be valid.
   *
   * @var int
   */
  protected $minutes;

  /**
   * The number of files in the audio directory.
   *
   * @var int
   */
  protected $filecount;

    /**
    * Create a new TejasCaptcha garbage collector
    */
    public function __construct()
    {
      $this->filesystem = new Filesystem();
      $this->path = 'var/www/dev.173.255.195.42/resources/audio';
      $this->filenames = null;
      $this->minutes = 1;
    }

    /**
    * Create a new TejasCaptcha garbage collector
    * @param  array $options
    * @return void
    */
    public function gc(array $options = []) {
        $this->path = $options['path'] ?? $this->path;
        $this->filenames = $options['filenames'] ?? $this->filenames;
        try{
            $files = Finder::create()
                        ->in($this->path)
                        ->files()
                        ->ignoreDotFiles(true)
                        ->date('<= now - '.$this->minutes.' minutes');

              foreach ($files as $file) {
                  $this->filesystem->delete($file->getRealPath());
              }

        }catch(exception $e){
            LOG::debug('Caught exception: ' . $e->getMessage() . "\n");
        }

        try{
          $files = Finder::create()
                      ->in($this->path)
                      ->files()
                      ->ignoreDotFiles(true);

            if(iterator_count($files) > 50) {
                foreach ($files as $file) {
                    $this->filesystem->delete($file->getRealPath());
                }
            }

        } catch(exception $e){
            LOG::debug('Caught exception: ' . $e->getMessage() . "\n");
        }


        if($this->filenames !== null) {
            try{
                $files = Finder::create()
                            ->in($this->path)
                            ->name($this->filenames);

                foreach ($files as $file) {
                    $this->filesystem->delete($file->getRealPath());
                }

              }catch(exception $e){
                  LOG::debug('Caught exception: ' . $e->getMessage() . "\n");
              }
          }

    }

}
