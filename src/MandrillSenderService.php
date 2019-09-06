<?php

namespace MandrillSender;

use Illuminate\Support\Facades\Mail;
use Mandrill;
use MandrillSender\Exceptions\MandrillSenderException;

class MandrillSenderService
{
    /**
     * @var Mandrill
     */
    private $mandrill;
    /**
     * @var Logger
     */
    private $log;

    /**
     * MandrillSenderService constructor.
     * @param Mandrill $mandrill
     * @param Logger $logger
     */
    public function __construct(Mandrill $mandrill, Logger $logger)
    {
        $this->mandrill = $mandrill;
        $this->log = $logger;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $content
     * @throws MandrillSenderException
     */
    private function _send(string $to, string $subject, string $content)
    {
        try {
            Mail::send([], [], function ($message) use ($to, $subject, $content) {
                $message->to($to)
                    ->subject($subject)
                    ->setBody($content, 'text/html');
            });
        } catch (\Exception $exception) {
            throw new MandrillSenderException($exception,$to);
        }
    }

    /**
     * @param array $placeholders
     * @param string $templateName
     * @throws MandrillSenderException
     */
    public function sendTemplate(array $placeholders, string $templateName)
    {
        $template = $this->mandrill->templates->info($templateName);
        $content = str_replace(array_keys($placeholders), $placeholders, $template['code']);
        $this->_send($placeholders['email'], $template['subject'], $content);
    }
}
