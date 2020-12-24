<?php

namespace SimpleSoftwareIO\SMS;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\Str;
use Opis\Closure\SerializableClosure;
use SimpleSoftwareIO\SMS\Drivers\DriverInterface;

class SMS
{
    /**
     * The Driver Interface instance.
     *
     * @var \SimpleSoftwareIO\SMS\Drivers\DriverInterface
     */
    protected $driver;

    /**
     * The IOC Container.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * The global from address.
     *
     * @var string
     */
    protected $from;

    /**
     * Holds the queue instance.
     *
     * @var \Illuminate\Queue\QueueManager
     */
    protected $queue;

    /**
     * Creates the SMS instance.
     *
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Changes the set SMS driver.
     *
     * @param $driver
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function driver($driver)
    {
        $this->container['sms.sender'] = $this->container->make(function ($container) use ($driver) {
            return (new DriverManager($container))->driver($driver);
        });

        $this->driver = $this->container['sms.sender'];
    }

    /**
     * Send a SMS.
     *
     * @param string $text The text to be send.
     * @param \Closure $callback The methods that you wish to fun on the message.
     *
     * @return \SimpleSoftwareIO\SMS\OutgoingMessage The outgoing message that was sent.
     */
    public function send(string $text, Closure $callback)
    {
        $message = $this->createOutgoingMessage();

        //We need to set the properties so that we can later pass this onto the Illuminate Mailer class if the e-mail gateway is used.
        $message->text($text);

        call_user_func($callback, $message);

        $this->driver->send($message);

        return $message;
    }

    /**
     * Creates a new Message instance.
     *
     * @return \SimpleSoftwareIO\SMS\OutgoingMessage
     */
    protected function createOutgoingMessage()
    {
        $message = new OutgoingMessage();

        //If a from address is set, pass it along to the message class.
        if (isset($this->from)) {
            $message->from($this->from);
        }

        return $message;
    }

    /**
     * Sets the IoC container.
     *
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Sets the number that message should always be sent from.
     *
     * @param $number
     */
    public function alwaysFrom($number)
    {
        $this->from = $number;
    }

    /**
     * Queues a SMS message.
     *
     * @param string $text
     * @param \Closure|string $callback The callback to run on the Message class.
     * @param null|string $queue The desired queue to push the message to.
     */
    public function queue(string $text, $callback, $queue = null)
    {
        $callback = $this->buildQueueCallable($callback);

        $this->queue->push('sms@handleQueuedMessage', compact('text', 'callback'), $queue);
    }

    /**
     * Queues a SMS message to a given queue.
     *
     * @param null|string $queue The desired queue to push the message to.
     * @param string $text
     * @param \Closure|string $callback The callback to run on the Message class.
     */
    public function queueOn(?string $queue, string $text, $callback)
    {
        $this->queue($text, $callback, $queue);
    }

    /**
     * Queues a message to be sent a later time.
     *
     * @param int $delay The desired delay in seconds
     * @param string $text
     * @param \Closure|string $callback The callback to run on the Message class.
     * @param null|string $queue The desired queue to push the message to.
     */
    public function later(int $delay, string $text, $callback, $queue = null)
    {
        $callback = $this->buildQueueCallable($callback);

        $this->queue->later($delay, 'sms@handleQueuedMessage', compact('text', 'callback'), $queue);
    }

    /**
     * Queues a message to be sent a later time on a given queue.
     *
     * @param null|string $queue The desired queue to push the message to.
     * @param int $delay The desired delay in seconds
     * @param string $text
     * @param \Closure|string $callback The callback to run on the Message class.
     */
    public function laterOn(?string $queue, int $delay, string $text, $callback)
    {
        $this->later($delay, $text, $callback, $queue);
    }

    /**
     * Builds the callable for a queue.
     *
     * @param \Closure|string $callback The callback to be serialized
     *
     * @return string
     */
    protected function buildQueueCallable($callback)
    {
        if (!$callback instanceof Closure) {
            return $callback;
        }

        return serialize(new SerializableClosure($callback));
    }

    /**
     * Handles a queue message.
     *
     * @param \Illuminate\Queue\Jobs\Job $job
     * @param array $data
     */
    public function handleQueuedMessage(Job $job, array $data)
    {
        $this->send($data['text'], $this->getQueuedCallable($data));

        $job->delete();
    }

    /**
     * Gets the callable for a queued message.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected function getQueuedCallable(array $data)
    {
        if (Str::contains($data['callback'], 'SerializableClosure')) {
            return unserialize($data['callback'])->getClosure();
        }

        return $data['callback'];
    }

    /**
     * Set the queue manager instance.
     *
     * @param \Illuminate\Queue\QueueManager $queue
     *
     * @return void
     */
    public function setQueue(QueueManager $queue)
    {
        $this->queue = $queue;
    }
}
