<?php

namespace Tejas\TejasCaptcha;

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
   * The number of minutes the session audio should be valid.
   *
   * @var int
   */
  protected $minutes;

    /**
    * Create a new TejasCaptcha garbage collector
    */
    public function __construct()
    {
      $this->filesystem = new Filesystem();
    }

    /**
    * Create a new TejasCaptcha garbage collector
    * @param  array $options
    * @return void
    */
    public function gc(array $options = []) {
        $this->path = $options['path'] ?? $this->path;
        $this->minutes = $options['minutes'] ?? $this->minutes;

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
    }
}
