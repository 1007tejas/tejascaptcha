<?php

namespace Tejas\TejasCaptcha\Sessions;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;

use Illuminate\Support\Facades\Log;

// From laravel framework/src/Illuminate/Session/FileSessionHandler.
class TejasCaptchaSessionCleanup {

  /**
   * The filesystem instance.
   *
   * @var \Illuminate\Filesystem\Filesystem
   */
  protected $files;

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
    * @param  \Illuminate\Filesystem\Filesystem  $files
    * @param  string  $path
    * @param  int  $minutes
    * @return void
    */
    public function __construct(Filesystem $files, $path=null, $minutes=null)
    {
      $this->path = $path;
      $this->files = $files;
      $this->minutes = $minutes;
    }

    /**
     * {@inheritdoc}
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
                $this->files->delete($file->getRealPath());
            }
        }catch(exception(e)){
            LOG::debug('Caught exception: ' . $e->getMessage() . "\n");
            return false;
        }
        return true;
    }
}
