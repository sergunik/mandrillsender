<?php

namespace MandrillSender\Exceptions;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MandrillSenderException extends Exception
{
    public function __construct(Exception $exception, string $email = '')
    {
        parent::__construct($exception->getMessage(), $exception->getCode(), $exception->getPrevious());

        if (config('mandrill_sender.debug')) {
            $logger = (new Logger('MandrillSender'))->pushHandler(new StreamHandler(config('mandrill_sender.log_path')));
            $logger->warning('Mail error', ['errorMessage' => $exception->getMessage(), 'mail to' => $email]);
        }
    }
}