<?php

namespace SimpleSoftwareIO\SMS;

class OutgoingMessage
{

    /**
     * The text to be used when composing a message.
     *
     * @var string
     */
    protected $text;

    /**
     * The number messages are being sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Array of numbers a message is being sent to.
     *
     * @var array
     */
    protected $to;

    /**
     * Create a OutgoingMessage Instance.
     *
     *
     */
    public function __construct()
    {

    }

    /**
     * Sets the numbers messages will be sent from.
     *
     * @param string $number Holds the number that messages
     */
    public function from(string $number)
    {
        $this->from = $number;
    }

    /**
     * Sets the to addresses.
     *
     * @param string $number Holds the number that a message will be sent to.
     * @param string|null $carrier The carrier the number is on.
     *
     * @return $this
     */
    public function to(string $number, string $carrier = null)
    {
        $this->to[] = [
            'number'  => $number,
            'carrier' => $carrier,
        ];

        return $this;
    }

    /**
     * Returns the To addresses without the carriers.
     *
     * @return array
     */
    public function getTo()
    {
        $numbers = [];
        foreach ($this->to as $to) {
            $numbers[] = $to['number'];
        }

        return $numbers;
    }

    /**
     * Returns all numbers that a message is being sent to and includes their carriers.
     *
     * @return array An array with numbers and carriers
     */
    public function getToWithCarriers()
    {
        return $this->to;
    }

    public function text(string $text)
    {
        $this->text = $text;
    }

    /**
     * Returns the current view file.Returns.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
