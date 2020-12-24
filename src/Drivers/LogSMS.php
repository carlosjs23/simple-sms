<?php

namespace SimpleSoftwareIO\SMS\Drivers;

use Illuminate\Log\Logger;
use SimpleSoftwareIO\SMS\OutgoingMessage;

class LogSMS implements DriverInterface
{

    /**
     * Laravel Logger.
     *
     * @var \GuzzleHttp\Client
     */
    protected $logger;

    /**
     * Create the CallFire instance.
     *
     * @param \Illuminate\Log\Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Sends a SMS message.
     *
     * @param \SimpleSoftwareIO\SMS\OutgoingMessage $message
     */
    public function send(OutgoingMessage $message)
    {
        foreach ($message->getTo() as $number) {
            $this->logger->notice("Sending SMS message to: $number");
        }
    }
}
