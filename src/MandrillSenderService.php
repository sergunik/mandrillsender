<?php

namespace MandrillSender;

use Illuminate\Support\Facades\Mail;
use Mandrill;
use MandrillSender\Exceptions\CantSendException;
use MandrillSender\Mail\MailTemplate;

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
     * @param array $placeholders
     * @param string $templateName
     * @throws CantSendException
     */
    public function sendTemplate(array $placeholders, string $templateName)
    {
        try {
            Mail::to($placeholders['email'])->send(new MailTemplate($this->mandrill, $placeholders, $templateName));
        } catch (\Exception $exception) {
            throw new CantSendException($placeholders['email']);
        }
    }
}
