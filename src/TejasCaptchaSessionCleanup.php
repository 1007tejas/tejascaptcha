<?php

namespace Tejas\TejasCaptcha;

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
   * @var array
   */
  protected $force_delete_filenames;

  /**
   * The number of seconds the session audio should be valid.
   *
   * @var int
   */
  protected $seconds;

  /**
   * The number of files in the audio directory.
   *
   * @var int
   */
  protected $filecount;

    /**
    * Construct a TejasCaptcha garbage collector
    */
    public function __construct()
    {
      $this->filesystem = new Filesystem();
      $this->path = 'var/www/dev.173.255.195.42/storage/app/audio';
      $this->force_delete_filenames = null;
      $this->seconds = 30;
    }

    /**
    * Create a new TejasCaptcha garbage collector
    * @param  array $options
    * @return void
    */
    public function gc(array $options = []) {
        $this->path = $options['path'] ?? $this->path;
        $this->force_delete_filenames = $options['force_delete_filenames'] ?? $this->force_delete_filenames;

		$testDate = (new \DateTime("{$this->seconds} seconds ago"))->format('r');

        try{
            $files = Finder::create()
                        ->in($this->path)
                        ->files()
                        ->ignoreDotFiles(true)
                        ->date("<=" . $testDate);

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

			// script.py specifies a jobQueue = queue.Queue(50)
			// But the high water mark is 45, enforced here by TejasCaptchaSessionCleanup.php -> gc
			// Max audio files in storage/app/audio is 45 * 3 = 135
			// gc will garbage collect by deleting all audio files in the storage/app/audio
			// directory when file count is greater than or equal to 135.
			if(iterator_count($files) >= 135) {
				foreach ($files as $file) {
				    $this->filesystem->delete($file->getRealPath());
				}
			}

        } catch(exception $e){
            LOG::debug('Caught exception: ' . $e->getMessage() . "\n");
        }


        if($this->force_delete_filenames !== null) {
            try{
                $files = Finder::create()
                            ->in($this->path)
                            ->name($this->force_delete_filenames);

                foreach ($files as $file) {
                    $this->filesystem->delete($file->getRealPath());
                }

              }catch(exception $e){
                  LOG::debug('Caught exception: ' . $e->getMessage() . "\n");
              }
          }
    }
}
