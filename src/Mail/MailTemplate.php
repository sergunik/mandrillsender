<?php

namespace MandrillSender\Mail;

use Illuminate\Mail\Mailable;
use \Mandrill;

class MailTemplate extends Mailable
{
    /**
     * @var Mandrill
     */
    private $mandrill;
    /**
     * @var array
     */
    private $placeholders;
    /**
     * @var string
     */
    private $templateName;

    /**
     * MailTemplate constructor.
     * @param Mandrill $mandrill
     * @param array $placeholders
     * @param string $templateName
     */
    public function __construct(Mandrill $mandrill, array $placeholders, string $templateName)
    {
        $this->mandrill = $mandrill;
        $this->placeholders = $placeholders;
        $this->templateName = $templateName;
        $this->html = $this->getHtmlTemplate();
    }

    /**
     * @return string
     */
    private function getHtmlTemplate(): string
    {
        $template = $this->mandrill->templates->info($this->templateName);
        return str_replace(array_keys($this->placeholders), $this->placeholders, $template['code']);
    }
}
