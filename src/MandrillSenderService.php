<?php

namespace MandrillSender;

use Illuminate\Support\Facades\Mail;
use Mandrill;
use MandrillSender\Exceptions\CantSendException;

class MandrillSenderService
{
    /**
     * @var Mandrill
     */
    private $mandrill;

    /**
     * MandrillSenderService constructor.
     * @param Mandrill $mandrill
     */
    public function __construct(Mandrill $mandrill)
    {
        $this->mandrill = $mandrill;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $content
     * @throws CantSendException
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
            throw new CantSendException($to);
        }
    }

    /**
     * @param array $placeholders
     * @param string $templateName
     * @throws CantSendException
     */
    public function sendTemplate(array $placeholders, string $templateName)
    {
        $template = $this->mandrill->templates->info($templateName);
        $content = str_replace(array_keys($placeholders), $placeholders, $template['code']);
        $this->_send($placeholders['email'], $template['subject'], $content);
    }
}
