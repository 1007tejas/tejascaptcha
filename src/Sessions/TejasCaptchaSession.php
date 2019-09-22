<?php

namespace Tejas\TejasCaptcha\Sessions;

use Illuminate\Session\Store as Session;
use SessionHandlerInterface;

class TejasCaptchaSession implements SessionHandlerInterface
{
    /**
     * @var Session
     */
    protected $session;


    /**
     * Create a new file driven handler instance.
     *
     * @param Session $session
     * @return void
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime = 0)
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
