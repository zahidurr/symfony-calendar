<?php

namespace App\Tests\Mock;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBus;

class MessageBusMock extends MessageBus
{
    use MockTrait;

    public function __construct(iterable $middlewareHandlers = [])
    {
        parent::__construct($middlewareHandlers);
        $this->doMock();
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(object $message, array $stamps = []): Envelope
    {
        return $this->mock ? new Envelope($message, $stamps) : parent::dispatch($message, $stamps);
    }
}
