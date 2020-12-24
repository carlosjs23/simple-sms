<?php

namespace SimpleSoftwareIO\SMS\Drivers;

use SimpleSoftwareIO\SMS\OutgoingMessage;

interface DriverInterface
{
    /**
     * Sends a SMS message.
     *
     * @param \SimpleSoftwareIO\SMS\OutgoingMessage $message
     */
    public function send(OutgoingMessage $message);
}
