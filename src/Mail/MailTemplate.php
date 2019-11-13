<?php

namespace MandrillSender\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use \Mandrill;

class MailTemplate extends Mailable
{
    use Queueable, SerializesModels;

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
     * @var string
     */
    public $subject;
    /**
     * @var string
     */
    public $markup;
    /**
     * @var array
     */
    public $files;

    /**
     * MailTemplate constructor.
     * @param Mandrill $mandrill
     * @param array $placeholders
     * @param string $templateName
     * @param array $files
     */
    public function __construct(Mandrill $mandrill, array $placeholders, string $templateName, array $files = [])
    {
        $this->mandrill = $mandrill;
        $this->placeholders = $placeholders;
        $this->templateName = $templateName;
        $this->files = $files;
        $this->markup = $this->getHtmlTemplate();
    }

    /**
     * @return string
     */
    private function getHtmlTemplate(): string
    {
        $template = $this->mandrill->templates->info($this->templateName);
        $this->subject = $template['subject'] ?? config('mandrill_sender.default_subject');
        return str_replace(array_keys($this->placeholders), $this->placeholders, $template['code']);
    }

    /**
     * @return MailTemplate
     */
    public function build(): MailTemplate
    {
        $this->html($this->markup);
        foreach ($this->files as $file){
            $this->attach($file);
        }
        return $this;
    }
}
