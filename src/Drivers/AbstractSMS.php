<?php

namespace SimpleSoftwareIO\SMS\Drivers;

use SimpleSoftwareIO\SMS\SMSNotSentException;

abstract class AbstractSMS
{
    protected $debug;

    /**
     * Throw a not sent exception.
     *
     * @param string $message
     * @param null|int $code
     *
     * @throws SMSNotSentException
     */
    protected function throwNotSentException(string $message, $code = null)
    {
        throw new SMSNotSentException($message, $code);
    }

    /**
     * Defines if debug is enabled or disabled (SMS77).
     *
     * @param $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }
}
