<?php

namespace MandrillSender\Exceptions;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CantSendException extends Exception
{
    /**
     * CantSendException constructor.
     * @param string $email
     * @param string$message
     */
    public function __construct(string $email = '', $message = '')
    {
        if (config('mandrill_sender.debug')) {
            $logger = new Logger('MandrillSender');
            $logger->pushHandler(new StreamHandler(config('mandrill_sender.log_path')));
            $logger->warning('Mail Exception', ['Exception Message' => $message, 'mail to' => $email]);
        }
    }
}