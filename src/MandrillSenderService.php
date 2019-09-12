<?php

namespace MandrillSender;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Mandrill;
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
     * @param string $email
     * @param string $subject
     * @param array $placeholders
     * @param string $templateName
     */
    public function sendTemplate(string $email, string $subject, array $placeholders, string $templateName)
    {
        $mailTemplate = new MailTemplate($this->mandrill, $placeholders, $templateName);
        try {
            Mail::to($email)->subject($subject)->send($mailTemplate);
        } catch (\Exception $exception) {
            Log::warning('Mail Exception', [
                'to' => $email,
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }
    }
}
