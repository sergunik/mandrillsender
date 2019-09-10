<?php

namespace MandrillSender\Tests;

use MandrillSender\Mail\MailTemplate;
use MandrillSender\MandrillSenderService;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    /**
     * @return string
     */
    private function getHtmlTemplate(): string
    {
        return file_get_contents(__DIR__ . '/mail_template.html');
    }

    /**
     * @return array
     */
    private function getPlaceholders(): array
    {
        return [
            '*|SUBJECT|*' => 'Test Email',
            '*|TO|*' => 'test@gmail.com',
            'email' => 'test@gmail.com'
        ];
    }

    /**
     * @return \Mandrill|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    private function createMandrillMock()
    {
        $template = [
            'code' => $this->getHtmlTemplate(),
            'subject' => 'sbj'
        ];
        $MandrillTemplates = Mockery::mock(\Mandrill_Templates::class);
        $MandrillTemplates->shouldReceive('info')
            ->times(1)
            ->andReturn($template);

        $Mandrill = Mockery::mock(\Mandrill::class);
        $Mandrill->templates = $MandrillTemplates;

        return $Mandrill;
    }

    /**
     * @throws \MandrillSender\Exceptions\CantSendException
     */
    public function test_send_email()
    {
        Mail::fake();
        $placeholders = $this->getPlaceholders();
        $mandrill = $this->createMandrillMock();
        $mandrillService = new MandrillSenderService($mandrill);
        $mandrillService->sendTemplate($placeholders, 'tpl');

        Mail::assertSent(MailTemplate::class, function ($mail) use ($placeholders) {
            $this->assertTrue($mail->hasTo($placeholders['email']));
            $this->assertContains($placeholders['*|SUBJECT|*'], $placeholders);
            $this->assertContains($placeholders['*|TO|*'], $placeholders);
            return $mail;
        });
    }
}
