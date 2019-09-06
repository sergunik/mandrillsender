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
     */
    public function __construct(string $email = '')
    {
        if (config('mandrill_sender.debug')) {
            $logger = new Logger('MandrillSender');
            $logger->pushHandler(new StreamHandler(config('mandrill_sender.log_path')));
            $logger->warning('Mail error', ['errorMessage' => $this->getMessage(), 'mail to' => $email]);
        }
    }
}